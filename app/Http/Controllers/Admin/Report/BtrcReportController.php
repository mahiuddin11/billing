<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use App\Models\Billing;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Helpers\DataProcessingFile\BtrcReportDataProcessing;

class BtrcReportController extends Controller
{
    /**
     * String property
     */
    use BtrcReportDataProcessing;
    protected $routeName =  'reports';
    protected $viewName =  'admin.pages.reports';

    protected function getModel()
    {
        return new Customer();
    }

    protected function tableColumnNames()
    {
        return [
            [
                'label' => "client_type",
                'data' => "name",
                'searchable' => false,
                'relation' => "getClientType",
            ],
            [
                'label' => "connection_type",
                'data' => "connection_type",
                'searchable' => false,
            ],
            [
                'label' => "client_name",
                'data' => "name",
                'searchable' => false,
            ],
            [
                'label' => "bandwidth_distribution_point",
                'data' => "DC",
                'searchable' => false,
            ],
            [
                'label' => "connectivity_type",
                'data' => "connectivity_type",
                'searchable' => false,
            ],
            [
                'label' => "activation_date",
                'data' => "connection_date",
                'searchable' => false,
            ],
            [
                'label' => "bandwidth_allocation",
                'data' => "speed",
                'searchable' => false,
                'relation' => "getMProfile",
            ],
            [
                'label' => "allocated_ip",
                'data' => "username",
                'searchable' => false,
            ],
            [
                'label' => "division",
                'data' => "division",
                'searchable' => false,
            ],
            [
                'label' => "district",
                'data' => "district",
                'searchable' => false,
            ],
            [
                'label' => "thana",
                'data' => "thana",
                'searchable' => false,
            ],
            [
                'label' => "address",
                'data' => "address",
                'searchable' => false,
            ],
            [
                'label' => "client_mobile",
                'data' => "phone",
                'searchable' => false,
            ],
            [
                'label' => "unit_price_bdt",
                'data' => "unit_price_bdt",
                'searchable' => false,
            ],
            [
                'label' => "client_email",
                'data' => "email",
                'searchable' => false,
            ],
            [
                'label' => "selling_price_bdt_excluding_vat",
                'data' => "bill_amount",
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
    public function index()
    {
        $page_title = "BTRC Report";
        $page_heading = "BTRC Report";
        $ajax_url = route($this->routeName . '.dataProcessing');
        // $create_url = route($this->routeName . '.create');
        $is_show_checkbox = false;
        $columns = $this->reformatForRelationalColumnName(
            $this->tableColumnNames()
        );
        return view('admin.pages.reports.btrc.index', get_defined_vars());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function dataProcessing(Request $request)
    {
        $customer = Billing::where('company_id', auth()->user()->company_id)
            ->where('status', 'paid')->whereMonth('date_', date("m", strtotime(request('columns.7.search.value') ?? date('Y-m-d'))))->whereYear('date_', date("Y", strtotime(request('columns.7.search.value') ?? date('Y-m-d'))))->pluck('customer_id');
        return $this->getDataResponse(
            //Model Instance
            $this->getModel()->whereIn('id', $customer),
            //Table Columns Name
            $this->tableColumnNames(),
            //Route name
            $this->routeName
        );
    }
}
