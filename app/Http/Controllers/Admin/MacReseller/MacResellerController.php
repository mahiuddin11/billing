<?php

namespace App\Http\Controllers\Admin\MacReseller;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\MacReseller;
use App\Models\MacTariffConfig;
use App\Models\RollPermission;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Helpers\DataProcessingFile\MacResellerDataProcessing;

class MacResellerController extends Controller
{
    use MacResellerDataProcessing;

    /**
     * String property
     */

    protected $routeName =  'macreseller';
    protected $viewName =  'admin.pages.macreseller';

    protected function getModel()
    {
        return new MacReseller();
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
                'label' => 'Reseller Code',
                'data' => 'reseller_user_name',
                'searchable' => false,
            ],
            [
                'label' => 'Name',
                'data' => 'person_name',
                'searchable' => false,
            ],
            [
                'label' => 'Available Balance',
                'data' => 'recharge_balance',
                'searchable' => false,
            ],
            [
                'label' => 'Bill',
                'data' => 'curent_bill',
                'searchable' => false,
            ],
            [
                'label' => 'Active Customer',
                'data' => 'active_customer',
                'searchable' => false,
            ],
            [
                'label' => 'Inactive Customer',
                'data' => 'inactive_customer',
                'searchable' => false,
            ],
            [
                'label' => 'Email',
                'data' => 'email',
                'searchable' => false,
            ],
            [
                'label' => 'Phone',
                'data' => 'phone',
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
        $page_title = "Mac Client";
        $page_heading = "Mac Client List";
        $ajax_url = route($this->routeName . '.dataProcessing');
        $create_url = route($this->routeName . '.create');
        $is_show_checkbox = false;
        $columns = $this->reformatForRelationalColumnName(
            $this->tableColumnNames()
        );
        return view('admin.pages.index', get_defined_vars());
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
            $this->getModel(),
            //Table Columns Name
            $this->tableColumnNames(),
            //Route name
            $this->routeName,
            true,
            [
                [
                    'method_name' => 'edit',
                    'class' => 'btn-info',
                    'fontawesome' => 'fa fa-edit',
                    'text' => '',
                    'title' => 'edit',
                ],
                [
                    'method_name' => 'destroy',
                    'class' => 'btn-danger',
                    'fontawesome' => 'fa fa-trash',
                    'text' => '',
                    'code' => 'onclick="return confirm(`Are You sure`)"',
                    'title' => 'Destroy',
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
        $page_title = "Mac Client Add";
        $page_heading = "Mac Client Add";
        $back_url = route($this->routeName . '.index');
        $store_url = route($this->routeName . '.store');
        $Mactariffs = MacTariffConfig::get();
        $userRolls = RollPermission::get();
        $zones = Zone::get();
        $reselercode =  MacReseller::latest()->pluck('id')->first();
        $reselercode = str_pad($reselercode + 1, 3, "0", STR_PAD_LEFT);
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
            'person_name' => ['required'],
            'email' => ['required'],
            'mobile' => ['nullable'],
            'phone' => ['nullable'],
            'national_id' => ['nullable'],
            'zone_id' => ['nullable'],
            'reseller_code' => ['required'],
            'reseller_prefix' => ['required'],
            'set_prefix_mikrotikuser' => ['nullable'],
            'reseller_type' => ['nullable'],
            // 'rechargeable_amount' => ['nullable'],
            'address' => ['nullable'],
            'reseller_logo' => ['nullable'],
            'business_name' => ['nullable'],
            'tariff_id' => ['required'],
            'expire_date' => ['nullable'],
            'user_name' => ['nullable'],
            'password' => ['required', 'confirmed'],
            'reseller_menu' => ['nullable']
        ]);
        try {
            DB::beginTransaction();
            $company = Company::create([
                'company_name' => $request->person_name,
                "phone" => $request->phone,
                "email" => $request->email,
                'address' => $request->address,
            ]);

            $user =   User::create([
                "name" => $request->person_name,
                "email" => $request->email,
                "password" => Hash::make($request->password),
                "company_id" => $company->id,
                "phone" => $request->phone,
                "is_admin" => 3,
                "username" => $request->user_name,
                "roll_id" => $request->user_roll,
            ]);

            $valideted['reseller_user_name'] = $request->reseller_prefix . $request->reseller_code;
            $valideted['viewpassword'] = $request->password;
            $valideted['disabled_client'] = $request->disabled_client ?? 'false';
            $valideted['password'] = Hash::make($request->password);
            $valideted['user_id'] = $user->id;
            $valideted['created_by'] = auth()->id();
            $macreseller =  MacReseller::create($valideted);
            $company->update(['type' => $macreseller->id]);
            DB::commit();
            return back()->with('success', 'Data Store Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('failed', 'Oops! Something was wrong. Message: ' . $e->getMessage() . ' Line: ' . $e->getLine() . 'File: ' . $e->getFile());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Account  $Account
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, MacReseller $MacReseller)
    {
        $modal_title = 'Mac Client Details';
        $modal_data = $MacReseller;
        $html = view($this->viewName . '.show', get_defined_vars())->render();
        return $html;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Account  $Account
     * @return \Illuminate\Http\Response
     */

    public function edit(MacReseller $MacReseller)
    {
        $page_title = "Mac Client Edit";
        $page_heading = "Mac Client Edit";
        $back_url = route($this->routeName . '.index');
        $update_url = route($this->routeName . '.update', $MacReseller->id);
        $userRolls = RollPermission::get();
        $zones = Zone::get();
        $editinfo = $MacReseller;
        $Mactariffs = MacTariffConfig::get();
        return view($this->viewName . '.edit', get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Account  $Account
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MacReseller $MacReseller)
    {
        $valideted = $this->validate($request, [
            'person_name' => ['required'],
            'email' => ['required'],
            'mobile' => ['nullable'],
            'phone' => ['nullable'],
            'national_id' => ['nullable'],
            'zone_id' => ['nullable'],
            'reseller_code' => ['required'],
            'reseller_prefix' => ['required'],
            'set_prefix_mikrotikuser' => ['nullable'],
            'reseller_type' => ['nullable'],
            // 'rechargeable_amount' => ['nullable'],
            'address' => ['nullable'],
            'reseller_logo' => ['nullable'],
            'business_name' => ['nullable'],
            'expire_date' => ['nullable'],
            'tariff_id' => ['required'],
            'user_name' => ['nullable'],
            'password' => ['nullable', 'confirmed'],
            'reseller_menu' => ['nullable']
        ]);

        try {
            DB::beginTransaction();
            $valideted['reseller_user_name'] = $request->reseller_prefix . $request->reseller_code;
            if ($request->filled('password')) {
                $valideted['password'] = Hash::make($request->password);
                $valideted['viewpassword'] = $request->password;
            } else {
                $valideted = collect($valideted)->except('password')->toArray();
            }
            User::where('id', $MacReseller->user_id)->update([
                "name" => $request->person_name,
                "email" => $request->email,
                "password" => $request->filled('password') ? Hash::make($request->password) : $MacReseller->password,
                "phone" => $request->phone,
                "is_admin" => 3,
                "username" => $request->user_name,
                "roll_id" => $request->user_roll,
            ]);

            $valideted['updated_by'] = auth()->id();
            $MacReseller->update($valideted);
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
    public function destroy(MacReseller $MacReseller)
    {
        $MacReseller->delete();
        $MacReseller->getUser->delete();
        $MacReseller->getUser->company->delete();
        return back()->with('success', 'Data deleted successfully.');
    }
    public function sendMessage(MacReseller $MacReseller)
    {
        $editinfo = $MacReseller;
        return view('admin.pages.sms.send-message', get_defined_vars());
    }
}
