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
use App\Models\Subzone;
use App\Models\Zone;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use App\Helpers\DataProcessingFile\CustomerDataProcessing;
use App\Models\Billing;
use App\Models\Box;
use App\Models\MacCustomerBill;
use App\Models\Splitter;
use App\Models\Tj;
use App\Models\Transaction;
use Illuminate\Support\Facades\Http;

class CustomerController extends Controller
{

    use CustomerDataProcessing;
    /**
     * String property
     */
    protected $routeName =  'customers';
    protected $viewName =  'admin.pages.customers';


    protected function getModel()
    {
        return new Customer();
    }

    protected function tableColumnNames()
    {
        return [
            [
                'label' => 'Sl',
                'data' => 'id',
                'searchable' => false,
            ],
            [
                'label' => 'Client Id',
                'data' => 'client_id',
                'searchable' => true,
            ],
            [
                'label' => 'IP/ID',
                'data' => 'username',
                'searchable' => true,
            ],
            [
                'label' => 'Password',
                'data' => 'm_password',
                'searchable' => true,
            ],


            [
                'label' => 'Mobile Number',
                'data' => 'phone',
                'searchable' => true,
            ],

            [
                'label' => 'Expiry Date',
                'data' => 'exp_date',
                'searchable' => false,
            ],
            [
                'label' => 'Box',
                'data' => 'box_id',
                'customesearch' => 'box_id',
                'searchable' => false,
                'relation' => 'getBox',
            ],
            [
                'label' => 'Package',
                'data' => 'name',
                'customesearch' => 'm_p_p_p_profile',
                'searchable' => false,
                'relation' => 'getMProfile',
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
                'data' => 'disabled',
                'checked' => ['false'],
                'searchable' => false,
            ],
            [
                'label' => 'Server',
                'data' => 'server_id',
                'customesearch' => 'server_id',
                'searchable' => false,
            ],
            [
                'label' => 'zone',
                'data' => 'zone_id',
                'customesearch' => 'zone_id',
                'searchable' => false,
            ],
            [
                'label' => 'Sub Zone',
                'data' => 'subzone_id',
                'customesearch' => 'subzone_id',
                'searchable' => false,
            ],
            [
                'label' => 'Cli.Type',
                'data' => 'connection_type_id',
                'customesearch' => 'connection_type_id',
                'searchable' => false,
            ],
            [
                'label' => 'Device',
                'data' => 'device_id',
                'customesearch' => 'device_id',
                'searchable' => false,
            ],
            [
                'label' => 'TJ',
                'data' => 'tj_id',
                'customesearch' => 'tj_id',
                'searchable' => false,
            ],
            [
                'label' => 'Splitter',
                'data' => 'splitter_id',
                'customesearch' => 'splitter_id',
                'searchable' => false,
            ],
            [
                'label' => 'Billing type',
                'data' => 'billing_type',
                'searchable' => false,
            ],
            [
                'label' => 'from search',
                'data' => 'from_search',
                'searchable' => false,
            ],
            [
                'label' => 'to search',
                'data' => 'to_search',
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
        $page_title = "Pppoe Customer";
        $page_heading = "Pppoe Customer List";
        $ajax_url = route($this->routeName . '.dataProcessing');
        $create_url = route($this->routeName . '.create');
        $is_show_checkbox = false;
        $servId = Customer::where('company_id', auth()->user()->company_id)->groupBy('server_id')->pluck('server_id');
        $servers = MikrotikServer::where('status', true)->whereIn('id', $servId)->get();
        $clienttyps = ClientType::where('status', true)->get();
        $zones = Zone::where('company_id', auth()->user()->company_id)->get();
        $devices  = Device::where('status', 'active')->get();
        $package2s = MPPPProfile::get();
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
        $model = $this->getModel()->where('type', 'internet')->where('company_id', auth()->user()->company_id)->where('protocol_type_id', 3);
        if (auth()->user()->is_admin == 4)
            $model = $model->where('type', 'internet')->where('billing_person', auth()->id());
        return $this->getDataResponse(
            //Model Instance
            $model,
            //Table Columns Name
            $this->tableColumnNames(),
            //Route name
            $this->routeName,
            true,
            [
                [
                    'method_name' => 'paylist',
                    'class' => 'btn-info  btn-sm',
                    'fontawesome' => 'fa fa-eye',
                    'text' => '',
                    'title' => 'View',
                ],
                [
                    'method_name' => 'edit',
                    'class' => 'btn-secondary  btn-sm',
                    'fontawesome' => 'fa fa-edit',
                    'text' => '',
                    'title' => 'Edit',
                ],
                [
                    'method_name' => 'destroy',
                    'class' => 'btn-danger delete_customer btn-sm',
                    'fontawesome' => 'fa fa-trash',
                    'text' => '',
                    'title' => 'Delete',
                    'code' => 'onclick="return confirm(`Are You Sure`)"',
                ],
            ],
        );
    }


    public function paylist(Customer $billcollected)
    {
        $back_url = route($this->routeName . '.index');
        $customerPaymentDetails = Billing::where('customer_id', $billcollected->id)->get();
        return view('admin.pages.billcollect.view', get_defined_vars());
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        $page_title = "Pppoe Customer Create";
        $page_heading = "Pppoe Customer Create";
        $back_url = route($this->routeName . '.index');
        $store_url = route($this->routeName . '.store');
        $packages = Package2::where('tariffconfig_id', auth()->user()->mac_reseler ? auth()->user()->mac_reseler->tariff_id : 0)->pluck('m_profile_id');
        $profiles = auth()->user()->mac_reseler ?  MPPPProfile::whereIn('id', $packages)->get() : MPPPProfile::get();
        $users = User::where('company_id', auth()->user()->company_id)->whereIn('is_admin', [1, 3, 4])->get(); //where('is_admin', 4);
        $zones = Zone::where('company_id', auth()->user()->company_id)->get();
        $servId = Customer::where('company_id', auth()->user()->company_id)->groupBy('server_id')->pluck('server_id');
        $servers = MikrotikServer::where('status', true)->whereIn('id', $servId)->get();
        $protocolTypes = Protocol::where('status', 'active')->get();
        $devices = Device::where('status', 'active')->where('company_id', auth()->user()->company_id)->get();
        $connectionType = ConnectionType::where('status', 'active')->get();
        $code = Customer::where('company_id', auth()->user()->company_id)->count();
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
            'username' => ['required'],
            'father_name' => ['nullable'],
            'mother_name' => ['nullable'],
            'spouse_name' => ['nullable'],
            'nid' => ['nullable'],
            'nid_front' => ['nullable'],
            'nid_back' => ['nullable'],
            'passport' => ['nullable'],
            'dob' => ['nullable'],
            'email' => ['nullable', 'email'],
            'reference' => ['nullable'],
            'comment' => ['nullable'],
            'phone' => ['required'],
            'duration' => ['nullable'],
            'm_p_p_p_profile' => ['required'],
            'package_id' => ['nullable'],
            'billing_person' => ['required'],
            'doc_image' => ['nullable'],
            'mac_address' => ['nullable'],
            'ip_address' => ['nullable'],
            'remote_address' => ['nullable'],
            'address' => ['nullable'],
            'bill_amount' => ['required'],
            'connection_date' => ['nullable'],
            'billing_date' => ['nullable'],
            'bill_collection_date' => ['nullable'],
            'disabled' => ['nullable'],
            'server_id' => ['required'],
            'billing_type' => ['required'],
            'client_id' => ['required'],
            'device_id' => ['nullable'],
            'client_type_id' => ['required'],
            'billing_status_id' => ['required'],
            'password' => ['required', 'confirmed'],
            'zone_id' => ["nullable"],
            'subzone_id' => ["nullable"],
            'tj_id' => ["nullable"],
            'splitter_id' => ["nullable"],
            'box_id' => ["nullable"],
            'auto_line_off' => ["required"]
        ]);

        try {

            $Mpppprofile = MPPPProfile::find($request->m_p_p_p_profile);

            if (auth()->user()->mac_reseler && auth()->user()->mac_reseler->reseller_type == "prepaid") {

                $macDetails = auth()->user()->mac_reseler;

                $charge = $macDetails->tariff->package->where('m_profile_id', $Mpppprofile->id)->pluck('rate')->first();

                // $checkbill = MacCustomerBill::where('customer_id', $customer->id)->whereMonth('date_', date('m-Y'));
                // if (!$checkbill->first()) {
                if ($macDetails->recharge_balance < $charge) {
                    return back()->with('failed', 'Please Recharge Your Account');
                };
                // $macDetails->update(['recharge_balance' => $macDetails->recharge_balance - $charge]);
                // $checkbill->create([
                //     'customer_id' => $customer->id,
                //     'date_' => today()->format('Y-m-d'),
                //     'charge' => $charge,
                //     'company_id' => auth()->user()->company_id,
                //     'created_by' => auth()->id(),
                // ]);
                // }
            }

            DB::beginTransaction();
            $valideted['dob'] = Carbon::parse($request->dob);
            $valideted['m_password'] = $request->password;
            $valideted['disabled'] = 'false';
            $valideted['password'] = Hash::make($request->password);
            $valideted['protocol_type_id'] = 3;
            $valideted['company_id'] = auth()->user()->company_id;
            $valideted['created_by'] = auth()->id();

            if ($request->hasFile('doc_image')) {
                $path1 = $request->file('doc_image')->store('customer', 'public');
                $valideted['doc_image'] = $path1;
            }

            if ($request->hasFile('nid_front')) {
                $path2 = $request->file('nid_front')->store('customer', 'public');
                $valideted['nid_front'] = $path2;
            }

            if ($request->hasFile('nid_back')) {
                $path3 = $request->file('nid_back')->store('customer', 'public');
                $valideted['nid_back'] = $path3;
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

            if (auth()->user()->mac_reseler && auth()->user()->mac_reseler->set_prefix_mikrotikuser == 'yes') {
                $prifix = stripos($request->username, auth()->user()->mac_reseler->reseller_prefix) ? "" : auth()->user()->mac_reseler->reseller_prefix;
                $valideted['username'] = $prifix . $request->username;
            } else {
                $prifix = "";
            }

            $client = $this->client($request->server_id);
            $query = new Query('/ppp/secret/add');
            $query->equal('name', $prifix . $request->username);
            $query->equal('service', "pppoe");
            $query->equal('caller-id', $request->mac_address);
            $request->ip_address ?  $query->equal('local-address', $request->ip_address) : null;
            $request->remote_address ? $query->equal('remote-address', $request->remote_address) : null;
            $query->equal('profile', $Mpppprofile->name);
            $query->equal('password', $request->password);
            $query->equal('comment', $request->comment);
            $response = $client->q($query)->r();
            if (isset($response['after']['ret']) && $response['after']['ret']) {
                $valideted['mid'] = $response['after']['ret'];
                $valideted['created_by'] = auth()->id();
                $customer = Customer::create($valideted);
            } else {
                return  $response;
                return back()->with('failed', 'OOps.., something was wrong Mikrotik');
            }

            $message = messageconvert($customer, $customer->getCompany->create_msg);
            sendSms($request->phone, $message);

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
        $page_title = "Pppoe Customer Edit";
        $page_heading = "Pppoe Customer Edit";

        $packages = Package2::where('tariffconfig_id', auth()->user()->mac_reseler ? auth()->user()->mac_reseler->tariff_id : 0)->pluck('m_profile_id');
        $profiles = auth()->user()->mac_reseler ?  MPPPProfile::whereIn('id', $packages)->get() : MPPPProfile::get();
        $back_url = route($this->routeName . '.index');
        $update_url = route($this->routeName . '.update', $customer->id);
        $editinfo = $customer;

        $users = User::where('company_id', auth()->user()->company_id)->whereIn('is_admin', [1, 3, 4])->get(); //where('is_admin', 4);
        $zones = Zone::where('company_id', auth()->user()->company_id)->get();
        $subzones = Subzone::where('company_id', auth()->user()->company_id)->get();
        $splitters = Splitter::get();
        $tjs = Tj::get();
        $boxs = Box::get();
        // configiration
        $servId = Customer::where('company_id', auth()->user()->company_id)->groupBy('server_id')->pluck('server_id');
        $servers = MikrotikServer::where('status', true)->whereIn('id', $servId)->get();
        $protocolTypes = Protocol::where('status', 'active')->get();
        $devices = Device::where('status', 'active')->where('company_id', auth()->user()->company_id)->get();
        $connectionType = ConnectionType::where('status', 'active')->get();
        $customer = Customer::latest('id')->pluck('id')->first() ?? "0";
        $code = str_pad($customer + 1, 8, "0", STR_PAD_LEFT);
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
            'username' => ['required'],
            'father_name' => ['nullable'],
            'mother_name' => ['nullable'],
            'spouse_name' => ['nullable'],
            'nid' => ['nullable'],
            'nid_front' => ['nullable'],
            'nid_back' => ['nullable'],
            'passport' => ['nullable'],
            'dob' => ['nullable'],
            'email' => ['nullable', 'email'],
            'reference' => ['nullable'],
            'comment' => ['nullable'],
            'phone' => ['required'],
            'duration' => ['nullable'],
            'm_p_p_p_profile' => ['required'],
            'package_id' => ['nullable'],
            'billing_person' => ['required'],
            'client_id' => ['required'],
            'doc_image' => ['nullable'],
            'mac_address' => ['nullable'],
            'ip_address' => ['nullable'],
            'remote_address' => ['nullable'],
            'address' => ['nullable'],
            'bill_amount' => ['required'],
            'connection_date' => ['nullable'],
            'billing_date' => ['nullable'],
            'bill_collection_date' => ['nullable'],
            'server_id' => ['required'],
            'billing_type' => ['required'],
            'device_id' => ['nullable'],
            'client_type_id' => ['required'],
            'billing_status_id' => ['required'],
            'password' => ['nullable', 'confirmed'],
            'zone_id' => ["required"],
            'subzone_id' => ["nullable"],
            'tj_id' => ["nullable"],
            'splitter_id' => ["nullable"],
            'box_id' => ["nullable"],
            'auto_line_off' => ["required"]
        ]);

        try {

            $Mpppprofile = MPPPProfile::find($request->m_p_p_p_profile);

            if (auth()->user()->mac_reseler && auth()->user()->mac_reseler->reseller_type == "prepaid") {
                // Check Customer Balance Update
                $macbalancecheck = new MacBalanceCheckAndUpdate();
                $statuscheck = $macbalancecheck->checkbalance($customer, $request);
                if ($statuscheck == "0") {
                    return back()->with('failed', 'Please Recharge Your Account');
                }
            }
            DB::beginTransaction();
            $valideted['dob'] = Carbon::parse($request->dob);
            $valideted['protocol_type_id'] = 3;
            $valideted['company_id'] = auth()->user()->company_id;

            if ($request->filled('password')) {
                $valideted['m_password'] = $request->password;
                $valideted['password'] = Hash::make($request->password);
            }

            if ($request->hasFile('doc_image')) {
                $path1 = $request->file('doc_image')->store('customer', 'public');
                $valideted['doc_image'] = $path1;
            }

            if ($request->hasFile('nid_front')) {
                $path2 = $request->file('nid_front')->store('customer', 'public');
                $valideted['nid_front'] = $path2;
            }

            if ($request->hasFile('nid_back')) {
                $path3 = $request->file('nid_back')->store('customer', 'public');
                $valideted['nid_back'] = $path3;
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

            if (auth()->user()->mac_reseler && auth()->user()->mac_reseler->set_prefix_mikrotikuser == 'yes') {
                $prifix = stripos($request->username, auth()->user()->mac_reseler->reseller_prefix) ? "" : auth()->user()->mac_reseler->reseller_prefix;
                $valideted['username'] = $prifix . $request->username;
            } else {
                $prifix = "";
            }
            // dd($request->password ?? $customer->m_password);
            // if ($request->m_p_p_p_profile != $customer->m_p_p_p_profile || $request->username != $customer->username) {
            $client = $this->client($request->server_id);
            $query =  new Query('/ppp/secret/set');
            $query->equal('.id', $customer->mid);
            $query->equal('name', $prifix . $request->username);
            $query->equal('service', "pppoe");
            $request->ip_address ?  $query->equal('local-address', $request->ip_address) : null;
            $request->remote_address ? $query->equal('remote-address', $request->remote_address) : null;
            $query->equal('caller-id', $request->mac_address);
            $query->equal('profile', $Mpppprofile->name);
            if ($request->password) {
                $query->equal('password', $request->password);
            }
            // $query->equal('disabled', $request->disabled);
            $query->equal('comment', $request->comment);
            $response = $client->query($query)->read();
            // }

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
        return;
    }

    public function messagesend(Request $req)
    {
        if ($req->deleteselectitem) {
            $customers = Customer::whereIn('id', $req->deleteselectitem)->get();
            foreach ($customers as $customer) {
                $message = messageconvert($customer, $customer->getCompany->bill_exp_warning_msg);
                sendSms($customer->phone, $message);
            }
            return back()->with('success', 'Message Send Successfully');
        } else {
            return back()->with('failed', 'Please Select Customer');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $user
     * @return \Illuminate\Http\Response
     */
    public function multidelete(Request $req)
    {
        if ($req->deleteselectitem) {
            Customer::whereIn('id', $req->deleteselectitem)->delete();
            return back()->with('success', 'Data deleted successfully.');
        }
        return back()->with('failed', 'Please Select a customer.');
    }

    public function getProfile(Request $request)
    {
        $profile = Package2::find($request->id);
        return response()->json([
            "amount" => $profile->price,
            "speed" => $profile->bandwidth_allocation,
        ]);
    }

    public function update_expire_date(Request $request)
    {
        $customer = Customer::find($request->id);
        $customer->update(['exp_date' => $request->date]);
        return 'success';
    }

    public function mikrotikStatus(Customer $customer)
    {
        $status = $customer->disabled == 'true' ? 'false' : 'true';
        $transaction['date'] = now();
        $transaction['local_id'] = $customer->id;
        $transaction['type'] = 12;
        $transaction['company_id'] = auth()->user()->company_id;
        $transaction['created_by'] = auth()->id();
        $transaction['note'] = $customer->name . " Disabled " . $status . " By " . auth()->user()->username;
        Transaction::create($transaction);

            if ($status == "false") {

                if (auth()->user()->mac_reseler) {

                    $macReseller = auth()->user()->mac_reseler; // Assuming 'macReseller' is the relationship name
                    $customer = $customer;

                    if ($customer->protocol_type_id == 3) {
                        $charge = $macReseller->tariff->package->where('m_profile_id', $customer->m_p_p_p_profile)->pluck('rate')->first();
                    } elseif ($customer->protocol_type_id == 1) {
                        $charge = $macReseller->tariff->package->where('m_static_id', $customer->queue_id)->pluck('rate')->first();
                    }

                    $checkdata  = MacCustomerBill::where('customer_id', $customer->id)->whereMonth('date_', date('m'))->whereYear('date_', date('Y'))->first();

                    if (!$checkdata) {

                    if ($macReseller->recharge_balance >= $charge) {
                        $macReseller->recharge_balance -= $charge;
                        $macReseller->save();
                    } else {
                        // If recharge balance is less than charge amount, display a message
                        return back()->with('failed', 'Not enough balance. Please recharge your account.');
                    }


                        MacCustomerBill::create([
                            'customer_id' => $customer->id,
                            'date_' => Carbon::now()->toDateString(),
                            'charge' => $charge,
                            'company_id' => $customer->company_id,
                            'created_by' => auth()->id(),
                        ]);
                    }
                }


                $customer->update(['disabled' => $status, 'billing_status_id' => 5]);
                $this->customer_active_inactive($customer);
            } else {
                $customer->update(['disabled' => $status, 'billing_status_id' => 4]);
                $this->customer_active_inactive($customer);
            }

        return response()->json(200);
    }
}
