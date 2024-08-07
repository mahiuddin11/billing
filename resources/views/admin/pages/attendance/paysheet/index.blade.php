@extends('backend.layouts.master')
@section('title')
    Hrm - {{ $title }}
@endsection

@section('styles')
    <style>
        .bootstrap-switch-large {
            width: 200px;
        }
    </style>
@endsection

@section('navbar-content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        HRM </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                        {{-- @if (helper::roleAccess('inventorySetup.adjust.index'))
                            <li class="breadcrumb-item"><a href="{{ route('hrm.attendance.index') }}">Hrm</a>
                            </li>
                        @endif --}}
                        <li class="breadcrumb-item active"><span>{{ $title }}</span></li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
@endsection

@section('admin-content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Salary Pay Sheet</h3>
                    <div class="card-tools">
                        <span id="buttons"></span>
                        <a class="btn btn-tool btn-default" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </a>
                        <a class="btn btn-tool btn-default" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form action="{{ route('hrm.paysheet.index') }}" method="get">

                        <div class="form-group row">
                            <label for="employe" class="mt-2">Employee:</label>
                            <div class="col-md-3">
                                <select name="employee_id" class="form-control select2" id="employe">
                                    <option value="all" selected>All</option>
                                    @foreach ($employees->all() as $employee)
                                        <option {{ $request->employee_id == $employee->id ? 'selected' : '' }}
                                            value="{{ $employee->id }}">
                                            {{ $employee->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <label for="From" class="mt-2">Select Month:</label>
                            <div class="col-md-3">
                                <input type="month" id="From" value="{{ $request->month }}" class="form-control"
                                    name="month">
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

                    <h5 class="text-center mt-3">Salary Pay Sheet History </h5>
                    <table class="table table-bordered table-responsive">
                        <thead>
                            <tr>
                                <th scope="col">SL</th>
                                <th scope="col">Name</th>
                                <th scope="col">Basic Salary</th>
                                <th scope="col">House Rent</th>
                                <th scope="col">Medical Allowance</th>
                                <th scope="col">Travel Allowance</th>
                                <th scope="col">Food Allowance</th>
                                <th scope="col">Gross Salary (GS)</th>
                                <th scope="col">Working Day</th>
                                <th scope="col">Presence</th>
                                <th scope="col">Absence (AB)</th>
                                <th scope="col">Late (LT)</th>
                                <th scope="col">Paid Leave (PL)</th>
                                <th scope="col">Unpaid Leave (UL)</th>
                                <th scope="col">Overtime Houre</th>
                                <th scope="col">Overtime Salary (OS)</th>
                                <th scope="col">Loan</th>
                                <th scope="col">Payable Salary </th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($MonthlyPaySheets))
                                @foreach ($MonthlyPaySheets as $key => $MonthlyPaySheet)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $MonthlyPaySheet->name }}</td>
                                        <td>{{ $MonthlyPaySheet->basic_salary }} </td>
                                        <td>{{ $MonthlyPaySheet->house_rent }} </td>
                                        <td>{{ $MonthlyPaySheet->medical_allowance }} </td>
                                        <td>{{ $MonthlyPaySheet->travel_allowance }} </td>
                                        <td>{{ $MonthlyPaySheet->food_allowance }} </td>
                                        <td>{{ $MonthlyPaySheet->total_salary }} </td>
                                        <td>{{ $MonthlyPaySheet->working_day }} </td>
                                        <td>{{ $MonthlyPaySheet->employee_presence_day }} </td>
                                        <td class="text-danger">{{ $MonthlyPaySheet->employee_absence_day }} </td>
                                        <td>{{ $MonthlyPaySheet->employee_late }} </td>
                                        <td>{{ $MonthlyPaySheet->employee_paid_leave }} </td>
                                        <td>{{ $MonthlyPaySheet->employee_unpaid_leave }} </td>
                                        <td>{{ $MonthlyPaySheet->overtime_houre }}h </td>
                                        <td>{{ $MonthlyPaySheet->overtime_salary }} </td>
                                        <td class="loanamount">
                                            @php
                                                $loan = DB::table('transections')
                                                    ->where('account_id', 1)
                                                    ->where('employee_id', $MonthlyPaySheet->employee_id)
                                                    ->selectRaw(
                                                        'SUM(debit) as debit
                                            ,SUM(credit) as credit',
                                                    )
                                                    ->first();
                                                $loanBalance = $loan->debit - $loan->credit;
                                                $loanAdjustment = App\Models\Lone::where('employee_id', $MonthlyPaySheet->employee_id)
                                                    ->where('status', 'approved')
                                                    ->latest()
                                                    ->pluck('lone_adjustment')
                                                    ->first();
                                            @endphp
                                            {{ $loanBalance }}
                                        </td>
                                        <td class="loanAdjustment d-none">{{ $loanAdjustment }} </td>
                                        <td class="payable">{{ $MonthlyPaySheet->employee_payable_salary }} </td>
                                        <td>
                                            @if ($MonthlyPaySheet->status == 'paid')
                                                <b class="text-success">Paid</b>
                                            @elseif($MonthlyPaySheet->status == 'unpaid')
                                                <b class="text-danger">Unpaid</b>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($MonthlyPaySheet->status == 'paid')
                                                <button class="btn btn-success"><i class="fas fa-check"></i></button>
                                            @elseif($MonthlyPaySheet->status == 'unpaid')
                                                <button class="paynow"
                                                    href="{{ route('hrm.paysheet.empPayDetailsStore', $MonthlyPaySheet->employee_id) }}"
                                                    data-toggle="modal" data-target="#exampleModal"><i
                                                        class="fas fa-money-bill"></i></button>
                                            @endif

                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="18"></td>
                                </tr>
                            @endif
                        </tbody>
                    </table>


                </div>
                <!-- /.card-body -->
            </div>
        </div>
        <!-- /.col-->

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="card-header">
                        <h3 class="card-title">Salary Pay Sheet</h3>
                        <div class="card-tools">
                            <span id="buttons"></span>
                            <a class="btn btn-tool btn-default" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </a>
                            <a class="btn btn-tool btn-default" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card card-body">
                        <form class="needs-validation" action="" method="post" novalidate>
                            @csrf
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Paybale Salary</label>
                                <div class="col-md-9 mb-1">
                                    <h5 class="showpayable"></h5>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Amount <span class="text-danger">*</span></label>
                                <div class="col-md-9 mb-1">
                                    <input type="number" class="form-control payamount" min="1" required
                                        name="amount">
                                    @error('amount')
                                        <span class=" error text-red text-bold">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Payment Type <span
                                        class="text-danger">*</span></label>
                                <div class="col-md-9 mb-1">
                                    <select name="payment_type" class="form-control">
                                        <option selected disabled>Select a Method</option>
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->id }}">{{ $account->account_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('amount')
                                        <span class=" error text-red text-bold">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <hr>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Loan </span></label>
                                <div class="col-md-9 mb-1">
                                    <h5 class="showloanamount">
                                    </h5>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Loan Adjustment</span></label>
                                <div class="col-md-9 mb-1">
                                    <h5 class="showloanadj">
                                    </h5>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('scripts')
    <script>
        $(document).on('click', '.paynow', function() {
            let url = $(this).attr('href');
            $('form').attr('action', url);
            let payable = $(this).closest('tr').find('.payable').text();
            $('.showpayable').text(payable)
            $('.payamount').val(Number(payable))
            let loanamount = $(this).closest('tr').find('.loanamount').text();
            $('.showloanamount').text(loanamount);
            let loanAdjustment = $(this).closest('tr').find('.loanAdjustment').text();
            $('.showloanadj').text(loanAdjustment);
        })
    </script>
@endsection
