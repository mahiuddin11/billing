<?php

namespace App\Http\Controllers\Admin\Expense;

use App\Models\Expense;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AccountTransaction;
use App\Models\Customer;
use App\Models\ExpenseCategory;
use App\Models\PaymentMethod;
use App\Models\Supplier;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    /**
     * String property
     */
    protected $routeName =  'expenses';
    protected $viewName =  'admin.pages.expenses';

    protected function getModel()
    {
        return new Expense();
    }

    protected function tableColumnNames()
    {
        return [
            [
                'label' => 'Date',
                'data' => 'date',
                'searchable' => false,
            ],
            [
                'label' => 'Supplier',
                'data' => 'name',
                'relation' => 'supplier',
                'searchable' => false,
            ],
            [
                'label' => 'Customer',
                'data' => 'username',
                'searchable' => false,
                'relation' => 'customer'
            ],
            [
                'label' => 'Account',
                'data' => 'account_name',
                'customesearch' => 'account_id',
                'relation' => 'accountlist',
                'searchable' => false,
            ],
            [
                'label' => 'Category Method',
                'data' => 'name',
                'relation' => 'expense_category',
                'searchable' => false,
            ],
            [
                'label' => 'Amount',
                'data' => 'amount',
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
        $page_title = "Expense";
        $page_heading = "Expense List";
        $ajax_url = route($this->routeName . '.dataProcessing');
        $create_url = route($this->routeName . '.create');
        $is_show_checkbox = false;
        $columns = $this->reformatForRelationalColumnName(
            $this->tableColumnNames()
        );
        $accounts = Account::getaccount()->get();
        $paymentods = PaymentMethod::where('status', 'active')->where('company_id', auth()->user()->company_id)->get();
        $expensecategorys = ExpenseCategory::where('status', 'Active')->get();
        $customers = Customer::where('company_id', auth()->user()->company_id)->get();
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
            $this->getModel()->orderBy('id', 'desc'),
            //Table Columns Name
            $this->tableColumnNames(),
            //Route name
            $this->routeName,
            true,
            [
                'edit',
                [
                    'method_name' => 'destroy',
                    'class' => 'btn-danger btn-sm',
                    'fontawesome' => 'fa fa-trash',
                    'code' => 'onclick ="return confirm(`Are you sure you want to delete this`)"',
                    'text' => '',
                    'title' => 'Delete',
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
        $page_title = "Expense Create";
        $page_heading = "Expense Create";
        $back_url = route($this->routeName . '.index');
        $store_url = route($this->routeName . '.store');
        $customers = Customer::where('company_id', auth()->user()->company_id)->get();
        $paymentMethods = Account::whereIn('id', [2, 3, 4])->get();
        $accounts = Account::getaccount()->where('parent_id', 6)->whereNotIn('id', [7, 8, 13])->get();
        $suppliers = Supplier::get();
        $categories = ExpenseCategory::where('status', 'Active')->get();
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
            'customer_id' => ['nullable'],
            'account_id' => ['required'],
            'payment_id' => ['required'],
            'supplier_id' => ['nullable'],
            'expense_category_id' => ['nullable'],
            'date' => ['required'],
            'amount' => ['nullable'],
            'note' => ['nullable'],
        ]);

        try {
            DB::beginTransaction();

            $valideted['account_id'] = $request->account_id;
            // $valideted['account_2nd'] = $request->account_3rd;
            // $valideted['account_3rd'] = $request->account_4th;
            $valideted['created_by'] = auth()->id();
            $valideted['company_id'] = auth()->user()->company_id;
            $expense =  Expense::create($valideted);

            $invoice = AccountTransaction::accountInvoice();
            $transaction['invoice'] = $invoice;
            $transaction['table_id'] = $expense->id;
            $transaction['account_id'] = $request->account_id;
            $transaction['type'] = 2;
            $transaction['company_id'] = auth()->user()->company_id;
            $transaction['debit'] = $request->amount;
            $transaction['remark'] = $request->description;
            $transaction['supplier_id'] = $request->supplier_id;
            $transaction['customer_id'] = $request->customer_id;
            $transaction['created_by'] = Auth::id();
            AccountTransaction::create($transaction);

            $transactionPay['invoice'] = $invoice;
            $transactionPay['table_id'] = $expense->id;
            $transactionPay['account_id'] = $request->payment_id;
            $transactionPay['type'] = 2;
            $transactionPay['company_id'] = auth()->user()->company_id;
            $transactionPay['credit'] = $request->amount;
            $transactionPay['remark'] = $request->description;
            $transactionPay['supplier_id'] = $request->supplier_id;
            $transactionPay['customer_id'] = $request->customer_id;
            $transactionPay['created_by'] = Auth::id();
            AccountTransaction::create($transactionPay);

            DB::commit();
            return back()->with('success', 'Data Store Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('failed', 'Something was wrong Message' . $e->getMessage() . 'Line' . $e->getLine());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Account  $Account
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Expense $Expense)
    {

        $modal_title = 'Account Details';
        $modal_data = $Expense;

        $html = view('admin.pages.Expense.show', get_defined_vars())->render();
        return $html;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Account  $Account
     * @return \Illuminate\Http\Response
     */
    public function edit(Expense $Expense)
    {
        $page_title = "Expense Edit";
        $page_heading = "Expense Edit";
        $back_url = route($this->routeName . '.index');
        $update_url = route($this->routeName . '.update', $Expense->id);
        $customers = Customer::where('company_id', auth()->user()->company_id)->get();
        $paymentMethods = Account::whereIn('id', [2, 3, 4])->get();
        $accounts = Account::getaccount()->where('parent_id', 6)->whereNotIn('id', [7, 8])->get();
        $suppliers = Supplier::get();
        $categories = ExpenseCategory::where('status', 'Active')->get();

        return view($this->viewName . '.edit', get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Account  $Account
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Expense $expense)
    {
        $valideted = $this->validate($request, [
            'customer_id' => ['nullable'],
            'account_id' => ['required'],
            'expense_category_id' => ['nullable'],
            'payment_id' => ['required'],
            'supplier_id' => ['nullable'],
            'date' => ['required'],
            'amount' => ['nullable'],
            'note' => ['nullable'],
        ]);

        try {
            DB::beginTransaction();

            $valideted['account_id'] = $request->account_id;
            // $valideted['account_2nd'] = $request->account_3rd;
            // $valideted['account_3rd'] = $request->account_4th;
            $valideted['created_by'] = auth()->id();
            $valideted['company_id'] = auth()->user()->company_id;
            $expense->update($valideted);

            AccountTransaction::where('type', 2)->where('table_id', $expense->id)->delete();

            $invoice = AccountTransaction::accountInvoice();
            $transaction['invoice'] = $invoice;
            $transaction['table_id'] = $expense->id;
            $transaction['account_id'] = $request->account_id;
            $transaction['type'] = 2;
            $transaction['debit'] = $request->amount;
            $transaction['company_id'] = auth()->user()->company_id;
            $transaction['remark'] = $request->description;
            $transaction['supplier_id'] = $request->supplier_id;
            $transaction['customer_id'] = $request->customer_id;
            $transaction['created_by'] = Auth::id();
            AccountTransaction::create($transaction);

            $transactionPay['invoice'] = $invoice;
            $transactionPay['table_id'] = $expense->id;
            $transactionPay['account_id'] = $request->payment_id;
            $transactionPay['type'] = 2;
            $transactionPay['company_id'] = auth()->user()->company_id;
            $transactionPay['credit'] = $request->amount;
            $transactionPay['remark'] = $request->description;
            $transactionPay['supplier_id'] = $request->supplier_id;
            $transactionPay['customer_id'] = $request->customer_id;
            $transactionPay['created_by'] = Auth::id();
            AccountTransaction::create($transactionPay);

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
    public function destroy(Expense $Expense)
    {
        AccountTransaction::where('type', 2)->where('table_id', $Expense->id)->delete();
        $Expense->delete();
        return Redirect()->back()->with('success', 'Data deleted successfully.');
    }
}
