<?php

namespace App\Http\Controllers\StaticIp;

use App\Http\Controllers\Controller;
use App\Models\MikrotikServer;
use App\Models\Queue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \RouterOS\Query;

class QueueController extends Controller
{
    /**
     * String property
     */
    protected $routeName =  'queue';
    protected $viewName =  'admin.pages.staticip.queue';

    protected function getModel()
    {
        return new Queue();
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
                'label' => 'Name',
                'data' => 'queue_name',
                'searchable' => true,
            ],
            [
                'label' => 'Target',
                'data' => 'queue_target',
                'searchable' => true,
            ],
            [
                'label' => 'Dst',
                'data' => 'queue_dst',
                'searchable' => true,
            ],
            [
                'label' => 'Max Upload',
                'data' => 'queue_max_upload',
                'searchable' => true,
            ],
            [
                'label' => 'Max Download',
                'data' => 'queue_max_download',
                'searchable' => true,
            ],
            // [
            //     'label' => 'Status',
            //     'data' => 'queue_disabled',
            //     'checked' => ['false'],
            //     'searchable' => false,
            // ],

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
        $page_title = "Queue";
        $page_heading = "Queue List";
        $ajax_url = route($this->routeName . '.dataProcessing');
        $create_url = route($this->routeName . '.create');
        $is_show_checkbox = false;
        $columns = $this->reformatForRelationalColumnName(
            $this->tableColumnNames()
        );
        return view('admin.pages.index', get_defined_vars());
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
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
                    'method_name' => 'edit',
                    'class' => 'btn-success',
                    'fontawesome' => 'fa fa-edit',
                    'text' => '',
                    'title' => 'Edit',
                ],
            ]
        );
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page_title = "Queue Create";
        $page_heading = "Queue Create";
        $back_url = route($this->routeName . '.index');
        $store_url = route($this->routeName . '.store');
        $servers = MikrotikServer::condition()->get();
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
            'queue_name' => 'required',
            'queue_target' => 'required',
            'server_id' => 'required',
            'queue_dst' => ['nullable'],
            'queue_max_upload' => ['nullable'],
            'amount' => ['required'],
            'queue_max_download' => ['nullable'],
        ]);

        try {
            DB::beginTransaction();
            $client = $this->client($request->server_id);
            $query = new Query('/queue/simple/add');
            $query->equal('name', $request->queue_name);
            $query->equal('target', $request->queue_target);
            $request->queue_dst ? $query->equal('dst', $request->queue_dst) : null;
            $query->equal('max-limit', $request->queue_max_upload . '/' . $request->queue_max_download);
            $response = $client->q($query)->r();
            if (isset($response['after']['ret']) && $response['after']['ret']) {
                $valideted['queue_mid'] = $response['after']['ret'];
                $valideted['queue_disabled'] = 'false';
                Queue::create($valideted);
            } else {
                return back()->with('failed', 'OOps.., something was wrong Mikrotik' . $response);
            }
            DB::commit();
            return back()->with('success', 'Data Store Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('failed', 'Oops! Something was wrong. Message: ' . $e->getMessage() . ' Line: ' . $e->getLine() . 'File: ' . $e->getFile());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Account  $Account
     * @return \Illuminate\Http\Response
     */
    public function edit(Queue $Queue)
    {
        $page_title = "Queue Edit";
        $page_heading = "Queue Edit";
        $back_url = route($this->routeName . '.index');
        $update_url = route($this->routeName . '.update', $Queue->id);
        $editinfo = $Queue;
        $servers = MikrotikServer::condition()->get();
        return view($this->viewName . '.edit', get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vlan  $Account
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Queue $Queue)
    {
        $valideted = $this->validate($request, [
            'queue_name' => 'required',
            'queue_target' => 'required',
            'server_id' => 'required',
            'queue_dst' => ['nullable'],
            'amount' => ['required'],
            'queue_max_upload' => ['nullable'],
            'queue_max_download' => ['nullable'],
        ]);

        try {
            DB::beginTransaction();
            $client = $this->client($request->server_id);
            $query = new Query('/queue/simple/set');
            $query->equal('.id', $Queue->queue_mid);
            $query->equal('name', $request->queue_name);
            $query->equal('target', $request->queue_target);
            $request->queue_dst ?  $query->equal('dst', $request->queue_dst) : null;
            $query->equal('max-limit', $request->queue_max_upload . '/' . $request->queue_max_download);
            $response = $client->q($query)->r();
            if (isset($response['after']['ret']) && $response['after']['ret']) {
                $Queue->update($valideted);
            } else {
                return back()->with('failed', 'OOps.., something was wrong Mikrotik' . $response);
            }

            DB::commit();
            return back()->with('success', 'Data Updated Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('failed', 'Oops! Something was wrong. Message: ' . $e->getMessage() . ' Line: ' . $e->getLine() . 'File: ' . $e->getFile());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Account  $Account
     * @return \Illuminate\Http\Response
     */
    public function statusUpdate(Queue $Queue)
    {
        $status = $Queue->status == 'Active' ? 'Inactive' : 'Active';
        $Queue->update(['status' => $status]);
        return true;
    }

    public function disabled(Queue $Queue)
    {
        $status = $Queue->disabled == 'true' ? 'false' : "true";
        $Queue->update(['disabled' => $status]);
        $this->Queuedisabled($Queue);
        return back()->with('success', 'Status Update successfully.');
    }
    public function destroy(Queue $Queue)
    {
        $Queue->delete();
        return back()->with('success', 'Data deleted successfully.');
    }
}
