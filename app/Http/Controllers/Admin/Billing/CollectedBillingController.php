<?php

namespace App\Http\Controllers\Admin\Billing;

use App\Helpers\DataProcessingFile\CollectedDataProcessing;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Billing;
use App\Models\Customer;
use App\Models\User;
use App\Models\AccountTransaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CollectedBillingController extends Controller
{
    use CollectedDataProcessing;
    /**
     * String property
     */
    protected $routeName =  'billcollected';
    protected $viewName =  'admin.pages.billcollected';

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
                'label' => 'UserName/Queue',
                'data' => 'username',
                'customesearch' => 'customer_id',
                'searchable' => false,
                // 'relation' => 'getCustomer',
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
                // 'relation' => 'getCustomer',
            ],
            [
                'label' => 'Customer Phone',
                'data' => 'customer_phone',
                'searchable' => false,
            ],
            [
                'label' => 'Customer Profile Id',
                'data' => 'name',
                'searchable' => false,
                'relation' => 'getProfile',
            ],
            [
                'label' => 'Payed Bill',
                'data' => 'pay_amount',
                'searchable' => false,
            ],
            [
                'label' => 'Collected Date',
                'data' => 'collected_date',
                'searchable' => false,
            ],
            [
                'label' => 'Collected Customer',
                'data' => 'name',
                'relation' => 'getBiller',
                'customesearch' => 'billing_by',
                'searchable' => false,
            ],
            [
                'label' => 'From date',
                'data' => 'created_at',
                'searchable' => false,
            ],
            [
                'label' => 'TO date',
                'data' => 'created_at',
                'searchable' => false,
            ],
            [
                'label' => 'Invoice',
                'data' => 'invoice_name',
                'searchable' => false,
            ],
            [
                'label' => 'Method',
                'data' => 'method',
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
        $page_title = "Bill Collected";
        $page_heading = "Bill Collected List";
        $ajax_url = route($this->routeName . '.dataProcessing');
        // $create_url = route($this->routeName . '.create');
        $is_show_checkbox = false;
        $columns = $this->reformatForRelationalColumnName(
            $this->tableColumnNames()
        );
        $employees = User::where('company_id', auth()->user()->company_id)->whereIn('is_admin', [4, 5])->get();
        $customers = Customer::where('company_id', auth()->user()->company_id)->get();
        return view($this->viewName . '.index', get_defined_vars());
    }

    /**
     * Show the form for creating a new resource.s
     *
     * @return \Illuminate\Http\Response
     */
    public function dataProcessing()
    {
        // $model = DB::table('account_transactions')->join('billings', 'billings.id', '=', 'account_transactions.table_id')
        //     ->join('customers', 'customers.id', '=', 'billings.customer_id')
        //     ->select('account_transactions.id as id', 'customers.billing_person as billing_person', 'customers.username as username', 'billings.date_ as date_', 'customers.exp_date as exp_date', 'customers.phone as phone', 'billings.pay_amount as pay_amount', 'account_transactions.created_at as paid_time')
        //     ->where('account_transactions.type', 4);
        $this_monthly_collected_bill = AccountTransaction::where('company_id', auth()->user()->company_id)->where('type', 4)->where('account_id', '!=', 5)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->pluck('table_id');

        $model = $this->getModel()
            ->leftJoin('customers', 'customers.id', '=', 'billings.customer_id')
            ->select('billings.*', 'billings.id', 'customers.username as username', 'customers.queue_name as queue_name',  'customers.zone_id as zone_id', 'customers.exp_date as exp_date',  'customers.billing_person as billing_person')
            ->where('billings.company_id', auth()->user()->company_id)
            ->whereIn('billings.id', $this_monthly_collected_bill)
            ->where('billings.status', '!=', 'unpaid')
            ->orderBy('username', 'asc');

        if (auth()->user()->is_admin == 4)
            $model = $model->where('billing_person', auth()->id());

        return $this->getDataResponse(
            //Model Instance,
            $model,
            //Table Columns Name
            $this->tableColumnNames(),
            //Route name
            $this->routeName,
            true,
            [
                [
                    'method_name' => 'payment',
                    'class' => 'btn-success btn-sm',
                    'fontawesome' => '',
                    'text' => 'View',
                    'title' => 'View',
                ],
                (auth()->user()->is_admin == 1 || auth()->user()->is_admin == 3) ? [
                    'method_name' => 'destroy',
                    'class' => 'btn-danger btn-sm',
                    'fontawesome' => 'fa fa-trash',
                    'text' => '',
                    'title' => 'Destroy',
                ] : null,
            ]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Bill Collection  $user
     * @return \Illuminate\Http\Response
     */

    public function paylist(Customer $billcollected)
    {
        $back_url = route($this->routeName . '.index');
        $customerPaymentDetails = Billing::where('customer_id', $billcollected->id)->get();
        $paymentMethods = Account::whereIn('id', [2, 3, 4])->get();
        return view('admin.pages.billcollect.view', get_defined_vars());
    }

    public function destroy(Billing $billing)
    {

        $maccustomer = \App\Models\MacCustomerBill::where("customer_id",$billing->customer_id)->whereMonth("date_",date("m",strtotime($billing->date_)))->whereYear("date_",date("Y",strtotime($billing->date_)))->first();

        if (auth()->user()->mac_reseler) {

            $macReseller = auth()->user()->mac_reseler; // Assuming 'macReseller' is the relationship name

            $macReseller->update(['rechargeable_amount' => $macReseller->rechargeable_amount + $maccustomer->charge]);

            $maccustomer->delete();
        }

        $startDate = Carbon::parse($billing->getCustomer->start_date)->subMonth($billing->getCustomer->duration)->format('Y-m-d');
        $endDate = Carbon::parse($billing->getCustomer->exp_date)->subMonth($billing->getCustomer->duration)->format('Y-m-d');

        $billing->getCustomer->update([
            'start_date' => $startDate,
            'exp_date' => $endDate,
        ]);

        $billing->paymentDetails()->delete();
        $billing->update([
            'invoice_name' => null,
            'discount' => null,
            'description' => null,
            'pay_amount' => null,
            'partial' => null,
            "payment_method_id" => null,
            'billing_by' => null,
            'status' => "unpaid"
        ]);
        return redirect()->back()->with('success', 'Delete Successfully !!');
    }
}
