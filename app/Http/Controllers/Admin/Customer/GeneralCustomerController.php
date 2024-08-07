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
use Illuminate\Validation\Rule;

class GeneralCustomerController extends Controller
{
    /**
     * String property
     */
    protected $routeName =  'general_customers';
    protected $viewName =  'admin.pages.general_customers';


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
                'label' => 'IP/ID',
                'data' => 'username',
                'searchable' => true,
            ],
            [
                'label' => 'Password',
                'data' => 'm_password',
                'searchable' => false,
            ],
            [
                'label' => 'C.Name',
                'data' => 'name',
                'searchable' => true,
            ],
            [
                'label' => 'Mobile Number',
                'data' => 'phone',
                'searchable' => false,
            ],
            // [
            //     'label' => 'Zone',
            //     'data' => 'name',
            //     'customesearch' => 'zone_id',
            //     'searchable' => false,
            //     'relation' => 'getZone',
            // ],
            [
                'label' => 'Conn.Type',
                'data' => 'name',
                'customesearch' => 'connection_type_id',
                'searchable' => false,
                'relation' => 'getConnectionType',
            ],

            [
                'label' => 'Cli.Type',
                'data' => 'name',
                'customesearch' => 'client_type_id',
                'searchable' => false,
                'relation' => 'getClientType',
            ],
            [
                'label' => 'Protocol Type',
                'data' => 'name',
                'customesearch' => 'protocol_type_id',
                'searchable' => false,
                'relation' => 'getProtocolType',
            ],
            [
                'label' => 'Expiry Date',
                'data' => 'exp_date',
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
        $page_title = "General Customer";
        $page_heading = "General Customer List";
        $ajax_url = route($this->routeName . '.dataProcessing');
        $create_url = route($this->routeName . '.create');
        $is_show_checkbox = false;
        $servers = MikrotikServer::where('status', true)->get();
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
            $this->getModel()->where('company_id', auth()->user()->company_id)->where('protocol_type_id', 1),
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

        $queues = Queue::where('queue_disabled', 'false')->get();
        $users = User::get(); //where('is_admin', 4);
        $zones = Zone::where('company_id', auth()->user()->company_id)->get();
        $servers = MikrotikServer::where('status', true)->get();
        $protocolTypes = Protocol::where('status', 'active')->get();
        $devices = Device::where('status', 'active')->where('company_id', auth()->user()->company_id)->get();
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
            'spouse_name' => ['nullable'],
            'nid' => ['nullable'],
            'passport' => ['nullable'],
            'dob' => ['nullable'],
            'email' => ['nullable', 'email'],
            'reference' => ['nullable'],
            // 'zone_id' => ['nullable'],
            // 'comment' => ['nullable'],
            'phone' => ['required'],
            // 'duration' => ['nullable'],
            // 'm_p_p_p_profile' => ['required'],
            // 'package_id' => ['nullable'],
            // 'billing_person' => ['required'],
            'doc_image' => ['nullable'],
            // 'mac_address' => ['nullable'],
            // 'ip_address' => ['nullable'],
            // 'remote_address' => ['nullable'],
            'address' => ['nullable'],
            // 'bill_amount' => ['required'],
            // 'connection_date' => ['nullable'],
            // 'billing_date' => ['nullable'],
            // 'bill_collection_date' => ['nullable'],
            // 'disabled' => ['nullable'],
            // 'server_id' => ['required'],
            // 'billing_type' => ['required'],
            // 'protocol_type_id' => ['required'],
            // 'device_id' => ['nullable'],
            // 'connection_type_id' => ['nullable'],
            // 'client_type_id' => ['required'],
            // 'billing_status_id' => ['required'],
            // 'password' => ['required', 'confirmed'],
            // 'queue_id' => ['required'],

        ]);

        try {
            DB::beginTransaction();
            $valideted['dob'] = Carbon::parse($request->dob);
            $valideted['m_password'] = $request->password;
            $valideted['password'] = Hash::make($request->password);
            $valideted['protocol_type_id'] = 1;
            $valideted['company_id'] = auth()->user()->company_id;
            $valideted['type'] = 'general';
            $valideted['created_by'] = auth()->id();
            Customer::create($valideted);


            // InstallationFee::firstOrCreate(
            //     [
            //         "customer_id" => $customer->id,
            //     ],
            //     [
            //         "installation_fee" => $request->installation_fee,
            //         "created_on" => Carbon::now()->format('Y-m-d'),
            //         "received_amount" => 0,
            //         "due" => 0,

            //     ]
            // );

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
        $zones = Zone::where('company_id', auth()->user()->company_id)->get();
        // configiration
        $servers = MikrotikServer::where('status', true)->get();
        $protocolTypes = Protocol::where('status', 'active')->get();
        $devices = Device::where('status', 'active')->where('company_id', auth()->user()->company_id)->get();
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
            'spouse_name' => ['nullable'],
            'nid' => ['nullable'],
            'passport' => ['nullable'],
            'dob' => ['nullable'],
            'email' => ['nullable', 'email'],
            'reference' => ['nullable'],
            // 'zone_id' => ['nullable'],
            // 'comment' => ['nullable'],
            'phone' => ['required'],
            // 'duration' => ['nullable'],
            // 'm_p_p_p_profile' => ['required'],
            // 'package_id' => ['nullable'],
            // 'billing_person' => ['required'],
            'doc_image' => ['nullable'],
            // 'mac_address' => ['nullable'],
            // 'ip_address' => ['nullable'],
            // 'remote_address' => ['nullable'],
            'address' => ['nullable'],
            // 'bill_amount' => ['required'],
            // 'connection_date' => ['nullable'],
            // 'billing_date' => ['nullable'],
            // 'bill_collection_date' => ['nullable'],
            // 'disabled' => ['nullable'],
            // 'server_id' => ['required'],
            // 'billing_type' => ['required'],
            // 'protocol_type_id' => ['required'],
            // 'device_id' => ['nullable'],
            // 'connection_type_id' => ['nullable'],
            // 'client_type_id' => ['required'],
            // 'billing_status_id' => ['required'],
            // 'password' => ['required', 'confirmed'],
            // 'queue_id' => ['required'],

        ]);

        try {
            DB::beginTransaction();
            $valideted['dob'] = Carbon::parse($request->dob);
            $valideted['protocol_type_id'] = 1;
            if ($request->filled('password')) {
                $valideted['m_password'] = $request->password;
                $valideted['password'] = Hash::make($request->password);
            }

            if ($request->hasFile('doc_image')) {
                $path = $request->file('doc_image')->store('customer', 'public');
                $valideted['doc_image'] = $path;
            }
            if (
                !($customer->duration == $request->duration) ||
                !($customer->bill_collection_date == $request->bill_collection_date) ||
                !($customer->billing_type == $request->billing_type) ||
                empty($customer->duration) ||
                empty($customer->bill_collection_date)
            ) {
                $startDate =  $valideted['start_date'] = $request->billing_type == 'month_to_month' ? new Carbon('first day of this month') : $request->start_date;
                $valideted['exp_date'] = Carbon::parse($startDate)->addMonths($request->duration)->addDay($request->bill_collection_date)->format('Y-m-d');
            }

            $valideted['updated_by'] = auth()->id();
            $customer->update($valideted);

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
        $status = $customer->disabled == 'true' ? 'false' : 'true';
        if ($customer->protocol_type_id == 3 && $customer->disabled != 10) {
            $customer->update(['disabled' => $status]);
            $this->customer_active_inactive($customer);
        }
        return response()->json(200);
    }
}
