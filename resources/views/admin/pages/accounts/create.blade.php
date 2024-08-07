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
                                <div class="col-md-6 mb-1">
                                    <label for=""> Account List <span class="text-danger">*</span></label>
                                    <select name="parent_id" class="custom-select select2" id="">
                                        <option selected value="0">Root</option>
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->id }}">{{ $account->account_name }}</option>

                                            @if ($account->subAccount->isNotEmpty())
                                                @foreach ($account->subAccount as $subaccount)
                                                    <option value="{{ $subaccount->id }}">-{{ $subaccount->account_name }}
                                                    </option>

                                                    @if ($subaccount->subAccount->isNotEmpty())
                                                        @foreach ($subaccount->subAccount as $subaccount2)
                                                            <option value="{{ $subaccount2->id }}">
                                                                --{{ $subaccount2->account_name }}</option>
                                                            @if ($subaccount2->subAccount->isNotEmpty())
                                                                @foreach ($subaccount2->subAccount as $subaccount3)
                                                                    <option value="{{ $subaccount3->id }}" disabled>
                                                                        ---{{ $subaccount3->account_name }}
                                                                    </option>
                                                                @endforeach
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-1">
                                    <label for=""> Account Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control input-rounded" name="account_name"
                                        placeholder="Account Name">
                                </div>
                                <div class="col-md-6 mb-1">
                                    <label for=""> Head code <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control input-rounded" name="head_code"
                                        placeholder="Head code">
                                </div>
                                <div class="col-md-6 mb-1">
                                    <label for="">Account Details</label>
                                    <input type="text" class="form-control input-rounded" name="account_details"
                                        placeholder="Account Details">
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
