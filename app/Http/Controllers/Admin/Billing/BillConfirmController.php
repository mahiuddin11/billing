<?php

namespace App\Http\Controllers\Admin\Billing;

use App\Helpers\DataProcessingFile\CollectedDataProcessing;
use App\Http\Controllers\Controller;
use App\Models\Billing;
use App\Models\Customer;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use \RouterOS\Query;

class BillConfirmController extends Controller
{
    use CollectedDataProcessing;

    /**
     * String property
     */

    protected $routeName =  'billconfirm';
    protected $viewName =  'admin.pages.billconfirm';

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
        $page_title = "Bill Confirm";
        $page_heading = "Bill Confirm List";
        $ajax_url = route($this->routeName . '.dataProcessing');
        // $create_url = route($this->routeName . '.create');
        $is_show_checkbox = false;
        $columns = $this->reformatForRelationalColumnName(
            $this->tableColumnNames()
        );
        $employees = User::where('company_id', auth()->user()->company_id)->where('is_admin', 4)->get();
        $customers = Customer::where('company_id', auth()->user()->company_id)->get();
        return view('admin.pages.index', get_defined_vars());
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
                ->where('status', 'confirm'),
            //Table Columns Name
            $this->tableColumnNames(),
            //Route name
            $this->routeName,
            true,
            [
                [
                    'method_name' => 'confirm',
                    'class' => 'btn-success btn-sm',
                    'fontawesome' => '',
                    'text' => 'Confirm',
                    'title' => 'Confirm',
                    'code' => 'onclick="return confirm(`Are You Sure , you want to Confirm`)"',

                ],
                [
                    'method_name' => 'reject',
                    'class' => 'btn-danger btn-sm',
                    'fontawesome' => '',
                    'text' => 'reject',
                    'title' => 'reject',
                    'code' => 'onclick="return confirm(`Are You Sure , you want to reject`)"',

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

    public function confirm(Billing $billing)
    {
        try {
            $billing->update([
                'alert' => "white",
                'pay_amount' => $billing->customer_billing_amount,
                'status' => 'paid',
            ]);

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
                "total_paid" => $billing->getCustomer->total_paid +  $billing->customer_billing_amount,
            ]);

            $message = "Sir," . $billing->getCustomer->username . " Recieve Amount " . $billing->customer_billing_amount . " Monthly Bill: " . $billing->customer_billing_amount . " Tk Thank you Sabuj Bangla 01919195588";
            $url = "http://188.138.41.146:7788/sendtext?";
            $data = [
                "apikey" => "524e67d6e2334f64",
                "callerID" => "sabujbangla123",
                "toUser" => $billing->getCustomer->phone,
                "secretkey" => "3165a4f5",
                "messageContent" => $message
            ];
            Http::post($url, $data);
            return redirect()->route('billconfirm.index')->with('success', 'Confirm Successfully !!');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('billconfirm.index')->with('error', 'Somthing was Wrong !!');
        }
    }

    public function reject(Billing $billing)
    {
        try {
            $billing->update([
                'invoice_name' => null,
                'pay_amount' => 0,
                'status' => 'unpaid',
                'billing_by' => null,
            ]);

            Transaction::where('type', 10)->where('local_id', $billing->id)->delete();
            Transaction::where('type', 11)->where('local_id', $billing->id)->delete();

            $client = $this->client($billing->getCustomer->server_id);
            $query =  new Query('/ppp/secret/set');
            $query->equal('.id', $billing->getCustomer->mid);
            $query->equal('disabled', $billing->getCustomer->disabled);
            $client->query($query)->read();

            return redirect()->route('billconfirm.index')->with('success', 'Confirm Successfully !!');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('billconfirm.index')->with('error', 'Somthing was Wrong !!');
        }
    }
}
