@extends('admin.master')

@section('content')
    <div class="row">
        <div class="col-xl-12 col-lg-12">

            <form action="{{ $store_url ?? '#' }}" method="POST" enctype="multipart/form-data" autocomplete="false">
                @csrf
                <x-alert></x-alert>

                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Basic details</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-1">
                                <label for="">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control input-rounded" value="{{ old('name') }}"
                                    name="name">
                                @error('name')
                                    <span class=" error text-red text-bold">{{ $message }}</span>
                                @enderror
                            </div>
                            <input autocomplete="false" name="hidden" type="text" class="hidden">
                            <div class="col-md-4 mb-1">
                                <label for="">Employee Id<span class="text-danger">*</span></label>
                                <input type="text" class="form-control input-rounded" value="{{ old('id_card') }}"
                                    name="id_card">
                                @error('id_card')
                                    <span class=" error text-red text-bold">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-1">
                                <label for="">Email</label>
                                <input type="email" class="form-control input-rounded" value="{{ old('email') }}"
                                    name="email">
                                @error('email')
                                    <span class=" error text-red text-bold">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-1">
                                <label for="">Profile Photo<span class="text-danger">*</span></label>
                                <input type="file" class="form-control input-rounded" value="{{ old('image') }}"
                                    name="image">
                                @error('image')
                                    <span class=" error text-red text-bold">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-1">
                                <label for="">Signature [PNG Photo Only]<span class="text-danger">*</span></label>
                                <input type="file" class="form-control input-rounded" value="{{ old('emp_signature') }}"
                                    name="emp_signature">
                                @error('emp_signature')
                                    <span class=" error text-red text-bold">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-1">
                                <label for="">Personal Number <span class="text-danger">*</span></label>
                                <input type="number" class="form-control input-rounded"
                                    value="{{ old('personal_phone') }}" name="personal_phone">
                                @error('personal_phone')
                                    <span class=" error text-red text-bold">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-1">
                                <label for="">Office Number</label>
                                <input type="number" class="form-control input-rounded" value="{{ old('office_phone') }}"
                                    name="office_phone">
                                @error('office_phone')
                                    <span class=" error text-red text-bold">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-1">
                                <label for="">Marital Status</label>
                                <select name="marital_status" class="form-control">
                                    <option value="married">Married</option>
                                    <option value="unmarried">Unmarried</option>
                                </select>
                                @error('marital_status')
                                    <span class=" error text-red text-bold">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-1">
                                <label for="">Nid</label>
                                <input type="text" class="form-control input-rounded" value="{{ old('nid') }}"
                                    name="nid">
                                @error('nid')
                                    <span class=" error text-red text-bold">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-1">
                                <label for="">Date Of Birth</label>
                                <input type="date" class="form-control input-rounded" value="{{ old('dob') }}"
                                    name="dob">
                                @error('dob')
                                    <span class=" error text-red text-bold">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-1">
                                <label for="">Blood Group</label>
                                <input type="text" class="form-control input-rounded" value="{{ old('blood_group') }}"
                                    name="blood_group">
                                @error('blood_group')
                                    <span class=" error text-red text-bold">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-1">
                                <label for="">Gender <span class="text-danger">*</span></label>
                                <select name="gender" class="form-control">
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                                @error('gender')
                                    <span class=" error text-red text-bold">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-1">
                                <label for="">Experience</label>
                                <textarea value="{{ old('experience') }}" name="experience" class="form-control input-rounded"></textarea>
                                @error('experience')
                                    <span class=" error text-red text-bold">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-1">
                                <label for="">Present Address</label>
                                <textarea value="{{ old('present_address') }}" name="present_address" class="form-control input-rounded"></textarea>
                                @error('present_address')
                                    <span class=" error text-red text-bold">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-1">
                                <label for="">Permanent Address</label>
                                <textarea value="{{ old('permanent_address') }}" name="permanent_address" class="form-control input-rounded"></textarea>
                                @error('permanent_address')
                                    <span class=" error text-red text-bold">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-1">
                                <label for="">Reference</label>
                                <input type="text" class="form-control input-rounded" value="{{ old('reference') }}"
                                    name="reference">
                                @error('reference')
                                    <span class=" error text-red text-bold">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Qualification Info</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-1">
                                <label for="">Achieved Degree</label>
                                <input type="text" class="form-control input-rounded"
                                    value="{{ old('achieved_degree') }}" name="achieved_degree">
                                @error('achieved_degree')
                                    <span class=" error text-red text-bold">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-1">
                                <label for="">Institution</label>
                                <input type="text" class="form-control input-rounded"
                                    value="{{ old('institution') }}" name="institution">
                                @error('institution')
                                    <span class=" error text-red text-bold">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-1">
                                <label for="">Passing Year</label>
                                <input type="number" class="form-control input-rounded"
                                    value="{{ old('passing_year') }}" name="passing_year">
                                @error('passing_year')
                                    <span class=" error text-red text-bold">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Office Information</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-1">
                                <label for="">Joining Date</label>
                                <input type="date" class="form-control input-rounded" value="{{ old('join_date') }}"
                                    name="join_date">
                                @error('join_date')
                                    <span class=" error text-red text-bold">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-1">
                                <label for="">Last In Date</label>
                                <input type="time" class="form-control input-rounded"
                                    value="{{ old('last_in_time') ?? '21:00:00' }}" name="last_in_time">
                                @error('last_in_time')
                                    <span class=" error text-red text-bold">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-1">
                                <label for="">Department <span class="text-danger">*</span></label>
                                <select name="department_id" class="form-control">
                                    <option selected disabled>Select Department</option>
                                    @foreach ($departments as $value)
                                        <option value="{{ $value->id }}">{{ $value->name }}</option>
                                    @endforeach
                                </select>
                                @error('position_id')
                                    <span class=" error text-red text-bold">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-1">
                                <label for="">Designations <span class="text-danger">*</span></label>
                                <select name="designation_id" class="form-control">
                                    <option selected disabled>Select Designations</option>
                                    @foreach ($designations as $value)
                                        <option value="{{ $value->id }}">{{ $value->name }}</option>
                                    @endforeach
                                </select>
                                @error('position_id')
                                    <span class=" error text-red text-bold">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-1">
                                <label for="">Salary <span class="text-danger">*</span></label>
                                <input type="number" class="form-control input-rounded" value="{{ old('salary') }}"
                                    name="salary">
                                @error('salary')
                                    <span class=" error text-red text-bold">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-1">
                                <label for="">Overtime</label>
                                <select name="over_time_is" class="form-control">
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                                @error('salary')
                                    <span class=" error text-red text-bold">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-1">
                                <label for="">Is Login</label>
                                <select name="is_login" id="_isLogin" onchange="isLogin()" class="form-control">
                                    <option value="true">Yes</option>
                                    <option selected value="false">No</option>
                                </select>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="card" id="_logindiv">
                    <div class="card-header">
                        <h4 class="card-title">Login Info</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-1">
                                <label for="">User Name</label>
                                <input type="text" class="form-control input-rounded" value="{{ old('username') }}"
                                    name="username">
                            </div>
                            <div class="col-md-3 mb-1">
                                <label for="">Access Roll</label>
                                <select name="roll_id" class="form-control">
                                    <option selected disabled>Select Roll</option>
                                    @foreach ($userrolls as $userroll)
                                        <option value="{{ $userroll->id }}">{{ $userroll->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-1">
                                <label for="">Access Type</label>
                                <select name="access_type" class="form-control">
                                    <option selected disabled>Select Type</option>
                                    <option value="5">Manager</option>
                                    <option value="4">Employee</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-1">
                                <label for="">Password</label>
                                <input type="password" class="form-control input-rounded" autocomplete="new-password"
                                    name="password">
                            </div>

                            <div class="col-md-3 mb-1">
                                <label for="">Confirm Password</label>
                                <input type="password" class="form-control input-rounded"
                                    value="{{ old('password_confirmation') }}" name="password_confirmation">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-1 form-group" style="text-align:right">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function isLogin() {
            let getValue = $('#_isLogin option:selected').val();
            if (getValue == 'true') {
                $('#_logindiv').removeClass('d-none')
            } else {
                $('#_logindiv').addClass('d-none')
            }

        }
        isLogin();
    </script>
@endsection
