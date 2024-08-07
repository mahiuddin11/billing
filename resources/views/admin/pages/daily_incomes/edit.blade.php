@extends('admin.master')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-header">
                        <h4 class="card-title">{{ $page_heading ?? 'Create' }}</h4>
                        <a href="{{ $back_url ?? 'javascript:;' }}" class="btn btn-dark">Back</a>
                    </div>
                    <x-alert></x-alert>
                    <form class="form" action="{{ route('dailyIncome.update', $dailyincome->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 col-12 mb-1">
                                <div class="form-group">
                                    <label for="last-name-column">Date</label>
                                    <input type="date" value="{{ $dailyincome->date }}"
                                        class="form-control flatpickr-basic" placeholder="Select date" name="date"
                                        id="date" required />
                                </div>
                            </div>
                            <div class="col-md-6 col-12 mb-1">
                                <div class="form-group">
                                    <label for="email-id-column">Income Category</label>
                                    <select class="select2 form-control" name="category_id">
                                        <option selected value="0">Select Customer</option>
                                        @foreach ($incomecategories as $item)
                                            <option {{ $dailyincome->category_id == $item->id ? 'selected' : '' }} value="{{ $item->id }}">
                                                {{ $item->service_category_type }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-12 mb-1">
                                <div class="form-group">
                                    <label for="email-id-column">Customer</label>
                                    <select class="select2 form-control" name="customer_id">
                                        <option selected value="0">Select Customer</option>
                                        @foreach ($customers as $customer)
                                            <option {{ $dailyincome->customer_id == $customer->id ? 'selected' : '' }}
                                                value="{{ $customer->id }}">
                                                {{ $customer->name }}({{ $customer->username }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 col-12 mb-1">
                                <div class="form-group">
                                    <label for="email-id-column">Supplier</label>
                                    <select class="select2 form-control" name="supplier_id">
                                        <option selected value="0">Select Supplier</option>
                                        @foreach ($suppliers as $supplier)
                                            <option {{ $dailyincome->supplier_id == $supplier->id ? 'selected' : '' }}
                                                value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 col-12 mb-1">
                                <div class="form-group">
                                    <label for="email-id-column">Payment Method</label>
                                    <select class="select2 form-control" name="payment_id">
                                        <option selected disabled>Select Account</option>
                                        @foreach ($paymentMethods as $account)
                                            <option {{ $dailyincome->payment_id == $account->id ? 'selected' : '' }}
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
                                            <option {{ $dailyincome->account_id == $account->id ? 'selected' : '' }}
                                                value="{{ $account->id }}">
                                                ({{ $account->head_code }})
                                                {{ $account->account_name }}
                                            </option>
                                            @if ($account->subAccount->isNotEmpty())
                                                @foreach ($account->subAccount as $subaccount)
                                                    <option
                                                        {{ $dailyincome->account_id == $subaccount->id ? 'selected' : '' }}
                                                        value="{{ $subaccount->id }}">
                                                        - ({{ $subaccount->head_code }}) {{ $subaccount->account_name }}
                                                    </option>

                                                    @if ($subaccount->subAccount->isNotEmpty())
                                                        @foreach ($subaccount->subAccount as $subaccount2)
                                                            <option
                                                                {{ $dailyincome->account_id == $subaccount2->id ? 'selected' : '' }}
                                                                value="{{ $subaccount2->id }}">
                                                                -- ({{ $subaccount2->head_code }})
                                                                {{ $subaccount2->account_name }}</option>
                                                            @if ($subaccount2->subAccount->isNotEmpty())
                                                                @foreach ($subaccount2->subAccount as $subaccount3)
                                                                    <option
                                                                        {{ $dailyincome->account_id == $subaccount3->id ? 'selected' : '' }}
                                                                        value="{{ $subaccount3->id }}" disabled>
                                                                        --- ({{ $subaccount3->head_code }})
                                                                        {{ $subaccount3->account_name }}
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
                            {{-- <div class="col-md-6 col-12 mb-1 account3rd {{ $dailyincome->account_2nd ?? 'd-none' }}">
                                <div class="form-group">
                                    <label for="email-id-column">Account 2nd</label>
                                    <select class="select2 form-control 3rd accounthead" name="account_3rd">
                                        @foreach ($accounts as $account)
                                            <option {{ $dailyincome->account_2nd == $account->id ? 'selected' : '' }}
                                                available="{{ $account->amount }}" value="{{ $account->id }}">
                                                {{ $account->account_name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-success account-message"></span>
                                </div>
                            </div>
                            <div class="col-md-6 col-12 mb-1 account4th {{ $dailyincome->account_3rd ?? 'd-none' }}">
                                <div class="form-group">
                                    <label for="email-id-column">Account 3rd</label>
                                    <select class="select2 form-control 4th accounthead" name="account_4th">
                                        @foreach ($accounts as $account)
                                            <option {{ $dailyincome->account_3rd == $account->id ? 'selected' : '' }}
                                                available="{{ $account->amount }}" value="{{ $account->id }}">
                                                {{ $account->account_name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-success account-message"></span>
                                </div>
                            </div> --}}

                            <div class="col-md-6 col-12 mb-1">
                                <div class="form-group">
                                    <label for="email-id-column">Service Charge</label>
                                    <input type="number" value="{{ $dailyincome->amount }}" class="form-control"
                                        min="0" value="0" id="sercice-charge" name="amount"
                                        placeholder="Enter amount" required />
                                </div>
                            </div>
                            <div class="col-md-6 col-12 mb-1">
                                <div class="form-group">
                                    <label for="email-id-column">Paid Amount</label>
                                    <input type="number" value="{{ $dailyincome->paid_amount }}" class="form-control"
                                        min="0" value="0" id="paid-amount" name="paid_amount"
                                        placeholder="Enter amount" required />
                                    <span class="alert-text text-danger"></span>
                                </div>
                            </div>
                            <div class="col-md-6 col-12 mb-1">
                                <div class="form-group">
                                    <label for="company-column">Description</label>
                                    <textarea name="description" class="form-control">
                                        {{ $dailyincome->description }}
                                    </textarea>
                                </div>
                            </div>
                        </div>
                        <div class="mb-1">
                            <button href="" type="submit" class="btn btn-primary">Save</button>
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

        $(document).on('input', '#paid-amount', function() {
            let amount = $('#sercice-charge').val();
            let thisVal = $(this).val();
            if (Number(amount) < Number(thisVal)) {
                $('.alert-text').text('this amount cannot bigger than service charge');
            } else {
                $('.alert-text').text('');
            }
        })
    </script>
@endpush
