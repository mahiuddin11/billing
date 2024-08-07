<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AccountTransaction;
use App\Models\Account;
use Illuminate\Support\Facades\DB;

class IncomeStatementController extends Controller
{
    public function index(Request $request)
    {
        $account = new  Account();
        $expensehead = $account->getaccount(6)->pluck('id');
        $incomehead = $account->getaccount(9)->pluck('id');
        if ($request->method() == 'POST') {
            $incomes = AccountTransaction::selectRaw('SUM(credit) as credit,account_id,created_at')
                ->with('account')
                ->whereYear('created_at', $request->yearpicker)
                ->whereIn('account_id', $incomehead)
                ->groupBy('account_id')
                ->get();
            $expense = AccountTransaction::selectRaw('SUM(debit) as debit,account_id,created_at')
                ->with('account')
                ->whereYear('created_at', $request->yearpicker)
                ->whereIn('account_id', $expensehead)
                ->groupBy('account_id')
                ->get();
        }

        return view('report.incomestatement', get_defined_vars());
    }
}
