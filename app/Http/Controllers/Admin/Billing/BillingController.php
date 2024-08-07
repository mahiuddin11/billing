<?php

namespace App\Http\Controllers\Admin\Billing;

use App\Http\Controllers\Controller;
use App\Models\Billing;
use App\Models\Customer;
use App\Models\DailyIncome;
use App\Models\PaymentMethod;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\DataProcessingFile\BillingDataProcessing;
use App\Models\Account;
use App\Models\AccountTransaction;
use App\Models\MacCustomerBill;
use App\Models\Zone;
use Illuminate\Support\Facades\Auth;
use \RouterOS\Query;

class BillingController extends Controller
{

    use BillingDataProcessing;
    /**
     * String property
     */
    protected $routeName =  'billcollect';
    protected $viewName =  'admin.pages.billcollect';

    protected function getModel()
    {
        return new Billing();
    }

    protected function tableColumnNames()
    {
        return [
            [
                'label' => 'ID',
                'data' => 'id',
                'searchable' => false,
            ],
            [
                'label' => 'Full Name',
                'data' => 'name',
                'searchable' => false,
            ],
            [
                'label' => 'UserName/Queue',
                'data' => 'username',
                'customesearch' => 'customer_id',
                'searchable' => true,
                // 'relation' => 'getCustomer',
            ],
            [
                'label' => 'Zone',
                'data' => 'zone',
                'searchable' => false,
                // 'relation' => 'getCustomer',
            ],
            [
                'label' => 'Address',
                'data' => 'address',
                'searchable' => false,
                'relation' => 'getCustomer',
            ],
            [
                'label' => 'Billing Month',
                'data' => 'date_',
                'searchable' => false,
            ],
            [
                'label' => 'EXP Date',
                'data' => 'exp_date',
                'customesearch' => 'exp_date',
                'searchable' => false,
                // 'relation' => 'getCustomer',
            ],
            [
                'label' => 'Customer Phone',
                'data' => 'phone',
                'searchable' => false,
                'relation' => 'getCustomer'
            ],
            [
                'label' => 'Customer Profile Id',
                'data' => 'name',
                'searchable' => false,
                'relation' => 'getProfile',
            ],

            [
                'label' => 'Customer Billing Amount',
                'data' => 'customer_billing_amount',
                'searchable' => false,
            ],
            [
                'label' => 'Total Amount',
                'data' => 'totalamount',
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
        $page_title = "Bill Collection";
        $page_heading = "Bill Collection List";
        $ajax_url = route($this->routeName . '.dataProcessing');
        $create_url = route($this->routeName . '.create');
        $is_show_checkbox = false;
        $zones = Zone::where('company_id', auth()->user()->company_id)->get();
        $accounts = Account::whereIn('id', [2, 3, 4])->get();
        $columns = $this->reformatForRelationalColumnName(
            $this->tableColumnNames()
        );
        $customers = Customer::where('company_id', auth()->user()->company_id)->get();
        return view($this->viewName . '.index', get_defined_vars());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function dataProcessing()
    {
        $model = $this->getModel()
            ->leftJoin('customers', 'customers.id', '=', 'billings.customer_id')
            ->leftJoin('zones', 'zones.id', '=', 'customers.zone_id')
            ->select('billings.*', 'billings.id', 'customers.username as username', 'customers.name as name', 'customers.queue_name as queue_name', 'customers.exp_date as exp_date', 'customers.zone_id as zone_id', 'customers.billing_person as billing_person', 'zones.name as zone')
            ->where('billings.company_id', auth()->user()->company_id)
            ->where('billings.status', 'unpaid')->whereMonth('billings.date_', date('m'))->whereYear('billings.date_', date('Y'))
            ->orderBy('zone', 'asc')
            ->orderBy('username', 'asc');

        if (request('columns.10.search.value'))
            $model = $model->where('customers.billing_status_id', request('columns.10.search.value'));

        if (auth()->user()->is_admin == 4)
            $model = $model->where('billing_person', auth()->id());

        // $model = $this->getModel()->where('company_id', auth()->user()->company_id);
        if (request('columns.0.search.value')) {
            $customer = Customer::where('zone_id', request('columns.0.search.value'))->pluck('id');
            $model = $model->whereIn('customer_id', $customer);
        }
        // $model =  $model->where('status', 'unpaid')->whereMonth('date_', date('m'))->whereYear('date_', date('Y'));
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
                    'method_name' => 'pay',
                    'class' => 'btn-info  btn-sm paymodel',
                    'fontawesome' => '',
                    'text' => 'Pay',
                    'title' => 'View',
                    'code' => "data-toggle='modal' data-target='#default'",
                ],
                [
                    'method_name' => 'payment',
                    'class' => 'btn-success btn-sm',
                    'fontawesome' => '',
                    'text' => 'View',
                    'title' => 'View',
                ],
                [
                    'method_name' => 'invoice',
                    'class' => 'btn-info btn-sm',
                    'fontawesome' => '',
                    'text' => 'Inv',
                    'title' => 'View',
                ],
                [
                    'method_name' => 'delete',
                    'class' => 'btn-danger btn-sm',
                    'fontawesome' => 'fa fa-trash',
                    'code' => 'onclick ="return confirm(`Are you sure you want to delete this`)"',
                    'text' => '',
                    'title' => 'Delete',
                ],
            ]

        );
    }

