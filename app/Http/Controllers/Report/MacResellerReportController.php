<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Admin\MacReseller\MacResellerController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AccountTransaction;
use App\Models\Account;
use App\Models\MacReseller;
use Illuminate\Support\Facades\DB;

class MacResellerReportController extends Controller
{
    public function index(Request $request)
    {
        $macresellers = MacReseller::get();

        if ($request->method() == "POST") {
            $findreports = new AccountTransaction();

            $findreports = $findreports->where('type', 7);

            if ($request->reseller_id) {
                $findreports = $findreports->where('customer_id', $request->reseller_id);
            }

            if ($request->month) {
                $findreports = $findreports->whereMonth('created_at', date('m', strtotime($request->month)));
            }

            $findreports = $findreports->get();
        }

        return view('report.macresellerreport', get_defined_vars());
    }

    public function paymentDelete(AccountTransaction $accountTransaction)
    {
        // dd($accountTransaction);
        $accountTransaction->where('invoice', $accountTransaction->invoice)->where('type', 7)->delete();
        return back()->with('success', 'Data deleted successfully.');
    }
}
