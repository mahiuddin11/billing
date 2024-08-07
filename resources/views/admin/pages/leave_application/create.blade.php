@extends('admin.master')

@section('content')
    <div class="row">
        <div class="col-xl-12 col-lg-12">

            <form action="{{ $store_url ?? '#' }}" method="POST" enctype="multipart/form-data" autocomplete="false">
                @csrf
                <x-alert></x-alert>

                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Leave Applicaiton Form</h4>
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
                                <label for="">Apply Date</label>
                                <input type="date" class="form-control input-rounded" value="{{ old('apply_date') }}"
                                    name="apply_date">
                                @error('apply_date')
                                    <span class=" error text-red text-bold">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-1">
                                <label for="">End Date</label>
                                <input type="date" class="form-control input-rounded" value="{{ old('end_date') }}" name="end_date">
                                @error('end_date')
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
                                <input type="file" class="form-control input-rounded" value="{{ old('image') }}"
                                    name="image">
                                @error('image')
                                    <span class=" error text-red text-bold">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-1">
                                <label for="">Payment Status</label>
                                <select name="payment_status" class="form-control">
                                    <option value="">Please Seleect Payment Status</option>
                                    <option value="paid">Paid</option>
                                    <option value="non-paid">Non-paid</option>
                                </select>
                                @error('payment_status')
                                <span class=" error text-red text-bold">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- <div class="col-md-4 mb-1">
                                <label for="">Status</label>
                                <select name="status" class="form-control">
                                    <option value="">Please Seleect Status</option>
                                    <option value="approved">Approved</option>
                                    <option value="pending">Pending</option>
                                    <option value="cancel">Cancel</option>
                                </select>
                                @error('status')
                                    <span class=" error text-red text-bold">{{ $message }}</span>
                                @enderror
                            </div> --}}
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
