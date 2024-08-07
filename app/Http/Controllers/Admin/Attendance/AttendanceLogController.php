<?php

namespace App\Http\Controllers\Admin\Attendance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;

class AttendanceLogController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function index(Request $request)
    {
        $title = 'Attendance Log';

        $employees = Employee::all();
        if ($request->method() == "POST") {
            $attendances = Attendance::selectRaw('DATE(date) date,emplyee_id,sign_in,sign_out')->with('employe');

            if ($request->employee_id != 'all') {
                $attendances =  $attendances->where('emplyee_id', $request->employee_id);
            }

            if ($request->from && $request->to) {
                $attendances =  $attendances->where('date', '>=', $request->from);
                $attendances =  $attendances->where('date', '<=', $request->to);
            }
            $attendances = $attendances->get();

            $dayes = Attendance::selectRaw('DATE(date) date')->groupBy('date')->get();
        }

        return view('admin.pages.attendance.attendance-log.index', get_defined_vars());
    }
}
