<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Helpers\DataProcessingFile\Dashboardbillmanage;
use App\Models\Billing;
use App\Models\AccountTransaction;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardBillListController extends Controller
{
    use Dashboardbillmanage;
    protected $routeName =  'dashboardbill';
    protected $viewName =  'admin.pages.dashboardbill';

    protected function getModel()
    {
        return new Customer();
    }

    protected function tableColumnNames()
    {
        return [
            [
                'label' => 'IP/ID',
                'data' => 'username',
                'searchable' => true,
            ],
            [
                'label' => 'Name',
                'data' => 'name',
                'searchable' => true,
            ],
            [
                'label' => 'Mobile Number',
                'data' => 'phone',
                'searchable' => false,
            ],

            [
                'label' => 'Expiry Date',
                'data' => 'exp_date',
                'isdate' => true,
                'searchable' => false,
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

        ];
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($type)
    {

        if ($type == 1) {
            $page_title = "Total Bill";
            $page_heading = "Total Bill List";
        } elseif ($type == 2) {
            $page_title = "Collected Bill";
            $page_heading = "Collected Bill List";
        } elseif ($type == 3) {
            $page_title = "Due Bill";
            $page_heading = "Due Bill List";
        }
        $ajax_url = route($this->routeName . '.dataProcessing', $type);
        // $create_url = route($this->routeName . '.create');
        $is_show_checkbox = false;
        $columns = $this->reformatForRelationalColumnName(
            $this->tableColumnNames()
        );
        // dd(get_defined_vars());
        return view('admin.pages.Dashboardindex', get_defined_vars());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function dataProcessing($id)
    {

        if ($id == 1) {
            $model = $this->getModel()->where('billing_status_id', 5)->where('company_id', auth()->user()->company_id);
        } elseif ($id == 2) {
            // $this_monthly_collected_bill = Transaction::where('company_id', auth()->user()->company_id)->where('type', 10)->whereMonth('date', date('m'))->whereYear('date', date('Y'))->pluck('local_id');
            // dd($this_monthly_collected_bill);
            // $this_monthly_collected_bill = AccountTransaction::where('company_id', auth()->user()->company_id)->where('type', 4)->where('account_id', '!=', 5)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->pluck('table_id');
            $billingCustomer = Billing::where('company_id', auth()->user()->company_id)->whereMonth('date_', date('m'))->whereYear('date_', date('Y'))->sum('pay_amount');
            $model = $this->getModel()->whereIn('id', $billingCustomer);
        } elseif ($id == 3) {
            $duebillingCustomer = Billing::where('company_id', auth()->user()->company_id)
                ->where('status', 'unpaid')->whereMonth('date_', date('m'))->whereYear('date_', date('Y'))->pluck('customer_id');
            $model = $this->getModel()->whereIn('id', $duebillingCustomer);
        }
        return $this->getData(
            $model,
            $this->tableColumnNames(),
            //Route name
            $this->routeName,
        );
    }
}
