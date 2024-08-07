<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AccountTransaction;
use Illuminate\Auth\Events\Validated;

class AccountReportController extends Controller
{
    public function cashbook()
    {
        return view('report.accountindex');
    }

    public function createReport(Request $request)
    {
        $this->validate($request, [
            'from_date' => 'required',
            'to_date' => 'required',
        ]);

        $findreports = new AccountTransaction();
        $openingBalance = $findreports->whereDate('created_at', '<=', $request->from_date)->selectRaw('SUM(debit) as debit,SUM(credit) as credit')->first();
        $newOpeningBalance = $openingBalance->debit - $openingBalance->credit;
        if ($request->from_date) {
            $findreports = $findreports->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->to_date) {
            $findreports = $findreports->whereDate('created_at', '<=', $request->to_date);
        }
        $getaccountInv = AccountTransaction::where('account_id', 5)->whereNull('credit')->pluck('invoice')->toArray();
        $findreports = $findreports->selectRaw('SUM(debit) as debit,SUM(credit) as credit,account_id,created_at,invoice,remark,type')
            ->groupBy('invoice')
            ->get();
        return view('report.accountindex', get_defined_vars());
    }
}
