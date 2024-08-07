<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\DataProcessingFile\TodayBillDataProcessing;
use App\Http\Controllers\Controller;
use App\Models\Billing;
use App\Models\Customer;
use App\Models\Transaction;
use Carbon\Carbon;

class TodayCollectedController extends Controller
{
    use TodayBillDataProcessing;
    /**
     * String property
     */
    protected $routeName =  'todaybillcollected';
    protected $viewName =  'admin.pages.billcollected';

    protected function getModel()
    {
        return new Transaction();
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
                'data' => 'customer_id',
                'searchable' => false,
                'relation' => 'billcollection',
            ],

            [
                'label' => 'Billing Month',
                'data' => 'date_',
                'searchable' => false,
                'relation' => 'billcollection',
            ],
            [
                'label' => 'Customer Phone',
                'data' => 'customer_phone',
                'searchable' => false,
                'relation' => 'billcollection',
            ],
            [
                'label' => 'Customer Profile Id',
                'data' => 'name',
                'searchable' => false,
                'relation' => 'billcollection',
            ],

            [
                'label' => 'Customer Billing Amount',
                'data' => 'amount',
                'searchable' => false,
            ],

            [
                'label' => 'Collected Date',
                'data' => 'created_at',
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
        $page_title = "Today Bill Collection";
        $page_heading = "Today Bill Collection List";
        $ajax_url = route($this->routeName . '.dataProcessing');
        // $create_url = route($this->routeName . '.create');
        $is_show_checkbox = false;
        $columns = $this->reformatForRelationalColumnName(
            $this->tableColumnNames()
        );
        return view('admin.dashboard.customerbilling', get_defined_vars());
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
            $this->getModel()->where('company_id', auth()->user()->company_id)
                ->where('status', 'paid')->whereMonth('date_', date('m'))->whereYear('date_', date('Y'))->whereDate('updated_at', Carbon::now()),
            //Table Columns Name
            $this->tableColumnNames(),
            //Route name
            $this->routeName,
            true,
            []
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Bill Collection  $user
     * @return \Illuminate\Http\Response
     */
}
