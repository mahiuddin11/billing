<?php

namespace App\Http\Controllers;

use App\Helpers\DataProcessingFile\MikrotikDataProcessing;
use App\Models\Customer;
use App\Models\MikrotikServer;
use App\Models\MPPPProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MikrotikCustomerList extends Controller
{

    use MikrotikDataProcessing;
    /**
     * String property
     */
    protected $routeName =  'mikrotiklist';
    protected $viewName =  'admin.pages.mikrotiklist';

    protected function tableColumnNames()
    {
        return [
            [
                'label' => 'Sl',
                'data' => 'check',
                'searchable' => true,
            ],
            [
                'label' => 'User Name',
                'data' => 'name',
                'searchable' => true,
            ],
            [
                'label' => 'Company name',
                'data' => 'company_name',
                'searchable' => true,
            ],
            [
                'label' => 'Status',
                'data' => 'disabled',
                'searchable' => true,
            ],

        ];
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page_title = "Mikrotik Customer";
        $page_heading = "Mikrotik Customer List";
        // $ajax_url = route($this->routeName . '.dataProcessing');
        $is_show_checkbox = false;
        $servers = MikrotikServer::where('status', true)->get();
        $columns = $this->reformatForRelationalColumnName(
            $this->tableColumnNames()
        );
        return view($this->viewName . '.index', get_defined_vars());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function dataProcessing($id)
    {
        return $this->getMikrotikDataResponse(
            //Table Columns Name
            $this->tableColumnNames(),
            $id
        );
    }

    public function importCustomer(Request $request)
    {
        try {
            $client = $this->client($request->server_id);
            $users = $client->q('/ppp/secret/print')->r();
            $chunk = array_chunk($users, 500);
            $customer =[] ;
            foreach ($chunk as $users) {
                foreach ($users as $user) {
                    if (in_array($user['.id'], $request->customers)) {
                        $profile = MPPPProfile::where('name', isset($user['profile']) ? $user['profile'] : null)->where('server_id', $request->server_id)->first();
                        $customer[] =   [
                            "mid" => isset($user['.id']) ? $user['.id'] : null,
                            "username" => isset($user['name']) ? $user['name'] : null,
                            "service" => isset($user['service']) ? $user['service'] : null,
                            "caller" => isset($user['caller-id']) ? $user['caller-id'] : null,
                            "remote_address" => isset($user['remote-address']) ? $user['remote-address'] : null,
                            "routes" => isset($user['routes']) ? $user['routes'] : null,
                            "company_id" => auth()->user()->company_id,
                            "protocol_type_id" => 3,
                            "m_p_p_p_profile" => $profile->id ?? 0,
                            "server_id" => $request->server_id,
                            "m_password" => isset($user['password']) ? $user['password'] : null,
                            "password" => isset($user['password']) ? Hash::make($user['password']) : null,
                            "limit_bytes_in" => isset($user['limit-bytes-in']) ? $user['limit-bytes-in'] : null,
                            "limit_bytes_out" => isset($user['limit-bytes-out']) ? $user['limit-bytes-out'] : null,
                            "last_logged_out" => isset($user['last-logged-out']) ? $user['last-logged-out'] : null,
                            "disabled" => isset($user['disabled']) ? $user['disabled'] : null,
                            "comment" => isset($user['comment']) ? $user['comment'] : null,
                        ];
                    }
                }
                Customer::upsert($customer, ['mid', 'server_id'], [
                    "mid",
                    "username",
                    "service",
                    "caller",
                    "remote_address",
                    "routes",
                    "company_id",
                    "protocol_type_id",
                    "m_p_p_p_profile",
                    "server_id",
                    "m_password",
                    "password",
                    "limit_bytes_in",
                    "limit_bytes_out",
                    "last_logged_out",
                    "disabled",
                    "comment"
                ]);
            }
            return back()->with('success', 'Customer Sync successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return "Message" . $e->getMessage() . 'File' . $e->getFile() . 'Line' . $e->getLine();
            return back()->with('failed', 'Something was wrong');
        }
    }

    public function importStaticCustomer(Request $request)
    {
        try {
            $client = $this->client($request->server_id);
            $users = $client->q('/queue/simple/print')->r();
            $chunk = array_chunk($users, 500);
            $customer = [];
            foreach ($chunk as $users) {
                foreach ($users as $user) {
                    $queue = Customer::where('queue_id', $user['.id'])->where('server_id', $request->server_id)->first();
                    if (in_array($user['.id'], $request->customers) && !$queue) {
                        $uploadlimit = explode('/', $user['max-limit']);
                        $upload =  1000000 <= $uploadlimit[0] ? ($uploadlimit[0] / 1000) / 1000 . "M" : ($uploadlimit[0] / 1000) . "K";
                        $download =  1000000 <= $uploadlimit[1] ? ($uploadlimit[0] / 1000) / 1000 . "M" : ($uploadlimit[0] / 1000) . "K";
                        $customer[] =   [
                            'queue_id' => $user['.id'],
                            'server_id' => $request->server_id,
                            'queue_name' => $user['name'] ?? null,
                            'protocol_type_id' => 1,
                            "company_id" => auth()->user()->company_id,
                            'billing_status_id' => $user['disabled'] == 'true' ? 4 : 5,
                            'queue_max_upload' => $upload,
                            'queue_max_download' => $download,
                            'queue_target' => $user['target'] ?? null,
                            'queue_dst' => $user['dst'] ?? null,
                            'queue_disabled' => $user['disabled'] ?? null,
                        ];
                    }
                }
                Customer::insert($customer);
            }
            return back()->with('success', 'Customer Sync successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return "Message" . $e->getMessage() . 'File' . $e->getFile() . 'Line' . $e->getLine();
            return back()->with('failed', 'Something was wrong');
        }
    }
}
