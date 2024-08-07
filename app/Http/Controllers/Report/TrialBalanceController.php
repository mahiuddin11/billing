<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AccountTransaction;
use App\Models\Account;
use Illuminate\Support\Facades\DB;

class TrialBalanceController extends Controller
{
    public function index(Request $request)
    {
        $accounts = Account::get();

        if ($request->method() == 'POST') {
            $findreports = DB::table('accounts')
                ->selectRaw('SUM(debit) as debit,SUM(credit) as credit,account_id,account_transactions.created_at,accounts.account_name,accounts.head_code')
                ->join('account_transactions', 'account_transactions.account_id', '=', 'accounts.id')
                ->groupBy('account_transactions.account_id')
                ->get();
        }

        return view('report.trialbalance', get_defined_vars());
    }
}
