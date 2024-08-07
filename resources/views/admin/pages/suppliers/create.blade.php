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
                    <form action="{{ $store_url ?? '#' }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-1">
                                <label for="">Name</label>
                                <input type="text" class="form-control" value="{{ old('name')}}" name="name"
                                    placeholder="Category Name">
                            </div>
                            <div class="col-md-6 mb-1">
                                <label for="">Phone</label>
                                <input type="text" class="form-control" value="{{ old('phone')}}" name="phone"
                                    placeholder="Category Name">
                            </div>
                            <div class="col-md-6 mb-1">
                                <label for="">Email</label>
                                <input type="text" class="form-control" value="{{ old('email')}}" name="email"
                                    placeholder="Category Name">
                            </div>
                            <div class="col-md-6 mb-1">
                                <label for="">Address</label>
                                <textarea name="address" class="form-control" id="" cols="30" rows="3">

                                </textarea>
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