    public function create()
    {
        $page_title = "Bill Create";
        $page_heading = "Bill Create";
        $back_url = route($this->routeName . '.index');
        $store_url = route($this->routeName . '.store');
        $customers = Customer::where('disabled', false)->get();
        $users = User::all();
        $accounts = Account::whereIn('id', [2, 3, 4])->get();
        return view($this->viewName . '.create', get_defined_vars());
    }

    public function store(Request $request)
    {
        $validation = $this->validate($request, [
            "customer_id" => "required",
            "biller_name" => "required",
            "customer_billing_amount" => "required",
            "payment_method_id" => "required",
            "date_" => "required",
            "pay_amount" => "nullable",
            "partial" => "nullable",
            "discount" => "nullable",
            "description" => "nullable",
            "status" => "required",
        ]);

        try {
            $customer = Customer::find($request->customer_id);
            $customer->total_paid = $customer->total_paid ?? 0 + $request->pay_amount;
            $customer->due = $customer->due ?? 0 + $request->partial;
            $customer->save();

            $validation['customer_phone'] = $customer->phone;
            $validation['customer_profile_id'] = $customer->m_p_p_p_profile_id;
            $validation['company_id'] = auth()->user()->company_id;
            $validation['type'] = "collection";
            $validation['billing_by'] = auth()->id();

            Billing::create($validation);
            return redirect()->route('billcollect.index')->with('success', 'Payment Successfully Done !!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('failed', 'Oops! Something was wrong. Message: ' . $e->getMessage() . ' Line: ' . $e->getLine() . 'File: ' . $e->getFile());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Bill Collection  $user
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Billing $billing)
    {
        $modal_title = 'Bill Collection Details';
        $modal_data = $billing;
        $html = view($this->viewName . '.show', get_defined_vars())->render();
        return $html;
    }

    public function payment(Billing $billing)
    {
        $back_url = route($this->routeName . '.index');
        $update_url = route($this->routeName . '.update', $billing->id);
        $data = $billing;
        $paymentMethods = Account::whereIn('id', [2, 3, 4])->get();
        $customerPaymentDetails = Billing::where('customer_id', $billing->customer_id)->where('status', '!=', 'unpaid')->get();
        $customerDetails = Billing::where('customer_id', $billing->customer_id)->where('status', '!=', 'paid')->get();
        return view('admin.pages.billcollect.pay', get_defined_vars());
    }

    public function messagesend(Request $req)
    {
        if ($req->deleteselectitem) {
            $customers = Billing::whereIn('id', $req->deleteselectitem)->cursor();
            foreach ($customers as $customer) {
                $message = messageconvert($customer->getCustomer, $customer->company->bill_exp_warning_msg);
                if ($customer->getCustomer->phone) {
                    sendSms($customer->getCustomer->phone, $message);
                }
            }
            return back()->with('success', 'Message Send Successfully');
        } else {
            return back()->with('failed', 'Please Select Customer');
        }
    }


    public function paylist(Billing $billing)
    {
        $back_url = route($this->routeName . '.index');
        // $zones = Zone::all();
        $customerPaymentDetails = Billing::where('customer_id', $billing->customer_id)->where('status', '!=', 'unpaid')->get();
        return view('admin.pages.billcollect.pay', get_defined_vars());
    }

    public function pay(Request $request, Billing $billing)
    {
        // dd($request->all());

        $this->validate($request, [
            'payment_method_id' => ['required'],
        ]);

        DB::beginTransaction();
        try {
            if (auth()->user()->mac_reseler) {
                $macReseller = auth()->user()->mac_reseler; // Assuming 'macReseller' is the relationship name
                $customer = $billing->getCustomer;
                if ($customer->protocol_type_id == 3) {
                    $charge = $macReseller->tariff->package->where('m_profile_id', $customer->m_p_p_p_profile)->pluck('rate')->first();
                } elseif ($customer->protocol_type_id == 1) {
                    $charge = $macReseller->tariff->package->where('m_static_id', $customer->queue_id)->pluck('rate')->first();
                }

                $checkdata = MacCustomerBill::where('customer_id', $customer->id)->whereMonth('date_', date('m'))->whereYear('date_', date('Y'))->first();
                if (!$checkdata) {

                if ($macReseller->recharge_balance >= $charge) {
                    $macReseller->recharge_balance -= $charge;
                    $macReseller->save();
                } else {
                    // If recharge balance is less than charge amount, display a message
                    return back()->with('failed', 'Not enough balance. Please recharge your account.');
                }


                    MacCustomerBill::create([
                        'customer_id' => $billing->customer_id,
                        'date_' => Carbon::now()->toDateString(),
                        'charge' => $charge,
                        'company_id' => $billing->company_id,
                        'created_by' => auth()->id(),
                    ]);
                }
            }
            $billing->update([
                'invoice_name' => $request->invoice_name,
                'alert' => "white",
                'discount' => $request->discount,
                'pay_amount' => $billing->customer_billing_amount - $request->discount,
                "payment_method_id" => $request->payment_method_id,
                'status' => 'paid',
                'billing_by' => auth()->id()
            ]);
            // dd($billing);

            $invoice = AccountTransaction::accountInvoice();
            $transaction['invoice'] = $invoice;
            $transaction['table_id'] = $billing->id;
            $transaction['account_id'] = 10;
            $transaction['type'] = 4;
            $transaction['company_id'] = auth()->user()->company_id;
            $transaction['credit'] = $billing->customer_billing_amount;
            $transaction['remark'] = "Internet Bill";
            $transaction['customer_id'] = $billing->customer_id;
            $transaction['created_by'] = Auth::id();
            AccountTransaction::create($transaction);

            $transactionPay['invoice'] = $invoice;
            $transactionPay['table_id'] = $billing->id;
            $transactionPay['account_id'] = 500;
            $transactionPay['type'] = 4;
            $transactionPay['company_id'] = auth()->user()->company_id;
            $transactionPay['debit'] = $billing->customer_billing_amount - $request->discount;
            $transactionPay['remark'] = "Internet Bill";
            $transactionPay['customer_id'] = $billing->customer_id;
            $transactionPay['created_by'] = Auth::id();
            AccountTransaction::create($transactionPay);

            if ($request->discount != null || $request->discount > 0) {
                $transactionPayDiscount['invoice'] = $invoice;
                $transactionPayDiscount['table_id'] = $billing->id;
                $transactionPayDiscount['account_id'] = 8; // discount head
                $transactionPayDiscount['type'] = 4;
                $transactionPayDiscount['company_id'] = auth()->user()->company_id;
                $transactionPayDiscount['debit'] = $request->discount;
                $transactionPayDiscount['remark'] = "Internet Bill";
                $transactionPayDiscount['customer_id'] = $billing->customer_id;
                $transactionPayDiscount['created_by'] = Auth::id();
                AccountTransaction::create($transactionPayDiscount);
            }

            $transactiondue['date'] = $billing->getCustomer->start_date;
            $transactiondue['local_id'] = $billing->id;
            $transactiondue['pay_method_id'] = $request->payment_method_id;
            $transactiondue['type'] = 10;
            $transactiondue['company_id'] = auth()->user()->company_id;
            $transactiondue['credit'] = $billing->customer_billing_amount;
            $transactiondue['amount'] = $billing->customer_billing_amount;
            $transactiondue['created_by'] = auth()->id();
            Transaction::create($transactiondue);

            if ($billing->getCustomer->billing_status_id == 4 && $billing->getCustomer->billing_type == "day_to_day") { // when customer disconnect few days and after few days when the recharge the
                $startDate = Carbon::parse($billing->getCustomer->start_date)->addMonths($billing->getCustomer->duration)->format('Y-m-d');
                $endDate = Carbon::parse(date("Y-m-d"))->addMonths($billing->getCustomer->duration);
                if ($billing->getCustomer->bill_collection_date != 0 && !empty($billing->getCustomer->bill_collection_date)) {
                    $endDate = $endDate->day(date("d"));
                }
                $endDate = $endDate->format('Y-m-d');
                $billing->getCustomer->update([
                    'start_date' => $startDate,
                    'billing_status_id' => 5,
                    'bill_collection_date' => date("d"),
                    'exp_date' => $endDate,
                    'disabled' => 'false',
                    'queue_disabled' => 'false',
                    "total_paid" => $billing->getCustomer->total_paid +  $billing->customer_billing_amount,
                ]);
            } else {
                $startDate = Carbon::parse($billing->getCustomer->start_date)->addMonths($billing->getCustomer->duration)->format('Y-m-d');
                $endDate = Carbon::parse($billing->getCustomer->exp_date)->addMonths($billing->getCustomer->duration);
                if ($billing->getCustomer->bill_collection_date != 0 && !empty($billing->getCustomer->bill_collection_date)) {
                    $endDate = $endDate->day($billing->getCustomer->bill_collection_date);
                }
                $endDate = $endDate->format('Y-m-d');
                $billing->getCustomer->update([
                    'start_date' => $startDate,
                    'billing_status_id' => 5,
                    'exp_date' => $endDate,
                    'disabled' => 'false',
                    'queue_disabled' => 'false',
                    "total_paid" => $billing->getCustomer->total_paid +  $billing->customer_billing_amount,
                ]);
            }


            $client = $this->client($billing->getCustomer->server_id);
            if ($billing->getCustomer->protocol_type_id == 3) {
                $query =  new Query('/ppp/secret/set');
                $query->equal('.id', $billing->getCustomer->mid);
                $query->equal('disabled', 'false');
                $client->query($query)->read();
            } elseif ($billing->getCustomer->protocol_type_id == 1) {
                $query =  new Query('/queue/simple/set');
                $query->equal('.id', $billing->getCustomer->queue_id);
                $query->equal('disabled', 'false');
                $client->query($query)->read();
            }

            $message = messageconvert($billing->getCustomer, $billing->getCustomer->getCompany->bill_paid_msg);
            // $message = "Sir," . $billing->getCustomer->username . " Recieve Amount " . $billing->customer_billing_amount . " Monthly Bill: " . $billing->customer_billing_amount . " Tk Thank you " . $billing->company->company_name;
            sendSms($billing->getCustomer->phone, $message);
            DB::commit();
            return redirect()->back()->with('success', 'Payment Successfully Done !!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('failed', 'Oops! Something was wrong. Message: ' . $e->getMessage() . ' Line: ' . $e->getLine() . 'File: ' . $e->getFile());
        }
    }


    public function multiplePay(Request $request)
    {
        $this->validate($request, [
            'selected_customers' => ['required'],
            'total_amount' => ['required', 'numeric'],
            'invoice_name' => ['nullable'],
        ]);

        $selectedCustomers = json_decode($request->selected_customers, true);
        $totalAmount = $request->total_amount;

        DB::beginTransaction();
        try {
            foreach ($selectedCustomers as $customerId) {
                $billing = Billing::find($customerId);
                if (!$billing) {
                    continue;
                }

                $billing->update([
                    'invoice_name' => $request->invoice_name,
                    'alert' => "white",
                    'discount' => $request->discount,
                    'description' => $request->remarks,
                    'pay_amount' => $billing->customer_billing_amount,
                    'partial' => $billing->customer_billing_amount - $billing->customer_billing_amount,
                    "payment_method_id" => $request->payment_method_id,
                    'status' => 'paid',
                    'billing_by' => auth()->id()
                ]);

                $invoice = AccountTransaction::accountInvoice();
                $transaction = [
                    'invoice' => $invoice,
                    'table_id' => $billing->id,
                    'account_id' => 10,
                    'type' => 4,
                    'company_id' => auth()->user()->company_id,
                    'credit' => $billing->customer_billing_amount,
                    'remark' => $request->remarks,
                    'customer_id' => $billing->customer_id,
                    'created_by' => Auth::id()
                ];
                AccountTransaction::create($transaction);

                $transactionfull = [
                    'invoice' => $invoice,
                    'table_id' => $billing->id,
                    'account_id' => $request->payment_method_id,
                    'type' => 4,
                    'company_id' => auth()->user()->company_id,
                    'debit' => $billing->customer_billing_amount - $request->discount,
                    'remark' => $request->remarks,
                    'customer_id' => $billing->customer_id,
                    'created_by' => Auth::id()
                ];
                AccountTransaction::create($transactionfull);

                if ($request->discount != null || $request->discount > 0) {
                    $transactionPayDiscount = [
                        'invoice' => $invoice,
                        'table_id' => $billing->id,
                        'account_id' => 8,
                        'type' => 4,
                        'company_id' => auth()->user()->company_id,
                        'debit' => $request->discount,
                        'remark' => "Internet Bill",
                        'customer_id' => $billing->customer_id,
                        'created_by' => Auth::id()
                    ];
                    AccountTransaction::create($transactionPayDiscount);
                }

                $transaction = [
                    'date' => $billing->getCustomer->start_date,
                    'local_id' => $billing->id,
                    'pay_method_id' => $request->payment_method_id ?? null,
                    'type' => 10,
                    'company_id' => auth()->user()->company_id,
                    'credit' => $billing->customer_billing_amount - $request->discount,
                    'amount' => $billing->customer_billing_amount - $request->discount,
                    'created_by' => auth()->id()
                ];
                Transaction::create($transaction);
            }

            $startDate = Carbon::parse($billing->getCustomer->start_date)->addMonths($request->extend_month)->format('Y-m-d');
            $endDate = Carbon::parse($billing->getCustomer->exp_date)->addMonths($request->extend_month);
            if ($billing->getCustomer->bill_collection_date != 0 && !empty($billing->getCustomer->bill_collection_date)) {
                $endDate = $endDate->day($billing->getCustomer->bill_collection_date);
            }
            $endDate = $endDate->format('Y-m-d');
            $billing->getCustomer->update([
                'billing_status_id' => 5,
                'disabled' => 'false',
                'queue_disabled' => 'false',
                "total_paid" => $billing->getCustomer->total_paid +  $billing->customer_billing_amount,
                'start_date' => $startDate,
                'exp_date' => $endDate,
            ]);

            $client = $this->client($billing->getCustomer->server_id);
            if ($billing->getCustomer->protocol_type_id == 3) {
                $query = new Query('/ppp/secret/set');
                $query->equal('.id', $billing->getCustomer->mid);
                $query->equal('disabled', 'false');
                $client->query($query)->read();
            } elseif ($billing->getCustomer->protocol_type_id == 1) {
                $query = new Query('/queue/simple/set');
                $query->equal('.id', $billing->getCustomer->queue_id);
                $query->equal('disabled', 'false');
                $client->query($query)->read();
            }

            $message = messageconvert($billing->getCustomer, $billing->company->partial_bill_msg, $totalAmount);

            sendSms($billing->getCustomer->phone, $message);

            DB::commit();
            return redirect()->back()->with('success', 'Payment Successfully Done !!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('failed', 'Oops! Something was wrong. Message: ' . $e->getMessage() . ' Line: ' . $e->getLine() . 'File: ' . $e->getFile());
        }
    }


    public function duepay(Request $request, Billing $billing)
    {
        $this->validate($request, [
            'payment_method_id' => ['required'],
            'amount' => ['required'],
            'invoice_name' => ['nullable'],
        ]);

        try {

            if (auth()->user()->mac_reseler) {
                $macReseller = auth()->user()->mac_reseler; // Assuming 'macReseller' is the relationship name
                $customer = $billing->getCustomer;
                if ($customer->protocol_type_id == 3) {
                    $charge = $macReseller->tariff->package->where('m_profile_id', $customer->m_p_p_p_profile)->pluck('rate')->first();
                } elseif ($customer->protocol_type_id == 1) {
                    $charge = $macReseller->tariff->package->where('m_static_id', $customer->queue_id)->pluck('rate')->first();
                }

                $checkdata = MacCustomerBill::where('customer_id', $customer->id)->whereMonth('date_', date('m'))->whereYear('date_', date('Y'))->first();
                if (!$checkdata) {
                if ($macReseller->recharge_balance >= $charge) {
                    $macReseller->recharge_balance -= $charge;
                    $macReseller->save();
                } else {
                    // If recharge balance is less than charge amount, display a message
                    return back()->with('failed', 'Not enough balance. Please recharge your account.');
                }

                    MacCustomerBill::create([
                        'customer_id' => $billing->customer_id,
                        'date_' => Carbon::now()->toDateString(),
                        'charge' => $charge,
                        'company_id' => $billing->company_id,
                        'created_by' => auth()->id(),
                    ]);
                }
            }

            $billing->update([
                'invoice_name' => $request->invoice_name,
                'alert' => "white",
                'discount' => $request->discount,
                'description' => $request->remarks,
                'pay_amount' => $request->amount,
                'partial' => $billing->customer_billing_amount - $request->amount,
                "payment_method_id" => $request->payment_method_id,
                'status' => (($billing->customer_billing_amount - $request->discount) > $request->amount) ? 'partial' : "paid",
                'billing_by' => auth()->id()
            ]);

            $invoice = AccountTransaction::accountInvoice();
            $transaction['invoice'] = $invoice;
            $transaction['table_id'] = $billing->id;
            $transaction['account_id'] = 10;
            $transaction['type'] = 4;
            $transaction['company_id'] = auth()->user()->company_id;
            $transaction['credit'] = $billing->customer_billing_amount;
            $transaction['remark'] = $request->remarks;
            $transaction['customer_id'] = $billing->customer_id;
            $transaction['created_by'] = Auth::id();
            AccountTransaction::create($transaction);

            if (($billing->customer_billing_amount - $request->discount) > $request->amount) {
                $transactionPay['invoice'] = $invoice;
                $transactionPay['table_id'] = $billing->id;
                $transactionPay['account_id'] = $request->payment_method_id;
                $transactionPay['type'] = 4;
                $transactionPay['company_id'] = auth()->user()->company_id;
                $transactionPay['debit'] = $request->amount;
                $transactionPay['remark'] = $request->remarks;
                $transactionPay['customer_id'] = $billing->customer_id;
                $transactionPay['created_by'] = Auth::id();
                AccountTransaction::create($transactionPay);

                $transactionPa['invoice'] = $invoice;
                $transactionPa['table_id'] = $billing->id;
                $transactionPa['account_id'] = 5;
                $transactionPa['type'] = 4;
                $transactionPa['company_id'] = auth()->user()->company_id;
                $transactionPa['debit'] = ($billing->customer_billing_amount - $request->discount) -  $request->amount;
                $transactionPa['remark'] = $request->remarks;
                $transactionPa['customer_id'] = $billing->customer_id;
                $transactionPa['created_by'] = Auth::id();
                AccountTransaction::create($transactionPa);
            } else {
                $transactionfull['invoice'] = $invoice;
                $transactionfull['table_id'] = $billing->id;
                $transactionfull['account_id'] = $request->payment_method_id;
                $transactionfull['type'] = 4;
                $transactionfull['company_id'] = auth()->user()->company_id;
                $transactionfull['debit'] = $billing->customer_billing_amount - $request->discount;
                $transactionfull['remark'] = $request->remarks;
                $transactionfull['customer_id'] = $billing->customer_id;
                $transactionfull['created_by'] = Auth::id();
                AccountTransaction::create($transactionfull);
            }

            if ($request->discount != null || $request->discount > 0) {
                $transactionPayDiscount['invoice'] = $invoice;
                $transactionPayDiscount['table_id'] = $billing->id;
                $transactionPayDiscount['account_id'] = 8; // discount head
                $transactionPayDiscount['type'] = 4;
                $transactionPayDiscount['company_id'] = auth()->user()->company_id;
                $transactionPayDiscount['debit'] = $request->discount;
                $transactionPayDiscount['remark'] = "Internet Bill";
                $transactionPayDiscount['customer_id'] = $billing->customer_id;
                $transactionPayDiscount['created_by'] = Auth::id();
                AccountTransaction::create($transactionPayDiscount);
            }

            $transaction['date'] = $billing->getCustomer->start_date;
            $transaction['local_id'] = $billing->id;
            $transaction['pay_method_id'] = $request->payment_method_id ?? null;
            $transaction['type'] = 10;
            $transaction['company_id'] = auth()->user()->company_id;
            $transaction['credit'] = $request->amount - $request->discount;
            $transaction['amount'] = $request->amount - $request->discount;
            $transaction['created_by'] = auth()->id();
            Transaction::create($transaction);


            if ($billing->getCustomer->billing_status_id == 4 && $billing->getCustomer->billing_type == "day_to_day") { // when customer disconnect few days and after few days when the recharge the
                $startDate = Carbon::parse($billing->getCustomer->start_date)->addMonths($billing->getCustomer->duration)->format('Y-m-d');
                $endDate = Carbon::parse(date("Y-m-d"))->addMonths($billing->getCustomer->duration);
                if ($billing->getCustomer->bill_collection_date != 0 && !empty($billing->getCustomer->bill_collection_date)) {
                    $endDate = $endDate->day($billing->getCustomer->bill_collection_date);
                }
                $endDate = $endDate->format('Y-m-d');
            } else {
                $startDate = Carbon::parse($billing->getCustomer->start_date)->addMonths($billing->getCustomer->duration)->format('Y-m-d');
                $endDate = Carbon::parse($billing->getCustomer->exp_date)->addMonths($billing->getCustomer->duration);
                if ($billing->getCustomer->bill_collection_date != 0 && !empty($billing->getCustomer->bill_collection_date)) {
                    $endDate = $endDate->day($billing->getCustomer->bill_collection_date);
                }
                $endDate = $endDate->format('Y-m-d');
            }

            if ($billing->getCustomer->billing_status_id == 4 && $billing->getCustomer->billing_type == "day_to_day") { // when customer disconnect few days and after few days when the recharge the
                $startDate = Carbon::parse($billing->getCustomer->start_date)->addMonths($billing->getCustomer->duration)->format('Y-m-d');
                $endDate = Carbon::parse(date("Y-m-d"))->addMonths($billing->getCustomer->duration);
                if ($billing->getCustomer->bill_collection_date != 0 && !empty($billing->getCustomer->bill_collection_date)) {
                    $endDate = $endDate->day(date("d"));
                }
                $endDate = $endDate->format('Y-m-d');
                $billing->getCustomer->update([
                    'billing_status_id' => 5,
                    'bill_collection_date' => date("d"),
                    'disabled' => 'false',
                    'queue_disabled' => 'false',
                    "total_paid" => $billing->getCustomer->total_paid +  $billing->customer_billing_amount,
                ]);
            } else {
                $startDate = Carbon::parse($billing->getCustomer->start_date)->addMonths($billing->getCustomer->duration)->format('Y-m-d');
                $endDate = Carbon::parse($billing->getCustomer->exp_date)->addMonths($billing->getCustomer->duration);
                if ($billing->getCustomer->bill_collection_date != 0 && !empty($billing->getCustomer->bill_collection_date)) {
                    $endDate = $endDate->day($billing->getCustomer->bill_collection_date);
                }
                $endDate = $endDate->format('Y-m-d');
                $billing->getCustomer->update([
                    'billing_status_id' => 5,
                    'disabled' => 'false',
                    'queue_disabled' => 'false',
                    "total_paid" => $billing->getCustomer->total_paid +  $billing->customer_billing_amount,
                ]);
            }


            if ($request->extend == 'yes') {
                $billing->getCustomer->update([
                    'start_date' => $startDate,
                    'exp_date' => $endDate,
                ]);
            }

            $client = $this->client($billing->getCustomer->server_id);
            if ($billing->getCustomer->protocol_type_id == 3) {
                $query =  new Query('/ppp/secret/set');
                $query->equal('.id', $billing->getCustomer->mid);
                $query->equal('disabled', 'false');
                $client->query($query)->read();
            } elseif ($billing->getCustomer->protocol_type_id == 1) {
                $query =  new Query('/queue/simple/set');
                $query->equal('.id', $billing->getCustomer->queue_id);
                $query->equal('disabled', 'false');
                $client->query($query)->read();
            }

            $message = messageconvert($billing->getCustomer, $billing->company->partial_bill_msg, $request->amount);

            sendSms($billing->getCustomer->phone, $message);


            return redirect()->back()->with('success', 'Payment Successfully Done !!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('failed', 'Oops! Something was wrong. Message: ' . $e->getMessage() . ' Line: ' . $e->getLine() . 'File: ' . $e->getFile());
        }
    }

    public function partial(Request $request, Billing $billing)
    {
        $this->validate($request, [
            'payment_method_id' => ['required'],
            'amount' => ['required'],
        ]);

        try {
            $billing->update([
                'invoice_name' => $request->invoice_name ?? $billing->invoice_name,
                'alert' => "white",
                'description' => $request->remarks,
                'pay_amount' => $billing->pay_amount + $request->amount,
                'partial' => $billing->customer_billing_amount - ($billing->pay_amount + $request->amount),
                "payment_method_id" => $request->payment_method_id,
                'status' => ($billing->customer_billing_amount > $billing->pay_amount + $request->amount) ? 'partial' : "paid",
                'billing_by' => auth()->id()
            ]);


            // $paymentMethods = PaymentMethod::find($request->payment_method_id);
            // $paymentMethods ? $paymentMethods->update(['amount' => $paymentMethods->amount + $billing->customer_billing_amount]) : null;

            $invoice = AccountTransaction::accountInvoice();
            $transactionPay['invoice'] = $invoice;
            $transactionPay['table_id'] = $billing->id;
            $transactionPay['account_id'] = $request->payment_method_id;
            $transactionPay['type'] = 4;
            $transactionPay['company_id'] = auth()->user()->company_id;
            $transactionPay['debit'] = $request->amount;
            $transactionPay['remark'] = $request->remarks;
            $transactionPay['customer_id'] = $billing->customer_id;
            $transactionPay['created_by'] = Auth::id();
            AccountTransaction::create($transactionPay);

            $transactionPa['invoice'] = $invoice;
            $transactionPa['table_id'] = $billing->id;
            $transactionPa['account_id'] = 5;
            $transactionPa['type'] = 4;
            $transactionPa['company_id'] = auth()->user()->company_id;
            $transactionPa['credit'] =  $request->amount;
            $transactionPa['remark'] = $request->remarks;
            $transactionPa['customer_id'] = $billing->customer_id;
            $transactionPa['created_by'] = Auth::id();
            AccountTransaction::create($transactionPa);

            $transaction['date'] = $billing->getCustomer->start_date;
            $transaction['local_id'] = $billing->id;
            $transaction['pay_method_id'] = $request->payment_method_id;
            $transaction['type'] = 10;
            $transaction['company_id'] = auth()->user()->company_id;
            $transaction['credit'] = $request->amount;
            $transaction['amount'] = $request->amount;
            $transaction['created_by'] = auth()->id();
            Transaction::create($transaction);

            return redirect()->back()->with('success', 'Payment Successfully Done !!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('failed', 'Oops! Something was wrong. Message: ' . $e->getMessage() . ' Line: ' . $e->getLine() . 'File: ' . $e->getFile());
        }
    }
    public function invoice(Billing $billing)
    {
        $customerPaymentDetails = Billing::where('customer_id', $billing->customer_id)->where('status', '!=', 'paid')->get();
        $serviceCharges = DailyIncome::where('customer_id', $billing->customer_id)->get();
        $companyInif = auth()->user()->company;
        return view($this->viewName . '.invoice', get_defined_vars());
    }

    public function delete(Billing $billing)
    {
        if ($billing->status == 'unpaid') {
            $billing->delete();
            return redirect()->back()->with('success', 'Bill Delete Successfully !!');
        }
        return redirect()->back()->with('failed', 'You are already Transaction, So Cannot Delete this  !!');
    }

    public function update(Request $request, Billing $billing)
    {
        if ($request->pay_type == 'full_pay') {
            $valideted = $this->validate($request, [
                'pay_amount' => ['nullable'],
                'discount' => ['nullable'],
                'month' => ['nullable'],
                'payment_method_id' => ['required'],
                'pay_type' => ['required'],
                'description' => ['nullable'],
            ]);
        } elseif ($request->pay_type == 'partial') {
            $valideted = $this->validate($request, [
                'pay_amount' => ['required'],
                'payment_method_id' => ['required'],
                'discount' => ['nullable'],
                'month' => ['required'],
            ]);
        }
        try {
            DB::beginTransaction();
            $billingsub = Billing::find($request->month);
            // $transaction['account_id'] = $request->account_id;
            if ($request->pay_type == 'full_pay') {
                $customerDetails = Billing::where('customer_id', $billing->customer_id)
                    ->where('status', '!=', 'paid')
                    ->get();
                $paymentMethods = PaymentMethod::find($request->payment_method_id);
                foreach ($customerDetails as $paid) {
                    $paymentMethods->update(['amount' => $paymentMethods->amount + $paid->customer_billing_amount]);

                    $transaction['date'] = $paid->getCustomer->start_date;
                    $transaction['local_id'] = $paid->id ?? null;
                    $transaction['pay_method_id'] = $request->payment_method_id ?? null;
                    $transaction['type'] = 10;
                    $transaction['company_id'] = auth()->user()->company_id;
                    $transaction['credit'] =  $paid->customer_billing_amount;
                    $transaction['amount'] = $paid->customer_billing_amount;
                    $transaction['note'] = $request->description;
                    $transaction['created_by'] = auth()->id();
                    Transaction::create($transaction);

                    $customer['total_paid'] = $billing->getCustomer->total_paid + ($billing->customer_billing_amount - $billing->pay_amount);
                    $customer['due'] = 0;
                    $billing->getCustomer->update($customer);

                    $paid->update([
                        'payment_method_id' => $request->payment_method_id,
                        'partial' => 0,
                        'billing_by' => auth()->id(),
                        'pay_amount' => $paid->customer_billing_amount,
                        'status' => 'paid'
                    ]);
                }
                if ($request->filled('extend_date')) {
                    $billing->getCustomer->update(['exp_date' => Carbon::parse($billing->getCustomer->exp_date)->day($request->extend_date)->format('Y-m-d')]);
                } elseif ($request->next_month == "yes") {
                    $startDate = Carbon::parse($billing->getCustomer->start_date)->addMonths($billing->getCustomer->duration)->format('Y-m-d');
                    $endDate = Carbon::parse($startDate)->addMonths($billing->getCustomer->duration)->day($billing->getCustomer->bill_collection_date)->format('Y-m-d');
                    $billing->getCustomer->update(['start_date' => $startDate, 'exp_date' => $endDate]);
                }
            } elseif ($request->pay_type == 'partial') {
                $amount = $billingsub->customer_billing_amount - $request->discount;
                $paidAmount = $request->pay_amount + $billingsub->pay_amount;
                $due = abs($amount - $paidAmount);
                if ($request->filled('extend_date')) {
                    $billingsub->getCustomer->update(['exp_date' => Carbon::parse($billingsub->getCustomer->exp_date)->day($request->extend_date)->format('Y-m-d')]);
                } elseif ($request->next_month == "yes") {
                    $startDate = Carbon::parse($billing->getCustomer->start_date)->addMonths($billing->getCustomer->duration)->format('Y-m-d');
                    $endDate = Carbon::parse($startDate)->addMonths($billing->getCustomer->duration)->day($billing->getCustomer->bill_collection_date)->format('Y-m-d');
                    $billingsub->getCustomer->update(['start_date' => $startDate, 'exp_date' => $endDate, 'billing_status_id' => 5]);

                    // enable customer in mikrotik
                    $client = $this->client($billingsub->getCustomer->server_id);
                    $query =  new Query('/ppp/secret/set');
                    $query->equal('.id', $billingsub->getCustomer->mid);
                    $query->equal('disabled', 'false');
                    $client->query($query)->read();
                }
                $billingsub->getCustomer->update(
                    [
                        'total_paid' => $billingsub->getCustomer->total_paid + $request->pay_amount,
                        'due' => $billingsub->getCustomer->due - $billingsub->partial
                    ]
                );
                $billingsub->getCustomer->update(
                    [
                        'due' => $billingsub->getCustomer->due + $due
                    ]
                );
                $duepaid['partial'] = $due;
                $duepaid['status'] =  $amount > $paidAmount ? "partial" : "paid";
                $duepaid['pay_amount'] = $paidAmount;
                $duepaid['description'] = $request->description;
                $duepaid['payment_method_id'] = $request->payment_method_id;
                $duepaid['discount'] = $request->discount;
                $duepaid['billing_by'] = auth()->id();
                $billingsub->update($duepaid);

                $paymentMethods = PaymentMethod::find($request->payment_method_id);
                $paymentMethods->update(['amount' => $paymentMethods->amount +  $request->pay_amount]);

                $invoice = AccountTransaction::accountInvoice();

                $transactionPay['invoice'] = $invoice;
                $transactionPay['table_id'] = $billingsub->id;
                $transactionPay['account_id'] = $request->payment_method_id;
                $transactionPay['type'] = 4;
                $transactionPay['debit'] = $request->pay_amount;
                $transactionPay['remark'] = "Internet Bill";
                $transactionPay['customer_id'] = $billingsub->customer_id;
                $transactionPay['created_by'] = Auth::id();
                AccountTransaction::create($transactionPay);

                if ($request->discount) {
                    $transactionPay['invoice'] = $invoice;
                    $transactionPay['table_id'] = $billingsub->id;
                    $transactionPay['account_id'] = 8; // Discount id
                    $transactionPay['type'] = 4;
                    $transactionPay['debit'] = $request->discount;
                    $transactionPay['remark'] = "Internet Bill Discount";
                    $transactionPay['customer_id'] = $billingsub->customer_id;
                    $transactionPay['created_by'] = Auth::id();
                    AccountTransaction::create($transactionPay);
                }

                $transaction['invoice'] = $invoice;
                $transaction['table_id'] = $billing->id;
                $transaction['account_id'] = 10;
                $transaction['type'] = 4;
                $transaction['credit'] = ($request->pay_amount + ($request->discount ?? 0));
                $transaction['remark'] = "Internet Bill";
                $transaction['customer_id'] = $billing->customer_id;
                $transaction['created_by'] = Auth::id();
                AccountTransaction::create($transaction);

                $transaction['date'] = $billingsub->getCustomer->start_date;
                $transaction['pay_method_id'] = $request->payment_method_id ?? null;
                $transaction['local_id'] = $billingsub->id;
                $transaction['type'] = 10;
                $transaction['company_id'] = auth()->user()->company_id;
                $transaction['credit'] =  $request->pay_amount;
                $transaction['amount'] = $billingsub->customer_billing_amount;
                $transaction['note'] = $request->description;
                $transaction['created_by'] = auth()->id();
                Transaction::create($transaction);
            } else {
                return back()->with('failed', 'Opps...Something was Wrong');
            }

            DB::commit();
            return back()->with('success', 'Data Updated Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('failed', 'Oops! Something was wrong. Message: ' . $e->getMessage() . ' Line: ' . $e->getLine() . 'File: ' . $e->getFile());
        }
    }
}
