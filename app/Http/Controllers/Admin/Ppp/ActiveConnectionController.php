<?php

namespace App\Http\Controllers\Admin\Ppp;

use App\Http\Controllers\Controller;
use App\Models\M_Secret;
use Illuminate\Http\Request;
use App\Helpers\DataProcessingFile\ActiveConnectionDataProcessing;
use App\Models\Company;
use App\Models\Customer;
use App\Models\MikrotikServer;

class ActiveConnectionController extends Controller
{
    // use ActiveConnectionDataProcessing;
    /**
     * String property
     */
    protected $routeName =  'activeconnections';
    protected $viewName =  'admin.pages.activeconnections';


    protected function getModel()
    {
        return new M_Secret();
    }

    protected function tableColumnNames()
    {
        return [

            [
                'label' => 'Name',
                'data' => 'name',
                'searchable' => false,
            ],
            [
                'label' => 'Service',
                'data' => 'service',
                'searchable' => false,
            ],

            [
                'label' => 'Mac Address',
                'data' => 'caller-id',
                'searchable' => false,
            ],

            [
                'label' => 'Address',
                'data' => 'address',
                'searchable' => false,
            ],
            [
                'label' => 'Uptime',
                'data' => 'uptime',
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
        $page_title = "Active Connection";
        $page_heading = "Active Connection List";
        $ajax_url = route($this->routeName . '.dataProcessing');
        $columns = $this->reformatForRelationalColumnName(
            $this->tableColumnNames()
        );
        $server = Customer::select('server_id')->where('company_id', auth()->user()->company_id)->groupBy('server_id')->pluck('server_id');
        $servers = [];
        if (auth()->user()->is_admin == 1) {
            $servers = MikrotikServer::where('status', true)->get();
        } else {
            $servers = MikrotikServer::whereIn('id', $server)->where('status', true)->get();
        }
        return view($this->viewName . '.index', get_defined_vars());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function dataProcessing(Request $request)
    {
        $client = $this->client($request->server_id);
        $activeUsers = $client->query('/ppp/active/print')->read();
        $data = array();
        if ($activeUsers) {
            foreach ($activeUsers as $key => $item) {
                $company = Customer::where('company_id', auth()->user()->company_id)->where('username', $item['name'])->first();
                if ($company) {
                    foreach ($this->tableColumnNames() as $columnItem) {
                        $columnName = $columnItem['data'];
                        $nestedData[$columnItem['data']] = $item[$columnName] ?? 'N/A';
                    }
                    $data[] = $nestedData;
                }
            }
        }
        $html = view($this->viewName . '.table', get_defined_vars());
        return $html;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Active Connection  $user
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, M_Secret $m_secret)
    {
        $modal_title = 'Active Connection Details';
        $modal_data = $m_secret;

        $html = view($this->viewName . '.show', get_defined_vars())->render();
        return $html;
    }
}
