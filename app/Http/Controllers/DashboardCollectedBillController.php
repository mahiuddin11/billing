<?php

namespace App\Http\Controllers;

use App\Models\Customer;

use App\Models\Billing;
use App\Models\AccountTransaction;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardCollectedBillController extends Controller
{

    protected $routeName =  'dashboardcollectedbill';
    protected $viewName =  'admin.pages.dashboardbill';

    protected function getModel()
    {
        return new Billing();
    }

    protected function tableColumnNames()
    {
        return [
            [
                'label' => 'IP/ID',
                'data' => 'username',
                'relation' => 'getCustomer',
                'searchable' => true,
            ],
            [
                'label' => 'Name',
                'data' => 'name',
                'relation' => 'getCustomer',
                'searchable' => true,
            ],
            [
                'label' => 'Mobile Number',
                'data' => 'phone',
                'relation' => 'getCustomer',
                'searchable' => false,
            ],

            [
                'label' => 'Expiry Date',
                'data' => 'exp_date',
                'isdate' => true,
                'relation' => 'getCustomer',
                'searchable' => false,
            ],

            [
                'label' => 'Amount',
                'data' => 'pay_amount',
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

        $page_title = "Collected Bill";
        $page_heading = "Collected Bill List";

        $ajax_url = route($this->routeName . '.dataProcessing');
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
    public function dataProcessing()
    {
        $this_monthly_collected_bill = AccountTransaction::where('company_id', auth()->user()->company_id)->where('type', 4)->where('account_id', '!=', 5)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->pluck('table_id');
        $model = $this->getModel()
            ->leftJoin('customers', 'customers.id', '=', 'billings.customer_id')
            ->leftJoin('zones', 'zones.id', '=', 'customers.zone_id')
            ->select('billings.*', 'billings.id', 'customers.username as username', 'customers.zone_id as zone_id', 'customers.billing_person as billing_person', 'zones.name as zone')
            ->where('billings.company_id', auth()->user()->company_id)
            ->whereIn('billings.id', $this_monthly_collected_bill)
            ->orderBy('zone', 'asc')
            ->whereMonth('billings.date_', date('m'))
            ->whereYear('billings.date_', date('Y'))
            ->orderBy('username', 'asc');

        // $this_monthly_collected_bill = Transaction::where('company_id', auth()->user()->company_id)->where('type', 10)->whereMonth('date', date('m'))->whereYear('date', date('Y'))->pluck('local_id');
        // dd($this_monthly_collected_bill);

        return $this->getDataResponse(
            $model,
            $this->tableColumnNames(),
            //Route name
            $this->routeName,
        );
    }
}
