<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use App\Models\AccountTransaction;
use App\Models\Customer;
use App\Models\Provider;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UpstreamReportController extends Controller
{
    //
    public function index(Request $request)
    {
        $customers = Provider::get();
        if ($request->method() == "POST") {
            // $this->validate($request, [
            //     'month' => ['required'],
            // ]);
            $upstream = new AccountTransaction();
            $upstream = $upstream::with('providerCustomer')->where('type', 6)->where('account_id', "!=" ,13);
            if ($request->customer !== 'all') {
                $upstream = $upstream->where('customer_id', $request->customer);
            }
            // if ($request->month) {
            //     $upstream = $upstream->whereMonth('date_', date('m', strtotime($request->month)));
            // }
            $upstream = $upstream->get();
        }
        return view('admin.pages.reports.upstream.index', get_defined_vars());
    }
}
