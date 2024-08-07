<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use App\Models\AccountTransaction;
use App\Models\BandwidthCustomer;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ResellerReportController extends Controller
{
    //
    public function index(Request $request)
    {
        $customers = BandwidthCustomer::get();
        if ($request->method() == "POST") {
            // $this->validate($request, [
            //     'month' => ['required'],
            // ]);

            $reseller = new AccountTransaction();
            $reseller = $reseller::with('resellerCustomer')->where('type', 5)->where('account_id', "!=", 5);
            if ($request->customer !== 'all') {
                $reseller = $reseller->where('customer_id', $request->customer);
            }
            // if ($request->month) {
            //     $reseller = $reseller->whereMonth('date_', date('m', strtotime($request->month)));
            // }
            $reseller = $reseller->get();
            // dd($reseller);
        }
        return view('admin.pages.reports.reseller.index', get_defined_vars());
    }
}
