@extends('admin.master')

@section('content')
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ $page_heading ?? 'Edit' }}</h4>
                    <a href="{{ $back_url ?? 'javascript:;' }}" class="btn btn-dark">Back</a>
                </div>
                <div class="card-body">

                    <x-alert></x-alert>

                    <div class="basic-form">
                        <form action="{{ $update_url ?? '#' }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-1">
                                    <label>Select Customer</label>
                                    <select name="customer_id" class="select2 default-select form-control wide">
                                        <option selected="selected" disabled>Select Customer</option>
                                        @foreach ($customers as $key => $value)
                                            <option {{ $Expense->customer_id == $value->id ? 'selected' : '' }}
                                                value="{{ $value->id }}">
                                                {{ $value->name }}({{ $value->username }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-1">
                                    <label>Select Supplier</label>
                                    <select name="supplier_id" class="select2 default-select form-control wide">
                                        <option selected="selected" disabled>Select Supplier</option>
                                        @foreach ($suppliers as $key => $value)
                                            <option {{ $Expense->supplier_id == $value->id ? 'selected' : '' }}
                                                value="{{ $value->id }}">
                                                {{ $value->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="email-id-column">Payment Method</label>
                                        <select class="select2 form-control" name="payment_id">
                                            <option selected disabled>Select Account</option>
                                            @foreach ($paymentMethods as $account)
                                                <option {{ $Expense->payment_id == $account->id ? 'selected' : '' }}
                                                    available="{{ $account->amount }}" value="{{ $account->id }}">
                                                    {{ $account->account_name }}</option>
                                            @endforeach
                                        </select>
                                        <span class="text-success account-message"></span>
                                    </div>
                                </div>

                                <div class="col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="email-id-column">Account Head</label>
                                        <select class="select2 form-control 2nd" name="account_id">
                                            <option selected disabled> Selecte Account </option>
                                            @foreach ($accounts as $account)
                                                <option {{ $Expense->account_id == $account->id ? 'selected' : '' }}
                                                    value="{{ $account->id }}">
                                                    {{ $account->head_code }}{{ $account->account_name }}
                                                </option>
                                                @if ($account->subAccount->isNotEmpty())
                                                    @foreach ($account->subAccount as $subaccount)
                                                        <option
                                                            {{ $Expense->account_id == $subaccount->id ? 'selected' : '' }}
                                                            value="{{ $subaccount->id }}">

                                                            -{{ $subaccount->head_code }}{{ $subaccount->account_name }}
                                                        </option>

                                                        @if ($subaccount->subAccount->isNotEmpty())
                                                            @foreach ($subaccount->subAccount as $subaccount2)
                                                                <option
                                                                    {{ $Expense->account_id == $subaccount2->id ? 'selected' : '' }}
                                                                    value="{{ $subaccount2->id }}">
                                                                    --{{ $subaccount2->head_code }}{{ $subaccount2->account_name }}
                                                                </option>
                                                                @if ($subaccount2->subAccount->isNotEmpty())
                                                                    @foreach ($subaccount2->subAccount as $subaccount3)
                                                                        <option
                                                                            {{ $Expense->account_id == $subaccount3->id ? 'selected' : '' }}
                                                                            value="{{ $subaccount3->id }}" disabled>
                                                                            ---{{ $subaccount3->head_code }}{{ $subaccount3->account_name }}
                                                                        </option>
                                                                    @endforeach
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        </select>
                                        <span class="text-success account-message"></span>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-1">
                                    <label>Category</label>
                                    <select name="expense_category_id" class="select2 form-control">
                                        <option selected="selected" disabled>Select Category</option>
                                        @foreach ($categories as $value)
                                        <option {{ $Expense->expense_category_id == $value->id ? 'selected' : '' }} value="{{ $value->id }}">
                                            {{ $value->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-success method-message"></span>
                                </div>

                                {{-- <div class="col-md-6 col-12 mb-1 account3rd {{ $Expense->account_2nd ?? 'd-none' }}">
                                    <div class="form-group">
                                        <label for="email-id-column">Account 2nd</label>
                                        <select class="select2 form-control 3rd accounthead" name="account_3rd">
                                            @foreach ($accounts as $account)
                                                <option {{ $Expense->account_2nd == $account->id ? 'selected' : '' }}
                                                    available="{{ $account->amount }}" value="{{ $account->id }}">
                                                    {{ $account->account_name }}</option>
                                            @endforeach
                                        </select>
                                        <span class="text-success account-message"></span>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12 mb-1 account4th {{ $Expense->account_3rd ?? 'd-none' }}">
                                    <div class="form-group">
                                        <label for="email-id-column">Account 3rd</label>
                                        <select class="select2 form-control 4th accounthead" name="account_4th">
                                            @foreach ($accounts as $account)
                                                <option {{ $Expense->account_3rd == $account->id ? 'selected' : '' }}
                                                    available="{{ $account->amount }}" value="{{ $account->id }}">
                                                    {{ $account->account_name }}</option>
                                            @endforeach
                                        </select>
                                        <span class="text-success account-message"></span>
                                    </div>
                                </div> --}}

                                <div class="col-md-6 mb-1">
                                    <label>Amount</label>
                                    <input type="number" value="{{ $Expense->amount }}" class="form-control input-rounded"
                                        name="amount" placeholder="Amount">
                                </div>
                                <div class="col-md-6 mb-1">
                                    <label>Date</label>
                                    <input type="text" id="date" readonly name="date"
                                        value="{{ old('date') ?? $Expense->date }}" class="form-file-input form-control ">
                                </div>
                                <div class="col-md-12 mb-1">
                                    <label>Note</label>
                                    <textarea type="text" class="form-control " name="note" placeholder="Note here"></textarea>
                                </div>
                            </div>


                            <div class=" form-group">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).on('change', '.1st', function() {
            let value = $(this).val();
            $.ajax({
                url: "{{ route('accounts.account1st') }}",
                type: "GET",
                data: {
                    "id": value,
                },
                success: function(data) {
                    $('.account2nd').removeClass('d-none')
                    $('.2nd').html(data);
                }
            })
        })
        $(document).on('change', '.2nd', function() {
            let value = $(this).val();
            $.ajax({
                url: "{{ route('accounts.account2st') }}",
                type: "GET",
                data: {
                    "id": value,
                },
                success: function(data) {
                    $('.account3rd').removeClass('d-none')
                    $('.3rd').html(data);
                }
            })
        })
        $(document).on('change', '.3rd', function() {
            let value = $(this).val();
            $.ajax({
                url: "{{ route('accounts.account3rd') }}",
                type: "GET",
                data: {
                    "id": value,
                },
                success: function(data) {
                    $('.account4th').removeClass('d-none')
                    $('.4th').html(data);
                }
            })
        })
        var today = new Date();
        var date = today.getFullYear() + '-' + (today.getMonth() + 1) + '-' + today.getDate();
        document.getElementById("date").value = date;
    </script>
@endpush
