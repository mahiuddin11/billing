<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AccountTransaction;
use App\Models\Account;
use Illuminate\Support\Facades\DB;

class BalanceSheetController extends Controller
{
    public function index(Request $request)
    {
        $account = new  Account();
        $assethead = $account->getaccount(1)->pluck('id', 'account_name');
        $liabilitiesHead = $account->getaccount(11)->pluck('id', 'account_name');
        $equityHead = $account->getaccount(12)->pluck('id', 'account_name');
        if ($request->method() == 'POST') {
            $assets = AccountTransaction::selectRaw('SUM(credit) as credit,account_id,created_at')
                ->with('account')
                ->whereYear('created_at', $request->yearpicker)
                ->whereIn('account_id', $assethead)
                ->groupBy('account_id')
                ->get();
            $others = AccountTransaction::selectRaw('SUM(debit) as debit,account_id,created_at')
                ->with('account')
                ->whereYear('created_at', $request->yearpicker)
                ->whereIn('account_id', array_merge(count($liabilitiesHead) > 0 ? $liabilitiesHead : ['asdf' => 0], count($equityHead) > 0 ? $equityHead : ['asdf' => 0]))
                ->groupBy('account_id')
                ->get();
        }

        return view('report.balancesheet', get_defined_vars());
    }
}
