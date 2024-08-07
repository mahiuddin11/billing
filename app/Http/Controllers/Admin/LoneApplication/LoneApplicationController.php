<?php

namespace App\Http\Controllers\Admin\LoneApplication;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Employee;
use App\Models\LoneApplication;
use Carbon\Carbon;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LoneApplicationController extends Controller
{
    protected $routeName =  'loneApplication';
    protected $viewName =  'admin.pages.lone_application';

    protected function getModel()
    {
        return new LoneApplication();
    }

    protected function tableColumnNames()
    {
        return [
            // [
            //     'label' => 'Show in Table header',
            //     'data' => 'action',
            //     'class' => 'text-nowrap', class name
            //     'orderable' => false,
            //     'searchable' => false,
            // ],
            [
                'label' => 'ID',
                'data' => 'id',
                'searchable' => false,
            ],
            [
                'label' => 'Name',
                'data' => 'employee_id',
                'searchable' => false,
            ],
            [
                'label' => 'Amount',
                'data' => 'amount',
                'searchable' => false,
            ],
            [
                'label' => 'Lone Adjustment',
                'data' => 'lone_adjustment',
                'searchable' => false,
            ],
            [
                'label' => 'Reason',
                'data' => 'reason',
                'searchable' => false,
            ],
            [
                'label' => 'status',
                'data' => 'status',
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
        $page_title = "Lone Application";
        $page_heading = "Lone Application List";
        $ajax_url = route($this->routeName . '.dataProcessing');
        $create_url = route($this->routeName . '.create');
        $is_show_checkbox = false;
        $model = $this->viewName . '.loneapplicationstore';
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
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page_title = "Lone Application Create";
        $page_heading = "Lone Application Create";
        $back_url = route($this->routeName . '.index');
        $store_url = route($this->routeName . '.store');
        $employees = Employee::all();
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
            'employee_id' => ['required'],
            'amount' => ['required'],
            'lone_adjustment' => ['required'],
            'reason' => ['required']
        ]);

        $image = $request->file('image');
        if (isset($image)) {
            $currentDate = Carbon::now()->toDateString();
            $imageName  = $currentDate . '-' . uniqid() . '.' . $image->getClientOriginalExtension();

            if (!Storage::disk('public')->exists('photo')) {
                Storage::disk('public')->makeDirectory('photo');
            }

            $image->storeAs('photo', $imageName, 'public');
        } else {
            $imageName = null;
        }
        $valideted['image'] = $imageName;
        $valideted['company_id'] = auth()->user()->company_id;

        LoneApplication::create($valideted);

        return back()->with('success', 'Data Successfully Created:');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Account  $Account
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Employee $employee)
    {

        $modal_title = 'Employee Details';
        $modal_data = $employee;
        $html = view($this->viewName . '.show', get_defined_vars())->render();
        return $html;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Account  $Account
     * @return \Illuminate\Http\Response
     */
    public function edit(LoneApplication $lone)
    {
        $page_title = "Lone Application Edit";
        $page_heading = "Lone Application Edit";
        $back_url = route($this->routeName . '.index');
        $update_url = route($this->routeName . '.update', $lone->id);
        $employees = Employee::all();
        $editinfo = $lone;
        return view($this->viewName . '.edit', get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Account  $Account
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LoneApplication $lone)
    {
        $valideted = $this->validate($request, [
            'employee_id' => ['required'],
            'amount' => ['required'],
            'lone_adjustment' => ['required'],
            'reason' => ['required']
        ]);


        $lone->update($valideted);

        return back()->with('success', 'Data Successfully Updated:');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Account  $Account
     * @return \Illuminate\Http\Response
     */
    public function destroy(LoneApplication $lone)
    {
        if ($lone->status == 'pending' || $lone->status == 'cancel') {
            $lone->delete();
            return back()->with('success', 'Data deleted successfully.');
        } else {
            return back()->with('failed', 'Oops! Something was wrong. Message: ');
        }
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
}
