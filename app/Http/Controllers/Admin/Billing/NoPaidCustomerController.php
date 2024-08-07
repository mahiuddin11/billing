<?php

namespace App\Http\Controllers\Admin\Billing;

use App\Http\Controllers\Controller;
use App\Models\Billing;
use App\Models\Customer;


class NoPaidCustomerController extends Controller
{
    /**
     * String property
     */
    protected $routeName =  'nopaidcustomer';
    protected $viewName =  'admin.pages.nopaidcustomer';

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
                'label' => 'User Name',
                'data' => 'username',
                'customesearch' => 'customer_id',
                'searchable' => false,
                'relation' => 'getCustomer',
            ],
            [
                'label' => 'Customer',
                'data' => 'name',
                'searchable' => false,
                'relation' => 'getCustomer',
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
                'label' => 'Customer Profile Id',
                'data' => 'name',
                'searchable' => false,
                'relation' => 'getProfile',
            ],
            [
                'label' => 'Payment Method',
                'data' => 'name',
                'searchable' => false,
                'relation' => 'PaymentMethod',
            ],
            [
                'label' => 'Customer Billing Amount',
                'data' => 'customer_billing_amount',
                'searchable' => false,
            ],
            [
                'label' => 'Biller Name',
                'data' => 'name',
                'searchable' => false,
                'relation' => 'getBiller',
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
        $page_title = "Bill Collected";
        $page_heading = "Bill Collected List";
        $ajax_url = route($this->routeName . '.dataProcessing');
        // $create_url = route($this->routeName . '.create');
        $is_show_checkbox = false;
        $columns = $this->reformatForRelationalColumnName(
            $this->tableColumnNames()
        );
        return view('admin.pages.index', get_defined_vars());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function dataProcessing()
    {
        $totalpaid = $this->getModel()->whereMonth('date_', "!=", date('m'))->whereMonth('updated_at', "=", date('m'))->pluck('customer_id');
        return $this->getDataResponse(
            //Model Instance
            $this->getModel()->whereNotIn('customer_id', $totalpaid)->where('company_id', auth()->user()->company_id)
                ->where('status', 'unpaid')->where('type', 'collection')->whereMonth('date_', date('m'))->whereYear('date_', date('Y')),
            //Table Columns Name
            $this->tableColumnNames(),
            //Route name
            $this->routeName,
            true,
            [
                [
                    'method_name' => 'paylist',
                    'class' => 'btn-success',
                    'fontawesome' => 'fa fa-eye',
                    'text' => '',
                    'title' => 'View',
                ],
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
        return view('admin.pages.billcollect.view', get_defined_vars());
    }
}
