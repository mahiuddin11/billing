<?php

namespace App\Http\Controllers\Admin\Income;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AccountTransaction;
use App\Models\Customer;
use App\Models\DailyIncome;
use App\Models\Employee;
use App\Models\IncomeCategory;
use App\Models\Supplier;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DailyIncomeController extends Controller
{

    protected $routeName =  'dailyIncome';
    protected $viewName =  'admin.pages.daily_incomes';

    protected function getModel()
    {
        return new DailyIncome();
    }

    protected function tableColumnNames()
    {
        return [
            [
                'label' => 'SL',
                'data' => 'id',
                'searchable' => false,
            ],
            [
                'label' => 'Date',
                'data' => 'date',
                'searchable' => true,
            ],
            [
                'label' => 'Category',
                'data' => 'service_category_type',
                'searchable' => false,
                'relation' => 'category'
            ],
            [
                'label' => 'Customer',
                'data' => 'name',
                'searchable' => false,
                'relation' => 'customer'
            ],
            [
                'label' => 'Supplier',
                'data' => 'name',
                'searchable' => false,
                'relation' => 'supplier'
            ],
            [
                'label' => 'Account Head',
                'data' => 'account_name',
                'searchable' => false,
                'relation' => 'account'
            ],
            [
                'label' => 'Served Charge',
                'data' => 'amount',
                'searchable' => false,
            ],
            [
                'label' => 'Paid Amount',
                'data' => 'paid_amount',
                'searchable' => false,
            ],
            [
                'label' => 'Description',
                'data' => 'description',
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


    public function index()
    {


        $incomecategories = IncomeCategory::get();
        $ajax_url = route($this->routeName . '.dataProcessing');
        $create_url = route($this->routeName . '.create');
        $is_show_checkbox = false;
        $columns = $this->reformatForRelationalColumnName(
            $this->tableColumnNames()
        );
        return view($this->viewName . '.index', get_defined_vars());
    }

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


    public function create()
    {
        $page_title = "Daily Income Create";
        $page_heading = "Daily Income Create";
        $incomecategories = IncomeCategory::get();
        $customers = Customer::where('company_id', auth()->user()->company_id)->get();
        $suppliers = Supplier::where('company_id', auth()->user()->company_id)->get();
        $paymentMethods = Account::whereIn('id', [2, 3, 4])->get();
        $accounts = Account::getaccount()->where('parent_id', 9)->whereNotIn('id', [10, 14])->get();
        $dailyincomes = DailyIncome::with('category')->get();
        $back_url = route($this->routeName . '.index');
        return view('admin.pages.daily_incomes.create', get_defined_vars());
    }

    public function store(Request $request)
    {
        $valideted = $this->validate($request, [
            'date' => ['required'],
            'category_id' => ['required'],
            'payment_id' => ['required'],
            'customer_id' => ['nullable'],
            'supplier_id' => ['nullable'],
            'amount' => ['required'],
            'account_id' => ['required'],
            'paid_amount' => ['nullable'],
            'description' => ['nullable'],
        ]);
        try {
            DB::beginTransaction();
            if ($request->amount < $request->paid_amount) {
                return back()->with('failed', 'Paid amount is bigger a Service charge');
            }

            // if (is_numeric($request->account_2nd)) {
            // $request->account_id = $request->account_id;
            // }

            // if (is_numeric($request->account_3rd)) {
            //     $request->account_id = $request->account_3rd;
            // }

            // if (is_numeric($request->account_4th)) {
            //     $request->account_id = $request->account_4th;
            // }

            $valideted['account_id'] = $request->account_id;
            // $valideted['account_2nd'] = $request->account_3rd;
            // $valideted['account_3rd'] = $request->account_4th;
            $valideted['company_id'] = auth()->user()->company_id;
            $valideted['created_by'] = auth()->id();
            $dailyincome = DailyIncome::create($valideted);

            $invoice = AccountTransaction::accountInvoice();
            $transaction['invoice'] = $invoice;
            $transaction['table_id'] = $dailyincome->id;
            $transaction['account_id'] = $request->account_id;
            $transaction['type'] = 1;
            $transaction['company_id'] = auth()->user()->company_id;
            $transaction['credit'] = $request->amount;
            $transaction['remark'] = $request->description;
            $transaction['supplier_id'] = $request->supplier_id;
            $transaction['customer_id'] = $request->customer_id;
            $transaction['created_by'] = Auth::id();
            AccountTransaction::create($transaction);

            if (($request->paid_amount > 0 || $request->paid_amount == 0 || $request->paid_amount == null) && $request->paid_amount < $request->amount) {
                $transactionAval['invoice'] = $invoice;
                $transactionAval['table_id'] = $dailyincome->id;
                $transactionAval['account_id'] = $request->payment_id;
                $transactionAval['type'] = 1;
                $transactionAval['company_id'] = auth()->user()->company_id;
                $transactionAval['debit'] = $request->paid_amount;
                $transactionAval['remark'] = $request->description;
                $transactionAval['supplier_id'] = $request->supplier_id;
                $transactionAval['customer_id'] = $request->customer_id;
                $transactionAval['created_by'] = Auth::id();
                AccountTransaction::create($transactionAval);

                $transactionsf['invoice'] = $invoice;
                $transactionsf['table_id'] = $dailyincome->id;
                $transactionsf['account_id'] = 5; //account receivable id;
                $transactionsf['type'] = 1;
                $transactionsf['company_id'] = auth()->user()->company_id;
                $transactionsf['debit'] = $request->amount - $request->paid_amount;
                $transactionsf['remark'] = $request->description;
                $transactionsf['supplier_id'] = $request->supplier_id;
                $transactionsf['customer_id'] = $request->customer_id;
                $transactionsf['created_by'] = Auth::id();
                AccountTransaction::create($transactionsf);
            } elseif ($request->paid_amount == $request->amount) {
                $transactionPay['invoice'] = $invoice;
                $transactionPay['table_id'] = $dailyincome->id;
                $transactionPay['account_id'] = $request->payment_id;
                $transactionPay['type'] = 1;
                $transactionPay['company_id'] = auth()->user()->company_id;
                $transactionPay['debit'] = $request->paid_amount;
                $transactionPay['remark'] = $request->description;
                $transactionPay['supplier_id'] = $request->supplier_id;
                $transactionPay['customer_id'] = $request->customer_id;
                $transactionPay['created_by'] = Auth::id();
                AccountTransaction::create($transactionPay);
            }

            DB::commit();
            return back()->with('success', 'Data Store Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('failed', 'Oops! Something was wrong. Message: ' . $e->getMessage() . ' Line: ' . $e->getLine() . 'File: ' . $e->getFile());
        }
    }

    public function edit($id)
    {
        $page_title = "Daily Income Edit";
        $page_heading = "Daily Income Edit";
        $back_url = route($this->routeName . '.index');
        $dailyincome = DailyIncome::findOrFail($id);
        $incomecategories = IncomeCategory::get();
        $paymentMethods = Account::whereIn('id', [2, 3, 4])->get();
        $customers = Customer::where('company_id', auth()->user()->company_id)->get();
        $suppliers = Supplier::where('company_id', auth()->user()->company_id)->get();
        $accounts = Account::getaccount()->where('parent_id', 9)->whereNotIn('id', [10])->get();
        $users = User::where('company_id', Auth::user()->company_id)->where('is_admin', 4)->get();


        return view('admin.pages.daily_incomes.edit', get_defined_vars());
    }

    public function update(Request $request, DailyIncome $dailyincome)
    {
        $valideted = $this->validate($request, [
            'date' => ['required'],
            'category_id' => ['required'],
            'payment_id' => ['required'],
            'customer_id' => ['nullable'],
            'supplier_id' => ['nullable'],
            'amount' => ['required'],
            'account_id' => ['required'],
            'paid_amount' => ['nullable'],
            'description' => ['nullable'],
        ]);
        try {
            DB::beginTransaction();
            if ($request->amount < $request->paid_amount) {
                return back()->with('failed', 'Paid amount is bigger a Service charge');
            }

            // if (is_numeric($request->account_2nd)) {
            //     $request->account_id = $request->account_2nd;
            // }
            // if (is_numeric($request->account_3rd)) {
            //     $request->account_id = $request->account_3rd;
            // }

            // if (is_numeric($request->account_4th)) {
            //     $request->account_id = $request->account_4th;
            // }

            $valideted['account_id'] = $request->account_id;
            // $valideted['account_2nd'] = $request->account_3rd;
            // $valideted['account_3rd'] = $request->account_4th;
            $valideted['company_id'] = auth()->user()->company_id;
            $valideted['created_by'] = auth()->id();
            $dailyincome->update($valideted);

            AccountTransaction::where('table_id', $dailyincome->id)->where('type', 1)->delete();

            $invoice = AccountTransaction::accountInvoice();
            $transaction['invoice'] = $invoice;
            $transaction['table_id'] = $dailyincome->id;
            $transaction['account_id'] = $request->account_id;
            $transaction['type'] = 1;
            $transaction['company_id'] = auth()->user()->company_id;
            $transaction['credit'] = $request->amount;
            $transaction['remark'] = $request->description;
            $transaction['supplier_id'] = $request->supplier_id;
            $transaction['customer_id'] = $request->customer_id;
            $transaction['created_by'] = Auth::id();
            AccountTransaction::create($transaction);

            if (($request->paid_amount > 0 || $request->paid_amount == 0 || $request->paid_amount == null) && $request->paid_amount < $request->amount) {
                $transactionPay['invoice'] = $invoice;
                $transactionPay['table_id'] = $dailyincome->id;
                $transactionPay['account_id'] = $request->payment_id;
                $transactionPay['type'] = 1;
                $transactionPay['company_id'] = auth()->user()->company_id;
                $transactionPay['debit'] = $request->paid_amount;
                $transactionPay['remark'] = $request->description;
                $transactionPay['supplier_id'] = $request->supplier_id;
                $transactionPay['customer_id'] = $request->customer_id;
                $transactionPay['created_by'] = Auth::id();
                AccountTransaction::create($transactionPay);

                $transactionPa['invoice'] = $invoice;
                $transactionPa['table_id'] = $dailyincome->id;
                $transactionPa['account_id'] = 5; //account receivable id;
                $transactionPa['type'] = 1;
                $transactionPa['company_id'] = auth()->user()->company_id;
                $transactionPa['debit'] = $request->amount - $request->paid_amount;
                $transactionPa['remark'] = $request->description;
                $transactionPa['supplier_id'] = $request->supplier_id;
                $transactionPa['customer_id'] = $request->customer_id;
                $transactionPa['created_by'] = Auth::id();
                AccountTransaction::create($transactionPa);
            } elseif ($request->paid_amount == $request->amount) {
                $transactionPdf['invoice'] = $invoice;
                $transactionPdf['table_id'] = $dailyincome->id;
                $transactionPdf['account_id'] = $request->payment_id;
                $transactionPdf['type'] = 1;
                $transactionPdf['company_id'] = auth()->user()->company_id;
                $transactionPdf['debit'] = $request->paid_amount;
                $transactionPdf['remark'] = $request->description;
                $transactionPdf['supplier_id'] = $request->supplier_id;
                $transactionPdf['customer_id'] = $request->customer_id;
                $transactionPdf['created_by'] = Auth::id();
                AccountTransaction::create($transactionPdf);
            }
            DB::commit();
            return back()->with('success', 'Data Updated Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('failed', 'Oops! Something was wrong. Message: ' . $e->getMessage() . ' Line: ' . $e->getLine() . 'File: ' . $e->getFile());
        }
    }

    public function destroy($id)
    {
        AccountTransaction::where('table_id', $id)->where('type', 1)->delete();
        DailyIncome::findOrFail($id)->delete();
        return Redirect()->back();
    }

    public function search(Request $request)
    {

        $incomecategories = IncomeCategory::get();
        $category = $request->category;
        $start = Carbon::parse($request->start_date);
        $end = Carbon::parse($request->end_date);
        $dailyincomes = DailyIncome::with('category')->whereDate('date', '<=', $end->format('Y-m-d'))
            ->whereDate('date', '>=', $start->format('Y-m-d'));

        // dd($dailyincomes);

        return view('admin.pages.daily_incomes.index', compact('dailyincomes', 'incomecategories'));
    }
}
