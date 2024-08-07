<?php

namespace App\Http\Controllers\Admin\LeaveApplication;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Employee;
use App\Models\LeaveApplication;
use Carbon\Carbon;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LeaveApplicationApproveController extends Controller
{
    protected $routeName =  'leaveApplicationApprove';
    protected $viewName =  'admin.pages.leave_application_approve';

    protected function getModel()
    {
        return new LeaveApplication();
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
                'label' => 'Apply date',
                'data' => 'apply_date',
                'searchable' => false,
            ],
            [
                'label' => 'End Date',
                'data' => 'end_date',
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
        $page_title = "Leave Application";
        $page_heading = "Leave Application List";
        $ajax_url = route($this->routeName . '.dataProcessing');
        $create_url = route($this->routeName . '.create');
        $is_show_checkbox = false;
        $model = $this->viewName . '.leaveapplicationstore';
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
                    'class' => 'btn-info  btn-sm paymodel',
                    'fontawesome' => 'fa fa-check',
                    'text' => '',
                    'title' => 'approve',
                ],
                [
                    'method_name' => 'cancel',
                    'class' => 'btn-warning btn-sm',
                    'fontawesome' => 'fa fa-ban',
                    'text' => '',
                    'title' => 'cancel',
                    'code' => 'onclick="return confirm(`Are You Sure`)"',
                ],
                [
                    'method_name' => 'destroy',
                    'class' => 'btn-danger btn-sm',
                    'fontawesome' => 'fa fa-trash',
                    'text' => '',
                    'title' => 'delete',
                ],
            ],
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page_title = "Leave Application Create";
        $page_heading = "Leave Application Create";
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

        // dd($request->all());
        $valideted = $this->validate($request, [
            'employee_id' => ['required'],
            'apply_date' => ['required'],
            'end_date' => ['required'],
            'reason' => ['required'],
            'payment_status' => ['required'],

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


        LeaveApplication::create($valideted);

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
    public function edit(LeaveApplication $leave)
    {

        if ($leave->status == 'pending') {
            $leave->status = 'approved';
            $leave->save();
            return back()->with('success', 'Approved successfully.');
        } else {
            return back()->with('failed', 'Oops! Something was wrong. Message: ');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Account  $Account
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LeaveApplication $leave)
    {
        $valideted = $this->validate($request, [
            'employee_id' => ['required'],
            'apply_date' => ['required'],
            'end_date' => ['required'],
            'reason' => ['required'],
            'payment_status' => ['required'],
        ]);


        $leave->update($valideted);

        return back()->with('success', 'Data Successfully Updated:');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Account  $Account
     * @return \Illuminate\Http\Response
     */
    public function destroy(LeaveApplication $leave)
    {
        if ($leave->status == 'pending' || $leave->status == 'cancel') {
            $leave->delete();
            return back()->with('success', 'Data deleted successfully.');
        } else {
            return back()->with('failed', 'Oops! Something was wrong. Message: ');
        }
    }



    public function cancel(LeaveApplication $leave)
    {
        if ($leave->status == 'pending') {
            $leave->status = 'cancel';

            $leave->save();
            return back()->with('success', 'Cancel successfully.');
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
