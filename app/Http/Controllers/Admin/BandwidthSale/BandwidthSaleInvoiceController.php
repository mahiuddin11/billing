<?php

namespace App\Http\Controllers\Admin\BandwidthSale;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AccountTransaction;
use App\Models\BandwidthCustomer;
use App\Models\BandwidthSaleInvoice;
use App\Models\Company;
use App\Models\Item;
use App\Models\PaymentMethod;
use App\Models\TransactionHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BandwidthSaleInvoiceController extends Controller
{
    /**
     * String property
     */
    protected $routeName =  'bandwidthsaleinvoice';
    protected $viewName =  'admin.pages.bandwidthsale.bandwidthsaleinvoice';

    protected function getModel()
    {
        return new BandwidthSaleInvoice();
    }

    protected function tableColumnNames()
    {
        return [
            [
                'label' => 'SN',
                'data' => 'id',
                'searchable' => false,
            ],
            [
                'label' => 'Invoice',
                'data' => 'invoice_no',
                'searchable' => false,
            ],
            [
                'label' => 'Company',
                'data' => 'name',
                'searchable' => false,
                'relation' => 'customer',
            ],
            [
                'label' => 'Contact Person',
                'data' => 'contact_person',
                'searchable' => false,
                'relation' => 'customer',
            ],

            [
                'label' => 'Month',
                'data' => 'billing_month',
                'searchable' => false,
            ],
            [
                'label' => 'Total Amount',
                'data' => 'total',
                'searchable' => false,
            ],
            [
                'label' => 'Received Amount',
                'data' => 'received_amount',
                'searchable' => false,
            ],
            [
                'label' => 'Discount',
                'data' => 'discount',
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
        $page_title = "Bandwidth Sale Invoice";
        $page_heading = "Bandwidth Sale Invoice List";
        $ajax_url = route($this->routeName . '.dataProcessing');
        $create_url = route($this->routeName . '.create');
        $bandwidthsales = BandwidthSaleInvoice::get();
        $columns = $this->reformatForRelationalColumnName(
            $this->tableColumnNames()
        );
        return view($this->viewName . '.index', get_defined_vars());
    }
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
                    'text' => 'Delete',
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


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page_title = "Bandwidth Sale Invoice Create";
        $page_heading = "Bandwidth Sale Invoice Create";
        $back_url = route($this->routeName . '.index');
        $store_url = route($this->routeName . '.store');
        $customers = BandwidthCustomer::get();
        $items = Item::where('status', 'active')->get();
        $accounts = Account::getaccount()->where('parent_id', 9)->whereNotIn('id', [10])->get();
        $purchaseLastData = BandwidthSaleInvoice::latest('id')->pluck('id')->first() ?? "0";
        $invoice_no = 'BS' . str_pad($purchaseLastData + 1, 5, "0", STR_PAD_LEFT);
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
            "customer_id" => ["required",],
            "invoice_no" => ["required"],
            "billing_month" => ["required",],
            "account_id" => ["required"],
            "remark" => ["nullable"],
        ]);

        try {
            DB::beginTransaction();
            $valideted['due'] = array_sum($request->total);
            $valideted['total'] = array_sum($request->total);
            $valideted['created_by'] = auth()->id();
            $bandwidthsaleinvoice = $this->getModel()->create($valideted);
            // dd($bandwidthsaleinvoice);
            for ($i = 0; $i < count($request->item_id); $i++) {
                $details[] = [
                    'bandwidth_sale_invoice_id' => $bandwidthsaleinvoice->id,
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
            $this->getModel()->detaile()->insert($details);

            $invoice = AccountTransaction::accountInvoice();
            $transactionPay['invoice'] = $invoice;
            $transactionPay['table_id'] = $bandwidthsaleinvoice->id;
            $transactionPay['account_id'] = $request->account_id;
            $transactionPay['type'] = 5;
            $transactionPay['company_id'] = auth()->user()->company_id;
            $transactionPay['credit'] = array_sum($request->total);
            $transactionPay['remark'] = $request->remark;
            $transactionPay['customer_id'] = $request->customer_id;
            $transactionPay['created_by'] = Auth::id();
            AccountTransaction::create($transactionPay);

            $transactionPa['invoice'] = $invoice;
            $transactionPa['table_id'] = $bandwidthsaleinvoice->id;
            $transactionPa['account_id'] = 5;
            $transactionPa['type'] = 5;
            $transactionPa['company_id'] = auth()->user()->company_id;
            $transactionPa['debit'] =  array_sum($request->total);
            $transactionPa['remark'] = $request->remark;
            $transactionPa['customer_id'] = $request->customer_id;
            $transactionPa['created_by'] = Auth::id();
            AccountTransaction::create($transactionPa);


            DB::commit();
            return redirect()->route('bandwidthsaleinvoice.invoice', $bandwidthsaleinvoice->id)->with('success', 'Data Store Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('failed', 'Something was wrong' . 'Message' . $e->getMessage() . 'File' . $e->getFile());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ItemCategory  $ItemCategory
     * @return \Illuminate\Http\Response
     */
    public function invoice(BandwidthSaleInvoice $banseidthsaleinvoice)
    {
        $page_title = "Bandwidth Sale Invoice Edit";
        $page_heading = "Bandwidth Sale Invoice Edit";
        $back_url = route($this->routeName . '.index');
        $update_url = route($this->routeName . '.update', $banseidthsaleinvoice->id);
        $editinfo = $banseidthsaleinvoice;
        $customers = BandwidthCustomer::get();
        $items = Item::where('status', 'active')->get();
        $invoice = $banseidthsaleinvoice;
        $companyInfo = Company::find(auth()->user()->company_id);
        return view($this->viewName . '.invoice', get_defined_vars());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ItemCategory  $ItemCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(BandwidthSaleInvoice $banseidthsaleinvoice)
    {
        $page_title = "Bandwidth Sale Invoice Edit";
        $page_heading = "Bandwidth Sale Invoice Edit";
        $back_url = route($this->routeName . '.index');
        $update_url = route($this->routeName . '.update', $banseidthsaleinvoice->id);
        $editinfo = $banseidthsaleinvoice;
        $customers = BandwidthCustomer::get();
        $items = Item::where('status', 'active')->get();
        return view($this->viewName . '.edit', get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ItemCategory  $ItemCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BandwidthSaleInvoice $banseidthsaleinvoice)
    {
        $valideted = $this->validate($request, [
            "customer_id" => ["required",],
            "invoice_no" => ["nullable"],
            "billing_month" => ["required",],
            "payment_due" => ["required"],
            "remark" => ["nullable"],
        ]);

        try {
            DB::beginTransaction();
            $valideted['due'] = array_sum($request->total);
            $valideted['total'] = array_sum($request->total);
            $valideted['updated_by'] = auth()->id();
            $banseidthsaleinvoice->update($valideted);
            $banseidthsaleinvoice->detaile()->delete();
            for ($i = 0; $i < count($request->item_id); $i++) {
                $details[] = [
                    'bandwidth_sale_invoice_id' => $banseidthsaleinvoice->id,
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

            $this->getModel()->detaile()->insert($details);

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
     * @param  \App\Models\ItemCategory  $ItemCategory
     * @return \Illuminate\Http\Response
     */
    public function itemval(Request $request)
    {
        $itels = Item::select('unit', 'vat')->find($request->item_id);
        return response()->json($itels);
    }
    public function destroy(BandwidthSaleInvoice $banseidthsaleinvoice)
    {
        $banseidthsaleinvoice->delete();
        return back()->with('success', 'Data deleted successfully.');
    }

    public function getAvailableBalance(Request $request)
    {
        $account = AccountTransaction::where('type', 5)->where('customer_id', $request->customer_id)->whereNotIn('account_id', [5])
            ->selectRaw('SUM(credit) as credit, SUM(debit) as debit')->first();
        return response()->json([
            'amount' => $account->credit - $account->debit,
        ]);
    }

    public function pay()
    {
        $accounts = Account::getaccount()->get();
        $back_url = route($this->routeName . '.index');
        $customers = BandwidthCustomer::get();
        $paymentMethods = Account::whereIn('id', [2, 3, 4])->get();
        $accounts = Account::getaccount()->where('parent_id', 9)->whereNotIn('id', [10])->get();
        return view($this->viewName . '.pay', get_defined_vars());
    }

    public function paystore(Request $request)
    {
        $valideted = $this->validate($request, [
            "date_" => ["required",],
            "amount" => ["required",],
            "customer_id" => ["required",],
            "discount" => ["nullable",],
            "payment_method" => ["required",],
            "account_id" => ["required",],
            "paid_by" => ["nullable",],
            "description" => ["nullable",],
        ]);

        try {
            DB::beginTransaction();

            $pay = TransactionHistory::create($valideted);

            $invoice = AccountTransaction::accountInvoice();
            $transactionAval['invoice'] = $invoice;
            $transactionAval['table_id'] = $pay->id;
            $transactionAval['account_id'] = $request->payment_method;
            $transactionAval['type'] = 5;
            $transactionAval['company_id'] = auth()->user()->company_id;
            $transactionAval['debit'] = $request->amount;
            $transactionAval['remark'] = $request->description . ' Paid By ' . $request->paid_by;
            $transactionAval['customer_id'] = $request->customer_id;
            $transactionAval['created_by'] = Auth::id();
            AccountTransaction::create($transactionAval);

            $transactionsf['invoice'] = $invoice;
            $transactionsf['table_id'] = $pay->id;
            $transactionsf['account_id'] = 5; //account receivable id;
            $transactionsf['type'] = 5;
            $transactionsf['company_id'] = auth()->user()->company_id;
            $transactionsf['credit'] = $request->amount;
            $transactionsf['remark'] = $request->description . ' Paid By ' . $request->paid_by;
            $transactionsf['customer_id'] = $request->customer_id;
            $transactionsf['created_by'] = Auth::id();
            AccountTransaction::create($transactionsf);



            DB::commit();
            return back()->with('success', 'Data Updated Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('failed', 'Oops! Something was wrong. Message: ' . $e->getMessage() . ' Line: ' . $e->getLine() . 'File: ' . $e->getFile());
        }
    }
}
