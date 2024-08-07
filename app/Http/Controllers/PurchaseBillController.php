<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AccountTransaction;
use App\Models\Company;
use App\Models\Item;
use App\Models\Provider;
use App\Models\PurchaseBill;
use App\Models\PurchaseBillDetails;
use App\Models\TransactionHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseBillController extends Controller
{
    protected $routeName =  'purchasebill';
    protected $viewName =  'admin.pages.purchasebill';


    protected function getModel()
    {
        return new PurchaseBill();
    }

    protected function tableColumnNames()
    {
        return [

            [
                'label' => 'SL',
                'data' => 'id',
                'searchable' => true,
            ],
            [
                'label' => 'Provider',
                'data' => 'company_name',
                'searchable' => false,
                'relation' => 'provider',
            ],

            [
                'label' => 'Billing Month',
                'data' => 'billing_month',
                'searchable' => false,
            ],
            [
                'label' => 'Total',
                'data' => 'total',
                'searchable' => false,
            ],
            // [
            //     'label' => 'Discount',
            //     'data' => 'discount',
            //     'searchable' => false,
            // ],
            // [
            //     'label' => 'Payed',
            //     'data' => 'payed',
            //     'searchable' => false,
            // ],
            // [
            //     'label' => 'Payment due',
            //     'data' => 'due',
            //     'searchable' => false,
            // ],
            [
                'label' => 'Invoice No',
                'data' => 'invoice_no',
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
        $page_title = "Bandwidth Buy";
        $page_heading = "Bandwidth Buy List";
        $ajax_url = route($this->routeName . '.dataProcessing');
        $create_url = route($this->routeName . '.create');
        $is_show_checkbox = false;
        $columns = $this->reformatForRelationalColumnName(
            $this->tableColumnNames()
        );
        $accounts = Account::getaccount()->get();

        return view($this->viewName . '.index', get_defined_vars());
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
            $this->getModel(),
            //Table Columns Name
            $this->tableColumnNames(),
            //Route name
            $this->routeName,
            true,
            [
                // [
                //     'method_name' => 'pay',
                //     'class' => 'btn-info  btn-sm paymodel',
                //     'fontawesome' => '',
                //     'text' => 'Pay',
                //     'title' => 'View',
                // ],
                [
                    'method_name' => 'invoice',
                    'class' => 'btn-info  btn-sm',
                    'fontawesome' => '',
                    'text' => 'invoice',
                    'title' => 'invoice',
                ],
                [
                    'method_name' => 'destroy',
                    'class' => 'btn-danger  btn-sm',
                    'fontawesome' => '',
                    'text' => 'Destroy',
                    'title' => 'Destroy',
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
        $page_title = "Bandwidth Buy Create";
        $page_heading = "Bandwidth Buy Create";
        $back_url = route($this->routeName . '.index');
        $store_url = route($this->routeName . '.store');
        $providers = Provider::get();
        $items = Item::get();
        $purchaseLastData = PurchaseBill::latest('id')->pluck('id')->first() ?? "0";
        $accounts = Account::getaccount()->where('parent_id', 6)->whereNotIn('id', [7, 8, 13])->get();
        $invoice_no = 'BB' . str_pad($purchaseLastData + 1, 5, "0", STR_PAD_LEFT);
        return view($this->viewName . '.create', get_defined_vars());
    }

    public function invoice(PurchaseBill $PurchaseBill)
    {
        $page_title = "Bandwidth Buy invoice";
        $page_heading = "Bandwidth Buy invoice";
        $back_url = route($this->routeName . '.index');
        $companyInfo = Company::find(auth()->user()->company_id);
        $invoice = $PurchaseBill;
        return view($this->viewName . '.invoice', get_defined_vars());
    }

    public function store(Request $request)
    {
        $valideted = $this->validate($request, [
            'provider_id' => ['required'],
            'invoice_no' => ['required'],
            'month' => ['required'],
        ]);

        try {
            DB::beginTransaction();
            $purchaseBill['provider_id'] = $request->provider_id;
            $purchaseBill['billing_month'] = $request->month;
            $purchaseBill['invoice_no'] = $request->invoice_no;
            $purchaseBill['note'] = $request->note;
            // $purchaseBill['account_id'] = $request->account_id;
            $purchaseBill['created_by'] = auth()->id();
            $purchaseBill['total'] = array_sum($request->total);
            $purchaseBill['discount'] = $request->discount;
            $purchaseBill['payed'] = $request->paid_amount;
            $due = (array_sum($request->total) - $request->discount) - $request->paid_amount;
            $purchaseBill['due'] = $due;
            $purchasebill =  $this->getModel()->create($purchaseBill);

            // $account =  Account::find($request->account_id);
            // $account->update(['amount' => $account->amount - $request->paid_amount]);

            for ($i = 0; $i < count($request->item_id); $i++) {
                $purchasedetails[] = [
                    'purchase_bill_id' => $purchasebill->id,
                    'item_id' => $request->item_id[$i],
                    'description' => $request->description[$i],
                    'unit' => $request->unit[$i],
                    'qty' => $request->qty[$i],
                    'rate' => $request->rate[$i],
                    'vat' => $request->vat[$i],
                    'from_date' => $request->from_date[$i],
                    'to_date' => $request->to_date[$i],
                    'total' => $request->total[$i],
                ];
            }

            PurchaseBillDetails::insert($purchasedetails);

            $invoice = AccountTransaction::accountInvoice();
            $transactionPay['invoice'] = $invoice;
            $transactionPay['table_id'] = $purchasebill->id;
            $transactionPay['account_id'] = $request->account_id;
            $transactionPay['type'] = 6;
            $transactionPay['company_id'] = auth()->user()->company_id;
            $transactionPay['debit'] = array_sum($request->total);
            $transactionPay['remark'] = $request->remark;
            $transactionPay['customer_id'] = $request->provider_id;
            $transactionPay['created_by'] = Auth::id();
            AccountTransaction::create($transactionPay);

            $transactionPa['invoice'] = $invoice;
            $transactionPa['table_id'] = $purchasebill->id;
            $transactionPa['account_id'] = 13;
            $transactionPa['type'] = 6;
            $transactionPa['company_id'] = auth()->user()->company_id;
            $transactionPa['credit'] =  array_sum($request->total);
            $transactionPa['remark'] = $request->remark;
            $transactionPa['customer_id'] = $request->provider_id;
            $transactionPa['created_by'] = Auth::id();
            AccountTransaction::create($transactionPa);


            DB::commit();
            return back()->with('success', 'Data Store Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('failed', $this->getError($e));
        }
    }

    public function edit(PurchaseBill $PurchaseBill)
    {
        $page_title = "Bandwidth Buy Edit";
        $page_heading = "Bandwidth Buy Edit";

        $back_url = route($this->routeName . '.index');
        $update_url = route($this->routeName . '.update', $PurchaseBill->id);
        $editinfo = $PurchaseBill;

        return view($this->viewName . '.edit', get_defined_vars());
    }

    public function update(Request $request, PurchaseBill $PurchaseBill)
    {
        $valideted = $this->validate($request, [
            'district_name' => ['required'],
            'details' => ['nullable'],
        ]);

        try {
            DB::beginTransaction();
            $district = $PurchaseBill->update($valideted);
            DB::commit();
            return back()->with('success', 'Data Updated Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('failed', 'Oops! Something was wrong. Message: ' . $e->getMessage() . ' Line: ' . $e->getLine() . 'File: ' . $e->getFile());
        }
    }

    public function destroy(PurchaseBill $PurchaseBill)
    {
        $PurchaseBill->delete();
        return back()->with('success', 'Data deleted successfully.');
    }

    public function pay()
    {
        $accounts = Account::getaccount()->get();
        $back_url = route($this->routeName . '.index');
        $providers = Provider::get();
        $accounts = Account::getaccount()->where('parent_id', 6)->whereNotIn('id', [7, 8, 13])->get();
        $paymentMethods = Account::whereIn('id', [2, 3, 4])->get();

        return view($this->viewName . '.pay', get_defined_vars());
    }

    public function paystore(Request $request)
    {
        $valideted = $this->validate($request, [
            "date_" => ["required",],
            "amount" => ["required",],
            "provider_id" => ["required",],
            "discount" => ["nullable",],
            "payment_method" => ["required",],
            "paid_by" => ["required",],
            "description" => ["nullable",],
        ]);

        try {
            DB::beginTransaction();

            $pay = TransactionHistory::create($valideted);


            $invoice = AccountTransaction::accountInvoice();
            $transactionAval['invoice'] = $invoice;
            $transactionAval['table_id'] = $pay->id;
            $transactionAval['account_id'] = $request->payment_method;
            $transactionAval['type'] = 6;
            $transactionAval['company_id'] = auth()->user()->company_id;
            $transactionAval['credit'] = $request->amount;
            $transactionAval['remark'] = $request->description . ' Paid By ' . $request->paid_by;
            $transactionAval['customer_id'] = $request->provider_id;
            $transactionAval['created_by'] = Auth::id();
            AccountTransaction::create($transactionAval);

            $transactionsf['invoice'] = $invoice;
            $transactionsf['table_id'] = $pay->id;
            $transactionsf['account_id'] = 13; //account payable id;
            $transactionsf['type'] = 6;
            $transactionsf['company_id'] = auth()->user()->company_id;
            $transactionsf['debit'] = $request->amount;
            $transactionsf['remark'] = $request->description . ' Paid By ' . $request->paid_by;
            $transactionsf['customer_id'] = $request->provider_id;
            $transactionsf['created_by'] = Auth::id();
            AccountTransaction::create($transactionsf);


            DB::commit();
            return back()->with('success', 'Data Updated Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('failed', 'Oops! Something was wrong. Message: ' . $e->getMessage() . ' Line: ' . $e->getLine() . 'File: ' . $e->getFile());
        }
    }

    public function getAvailableBalance(Request $request)
    {
        $account = AccountTransaction::where('type', 6)->where('customer_id', $request->provider_id)->whereNotIn('account_id', [13])
            ->selectRaw('SUM(credit) as credit, SUM(debit) as debit')->first();
        return response()->json([
            'amount' =>  $account->debit - $account->credit,
        ]);
    }
}
