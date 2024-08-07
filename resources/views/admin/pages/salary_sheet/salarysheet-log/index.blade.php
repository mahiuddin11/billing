
@extends('admin.master')
@section('content')
<style>
    .folder-icone {
        color: #D4AC0D;
    }
</style>

<section id="ajax-datatable">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Salary Sheet Log</h3>
                    <div class="card-tools">
                        <span id="buttons"></span>
                        {{-- <a class="btn btn-tool btn-default" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </a>
                        <a class="btn btn-tool btn-default" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </a> --}}
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form action="{{ route('hrm.salarysheetlog.index') }}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <label for="employe" class="mt-1">Employee:</label>
                            <div class="col-md-3">
                                <select name="employee_id" class="form-control select2" id="employe">
                                    <option value="all">All</option>
                                    @foreach ($employees as $employee)
                                    <option
                                        value="{{ $employee->id }}">{{ $employee->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <label for="From" class="mt-1">Select Month:</label>
                            <div class="col-md-3">
                               <input type="month" id="From" value="" class="form-control" name="month" max="">
                            </div>
                            <div class="col-md-1">
                                <button class="btn btn-success">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
        <!-- /.col-->
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card card-default">
                <!-- /.card-header -->
                <div class="card-body">
                    @if (isset($dayes))
                    @foreach ($dayes as $dayKey => $daye)
                    <h5 class="text-center mt-3">Attendance History of {{ $daye->date }}</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">SL</th>
                                <th scope="col">Employee Name</th>
                                <th scope="col"> In Time</th>
                                <th scope="col"> Office In Time</th>
                                <th scope="col"> Last Out Time</th>
                                <th scope="col"> Worked Hours</th>
                                <th scope="col"> OverTime Houre</th>
                                <th scope="col"> Late</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @php
                            $in = null;
                            $lastin = null;
                            $diff = null;
                            @endphp
                            @foreach ($attendances as $key => $attendance)
                            @if ($attendance->date == $daye->date)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $attendance->employe->name }}</td>
                                <td> {{ $attendance->sign_in }} </td>
                                <td>{{ $attendance->employe->last_in_time }} </td>
                                <td>{{ $attendance->sign_out }} </td>
                                @if (strtotime($attendance->sign_in) < strtotime($attendance->employe->last_in_time))
                                    @php
                                    $in = \Carbon\Carbon::parse($attendance->employe->last_in_time);
                                    $lastin = \Carbon\Carbon::parse($attendance->sign_out);
                                    $diff = $in->diff($lastin);
                                    @endphp
                                    @else
                                    @php
                                    $in = \Carbon\Carbon::parse($attendance->sign_in);
                                    $lastin = \Carbon\Carbon::parse($attendance->sign_out);
                                    $diff = $in->diff($lastin);
                                    @endphp
                                    @endif
                                    <td> {{ $diff->h }} : {{ $diff->i }}</td>
                                    <td> {{ CUSTOM_OVERTIME_HOURE($attendance->employe, date('m', strtotime($daye->date)),
                                        date('d', strtotime($attendance->date))) }}
                                    </td>
                                    <td> {{ CUSTOM_LATE_DAYS($attendance->employe, date('m', strtotime($daye->date)),
                                        date('d', strtotime($attendance->date))) == 1 ? 'Late' : 'N/A' }}
                                    </td>

                            </tr>
                            @endif
                            @endforeach --}}
                        </tbody>
                    </table>
                    @endforeach
                    @endif
                </div>
                <!-- /.card-body -->
            </div>
        </div>
        <!-- /.col-->
    </div>
</section>
@endsection
