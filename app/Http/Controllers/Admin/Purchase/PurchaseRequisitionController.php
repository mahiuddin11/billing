<?php

namespace App\Http\Controllers\Admin\Purchase;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Purchase;
use App\Models\PurchaseRequisition;
use App\Models\PurchaseRequisitionDetails;
use App\Models\StockSummary;
use App\Models\Supplier;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseRequisitionController extends Controller
{
    /**
     * String property
     */

    use PurchaseCalculation;

    protected $routeName =  'purchaseRequisition';
    protected $viewName =  'admin.pages.purchaseRequisition';

    protected function getModel()
    {
        return new PurchaseRequisition();
    }

    protected function tableColumnNames()
    {
        return [
            // [
            //     'label' => 'Show in Table header',
            //     'data' => 'action',
            //     'class' => 'text-nowrap', class name
            //     'orderable' => false,
            //     'searchable' => false,
            // ],
            [
                'label' => 'ID',
                'data' => 'id',
                'searchable' => false,
            ],
            [
                'label' => 'Invoice No',
                'data' => 'invoice_no',
                'searchable' => false,
            ],

            [
                'label' => 'Sub Total',
                'data' => 'subtotal',
                'searchable' => false,
            ],

            [
                'label' => 'Discount',
                'data' => 'discount',
                'searchable' => false,
            ],

            [
                'label' => 'Total Qty',
                'data' => 'quantity',
                'searchable' => false,
            ],

            [
                'label' => 'Status',
                'data' => 'status',
                'searchable' => false,
            ],

            [
                'label' => 'Create By',
                'data' => 'name',
                'searchable' => false,
                'relation' => "usersdet",
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
        $page_title = "Purchase Requisition";
        $page_heading = "Purchase Requisition List";
        $ajax_url = route($this->routeName . '.dataProcessing');
        $create_url = route($this->routeName . '.create');
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
    public function dataProcessing(Request $request)
    {
        return $this->getDataResponse(
            //Model Instance
            $this->getModel()->where('status', "Pending"),
            //Table Columns Name
            $this->tableColumnNames(),
            //Route name
            $this->routeName,
            true,
            [
                auth()->user()->is_admin != 1 ? null : [
                    'method_name' => 'invoice',
                    'class' => 'btn-success',
                    'fontawesome' => 'fa fa-check',
                    'text' => '',
                    'title' => 'invoice',
                ],

                [
                    'method_name' => 'destroy',
                    'class' => 'btn-danger',
                    'fontawesome' => 'fa fa-trash',
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
        $page_title = "Purchase Requisition Create";
        $page_heading = "Purchase Requisition Create";
        $back_url = route($this->routeName . '.index');
        $store_url = route($this->routeName . '.store');
        $supplier = Supplier::get();
        $category_info  = ProductCategory::withCount('products')->get();
        $account = Account::getaccount()->get();
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
            'date' => ['required'],
            // 'invoice_no' => ['required'],
            'account_id' => ['nullable'],
            'discount' => ['nullable'],
            'narration' => ['nullable'],
            'paid_amount' => ['nullable'],
        ]);

        try {
            DB::beginTransaction();
            $grandTotal = array_sum($request->total) - $request->discount;

            $valideted['subtotal'] = array_sum($request->total);
            $valideted['quantity'] = array_sum($request->qty);
            $valideted['grand_total'] = abs($grandTotal);
            $valideted['created_by'] = auth()->id();
            $purchase =  PurchaseRequisition::create($valideted);

            for ($i = 0; $i < count($request->proName); $i++) {
                $value[] = [
                    'purchase_requisition_id' => $purchase->id,
                    'product_category_id' => $request->catName[$i],
                    'product_id' => $request->proName[$i],
                    'quantity' => $request->qty[$i],
                    'unit_price' => $request->unitprice[$i],
                    'total_price' => $request->total[$i],
                ];
            }
            PurchaseRequisitionDetails::insert($value);

            DB::commit();
            return back()->with('success', 'Data Store Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('failed', 'Something was wrong' . $e->getMessage() . 'Line' . $e->getLine() . 'File' . $e->getFile());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Account  $Account
     * @return \Illuminate\Http\Response
     */
    public function edit(PurchaseRequisition $purchaseRequisition)
    {
        $page_title = "Purchase Requisition Edit";
        $page_heading = "Purchase Requisition Edit";
        $back_url = route($this->routeName . '.index');
        $update_url = route($this->routeName . '.update', $purchaseRequisition->id);
        $supplier = Supplier::get();
        $category_info  = ProductCategory::get();
        $accounts = Account::getaccount()->get();
        $editinfo = $purchaseRequisition;
        return view($this->viewName . '.edit', get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Account  $Account
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PurchaseRequisition $purchaseRequisition)
    {
        $valideted = $this->validate($request, [
            'invoice_no' => ['required'],
            'date' => ['required'],
            'supplier_id' => ['required'],
            'payment_type' => ['required'],
            'account_id' => ['nullable'],
            'discount' => ['nullable'],
            'narration' => ['nullable'],
            'paid_amount' => ['nullable'],
        ]);

        // dd($request->all());

        try {
            DB::beginTransaction();

            $grandTotal = array_sum($request->total) - $request->discount;
            $dueAmount = $this->dueAmount($grandTotal, $request->paid_amount);

            $valideted['subtotal'] = array_sum($request->total);
            $valideted['quantity'] = array_sum($request->qty);
            $valideted['due_amount'] = $dueAmount;
            $valideted['paid_amount'] = $request->paid_amount;
            $valideted['grand_total'] = abs($grandTotal);
            $valideted['created_by'] = auth()->id();
            $purchase =  Purchase::create($valideted);

            $this->productDetailsWithStock($purchase, $request);

            if ($request->account_id) {
                $this->accountBalanceReduct(Account::find($request->account_id), $request->paid_amount);
            }

            $this->supplierUnpaid(Supplier::find($request->supplier_id), $dueAmount);

            $transaction['account_id'] = $request->account_id;
            $transaction['supplier_id'] = $request->supplier_id;
            $transaction['purchase_id'] = $purchase->id;
            $transaction['type'] = 1;
            $transaction['date'] = $request->date;
            $transaction['credit'] = $request->paid_amount;
            $transaction['amount'] = $request->paid_amount;
            $transaction['due'] = $dueAmount;
            $transaction['note'] = $request->narration;
            $transaction['created_by'] = auth()->id();
            $transaction['company_id'] = auth()->user()->company_id;
            Transaction::create($transaction);

            $purchaseRequisition->update(['status' => "Approved"]);

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
    public function destroy(PurchaseRequisition $purchaseRequisition)
    {
        $purchaseRequisition->delete();
    }

    public function invoice(PurchaseRequisition $purchaseRequisition)
    {
        $page_title = "Purchase Requisition";
        $page_heading = "Purchase Requisition Approve";
        $back_url = route($this->routeName . '.index');
        $approve_url = route($this->routeName . '.update', $purchaseRequisition->id);
        $supplier = Supplier::get();
        $category_info  = ProductCategory::get();
        $accounts = Account::getaccount()->get();
        $editinfo = $purchaseRequisition;
        return view($this->viewName . '.invoice', get_defined_vars());
    }

    public function getProductList(Request $request)
    {
        $cat_id = $request->cat_id;
        $productList = Product::withCount('category')->where('product_category_id', $cat_id)->get();
        $add = '';
        if (!empty($productList)) :
            $add .= "<option value='all'>All Product</option>";
            foreach ($productList as $key => $value) :
                $add .= "<option proName='" . $value->name . "'   value='" . $value->id . "'>$value->productCode - $value->name</option>";
            endforeach;
            echo $add;
            die;
        else :
            echo "<option value='' selected disabled>No Product Available</option>";
            die;
        endif;
    }

    public function unitPrice(Request $request)
    {
        $proid = $request->productId;
        $productPrice = Product::get()->where('id', $proid)->first();
        echo $productPrice->purchases_price;
    }

    public function getAccounts(Request $request)
    {
        $accounts = Account::getaccount()->get();
        $html = '';
        if ($accounts->isNotEmpty()) {
            $html .= "<option value='' selected disabled>--Select Account--</option>";
            foreach ($accounts as $key => $account) {
                $html .= "<option value='" . $account->id . "'>$account->accountCode - $account->account_name</option>";
            }
        } else {
            $html .= "<option value='' selected disabled>--No Account Available--</option>";
        }
        return $html;
    }

    public function allstock()
    {
        $stocks = StockSummary::get();
        return view($this->viewName . '.stock', get_defined_vars());
    }
}
