<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccountTransaction;
use App\Models\BandwidthCustomer;
use App\Models\Billing;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\MacReseller;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $CustomerModel = new Customer();
        $billingModel = new Billing();
        $customers = $CustomerModel->where('company_id', auth()->user()->company_id)->count();
        
        $active_customers = $CustomerModel->where('company_id', auth()->user()->company_id)->where('billing_status_id', 5)->count();
        

        $today_line_off_transaction = Transaction::where('company_id', auth()->user()->company_id)->where('type', 12)->whereDate('created_at', today()->format('Y-m-d'))->pluck('local_id');
       
        $today_line_off = $CustomerModel->whereIn('id', $today_line_off_transaction)->count();
      
        
        $this_monthly_bill = $billingModel->where('company_id', auth()->user()->company_id)->whereMonth('date_', date('m'))->whereYear('date_', date('Y'))->sum('customer_billing_amount');
        $getTableId = AccountTransaction::where('company_id', auth()->user()->company_id)->where('type', 4)->where('account_id', '!=', 5)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->pluck('table_id');

        $this_monthly_collected_bill = Billing::where('company_id', auth()->user()->company_id)->whereIn('id', $getTableId)->whereMonth('date_', date('m'))->whereYear('date_', date('Y'))->sum('pay_amount');

        $this_monthly_discount_bill = Billing::where('company_id', auth()->user()->company_id)->whereNotIn('discount', [0, "null"])->whereMonth('date_', date('m'))->whereYear('date_', date('Y'))->sum("discount");

        $this_monthly_due_bill = $this_monthly_bill - $this_monthly_collected_bill -  $this_monthly_discount_bill;

        $inactive_customers = $CustomerModel->where('company_id', auth()->user()->company_id)->where('billing_status_id', 4)->count();
        $free_customers = $CustomerModel->where('company_id', auth()->user()->company_id)->where('billing_status_id', 2)->count();
        $left_customers = $CustomerModel->where('company_id', auth()->user()->company_id)->where('billing_status_id', 1)->count();

        $mac_client = MacReseller::count();
        $new_customers = $CustomerModel->where('company_id', auth()->user()->company_id)->whereMonth('created_at', today()->format('m'))->whereYear('created_at', today()->format('Y'))->count();
        $suppliers = Supplier::count();
        $products = Product::count();
        $todays_billings = $billingModel->where('company_id', auth()->user()->company_id)->whereDate('updated_at', today())->sum('pay_amount');
        $total_billings = $billingModel->where('company_id', auth()->user()->company_id)->whereIn('status', ['paid', 'partial'])->sum('pay_amount');

        $total_unpaids = Billing::where('billings.company_id', auth()->user()->company_id)
            ->where('billings.status', 'unpaid')->whereMonth('billings.date_', date('m'))->whereYear('billings.date_', date('Y'))->count();

        $this_monthly_collected_billlo = AccountTransaction::where('company_id', auth()->user()->company_id)->where('type', 4)->where('account_id', '!=', 5)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->pluck('table_id');

        $paid_customers = Billing::leftJoin('customers', 'customers.id', '=', 'billings.customer_id')
            ->select('billings.*', 'billings.id', 'customers.username as username', 'customers.queue_name as queue_name',  'customers.zone_id as zone_id', 'customers.exp_date as exp_date',  'customers.billing_person as billing_person')
            ->where('billings.company_id', auth()->user()->company_id)
            ->whereIn('billings.id', $this_monthly_collected_billlo)
            ->where('billings.status', '!=', 'unpaid')
            ->orderBy('username', 'asc')->count();


        $partial_customers = $CustomerModel->where('company_id', auth()->user()->company_id)->where('due', ">", 0)->count();

        // $total_due = $billingModel->whereNotNull('due')->sum('due');
        $partial_dues = $billingModel->where('company_id', auth()->user()->company_id)->whereNotNull('partial')->sum('partial');
        $unpaid_dues = $billingModel->where('company_id', auth()->user()->company_id)->where('status', 'unpaid')->sum('customer_billing_amount');
        $total = 0;
        $get_total_dues = $billingModel->where('company_id', auth()->user()->company_id)->whereIn('status', ['unpaid', 'partial'])->get();
        $total_due = 0;
        foreach ($get_total_dues as $due) {
            $total_due += intval($due->customer_billing_amount) - $due->pay_amount;
        }

        $macsallers = MacReseller::count();
        $bandwith_clients = BandwidthCustomer::count();
        // $billings = $billingModel->where('company_id', auth()->user()->company_id)->where('date_', today()->format('d-m-Y'))->get();
        return view('admin.pages.dashboard', get_defined_vars());
    }

    public function customerInfo()
    {
        $headerTitle = "Active Customer in mikrotik";
        $customers = Customer::where('company_id', auth()->user()->company_id)->where('disabled', 'false')->where('billing_status_id', 5)->paginate(100);
        return view('admin.dashboard.customerInfo', get_defined_vars());
    }

    public function customerInactive()
    {
        $headerTitle = "Inactive Customer in mikrotik";
        $customers = Customer::where('company_id', auth()->user()->company_id)->where('billing_status_id', 4)->paginate(100);
        return view('admin.dashboard.customerInfo', get_defined_vars());
    }

    public function newcustomer()
    {
        $headerTitle = "New Customer";
        $customers = Customer::where('company_id', auth()->user()->company_id)->whereMonth('created_at', today()->format('m'))->whereYear('created_at', today()->format('Y'))->paginate(100);
        return view('admin.dashboard.customerInfo', get_defined_vars());
    }

    public function TodayCollectedBill(Request $req)
    {
        $headerTitle = "Today Collected Bill Customer";
        if ($req->method() == "POST") {
            $tableid = AccountTransaction::where('company_id', auth()->user()->company_id)->where('type', 4)->whereDate('created_at', $req->searchPayment)->pluck('table_id');
        } else {
            $tableid = AccountTransaction::where('company_id', auth()->user()->company_id)->where('type', 4)->whereDate('created_at', date('Y-m-d'))->pluck('table_id');
        }
        $billings = Billing::with('getCustomer')->whereIn('id', $tableid);
        if ($req->employee_id) {
            $billings = $billings->where('billing_by', $req->employee_id);
        }

        $billings = $billings->get();
        $employees = User::where('is_admin', 5)->get();
        return view('admin.dashboard.customerbilling', get_defined_vars());
    }

    protected function getModel()
    {
        return new Billing();
    }

    protected function paidtableColumnNames()
    {
        return [
            [
                'label' => 'ID',
                'data' => 'id',
                'searchable' => false,
            ],
            [
                'label' => 'User Name',
                'data' => 'username',
                'customesearch' => 'customer_id',
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
                'searchable' => false,
                'relation' => 'getCustomer',
            ],
            [
                'label' => 'Customer Phone',
                'data' => 'phone',
                'searchable' => false,
                'relation' => 'getCustomer',
            ],
            [
                'label' => 'Customer Profile',
                'data' => 'name',
                'searchable' => false,
                'relation' => 'getProfile',
            ],

            [
                'label' => 'Customer Billing Amount',
                'data' => 'pay_amount',
                'searchable' => false,
            ],

            [
                'label' => 'Billing Status',
                'data' => 'status',
                'searchable' => false,
            ],

            // [
            //     'label' => 'Action',
            //     'data' => 'action',
            //     'class' => 'text-nowrap',
            //     'orderable' => false,
            //     'searchable' => false,
            // ],

        ];
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
                'label' => 'User Name',
                'data' => 'username',
                'customesearch' => 'customer_id',
                'searchable' => false,
                'relation' => 'getCustomer',
            ],
            // [
            //     'label' => 'Customer',
            //     'data' => 'name',
            //     'searchable' => false,
            //     'relation' => 'getCustomer',
            // ],
            [
                'label' => 'Billing Month',
                'data' => 'date_',
                'searchable' => false,
            ],
            [
                'label' => 'EXP Date',
                'data' => 'exp_date',
                'searchable' => false,
                'relation' => 'getCustomer',
            ],
            [
                'label' => 'Customer Phone',
                'data' => 'customer_phone',
                'searchable' => false,
            ],
            [
                'label' => 'Customer Profile',
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
                'label' => 'Billing Status',
                'data' => 'status',
                'searchable' => false,
            ],

            // [
            //     'label' => 'Action',
            //     'data' => 'action',
            //     'class' => 'text-nowrap',
            //     'orderable' => false,
            //     'searchable' => false,
            // ],

        ];
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function totalDiscountBill()
    {
        $page_title = "Discount Bill";
        $page_heading = "Discount Bill List";
        $is_show_checkbox = false;

        $this_monthly_discount_bill = Billing::where('company_id', auth()->user()->company_id)->whereNotIn('discount', [0, "null"])->whereMonth('date_', date('m'))->whereYear('date_', date('Y'))->get();

        return view('admin.dashboard.totalDiscountBill', get_defined_vars());
    }

    public function totalCollectedBill()
    {
        $page_title = "Collected Bill";
        $page_heading = "Collected Bill List";
        $ajax_url = route('totalCollectedBill.dataprocess');
        $is_show_checkbox = false;
        $columns = $this->reformatForRelationalColumnName(
            $this->paidtableColumnNames()
        );
        $customers = Customer::where('company_id', auth()->user()->company_id)->where('billing_status_id', 5)->get();
        return view('admin.dashboard.totalCollectedBill', get_defined_vars());
    }

    public function totalpaindingBill()
    {
        $page_title = "Due Bill";
        $page_heading = "Due Bill List";
        $ajax_url = route('totalpaindingBill.data');
        $is_show_checkbox = false;
        // $paymentmethods = PaymentMethod::where('status', 'active')->get();
        $columns = $this->reformatForRelationalColumnName(
            $this->tableColumnNames()
        );
        $customers = Customer::where('company_id', auth()->user()->company_id)->get();
        return view('admin.dashboard.totalpaindingBill', get_defined_vars());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function totalpaindingBilldataProcessing()
    {
        return $this->getDataResponse(
            //Model Instance
            $this->getModel()->where('company_id', auth()->user()->company_id)
                ->whereIn('status', ['unpaid', 'partial'])->orderBy('id', 'desc'),
            //Table Columns Name
            $this->tableColumnNames(),
            //Route name
            true,
            []

        );
    }
    public function dataProcessing()
    {
        return $this->getDataResponse(
            //Model Instance
            $this->getModel()->where('company_id', auth()->user()->company_id)
                ->whereIn('status', ['paid', 'partial']),
            //Table Columns Name
            $this->paidtableColumnNames(),
            //Route name
            true,
            []

        );
    }

    function invoice($dm, $id)
    {
        $billing = Billing::with(['company', 'getCustomer'])->find($id);
        $amount = (($billing->customer_billing_amount ?? 0) - ($billing->pay_amount ?? 0));
        return view('invoice', get_defined_vars());
    }
}
