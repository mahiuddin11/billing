@extends('admin.master')

@section('content')
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ $page_heading ?? 'Create' }}</h4>
                    {{-- <a href="{{ $back_url ?? 'javascript:;' }}" class="btn btn-dark">Back</a> --}}
                </div>
                <div class="card-body">

                    <x-alert></x-alert>

                    <div class="basic-form">
                        <form action="{{ route('resellerFunding.paymentStore') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-1">
                                    <label for="">Reseller Name</label>
                                    <select class="select2 form-control reseller_id" name="reseller_id">
                                        <option selected disabled>Select</option>
                                        @foreach ($resellers as $reseller)
                                            <option value="{{ $reseller->id }}">{{ $reseller->person_name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger" id="due_balance"></span>
                                </div>
                                <div class="col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="email-id-column">Payment Method</label>
                                        <select class="select2 form-control" name="payment_method_id">
                                            <option selected disabled>Select Account</option>
                                            @foreach ($paymentMethods as $account)
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
                                    <label for="">Amount<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control input-rounded" name="amount">
                                </div>
                                <div class="col-md-6 mb-1">
                                    <label for="">Note</label>
                                    <textarea name="note" class="form-control"></textarea>
                                </div>

                            </div>

                            <div class="mb-1 form-group">
                                <button type="submit" class="btn btn-primary submit">Save</button>
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
        $(document).on('change', '.reseller_id', function() {
            let val = $(this).val();
            $.ajax({
                "url": "{{ route('resellerFunding.get.due') }}",
                "type": "GET",
                "dataType": "json",
                "data": {
                    reseller_id: val
                },
                success: function(data) {
                    $('#due_balance').text('Due Balance ' + data);
                    if (Number(data) > 0) {
                        $('form').attr('action', "{{ route('resellerFunding.paymentStore') }}")
                        $('.submit').attr('disabled', false);
                    } else {
                        $('form').attr('action', '')
                        $('.submit').attr('disabled', true);
                    }
                }
            })
        })
    </script>
@endpush