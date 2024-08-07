@extends('admin.master')

@section('content')
<div class="row">
    <div class="col-xl-12 col-lg-12">
        <form action="{{ $update_url ?? '#' }}" method="POST" enctype="multipart/form-data">
            @csrf
            <x-alert></x-alert>

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{$page_heading ?? 'Edit'}}</h4>
                    <a href="{{$back_url ?? 'javascript:;'}}" class="btn btn-dark">Back</a>
                </div>
                <div class="card-body">
                    <div class="row">

                        <div class="col-md-4 mb-1">
                            <label for="">Name </label>
                            <select name="employee_id" id="" class="form-control select2">
                                <option selected disabled>Select Employe</option>
                                @foreach ($employees as $key => $value)
                                <option {{$editinfo->employee_id == $value->id ? "selected":""}} value="{{$value->id }}">{{ $value->name }}</option>
                                @endforeach
                            </select>
                            @error('employee_id')
                            <span class=" error text-red text-bold">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-1">
                            <label for="">Amount</label>
                            <input type="number" class="form-control input-rounded" value="{{old('amount') ?? $editinfo->amount}}" name="amount">
                        </div>
                        <div class="col-md-4 mb-1">
                            <label for="">Lone Adjustment</label>
                            <input type="number" class="form-control input-rounded" value="{{old('lone_adjustment') ?? $editinfo->lone_adjustment}}"
                                name="lone_adjustment">
                        </div>
                        <div class="col-md-4 mb-1">
                            <label for="">Reason</label>
                            <input type="text" class="form-control input-rounded" value="{{old('reason') ?? $editinfo->reason}}" name="reason">
                        </div>
                        {{-- <div class="col-md-4 mb-1">
                            <label for="">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-control">
                                <option {{$editinfo->status == 'approved' ? 'selected':""}} value="approved">Approved</option>
                                <option {{$editinfo->status == 'pending' ? 'selected':""}} value="pending">Pending</option>
                                <option {{$editinfo->status == 'cancel' ? 'selected':""}} value="cancel">Cancel</option>
                            </select>
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
