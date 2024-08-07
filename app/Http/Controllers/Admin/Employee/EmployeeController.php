<?php

namespace App\Http\Controllers\Admin\Employee;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\RollPermission;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Rats\Zkteco\Lib\ZKTeco;

class EmployeeController extends Controller
{
    /**
     * String property
     */
    protected $routeName =  'employees';
    protected $viewName =  'admin.pages.employee';

    protected function getModel()
    {
        return new Employee();
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
                'data' => 'name',
                'searchable' => false,
            ],
            [
                'label' => 'Email',
                'data' => 'email',
                'searchable' => false,
            ],
            [
                'label' => 'Phone',
                'data' => 'office_phone',
                'searchable' => false,
            ],
            [
                'label' => 'Nid',
                'data' => 'nid',
                'searchable' => false,
            ],
            [
                'label' => 'Join Date',
                'data' => 'join_date',
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
        $page_title = "Employee";
        $page_heading = "Employee List";
        $ajax_url = route($this->routeName . '.dataProcessing');
        $create_url = route($this->routeName . '.create');
        $is_show_checkbox = false;
        $model = $this->viewName . '.salarystore';
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
        $page_title = "Employee Create";
        $page_heading = "Employee Create";
        $back_url = route($this->routeName . '.index');
        $store_url = route($this->routeName . '.store');
        $designations = Designation::get();
        $userrolls = RollPermission::get();
        $departments = Department::get();
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
            'name' => ['required'],
            'id_card' => ['nullable'],
            'email' => ['required', 'unique:employees,email'],
            'user_id' => ['nullable', 'numeric'],
            'dob' => ['nullable'],
            'gender' => ['nullable'],
            'username' => [Rule::requiredIf(!empty($request->username)), 'unique:users,username'],
            'roll_id' => [Rule::requiredIf(!empty($request->roll_id))],
            'personal_phone' => ['nullable'],
            'office_phone' => ['nullable'],
            'marital_status' => ['nullable'],
            'nid' => ['nullable'],
            'last_in_time' => ['nullable'],
            'reference' => ['nullable'],
            'experience' => ['nullable'],
            'present_address' => ['nullable'],
            'permanent_address' => ['nullable'],
            'department_id' => ['nullable'],
            'designation_id' => ['nullable'],
            'achieved_degree' => ['nullable'],
            'institution' => ['nullable'],
            'passing_year' => ['nullable'],
            'salary' => ['nullable'],
            'join_date' => ['nullable'],
            'image' => ['nullable'],
            'emp_signature' => ['nullable'],
            'updated_by' => ['nullable'],
            'created_by' => ['nullable'],
            'deleted_by' => ['nullable'],
            'over_time_is' => ['required'],
            'blood_group' => ['nullable'],
            'is_login' => ['nullable'],
            'password' => ['nullable', 'confirmed', 'min:6',],
        ]);

        try {
            DB::beginTransaction();
            if ($request->is_login == "true") {
                $user['name'] = $request->name;
                $user['username'] = $request->username;
                $user['email'] = $request->email;
                $user['office_phone'] = $request->office_phone;
                $user['roll_id'] = $request->roll_id;
                $user['company_id'] = auth()->user()->company_id;
                $user['is_admin'] = $request->access_type;
                $user['password'] = Hash::make($request->password);
                $userDs = User::create($user);
                $valideted['user_id'] = $userDs->id;
            }

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

            // Emplyee Signature

            $image = $request->file('emp_signature');
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
            $valideted['emp_signature'] = $imageName;


              $employee = Employee::create($valideted);
            // $zkteco = $this->zktConnect();
            // if ($zkteco) {
            //    if ($employee) {
            //        $uid = $employee->id;
            //        $userId = $employee->id;
            //        $name = $employee->name;
            //        $password = "";
            //        $role = 0;
            //        $this->useZkt()->setUser($uid, $userId, $name, $password, $role);
            //    }
            // }

            DB::commit();
            return back()->with('success', 'Data Store Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('failed', 'Something was wrong' . $e->getMessage() . 'File' . $e->getFile() . "Line" . $e->getLine());
        }
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
    public function edit(Employee $employee)
    {
        $page_title = "Employee Edit";
        $page_heading = "Employee Edit";
        $back_url = route($this->routeName . '.index');
        $update_url = route($this->routeName . '.update', $employee->id);
        $designations = Designation::get();
        $userrolls = RollPermission::get();
        $departments = Department::get();
        $editinfo = $employee;
        return view($this->viewName . '.edit', get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Account  $Account
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Employee $employee)
    {
        $valideted = $this->validate($request, [
            'name' => ['required'],
            'id_card' => ['nullable'],
            'email' => ['required', 'unique:employees,email,' . $employee->id],
            'user_id' => ['nullable', 'numeric'],
            'dob' => ['nullable'],
            'gender' => ['nullable'],
            'username' => [Rule::requiredIf(!empty($request->username))],
            'roll_id' => [Rule::requiredIf(!empty($request->roll_id))],
            'personal_phone' => ['nullable'],
            'office_phone' => ['nullable'],
            'marital_status' => ['nullable'],
            'nid' => ['nullable'],
            'last_in_time' => ['nullable'],
            'reference' => ['nullable'],
            'experience' => ['nullable'],
            'present_address' => ['nullable'],
            'permanent_address' => ['nullable'],
            'department_id' => ['nullable'],
            'designation_id' => ['nullable'],
            'achieved_degree' => ['nullable'],
            'institution' => ['nullable'],
            'passing_year' => ['nullable'],
            'salary' => ['nullable'],
            'join_date' => ['nullable'],
            'image' => ['nullable'],
            'emp_signature' => ['nullable'],
            'updated_by' => ['nullable'],
            'created_by' => ['nullable'],
            'deleted_by' => ['nullable'],
            'blood_group' => ['nullable'],
            'is_login' => ['nullable'],
            'password' => ['nullable', 'confirmed', 'min:6',],
        ]);

        try {
            DB::beginTransaction();

            if ($employee->is_login == "true") {
                $user['name'] = $request->name;
                $user['username'] = $request->username;
                $user['is_admin'] = $request->access_type;
                $user['email'] = $request->email;
                $user['phone'] = $request->phone;
                $user['roll_id'] = $request->roll_id;
                $user['password'] = $request->password ? Hash::make($request->password) : $employee->employelist->password;
                $employee->employelist->update($user);
            }

            $image = $request->file('image');
            if (isset($image)) {
                $currentDate = Carbon::now()->toDateString();
                $imageName  = $currentDate . '-' . uniqid() . '.' . $image->getClientOriginalExtension();

                if (!Storage::disk('public')->exists('photo')) {
                    Storage::disk('public')->makeDirectory('photo');
                }

                $image->storeAs('photo', $imageName, 'public');
                $valideted['image'] = $imageName;
            }

            // Emplyee Signature

            $image = $request->file('emp_signature');
            if (isset($image)) {
                $currentDate = Carbon::now()->toDateString();
                $imageName  = $currentDate . '-' . uniqid() . '.' . $image->getClientOriginalExtension();

                if (!Storage::disk('public')->exists('photo')) {
                    Storage::disk('public')->makeDirectory('photo');
                }

                $image->storeAs('photo', $imageName, 'public');
                $valideted['emp_signature'] = $imageName;
            }


            $employee->update($valideted);
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
    public function destroy(Employee $employee)
    {
        $employee->delete();
        return back()->with('success', 'Data deleted successfully.');
    }
}
