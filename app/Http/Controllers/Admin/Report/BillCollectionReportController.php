<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AccountTransaction;
use App\Models\Billing;
use App\Models\Customer;
use Illuminate\Http\Request;

class BillCollectionReportController extends Controller
{
    public function index(Request $request)
    {
        $customers = Customer::where('company_id', auth()->user()->company_id)->get();
        $paymentMethods = Account::whereIn('id', [2, 3, 4])->get();
        if ($request->method() == "POST") {
            $monthlybill = new Billing();
            $monthlybill = $monthlybill->rightJoin('customers', 'customers.id', '=', 'billings.customer_id')
                ->select('customers.name as name', 'customers.address as address', 'customers.phone as phone', 'customers.username as username', 'billings.*')
                ->where('billings.company_id', auth()->user()->company_id);

            if ($request->customer !== 'all') {
                $monthlybill = $monthlybill->where('billings.customer_id', $request->customer);
            }

            if ($request->method !== 'all') {
                $monthlybill = $monthlybill->where('payment_method_id', $request->method);
            }

            if ($request->type !== 'all') {
                $monthlybill = $monthlybill->where('protocol_type_id', $request->type);
            }

            if ($request->status !== 'all') {
                $monthlybill = $monthlybill->where('billings.status', $request->status);
            }

            if ($request->month) {
                $monthlybill = $monthlybill->whereMonth('billings.date_', date('m', strtotime($request->month)))->whereYear('billings.date_', date('Y', strtotime($request->month)));
            }
            $monthlybill = $monthlybill->get();
        }

        return view('admin.pages.reports.billcollection.index', get_defined_vars());
    }
}
