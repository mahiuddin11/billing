<?php

namespace App\Http\Controllers\Admin\SalarySheet;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;

class SalarySheetControlller extends Controller
{
    public function index()
    {
        $employees = Employee::all();
        return view('admin.pages.salary_sheet.salarysheet-log.index', get_defined_vars());
    }
}
