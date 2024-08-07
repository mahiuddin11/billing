<?php

namespace App\Http\Controllers\Admin\MacReseller;

use AddAmountToPaymentMethods;
use App\Http\Controllers\Controller;
use App\Models\ResellerFunding;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\DataProcessingFile\MacCustomerBillDataProcessing;
use App\Models\Account;
use App\Models\AccountTransaction;
use App\Models\Company;
use App\Models\MacCustomerBill;
use App\Models\MacReseller;
use App\Models\PaymentMethod;
use Faker\Provider\ar_EG\Payment;
use Illuminate\Support\Facades\Auth;

class ResellerFundingController extends Controller
{
    use MacCustomerBillDataProcessing;
    /**
     * String property
     */
    protected $routeName =  'resellerFunding';
    protected $viewName =  'admin.pages.resellerFunding';

    protected function getModel()
    {
        return new MacCustomerBill();
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
                'label' => 'Reseller',
                'data' => 'name',
                'searchable' => false,
            ],
            [
                'label' => 'Month',
                'data' => 'month',
                'searchable' => false,
            ],
            [
                'label' => 'Amount',
                'data' => 'amount',
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
        $page_title = "Client Invoice";
        $page_heading = "Client Invoice List";
        $ajax_url = route($this->routeName . '.dataProcessing');
        // $create_url = route($this->routeName . '.create');
        $is_show_checkbox = false;
        $columns = $this->reformatForRelationalColumnName(
            $this->tableColumnNames()
        );
        return view($this->viewName . '.index', get_defined_vars());
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
            $this->getModel(),
            //Table Columns Name
            $this->tableColumnNames(),
            //Route name
            $this->routeName,
            true,
            [
                [
                    'method_name' => 'show',
                    'class' => 'btn-success',
                    'fontawesome' => 'fa fa-eye',
                    'text' => '',
                    'title' => 'SHOW',
                ],

            ]

        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page_title = "Client Invoice Create";
        $page_heading = "Client Invoice Create";
        $back_url = route($this->routeName . '.index');
        $store_url = route($this->routeName . '.store');

        return view($this->viewName . '.create', get_defined_vars());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $valideted = $this->validate($request, [
            'name' => ['required'],
            'bandwidth_md' => ['required'],
            'details' => ['nullable']
        ]);

        try {
            DB::beginTransaction();
            $valideted['created_by'] = auth()->id();
            ResellerFunding::create($valideted);
            DB::commit();
            return back()->with('success', 'Data Store Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('failed', 'Oops! Something was wrong. Message: ' . $e->getMessage() . ' Line: ' . $e->getLine() . 'File: ' . $e->getFile());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Account  $Account
     * @return \Illuminate\Http\Response
     */

    public function show(Request $req)
    {
        $companyInfo = auth()->user()->company;
        $resellerCustomerBills = MacCustomerBill::with(['company', 'customer'])->where('company_id', $req->company_id)->whereMonth('date_', $req->month)->whereYear('date_', $req->year)->get();
        $invoice = Company::find($req->company_id);
        // dd($resellerCustomerBills);
        return view($this->viewName . '.invoice', get_defined_vars());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Account  $Account
     * @return \Illuminate\Http\Response
     */
    public function edit(ResellerFunding $ResellerFunding)
    {
        $page_title = "Client Invoice Edit";
        $page_heading = "Client Invoice Edit";
        $back_url = route($this->routeName . '.index');
        $update_url = route($this->routeName . '.update', $ResellerFunding->id);
        $editinfo = $ResellerFunding;
        return view($this->viewName . '.edit', get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Account  $Account
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ResellerFunding $ResellerFunding)
    {
        $valideted = $this->validate($request, [
            'name' => ['required'],
            'bandwidth_md' => ['required'],
            'details' => ['nullable']
        ]);

        try {
            DB::beginTransaction();
            $valideted['updated_by'] = auth()->id();
            $ResellerFunding->update($valideted);
            DB::commit();
            return back()->with('success', 'Data Updated Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('failed', 'Oops! Something was wrong. Message: ' . $e->getMessage() . ' Line: ' . $e->getLine() . 'File: ' . $e->getFile());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Account  $Account
     * @return \Illuminate\Http\Response
     */
    public function destroy(ResellerFunding $ResellerFunding)
    {
        $ResellerFunding->delete();
        return back()->with('success', 'Data deleted successfully.');
    }
    public function sendMessage(ResellerFunding $ResellerFunding)
    {
        $editinfo = $ResellerFunding;
        return view('admin.pages.sms.send-message', get_defined_vars());
    }

    // Reseller Payment

    public function paymentCreate()
    {
        $back_url = route($this->routeName . '.index');
        $resellers = MacReseller::get();
        $paymentMethods = Account::whereIn('id', [2, 3, 4])->get();
        return view('admin.pages.addresellerfund.payment_create', get_defined_vars());
    }

    public function paymentStore(Request $request)
    {
        $valideted = $this->validate($request, [
            'reseller_id' => ['required'],
            'amount' => ['required'],
            'payment_method_id' => ['required']
        ]);

        $invoice = AccountTransaction::accountInvoice();
        $transactionPay['invoice'] = $invoice;
        $transactionPay['table_id'] = 0;
        $transactionPay['account_id'] = $request->payment_method_id;
        $transactionPay['type'] = 7;
        $transactionPay['company_id'] = auth()->user()->company_id;
        $transactionPay['debit'] = $request->amount;
        $transactionPay['remark'] = $request->note;
        $transactionPay['customer_id'] = $request->reseller_id;
        $transactionPay['created_by'] = Auth::id();
        AccountTransaction::create($transactionPay);

        $transactionPa['invoice'] = $invoice;
        $transactionPa['table_id'] = 0;
        $transactionPa['account_id'] = 5;
        $transactionPa['type'] = 7;
        $transactionPa['company_id'] = auth()->user()->company_id;
        $transactionPa['credit'] =  $request->amount;
        $transactionPa['remark'] = $request->note;
        $transactionPa['customer_id'] = $request->reseller_id;
        $transactionPa['created_by'] = Auth::id();
        AccountTransaction::create($transactionPa);

        return back()->with('success', 'successfully Updated');
    }

    public function resellerdue(Request $request)
    {
        $accountTrans = AccountTransaction::where('type', 7)
            ->selectRaw('SUM(debit) as debit, SUM(credit) as credit')
            ->where('customer_id', $request->reseller_id)->whereNotIn('account_id', [5])->groupBy('invoice')->get();
        $total = 0;
        foreach ($accountTrans as $accountTran) {
            $total += ($accountTran->credit ?? 0) - ($accountTran->debit ?? 0);
        }
        return $total;
    }
}
