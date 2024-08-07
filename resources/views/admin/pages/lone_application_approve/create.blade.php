@extends('admin.master')

@section('content')
    <div class="row">
        <div class="col-xl-12 col-lg-12">

            <form action="{{ $store_url ?? '#' }}" method="POST" enctype="multipart/form-data" autocomplete="false">
                @csrf
                <x-alert></x-alert>

                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Lone Applicaiton Form</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-1">
                                <label for="">Name </label>
                                <select name="employee_id" id="" class="form-control select2">
                                    <option selected disabled>Select Employe</option>
                                    @foreach ($employees as $key => $value)
                                    <option value="{{ $value->id }}">{{ $value->name }}</option>
                                    @endforeach
                                </select>
                                @error('employee_id')
                                <span class=" error text-red text-bold">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-1">
                                <label for="">Amount</label>
                                <input type="number" class="form-control input-rounded" value="{{ old('amount') }}"
                                    name="amount">
                                @error('amount')
                                    <span class=" error text-red text-bold">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-1">
                                <label for="">Lone Adjustment</label>
                                <input type="number" class="form-control input-rounded" value="{{ old('lone_adjustment') }}" name="lone_adjustment">
                                @error('lone_adjustment')
                                <span class=" error text-red text-bold">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-1">
                                <label for="">Reason</label>
                                <input type="text" class="form-control input-rounded" value="{{ old('reason') }}" name="reason">
                                @error('reason')
                                <span class=" error text-red text-bold">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-1">
                                <label for="">Upload Document<span class="text-danger">*</span></label>
                                <input type="file" class="form-control input-rounded" value="{{ old('image') }}" name="image">
                                @error('image')
                                <span class=" error text-red text-bold">{{ $message }}</span>
                                @enderror
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
