<?php

namespace App\Http\Controllers\Admin\Attendance;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Company;
use App\Models\Employee;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {

        return view('attendance.index');
    }

    public function create()
    {
        $employees  = Employee::all();
        return view('admin.pages.attendance.create', compact('employees'));
    }

    public function signin(Request $request)
    {
        $request->validate([
            'emplyee_id' => 'required',
            'date' => 'required',
            'sign_in' => 'required'
        ]);
        if (Attendance::where('emplyee_id', $request->emplyee_id)->whereDate('date', $request->date)->first()) {
            session()->flash('failed', 'This employee already check in');
            return redirect()->route('hrm.attendance.create');
        }
        $company = Company::first();
        $Attendance = new Attendance();
        $Attendance->emplyee_id = $request->emplyee_id;
        $Attendance->company_id = Auth()->id() ?? $company->id;
        $Attendance->date = $request->date;
        $Attendance->sign_in = $request->sign_in;
        // dd($Attendance);
        $Attendance->save();
        return redirect()->back()->with('success', 'Successfully Checked In');
    }

    public function signout(Request $request)
    {
        $request->validate([
            'emplyee_id' => 'required',
            'date' => 'required',
            'sign_out' => 'required'
        ]);


        $company = Company::first();
        $Attendance['emplyee_id'] = $request->emplyee_id;
        $Attendance['company_id'] = auth()->user()->company_id;
        $Attendance['sign_out'] = $request->sign_out;
        // dd($Attendance);
        $Attendance = Attendance::where('emplyee_id', $request->emplyee_id)->whereDate('date', $request->date)->update($Attendance);
        return redirect()->back()->with('success', 'Successfully Checked Out');
    }

    public function statusUpdate($id, $status)
    {
        $customer = $this->model::find($id);
        $customer->status = $status;
        $customer->save();
        return $customer;
    }

    public function destroy($id)
    {
        $customer = $this->model::find($id);
        $customer->delete();
        return true;
    }
}
