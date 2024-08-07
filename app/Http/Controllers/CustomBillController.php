<?php

namespace App\Http\Controllers;

use App\Models\BalanceTransfer;
use App\Models\CustomBill;
use App\Models\CustomBillDetail;
use App\Models\Customer;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CustomBillController extends Controller
{
    protected $routeName =  'custombill';
    protected $viewName =  'admin.pages.custombill';


    protected function getModel()
    {
        return new CustomBill();
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
                'label' => 'Customer',
                'data' => 'username',
                'searchable' => false,
                'relation' => 'getCustomer',
            ],
            [
                'label' => 'Date',
                'data' => 'date',
                'searchable' => false,
            ],
            [
                'label' => 'Total Amount',
                'data' => 'total',
                'searchable' => false,
            ],
            [
                'label' => 'Created By',
                'data' => 'name',
                'searchable' => false,
                'relation' => 'user',

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
        $page_title = "Custom Bill";
        $page_heading = "Custom Bill List";
        $ajax_url = route($this->routeName . '.dataProcessing');
        $create_url = route($this->routeName . '.create');
        $is_show_checkbox = false;
        $columns = $this->reformatForRelationalColumnName(
            $this->tableColumnNames()
        );
        // dd(get_defined_vars());
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
            $this->getModel(),
            //Table Columns Name
            $this->tableColumnNames(),
            //Route name
            $this->routeName,
            true,
            [
                [
                    'method_name' => 'invoice',
                    'class' => 'btn-info  btn-sm invoice',
                    'fontawesome' => '',
                    'text' => 'Invoice',
                    'title' => 'invoice',
                ],
                [
                    'method_name' => 'edit',
                    'class' => 'btn-info  btn-sm edit',
                    'fontawesome' => '',
                    'text' => 'edit',
                    'title' => 'edit',
                ],
                [
                    'method_name' => 'destroy',
                    'class' => 'btn-danger  btn-sm delete',
                    'fontawesome' => '',
                    'code' => 'onclick="return confirm(`Are You sure`)"',
                    'text' => 'delete',
                    'title' => 'delete',
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
        $page_title = "Custom Bill Create";
        $page_heading = "Custom Bill Create";
        $back_url = route($this->routeName . '.index');
        $store_url = route($this->routeName . '.store');
        $customers = Customer::get();

        return view($this->viewName . '.create', get_defined_vars());
    }

    public function store(Request $request)
    {
        $valideted = $this->validate($request, [
            'customer_id' => ['required'],
        ]);
        try {
            DB::beginTransaction();
            $valideted['customer_id'] = $request->customer_id;
            $valideted['date'] = $request->date;
            $valideted['total'] = array_sum($request->amount);
            $valideted['created_by'] = auth()->id();
            $transfer = $this->getModel()->create($valideted);

            if ($request->service_name) {
                for ($i = 0; $i < count($request->service_name); $i++) {
                    $details['custom_bill_id'] = $transfer->id;
                    $details['service_name'] = $request->service_name[$i];
                    $details['amount'] = $request->amount[$i];
                    CustomBillDetail::create($details);
                }
            }
            DB::commit();
            return back()->with('success', 'Data Store Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('failed', $this->getError($e));
        }
    }

    public function edit(CustomBill $customBill)
    {
        $page_title = "Custom Bill Edit";
        $page_heading = "Custom Bill Edit";
        $customers = Customer::get();
        $users = User::get();
        $back_url = route($this->routeName . '.index');
        $update_url = route($this->routeName . '.update', $customBill->id);
        $editinfo = $customBill;
        return view($this->viewName . '.edit', get_defined_vars());
    }

    public function update(Request $request, CustomBill $customBill)
    {
        $valideted = $this->validate($request, [
            'customer_id' => ['required'],
            'date' => ['required'],
            'total' => ['required'],
        ]);

        try {
            DB::beginTransaction();
            $valideted['updated_by'] = auth()->user()->id;
            $customBill = $customBill->update($valideted);
            DB::commit();
            return back()->with('success', 'Data Updated Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('failed', 'Oops! Something was wrong. Message: ' . $e->getMessage() . ' Line: ' . $e->getLine() . 'File: ' . $e->getFile());
        }
    }

    public function invoice(CustomBill $customBill)
    {
        $companyInif = auth()->user()->company;
        return view($this->viewName . '.invoice', get_defined_vars());
    }

    public function destroy(CustomBill $customBill)
    {
        $customBill->delete();
        return back()->with('success', 'Data deleted successfully.');
    }
}
