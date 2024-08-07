<?php

namespace App\Http\Controllers\Admin\MikrotikServer;

use App\Http\Controllers\Controller;
use App\Jobs\CustomerSync;
use App\Models\Company;
use App\Models\Customer;
use App\Models\MikrotikServer;
use App\Models\MPPPProfile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MikrotikServerController extends Controller
{
    /**
     * String property
     */
    protected $routeName =  'mikrotikserver';
    protected $viewName =  'admin.pages.mikrotikserver';

    protected function getModel()
    {
        return new MikrotikServer();
    }
    protected function tableColumnNames()
    {
        return [
            // [
            //     'label' => 'Show in Table header',
            //     'data' => 'action',
            //     'class' => 'text-nowrap', class name
            //     'orderable' => false,
            //     'searchable' => false,
            // ],
            [
                'label' => 'ID',
                'data' => 'id',
                'searchable' => false,
            ],
            [
                'label' => 'Server Ip',
                'data' => 'server_ip',
                'searchable' => false,
            ],
            [
                'label' => ' User Name',
                'data' => 'user_name',
                'searchable' => false,
            ],
            [
                'label' => 'Api Port',
                'data' => 'api_port',
                'searchable' => false,
            ],
            [
                'label' => 'Action',
                'data' => 'action',
                'class' => 'text-nowrap',
                'orderable' => false,
                'searchable' => false,
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
        $page_title = "Mikrotik Server";
        $page_heading = "Mikrotik Server List";
        $ajax_url = route($this->routeName . '.dataProcessing');
        $create_url = route($this->routeName . '.create');
        $columns = $this->reformatForRelationalColumnName(
            $this->tableColumnNames()
        );
        $companys = Company::get();
        return view($this->viewName . '.index', get_defined_vars());
    }


    public function dataProcessing(Request $request)
    {
        return $this->getDataResponse(
            //Model Instance
            $this->getModel(),
            //Table Columns Name
            $this->tableColumnNames(),
            //Route name
            $this->routeName,
            true,
            [
                [
                    'method_name' => 'sync',
                    'class' => 'btn-primary customerSync',
                    'fontawesome' => 'fa fa-sync',
                    'text' => '',
                    'title' => 'Edit',
                    'code' => "data-toggle='modal' data-target='#default'",
                ],
                [
                    'method_name' => 'edit',
                    'class' => 'btn-success ',
                    'fontawesome' => 'fa fa-edit',
                    'text' => '',
                    'title' => 'Edit',
                ],
                [
                    'method_name' => 'destroy',
                    'class' => 'btn-danger ',
                    'fontawesome' => 'fa fa-trash',
                    'text' => '',
                    'title' => 'Trash',
                ],
            ]

        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sync(Request $request, MikrotikServer $mikrotikser)
    {
        try {
            $client = $this->client($mikrotikser->id);
            $users = $client->q('/ppp/secret/print')->r();
            $chunk = array_chunk($users, 500);
            $customer = [];
            foreach ($chunk as $users) {
                $customer = [];
                foreach ($users as $user) {
                    if (str_contains($user['name'], $request->keyword) || empty($request->keyword)) {

                        $profile = MPPPProfile::where('name', isset($user['profile']) ? $user['profile'] : null)->first();
                        $customer[] =   [
                            "mid" => isset($user['.id']) ? $user['.id'] : null,
                            "username" => isset($user['name']) ? $user['name'] : null,
                            "service" => isset($user['service']) ? $user['service'] : null,
                            "caller" => isset($user['caller-id']) ? $user['caller-id'] : null,
                            "remote_address" => isset($user['remote-address']) ? $user['remote-address'] : null,
                            "routes" => isset($user['routes']) ? $user['routes'] : null,
                            "company_id" => $request->company_id,
                            "protocol_type_id" => 3,
                            "m_p_p_p_profile" => $profile->id ?? 0,
                            "server_id" => $mikrotikser->id,
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
            }
            Customer::upsert($customer, ['mid', 'username'], ['comment']);
            return back()->with('success', 'Customer Sync successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            dd("Message" . $e->getMessage() . 'File' . $e->getFile());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page_title = "Mikrotik Server Create";
        $page_heading = "Mikrotik Server Create";
        $back_url = route($this->routeName . '.index');
        $store_url = route($this->routeName . '.store');
        return view($this->viewName . '.create', get_defined_vars());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $valideted = $this->validate($request, [
            "server_ip" => ['required'],
            "user_name" => ['required'],
            "password" => ['required'],
            "api_port" => ['required'],
            "version" => ['required'],
        ]);


        try {
            DB::beginTransaction();
            $valideted['created_by'] = auth()->id();

            $mikrotik_server = $this->getModel()->create($valideted);
            DB::commit();

            return back()->with('success', 'Data Store Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('failed', 'Something was wrong');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Account  $Account
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, MikrotikServer $mikrotik_server)
    {

        $modal_title = 'Mikrotik Server Details';
        $modal_data = $mikrotik_server;
        $html = view($this->viewName . '.show', get_defined_vars())->render();
        return $html;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Account  $Account
     * @return \Illuminate\Http\Response
     */
    public function edit(MikrotikServer $mikrotik_server)
    {
        $page_title = "Mikrotik Server Edit";
        $page_heading = "Mikrotik Server Edit";
        $back_url = route($this->routeName . '.index');
        $update_url = route($this->routeName . '.update', $mikrotik_server->id);
        $editinfo = $mikrotik_server;

        // dd(get_defined_vars());
        return view($this->viewName . '.edit', get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Account  $Account
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MikrotikServer $mikrotik_server)
    {
        $valideted = $this->validate($request, [
            "server_ip" => ['required'],
            "user_name" => ['required'],
            "password" => ['required'],
            "api_port" => ['required'],
            "version" => ['required'],
        ]);

        try {
            DB::beginTransaction();

            $valideted['updated_by'] = auth()->id();

            $mikrotik_server->update($valideted);

            DB::commit();
            return back()->with('success', 'Data Updated Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('failed', $this->getError($e));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Account  $Account
     * @return \Illuminate\Http\Response
     */
    public function destroy(MikrotikServer $mikrotik_server)
    {
        try {
            DB::beginTransaction();

            $mikrotik_server->delete();

            DB::commit();
            return back()->with('success', 'Data deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('failed', $this->getError($e));
        }
    }
}
