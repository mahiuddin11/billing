<?php

namespace App\Http\Controllers\Admin\Attendance;

use App\Http\Controllers\Controller;
use App\Jobs\ZktecoSetUser;
use App\Models\Attendance;
use App\Models\Branch;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Rats\Zkteco\Lib\ZKTeco;

class ZktecoController extends Controller
{
    public function zktectoAttendance()
    {
        $zkteco = new ZKTeco('192.168.0.109');
        $conn = $zkteco->connect();
        if ($conn) {
            $zktecoUsers = $zkteco->getUser();
            $collectData = collect($zktecoUsers);
            $zktecoUserId = $collectData->pluck('userid')->toArray();
            $employees = Employee::WhereNotIn('id', $zktecoUserId)->get();
        }

        if (count($employees) != 0) {
            foreach ($employees as $key => $employee) {
                dispatch(new ZktecoSetUser($employee));
            }
        }
    }

    public function storeAtten()
    {
        $zkteco = new ZKTeco('192.168.0.109');
        $conn = $zkteco->connect();
        if ($conn) {
            $zktecoAtten = $zkteco->getAttendance();
            $attendances = collect($zktecoAtten);
            $todayDate = date('Y-m-d');
            $attendances = $attendances->filter(function ($item) use ($todayDate) {
                return substr($item['timestamp'], 0, 10) == $todayDate;
            });

            foreach ($attendances as $key => $atten) {
                $branch_id = Employee::findOrFail($atten['id']);
                $createDate = date_create($atten['timestamp']);
                $date = date_format($createDate, 'Y-m-d');

                $employee = Attendance::Where('emplyee_id', $atten['id'])->whereDate('date', $date)->first();
                if ($employee) {
                    $attend = $attendances->filter(function ($item) use ($date, $atten) {
                        return substr($item['timestamp'], 0, 10) == $date && $item['id'] == $atten['id'];
                    });

                    $endTime = $attend->SortByDesc('timestamp')->first();
                    $endTime = date_create($endTime['timestamp']);
                    $endTime = date_format($endTime, 'H:i');

                    $employee->update([
                        'date'          => $date,
                        'sign_out'      => $endTime,
                    ]);
                } else {
                    $attend = $attendances->filter(function ($item) use ($date, $atten) {
                        return substr($item['timestamp'], 0, 10) == $date && $item['id'] == $atten['id'];
                    });
                    $entryTime = $attend->SortBy('timestamp')->first();
                    $entryTime = date_create($entryTime['timestamp']);
                    $entryTime = date_format($entryTime, 'H:i');

                    Attendance::create([
                        'emplyee_id'    => $atten['id'],
                        'branch_id'     => $branch_id->branch_id,
                        'date'          => $date,
                        'sign_in'       => $entryTime,
                        'sign_out'      => '00.00',
                    ]);
                }
            }
            return redirect()->route('hrm.attendancelog.index');
        }
    }
}
