@extends('admin.master')

@section('content')
<div class="row">
    <div class="col-xl-12 col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{$page_heading ?? 'Create'}}</h4>
                <a href="{{$back_url ?? 'javascript:;'}}" class="btn btn-dark">Back</a>
            </div>
            <div class="card-body">

                <x-alert></x-alert>

                <div class="basic-form">
                    <form action="{{ $update_url ?? '#' }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-1">
                                <label for="">Customer Name</label>
                                <select class="select2 form-control" name="customer_id">
                                    <option selected>Select</option>
                                    @foreach($customers as $customer)
                                    <option {{$editinfo->customer_id == $customer->id ? 'selected':""}}
                                        value="{{$customer->id}}">{{$customer->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-1">
                                <label for="">Total Amount<span class="text-danger">*</span></label>
                                <input type="text" value="{{$editinfo->total}}" class="form-control" name="total">
                            </div>
                            <div class="col-md-6 mb-1">
                                <label for="">Date<span class="text-danger">*</span></label>
                                <input type="date" value="{{$editinfo->date}}" class="form-control"
                                    name="date">
                            </div>
                            <div class="col-md-6 mb-1">
                                <label for="">Receive By</label>
                                <select class="select2 form-control" name="created_by">
                                    <option selected>Select</option>
                                    @foreach($users as $user)
                                    <option {{$editinfo->created_by == $user->id ? 'selected':""}}
                                        value="{{$user->id}}">{{$user->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="mb-1 form-group">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
