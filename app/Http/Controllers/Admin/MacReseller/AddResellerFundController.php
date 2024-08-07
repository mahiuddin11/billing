<?php

namespace App\Http\Controllers\Admin\MacReseller;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AccountTransaction;
use App\Models\AddResellerFund;
use App\Models\MacReseller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AddResellerFundController extends Controller
{
    /**
     * String property
     */
    protected $routeName =  'addresellerfund';
    protected $viewName =  'admin.pages.addresellerfund';

    protected function getModel()
    {
        return new AddResellerFund();
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
                'data' => 'person_name',
                'searchable' => false,
                'relation' => 'macreseller',
            ],
            [
                'label' => ' fund Amount',
                'data' => 'fund',
                'searchable' => false,
            ],
            // [
            //     'label' => 'payed Amount',
            //     'data' => 'payed',
            //     'searchable' => false,
            // ],
            [
                'label' => 'Receive by',
                'data' => 'name',
                'searchable' => false,
                'relation' => 'user',
            ],
            [
                'label' => 'Date',
                'data' => 'date',
                'searchable' => false,
            ],
            [
                'label' => 'Note',
                'data' => 'note',
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
        $page_title = "Client Fund";
        $page_heading = "Client Fund List";
        $ajax_url = route($this->routeName . '.dataProcessing');
        $create_url = route($this->routeName . '.create');
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

        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        $page_title = "Client Fund Create";
        $page_heading = "Client Fund Create";
        $back_url = route($this->routeName . '.index');
        $store_url = route($this->routeName . '.store');
        $resellers = MacReseller::get();
        $users = User::where('company_id', auth()->user()->company_id)->get();
        $paymentMethods = Account::whereIn('id', [2, 3, 4])->get();
        $accounts = Account::getaccount()->where('parent_id', 9)->whereNotIn('id', [10, 14])->get();
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
            'reseller_id' => ['required'],
            'fund' => ['required'],
            'payed' => ['nullable'],
            'recive_by' => ['nullable'],
            // 'payment_id' => ['required'],
            'account_id' => ['required'],
            'date' => ['nullable'],
            'note' => ['nullable']
        ]);

        try {
            DB::beginTransaction();
            $macreseller = MacReseller::find($request->reseller_id);
            $macreseller->update(['recharge_balance' => $macreseller->recharge_balance + $request->fund]);
            $valideted['create_by'] = auth()->id();

            $addresellerfund = AddResellerFund::create($valideted);

            $invoice = AccountTransaction::accountInvoice();
            $transaction['invoice'] = $invoice;
            $transaction['table_id'] = $addresellerfund->id;
            $transaction['account_id'] = $request->account_id;
            $transaction['type'] = 7;
            $transaction['company_id'] = auth()->user()->company_id;
            $transaction['credit'] = $request->fund;
            $transaction['remark'] = $request->description;
            $transaction['customer_id'] = $request->reseller_id;
            $transaction['created_by'] = Auth::id();
            AccountTransaction::create($transaction);

            // if (($request->payed > 0 || $request->payed == 0 || $request->payed == null) && $request->payed < $request->fund) {
            // $transactionAval['invoice'] = $invoice;
            // $transactionAval['table_id'] = $request->reseller_id;
            // $transactionAval['account_id'] = $request->payment_id;
            // $transactionAval['type'] = 7;
            // $transactionAval['company_id'] = auth()->user()->company_id;
            // $transactionAval['debit'] = $request->payed;
            // $transactionAval['remark'] = $request->description;
            // $transactionAval['supplier_id'] = $request->supplier_id;
            // $transactionAval['customer_id'] = $request->customer_id;
            // $transactionAval['created_by'] = Auth::id();
            // AccountTransaction::create($transactionAval);

            $transactionsf['invoice'] = $invoice;
            $transactionsf['table_id'] = $addresellerfund->id;
            $transactionsf['account_id'] = 5; //account receivable id;
            $transactionsf['type'] = 7;
            $transactionsf['company_id'] = auth()->user()->company_id;
            $transactionsf['debit'] = $request->fund;
            $transactionsf['remark'] = $request->description;
            $transactionsf['customer_id'] = $request->reseller_id;
            $transactionsf['created_by'] = Auth::id();
            AccountTransaction::create($transactionsf);

            // } elseif ($request->payed == $request->fund) {
            //     $transactionPay['invoice'] = $invoice;
            //     $transactionPay['table_id'] = $request->reseller_id;
            //     $transactionPay['account_id'] = $request->payment_id;
            //     $transactionPay['type'] = 7;
            //     $transactionPay['company_id'] = auth()->user()->company_id;
            //     $transactionPay['debit'] = $request->payed;
            //     $transactionPay['remark'] = $request->description;
            //     $transactionPay['supplier_id'] = $request->supplier_id;
            //     $transactionPay['customer_id'] = $request->customer_id;
            //     $transactionPay['created_by'] = Auth::id();
            //     AccountTransaction::create($transactionPay);
            // }
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

    public function show(Request $request, AddResellerFund  $AddResellerFund)
    {
        $modal_title = 'Package Details';
        $modal_data = $AddResellerFund;
        $html = view($this->viewName . '.show', get_defined_vars())->render();
        return $html;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Account  $Account
     * @return \Illuminate\Http\Response
     */
    public function edit(AddResellerFund $AddResellerFund)
    {
        $page_title = "Client Fund Edit";
        $page_heading = "Client Fund Edit";
        $back_url = route($this->routeName . '.index');
        $update_url = route($this->routeName . '.update', $AddResellerFund->id);
        $editinfo = $AddResellerFund;
        $resellers = MacReseller::get();
        $users = User::where('company_id', auth()->user()->company_id)->get();
        $paymentMethods = Account::whereIn('id', [2, 3, 4])->get();
        $accounts = Account::getaccount()->where('parent_id', 9)->whereNotIn('id', [10, 14])->get();
        return view($this->viewName . '.edit', get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Account  $Account
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AddResellerFund $AddResellerFund)
    {
        $valideted = $this->validate($request, [
            'reseller_id' => ['required'],
            'fund' => ['required'],
            // 'payed' => ['nullable'],
            'recive_by' => ['nullable'],
            'date' => ['nullable'],
            'note' => ['nullable']
        ]);

        try {
            DB::beginTransaction();
            $macreseller = MacReseller::find($AddResellerFund->reseller_id);
            $macreseller->update(['recharge_balance' => $macreseller->recharge_balance - $AddResellerFund->fund]);

            $macreseller = MacReseller::find($request->reseller_id);
            $macreseller->update(['recharge_balance' => $macreseller->recharge_balance + $request->fund]);

            $valideted['create_by'] = auth()->id();
            $AddResellerFund->update($valideted);

            AccountTransaction::where('type', 7)->where('table_id', $AddResellerFund->id)->delete();

            $invoice = AccountTransaction::accountInvoice();
            $transaction['invoice'] = $invoice;
            $transaction['table_id'] = $AddResellerFund->id;
            $transaction['account_id'] = $request->account_id;
            $transaction['type'] = 7;
            $transaction['company_id'] = auth()->user()->company_id;
            $transaction['credit'] = $request->fund;
            $transaction['remark'] = $request->description;
            $transaction['customer_id'] = $request->reseller_id;
            $transaction['created_by'] = Auth::id();
            AccountTransaction::create($transaction);

            // if (($request->payed > 0 || $request->payed == 0 || $request->payed == null) && $request->payed < $request->fund) {
            // $transactionAval['invoice'] = $invoice;
            // $transactionAval['table_id'] = $request->reseller_id;
            // $transactionAval['account_id'] = $request->payment_id;
            // $transactionAval['type'] = 7;
            // $transactionAval['company_id'] = auth()->user()->company_id;
            // $transactionAval['debit'] = $request->payed;
            // $transactionAval['remark'] = $request->description;
            // $transactionAval['supplier_id'] = $request->supplier_id;
            // $transactionAval['customer_id'] = $request->customer_id;
            // $transactionAval['created_by'] = Auth::id();
            // AccountTransaction::create($transactionAval);

            $transactionsf['invoice'] = $invoice;
            $transactionsf['table_id'] = $AddResellerFund->id;
            $transactionsf['account_id'] = 5; //account receivable id;
            $transactionsf['type'] = 7;
            $transactionsf['company_id'] = auth()->user()->company_id;
            $transactionsf['debit'] = $request->fund;
            $transactionsf['remark'] = $request->description;
            $transactionsf['customer_id'] = $request->reseller_id;
            $transactionsf['created_by'] = Auth::id();
            AccountTransaction::create($transactionsf);

            // } elseif ($request->payed == $request->fund) {
            //     $transactionPay['invoice'] = $invoice;
            //     $transactionPay['table_id'] = $request->reseller_id;
            //     $transactionPay['account_id'] = $request->payment_id;
            //     $transactionPay['type'] = 7;
            //     $transactionPay['company_id'] = auth()->user()->company_id;
            //     $transactionPay['debit'] = $request->payed;
            //     $transactionPay['remark'] = $request->description;
            //     $transactionPay['supplier_id'] = $request->supplier_id;
            //     $transactionPay['customer_id'] = $request->customer_id;
            //     $transactionPay['created_by'] = Auth::id();
            //     AccountTransaction::create($transactionPay);
            // }

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
    public function destroy(AddResellerFund $AddResellerFund)
    {
        AccountTransaction::where('type', 7)->where('table_id', $AddResellerFund->id)->delete();
        $AddResellerFund->delete();

        return back()->with('success', 'Data deleted successfully.');
    }
    public function sendMessage(AddResellerFund $AddResellerFund)
    {
        $editinfo = $AddResellerFund;
        return view('admin.pages.sms.send-message', get_defined_vars());
    }
}
