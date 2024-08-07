<?php

namespace App\Http\Controllers\Admin\Customer;

use \RouterOS\Query;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\BillingStatus;
use App\Models\ClientType;
use App\Models\ConnectionType;
use App\Models\Customer;
use App\Models\Device;
use App\Models\MikrotikServer;
use App\Models\MPPPProfile;
use App\Models\Package2;
use App\Models\Protocol;
use App\Models\Queue;
use App\Models\Subzone;
use App\Models\Zone;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use App\Helpers\DataProcessingFile\StaticDataProcessing;
use App\Models\Billing;

class StaticCustomerController extends Controller
{
    /**
     * String property
     */
    use StaticDataProcessing;
    protected $routeName =  'static_customers';
    protected $viewName =  'admin.pages.static_customers';


    protected function getModel()
    {
        return new Customer();
    }

    protected function tableColumnNames()
    {
        return [
            [
                'label' => 'IP',
                'data' => 'id',
                'searchable' => false,
            ],
            [
                'label' => 'Client Id',
                'data' => 'client_id',
                'searchable' => true,
            ],
            [
                'label' => 'C.Name',
                'data' => 'name',
                'searchable' => true,
            ],
            [
                'label' => 'Mobile Number',
                'data' => 'phone',
                'searchable' => true,
            ],
            [
                'label' => 'Zone',
                'data' => 'name',
                'customesearch' => 'zone_id',
                'searchable' => false,
                'relation' => 'getZone',
            ],

            [
                'label' => 'Cli.Type',
                'data' => 'name',
                'customesearch' => 'client_type_id',
                'searchable' => false,
                'relation' => 'getClientType',
            ],
            [
                'label' => 'Expiry Date',
                'data' => 'exp_date',
                'searchable' => false,
            ],
            [
                'label' => 'Queue',
                'data' => 'queue_name',
                'customesearch' => 'package_id',
                'searchable' => false,
            ],
            [
                'label' => 'Billing',
                'data' => 'bill_amount',
                'searchable' => false,
            ],
            [
                'label' => 'Bill.Status',
                'data' => 'name',
                'customesearch' => 'billing_status_id',
                'searchable' => false,
                'relation' => 'getBillingStatus',
            ],
            [
                'label' => 'MIk.Status',
                'data' => 'queue_disabled',
                'checked' => ['false'],
                'searchable' => false,
            ],
            [
                'label' => 'Sub Zone',
                'customesearch' => 'subzone_id',
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
        $page_title = "Static Customer";
        $page_heading = "Static Customer List";
        $ajax_url = route($this->routeName . '.dataProcessing');
        $create_url = route($this->routeName . '.create');
        $is_show_checkbox = false;
        $servers = MikrotikServer::where('status', true)->get();
        $clienttyps = ClientType::where('status', true)->get();
        $zones = Zone::get();
        $subzones = Subzone::get();
        $connectiontypes = ConnectionType::where('status', 'active')->get();
        $protocoltypes = Protocol::where('status', 'active')->get();
        $package2s = Package2::where('status', true)->get();
        $billingStatus = BillingStatus::where('status', 'active')->get();

        $columns = $this->reformatForRelationalColumnName(
            $this->tableColumnNames()
        );
        // dd(get_defined_vars());
        return view($this->viewName . '.index', get_defined_vars());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function dataProcessing()
    {
        return $this->getDataResponse(
            //Model Instance
            $this->getModel()->where('type', 'internet')->where('company_id', auth()->user()->company_id)->where('protocol_type_id', 1),
            //Table Columns Name
            $this->tableColumnNames(),
            //Route name
            $this->routeName
        );
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page_title = "Static Customer Create";
        $page_heading = "Static Customer Create";
        $back_url = route($this->routeName . '.index');
        $store_url = route($this->routeName . '.store');
        $packages = Package2::where('tariffconfig_id', User::getmacReseler() ? User::getmacReseler()->tariff_id : 0)->get();
        $users = User::where('company_id', auth()->user()->company_id)->get(); //where('is_admin', 4);
        $zones = Zone::all();
        $servers = MikrotikServer::where('status', true)->get();
        $protocolTypes = Protocol::where('status', 'active')->get();
        $devices = Device::where('status', 'active')->get();
        $code = Customer::where('company_id', auth()->user()->company_id)->count();
        $connectionType = ConnectionType::where('status', 'active')->get();
        $clientType = ClientType::where('status', true)->get();
        $billingStatus = BillingStatus::where('status', 'active')->get();
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
            'name' => ['required'],
            'father_name' => ['nullable'],
            'mother_name' => ['nullable'],
            'spouse_name' => ['nullable'],
            'nid' => ['nullable'],
            'passport' => ['nullable'],
            'dob' => ['nullable'],
            'email' => ['nullable', 'email'],
            'reference' => ['nullable'],
            'zone_id' => ['nullable'],
            'comment' => ['nullable'],
            'phone' => ['nullable'],
            'duration' => ['nullable'],
            'package_id' => ['nullable'],
            'billing_person' => ['nullable'],
            'doc_image' => ['nullable'],
            'address' => ['nullable'],
            'bill_amount' => ['required'],
            'connection_date' => ['nullable'],
            'client_id' => ['required', 'unique:customers,client_id'],
            'billing_date' => ['nullable'],
            'bill_collection_date' => ['nullable'],
            'disabled' => ['nullable'],
            'server_id' => ['required'],
            'billing_type' => ['nullable'],
            'device_id' => ['nullable'],
            'client_type_id' => ['nullable'],
            'billing_status_id' => ['required'],
            'queue_target' => ['required'],
            'queue_dst' => ['nullable'],
            'queue_name' => ['required'],
            'queue_max_upload' => ['required'],
            'queue_max_download' => ['required'],

        ]);

        try {
            DB::beginTransaction();
            $valideted['dob'] = Carbon::parse($request->dob);
            $valideted['protocol_type_id'] = 1;
            $valideted['company_id'] = auth()->user()->company_id;

            if ($request->hasFile('doc_image')) {
                $path = $request->file('doc_image')->store('customer', 'public');
                $valideted['doc_image'] = $path;
            }

            $startDate =  $valideted['start_date'] = $request->billing_type == 'month_to_month' ? Carbon::now()->startOfMonth()->format('Y-m-d') : $request->start_date;

            if ($request->billing_type == 'month_to_month') {
                $submonth = 0;
                $day = $request->bill_collection_date == 0 ? -1 : $request->bill_collection_date;
            } else {
                $submonth = 1;
                $day = $request->bill_collection_date;
            }

            $valideted['exp_date'] = Carbon::parse($startDate)->addMonths($request->duration)->subMonth($submonth)->day($day)->format('Y-m-d');

            $client = $this->client($request->server_id);
            $query = new Query('/queue/simple/add');
            $query->equal('name', $request->queue_name);
            $query->equal('target', $request->queue_target);
            $request->queue_dst ? $query->equal('dst', $request->queue_dst) : null;
            $query->equal('max-limit', $request->queue_max_upload . '/' . $request->queue_max_download);
            $response = $client->q($query)->r();
            if (isset($response['after']['ret']) && $response['after']['ret']) {
                $valideted['queue_id'] = $response['after']['ret'];
                $valideted['queue_disabled'] = 'false';
                $valideted['created_by'] = auth()->id();
                Customer::create($valideted);
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
     * @param  \App\Models\Customer  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        $page_title = "Static Customer Edit";
        $page_heading = " Customer Edit";
        $packages = Package2::where('tariffconfig_id', User::getmacReseler() ? User::getmacReseler()->tariff_id : 0)->get();
        $queues = Queue::where('queue_disabled', 'false')->get();
        $back_url = route($this->routeName . '.index');
        $update_url = route($this->routeName . '.update', $customer->id);
        $editinfo = $customer;
        $users = User::get(); //where('is_admin', 4);
        $zones = Zone::all();
        // configiration
        $servers = MikrotikServer::where('status', true)->get();
        $protocolTypes = Protocol::where('status', 'active')->get();
        $devices = Device::where('status', 'active')->get();
        $connectionType = ConnectionType::where('status', 'active')->get();
        $clientType = ClientType::where('status', true)->get();
        $billingStatus = BillingStatus::where('status', 'active')->get();
        return view($this->viewName . '.edit', get_defined_vars());
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        $valideted = $this->validate($request, [
            'name' => ['required'],
            'father_name' => ['nullable'],
            'mother_name' => ['nullable'],
            'spouse_name' => ['nullable'],
            'nid' => ['nullable'],
            'passport' => ['nullable'],
            'dob' => ['nullable'],
            'email' => ['nullable', 'email'],
            'reference' => ['nullable'],
            'zone_id' => ['nullable'],
            'comment' => ['nullable'],
            'phone' => ['nullable'],
            'duration' => ['nullable'],
            'package_id' => ['nullable'],
            'billing_person' => ['nullable'],
            'doc_image' => ['nullable'],
            'address' => ['nullable'],
            'client_id' => ['required', 'unique:customers,client_id,' . $customer->id],
            'bill_amount' => ['required'],
            'connection_date' => ['nullable'],
            'billing_date' => ['nullable'],
            'bill_collection_date' => ['nullable'],
            'disabled' => ['nullable'],
            'server_id' => ['required'],
            'billing_type' => ['nullable'],
            // 'protocol_type_id' => ['required'],
            'device_id' => ['nullable'],
            // 'connection_type_id' => ['nullable'],
            'client_type_id' => ['nullable'],
            'billing_status_id' => ['required'],
            'queue_target' => ['required'],
            'queue_dst' => ['nullable'],
            'queue_name' => ['required'],
            'queue_max_upload' => ['required'],
            'queue_max_download' => ['required'],

        ]);

        try {
            DB::beginTransaction();
            $valideted['dob'] = Carbon::parse($request->dob);
            $valideted['protocol_type_id'] = 1;

            if ($request->hasFile('doc_image')) {
                $path = $request->file('doc_image')->store('customer', 'public');
                $valideted['doc_image'] = $path;
            }

            if (
                !($customer->duration == $request->duration) ||
                !($customer->start_date == $request->start_date) ||
                !($customer->bill_collection_date == $request->bill_collection_date) ||
                !($customer->billing_type == $request->billing_type) ||
                empty($customer->duration) ||
                empty($customer->bill_collection_date)
            ) {
                $startDate =  $valideted['start_date'] = $request->billing_type == 'month_to_month' ? Carbon::now()->startOfMonth()->format('Y-m-d') : $request->start_date;
                if ($request->billing_type == 'month_to_month') {
                    $submonth = 0;
                    $day = $request->bill_collection_date == 0 ? -1 : $request->bill_collection_date;
                } else {
                    $submonth = 1;
                    $day = $request->bill_collection_date;
                }
                $valideted['exp_date'] = Carbon::parse($startDate)->addMonths($request->duration)->subMonth($submonth)->day($day)->format('Y-m-d');
            }
            $client = $this->client($request->server_id);
            $query = new Query('/queue/simple/set');
            $query->equal('.id', $customer->queue_id);
            $query->equal('name', $request->queue_name);
            $query->equal('target', $request->queue_target);
            $request->queue_dst ?  $query->equal('dst', $request->queue_dst) : null;
            $query->equal('max-limit', $request->queue_max_upload . '/' . $request->queue_max_download);
            $response = $client->q($query)->r();
            if (empty($response)) {
                $valideted['updated_by'] = auth()->id();
                $customer->update($valideted);
            } else {
                return  $response;
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
     * @param  \App\Models\Customer  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();
        return back()->with('success', 'Data deleted successfully.');
    }

    public function getProfile(Request $request)
    {
        $profile = Package2::find($request->id);
        return response()->json([
            "amount" => $profile->price,
            "speed" => $profile->bandwidth_allocation,
        ]);
    }

    public function mikrotikStatus(Customer $customer)
    {
        $status = $customer->queue_disabled == 'true' ? 'false' : 'true';
        if ($customer->protocol_type_id == 1 && $customer->queue_disabled != 10) {
            $customer->update(['queue_disabled' => $status]);
            $this->static_customer_active_inactive($customer);
        }
        return response()->json(200);
    }
}
