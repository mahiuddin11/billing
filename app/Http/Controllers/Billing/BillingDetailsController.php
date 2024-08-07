<?php

namespace App\Http\Controllers\Billing;

use App\Helpers\DataProcessingFile\customerBillingDataProcessing;
use App\Http\Controllers\Controller;
use App\Models\Billing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BillingDetailsController extends Controller
{
    use customerBillingDataProcessing;

    protected $routeName =  'billing_details';
    protected $viewName =  'customer.pages.billing_details';

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
                'label' => 'Action',
                'data' => 'action',
                'class' => 'text-nowrap',
                'orderable' => false,
                'searchable' => false,
            ],
            [
                'label' => 'Billing Month',
                'data' => 'date_',
                'searchable' => false,
            ],
            [
                'label' => 'Package',
                'data' => 'name',
                'searchable' => false,
                'relation' => 'getProfile',
            ],

            [
                'label' => 'Billing Amount',
                'data' => 'customer_billing_amount',
                'searchable' => false,
            ],

            [
                'label' => 'Paid Amount',
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
        $page_title = "Billing Details";
        $page_heading = "Billing Details";
        $ajax_url = route($this->routeName . '.dataProcessing');
        $is_show_checkbox = false;
        $columns = $this->reformatForRelationalColumnName(
            $this->tableColumnNames()
        );

        return view('customer.pages.billing_details.index', get_defined_vars());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function dataProcessing(Request $request)
    {
        return $this->getDataResponse(
            //Model Instance
            $this->getModel()->where('customer_id', Auth::guard('customer')->id()),
            //Table Columns Name
            $this->tableColumnNames(),
            //Route name
            $this->routeName,
            true,
            [
                [
                    'method_name' => 'bkash-create-payment',
                    'class' => '',
                    'fontawesome' => '',
                    'text' => '
                    <img src="https://www.logo.wine/a/logo/BKash/BKash-Icon2-Logo.wine.svg" style="height:55px;" alt="text">
               ',
                    'title' => 'Pay',
                    'code' => "",
                ],
            ]
        );
    }
}
