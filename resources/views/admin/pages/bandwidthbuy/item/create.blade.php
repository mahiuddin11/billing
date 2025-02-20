@extends('admin.master')

@section('content')
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ $page_heading ?? 'Create' }}</h4>
                    <a href="{{ $back_url ?? 'javascript:;' }}" class="btn btn-dark">Back</a>
                </div>
                <div class="card-body">

                    <x-alert></x-alert>

                    <div class="basic-form">
                        <form action="{{ $store_url ?? '#' }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                {{-- <div class="col-md-4 mb-1">
                                <label for="">Item Id <span class="text-danger">*</span></label>
                                <input type="text" class="form-control input-rounded" name="item_id">
                            </div> --}}
                                <div class="col-md-4 mb-1">
                                    <label for="">Item Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control input-rounded" name="name">
                                </div>

                                <div class="col-md-4 mb-1">
                                    <label for="">Unit </label>
                                    <input type="text" class="form-control input-rounded" name="unit">
                                </div>

                                <div class="col-md-4 mb-1">
                                    <label for="">Vat% </label>
                                    <input type="text" class="form-control input-rounded" name="vat">
                                </div>

                                <div class="col-md-4 mb-1">
                                    <label for="">Item Category <span class="text-danger">*</span></label>
                                    <select {{ $categorys->isEmpty() ? 'disabled' : '' }} name="category_id"
                                        class="form-control">
                                        <option disabled selected>Category</option>
                                        @foreach ($categorys as $subcategory)
                                            <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                {{-- <div class="col-md-4 mb-1">
                                    <label for="">Income Account</label>
                                    <select name="income_account_id" class="form-control">
                                        <option disabled selected>Category</option>
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->id }}">{{ $account->account_name }}</option>
                                        @endforeach
                                    </select>
                                </div> --}}
                                {{-- <div class="col-md-4 mb-1">
                                    <label for="">Expense Account</label>
                                    <select name="expense_account_id" class="form-control">
                                        <option disabled selected>Category</option>
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->id }}">{{ $account->account_name }}</option>
                                        @endforeach
                                    </select>
                                </div> --}}
                                {{--
                                <div class="col-md-4 mb-1">
                                    <label for="">Status</label>
                                    <select name="status" class="form-control" id="">
                                        <option value="active">Active</option>
                                        <option value="inactive">InActive</option>
                                    </select>
                                </div> --}}

                                <div class="col-md-4 mb-1">
                                    <label for="">Description</label>
                                    <textarea name="description" class="form-control" id="" rows="0"></textarea>
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
