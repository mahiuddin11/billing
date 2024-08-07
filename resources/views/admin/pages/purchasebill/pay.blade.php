@extends('admin.master')

@section('content')
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ $page_heading ?? 'Record A Payment' }}</h4>
                    <a href="{{ $back_url ?? 'javascript:;' }}" class="btn btn-dark">Back</a>
                </div>
                <div class="card-body">

                    <x-alert></x-alert>

                    <div class="basic-form">
                        <form action="{{ route('purchasebill.paystore') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row justify-content-center">
                                <div class="col-md-6 mb-1">
                                    <label for="">Providers</label>
                                    <select name="provider_id" class="form-control select2" id="provider_id">
                                        <option disabled selected>Providers Select</option>
                                        @foreach ($providers as $provider)
                                            <option value="{{ $provider->id }}">{{ $provider->company_name }} -
                                                {{ $provider->contact_person }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger showAmount"></span>
                                </div>
                                <div class="col-md-6 mb-1">
                                    <label for="">Payment Date</label>
                                    <input type="date" class="form-control" value="{{ old('date_') }}" name="date_">
                                </div>

                                {{-- <div class="col-md-6 mb-1">
                                    <label for="">Discount</label>
                                    <input type="text" class="form-control" value="{{ old('discount') }}"
                                        name="discount">
                                </div> --}}
                                <div class="col-md-6 mb-1">
                                    <label for="">Payment Method</label>
                                    <select name="payment_method" class="form-control select2" id="">
                                        <option disabled selected>Select</option>
                                        @foreach ($paymentMethods as $account)
                                            <option value="{{ $account->id }}">
                                                {{ $account->account_name }}</option>
                                            @if ($account->subAccount->isNotEmpty())
                                                @foreach ($account->subAccount as $subaccount)
                                                    <option value="{{ $subaccount->id }}">
                                                        - {{ $subaccount->account_name }}
                                                    </option>
                                                    @if ($subaccount->subAccount->isNotEmpty())
                                                        @foreach ($subaccount->subAccount as $subaccount2)
                                                            <option value="{{ $subaccount2->id }}">
                                                                --
                                                                {{ $subaccount2->account_name }}</option>
                                                            @if ($subaccount2->subAccount->isNotEmpty())
                                                                @foreach ($subaccount2->subAccount as $subaccount3)
                                                                    <option value="{{ $subaccount3->id }}" disabled>
                                                                        ---
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
                                </div>
                                <div class="col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="email-id-column">Account Head</label>
                                        <select class="select2 form-control" name="account_id">
                                            <option selected disabled> Selecte Account </option>
                                            @foreach ($accounts as $account)
                                                <option value="{{ $account->id }}">({{ $account->head_code }})
                                                    {{ $account->account_name }}</option>
                                                @if ($account->subAccount->isNotEmpty())
                                                    @foreach ($account->subAccount as $subaccount)
                                                        <option value="{{ $subaccount->id }}">
                                                            - ({{ $subaccount->head_code }})
                                                            {{ $subaccount->account_name }}
                                                        </option>
                                                        @if ($subaccount->subAccount->isNotEmpty())
                                                            @foreach ($subaccount->subAccount as $subaccount2)
                                                                <option value="{{ $subaccount2->id }}">
                                                                    -- ({{ $subaccount2->head_code }})
                                                                    {{ $subaccount2->account_name }}</option>
                                                                @if ($subaccount2->subAccount->isNotEmpty())
                                                                    @foreach ($subaccount2->subAccount as $subaccount3)
                                                                        <option value="{{ $subaccount3->id }}" disabled>
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
                                <div class="col-md-6 mb-1">
                                    <label for="">Pay Amount</label>
                                    <input type="number" class="form-control payAmount" value="{{ old('amount') }}"
                                        name="amount">
                                </div>
                                <div class="col-md-6 mb-1">
                                    <label for="">Paid by</label>
                                    <input type="text" class="form-control" value="{{ old('paid_by') }}" name="paid_by">
                                </div>
                                <div class="col-md-6 mb-1">
                                    <label for="">Description</label>
                                    <textarea name="description" class="form-control" id="" cols="30" rows="5">

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

@push('scripts')
    <script>
        $(document).on('change', '#provider_id', function() {
            $.ajax({
                url: "{{ route('purchasebill.getAvailableBalance') }}",
                type: 'GET',
                dataType: 'JSON',
                data: {
                    provider_id: $(this).val(),
                },
                success: function(data) {
                    $('.showAmount').text('Due Balance ' + data.amount);
                    $('.payAmount').attr('max', data.amount)
                }
            })
        })
    </script>
@endpush
