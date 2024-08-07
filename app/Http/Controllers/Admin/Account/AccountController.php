<?php

namespace App\Http\Controllers\Admin\Account;

use App\Models\Account;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{
    /**
     * String property
     */
    protected $routeName =  'accounts';
    protected $viewName =  'admin.pages.accounts';

    protected function getModel()
    {
        return new Account();
    }

    protected function tableColumnNames()
    {
        return [
            [
                'label' => 'ID',
                'data' => 'id',
                'searchable' => false,
            ],
            [
                'label' => 'Name',
                'data' => 'account_name',
                'searchable' => true,
            ],
            [
                'label' => 'Head Code',
                'data' => 'head_code',
                'searchable' => true,
            ],
            [
                'label' => 'Details',
                'data' => 'account_details',
                'searchable' => false,
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
        $page_title = "Account";
        $page_heading = "Account List";
        $view_url = route($this->routeName . '.view');
        $ajax_url = route($this->routeName . '.dataProcessing');
        $create_url = route($this->routeName . '.create');
        $is_show_checkbox = false;
        $rootAccount = Account::getaccount()->where('parent_id', 0)->get();
        $columns = $this->reformatForRelationalColumnName(
            $this->tableColumnNames()
        );
        return view($this->viewName . '.index', get_defined_vars());
    }

    public function view()
    {
        $accounts = Account::with('subAccount')->where('parent_id', 0)->get();

        return view($this->viewName . '.viewaccount', get_defined_vars());
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
            $this->getModel()->where('parent_id', 0),
            //Table Columns Name
            $this->tableColumnNames(),
            //Route name
            $this->routeName,
            true,
            [
                [
                    'method_name' => 'edit',
                    'class' => 'btn-success',
                    'fontawesome' => 'fa fa-edit',
                    'text' => '',
                    'title' => 'Edit',
                ],
                [
                    'method_name' => 'subaccount',
                    'class' => 'btn-warning',
                    'fontawesome' => 'fa fa-plus',
                    'text' => '',
                    'title' => 'Edit',
                ],
            ]
        );
    }

    public function checkaccountlevel($account, $level = 1)
    {
        if ($account->parentAccount) {
            $level++;
            return $this->checkaccountlevel($account->parentAccount, $level);
        }
        return (($level > 2) ? true : false);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page_title = "Account Create";
        $page_heading = "Account Create";
        $back_url = route($this->routeName . '.index');
        $store_url = route($this->routeName . '.store');
        $accounts = $this->getModel()->getaccount()->where('parent_id', 0)->whereNotIn('id', [2, 3, 4])->get();
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
            'account_name' => ['required'],
            'head_code' => ['required'],
            'account_details' => ['nullable'],
            'parent_id' => ['required'],
        ]);

        try {

            if ($request->parent_id == 3) {
                return back()->with('failed', 'You cannot credit an account under cash');
            }

            DB::beginTransaction();
            $valideted['company_id'] = auth()->user()->company_id;
            $valideted['parent_id'] = $request->parent_id;
            $valideted['created_by'] = auth()->id();
            Account::create($valideted);
            DB::commit();
            return back()->with('success', 'Data Store Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('failed', 'Something was wrong');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Account  $Account
     * @return \Illuminate\Http\Response
     */
    public function edit(Account $Account)
    {
        $page_title = "Account Edit";
        $page_heading = "Account Edit";
        $back_url = route($this->routeName . '.index');
        $update_url = route($this->routeName . '.update', $Account->id);
        $accounts = $this->getModel()->getaccount()->whereNotIn('id', [2, 3, 4])->get();
        $editinfo = $Account;
        return view($this->viewName . '.edit', get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Account  $Account
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Account $Account)
    {
        $valideted = $this->validate($request, [
            'account_name' => ['required'],
            'head_code' => ['required'],
            'account_details' => ['nullable'],
        ]);

        try {
            DB::beginTransaction();
            $valideted['updated_by'] = auth()->id();
            $Account = $Account->update($valideted);
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
    public function statusUpdate(Account $account)
    {
        $status = $account->status == 'Active' ? 'Inactive' : 'Active';
        $account->update(['status' => $status]);
        return true;
    }

    public function destroy(Account $account)
    {
        if ($account->hasAccount->isNotEmpty()) {
            return back()->with('failed', 'You are Already Transaction in this Account.');
        } elseif (in_array($account->id, [2, 3, 4, 5, 7, 8, 10, 14, 13])) {
            return back()->with('failed', "You can't delete default account.");
        }

        $account->delete();
        return back()->with('success', 'Data deleted successfully.');
    }

    public function getBalance(Request $request)
    {
        $account = Account::find($request->account_id);
        echo $account->amount;
    }

    public function account1st(Request $req)
    {
        $accounts = Account::whereNotIn('id', [2, 3, 4])->where('parent_id', $req->id)->get();
        $html = '<option  >Select</option>';
        foreach ($accounts as $account) {
            $html .= '<option value="' . $account->id . '">' . $account->account_name . '</option>';
        }
        return $html;
    }
    public function account2st(Request $req)
    {
        $accounts = Account::whereNotIn('id', [2, 3, 4])->where('parent_id', $req->id)->get();
        $html = '<option >Select</option>';
        foreach ($accounts as $account) {
            $html .= '<option value="' . $account->id . '">' . $account->account_name . '</option>';
        }
        return $html;
    }
    public function account3rd(Request $req)
    {
        $accounts = Account::whereNotIn('id', [2, 3, 4])->where('parent_id', $req->id)->get();
        $html = '<option >Select</option>';
        foreach ($accounts as $account) {
            $html .= '<option value="' . $account->id . '">' . $account->account_name . '</option>';
        }
        return $html;
    }
}
