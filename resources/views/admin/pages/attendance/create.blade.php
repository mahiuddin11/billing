
@extends('admin.master')

@section('content')
<div class="row">
    <div class="col-xl-12 col-lg-12">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Attendence</h3>
                <div class="card-tools">
                    {{-- @if (helper::roleAccess('hrm.attendance.index'))
                    <a class="btn btn-default" href="{{ route('hrm.attendance.create') }}"><i class="fa fa-list"></i>
                        Employee List</a>
                    @endif --}}
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
<x-alert></x-alert>

                <button class="check_in btn btn-primary" type="button">
                    Check In
                </button>
                <button class="check_out btn btn-success" type="button">
                    Check Out
                </button>
                <div class="collapse active show" id="check_in">
                    <div class="card-header">
                        <h4 class="card-title">Check In</h4>
                    </div>
                    <div class="card card-body">
                        <form class="needs-validation" method="POST" action="{{ route('hrm.attendance.sign_in') }}" novalidate>
                            @csrf
                            <div class="form-group row">
                                <label for="intime" class="col-sm-3 col-form-label">Employee Name*</label>
                                <div class="col-md-4 mb-1">
                                    <select name="emplyee_id" id="" class="form-control select2">
                                        <option selected disabled>Select Employe</option>
                                        @foreach ($employees as $key => $value)
                                        <option value="{{ $value->id }}">{{ $value->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('emplyee_id')
                                    <span class=" error text-red text-bold">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="intime" class="col-sm-3 col-form-label">Date *</label>
                                <div class="col-md-4 mb-1">
                                    <input type="date" value="{{ date('Y-m-d') }}" class="form-control" name="date">
                                    @error('date')
                                    <span class=" error text-red text-bold">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label" for="Emplyee">Punch Time* <span
                                        class="text-danger">*</span></label>
                                <div class="col-md-4 mb-1">
                                    <input type="time" class="form-control" name="sign_in">
                                    @error('sign_in')
                                    <span class=" error text-red text-bold">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <button class="btn btn-info" type="submit"><i class="fa fa-save"></i>
                                    &nbsp;Save</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="collapse" id="check_out">
                    <div class="card-header">
                        <h4 class="card-title">Check Out</h4>
                    </div>
                    <div class="card card-body">
                        <form class="needs-validation" method="POST" action="{{ route('hrm.attendance.sign_out') }}" novalidate>
                            @csrf
                            <div class="form-group row">
                                <label for="intime" class="col-sm-3 col-form-label">Employee Name*</label>
                                <div class="col-md-4 mb-1">
                                    <select name="emplyee_id" id="" class="form-control select2">
                                        <option selected disabled>Select Employe</option>
                                        @foreach ($employees as $key => $value)
                                        <option value="{{ $value->id }}">{{ $value->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('emplyee_id')
                                    <span class=" error text-red text-bold">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="intime" class="col-sm-3 col-form-label">Date *</label>
                                <div class="col-md-4 mb-1">
                                    <input type="date" value="{{ date('Y-m-d') }}" class="form-control" name="date">
                                    @error('date')
                                    <span class=" error text-red text-bold">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label" for="Emplyee">Punch Time* <span
                                        class="text-danger">*</span></label>
                                <div class="col-md-4 mb-1">
                                    <input type="time" class="form-control" name="sign_out">
                                    @error('sign_out')
                                    <span class=" error text-red text-bold">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <button class="btn btn-info" type="submit"><i class="fa fa-save"></i>
                                    &nbsp;Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    $(document).ready(function() {
            if ("{{ session()->get('sign') }}" == "0") {
                $('#check_in').addClass('active show');
                $('#check_out').removeClass('active show');
                $('.check_out').css('background', '#8fbc8f');
                $('.check_in').css('background', 'green');
            } else if ("{{ session()->get('sign') }}" == "1") {
                $('#check_out').addClass('active show');
                $('#check_in').removeClass('active show');
                $('.check_in').css('background', '#8fbc8f');
                $('.check_out').css('background', 'green');
            } else {
                $('.check_out').css('background', '#8fbc8f');
                $('.check_in').css('background', 'green');
            }

            $('.check_in').on('click', function() {
                $('#check_in').addClass('active show');
                $('#check_out').removeClass('active show');
                $('.check_out').css('background', '#8fbc8f');
                $('.check_in').css('background', 'green');

            })
            $('.check_out').on('click', function() {
                $('#check_out').addClass('active show');
                $('#check_in').removeClass('active show');
                $('.check_in').css('background', '#8fbc8f');
                $('.check_out').css('background', 'green');

            })
        })
</script>
@endsection
