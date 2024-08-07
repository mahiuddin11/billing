@extends('admin.master')

@section('content')
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ $page_heading ?? 'Collected Bill' }}</h4>
                    <a href="{{ $back_url ?? 'javascript:;' }}" class="btn btn-dark">Back</a>
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-6">
                            <ul>
                                <li class="font-weight-bold">Name: {{ $billcollected->name }}
                                    ({{ $billcollected->username }})
                                </li>
                                <li class="font-weight-bold">Phone: {{ $billcollected->phone }}</li>
                                <li class="font-weight-bold">Address: {{ $billcollected->address }}</li>
                                <li class="font-weight-bold">Advance: {{ $billcollected->advanced_payment ?? '0.00' }}</li>
                                <li class="font-weight-bold">Expire Date: {{ $billcollected->exp_date }}
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            @if (isset($billcollected->nid_front))
                                <img src="{{ asset('/storage/' . $billcollected->nid_front) }}" alt="nid front image"
                                    width="200" height="100">
                            @endif
                            @if (isset($billcollected->nid_back))
                                <img src="{{ asset('/storage/' . $billcollected->nid_back) }}" alt="nid front image"
                                    width="200" height="100">
                            @endif
                        </div>
                    </div>

                    <div class="basic-form">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-responsive-sm">
                                    <thead class="thead-primary">
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Invoice</th>
                                            <th scope="col">Bill Generate Date</th>
                                            <th scope="col">Month</th>
                                            <th scope="col">Payment Method</th>
                                            <th scope="col">Description</th>
                                            <th scope="col">Discount</th>
                                            <th scope="col">Amount</th>
                                            <th scope="col">Pay</th>
                                            <th scope="col">Payment Date</th>
                                            <th scope="col">Billing By</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    @if ($customerPaymentDetails)
                                        <tbody>
                                            @php
                                                $totalpay = 0;
                                            @endphp
                                            @if ($customerPaymentDetails->isEmpty())
                                                <tr>
                                                    <td colspan="9" class="text-center">No Data Found</td>
                                                </tr>
                                            @else
                                            @foreach ($customerPaymentDetails as $key => $customer)
                                                    @php
                                                        $totalpay += $customer->pay_amount;
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>{{ $customer->invoice_name }}</td>
                                                        <td>{{ Carbon\Carbon::parse($customer->updated_at)->format('d,M,Y') }}
                                                        </td>
                                                        <td>{{ Carbon\Carbon::parse($customer->date_)->format('F-Y') }}
                                                        </td>
                                                        <td>
                                                            @if ($customer->payment_method_id == 500)
                                                                <p>Advance Payed
                                                                </p>
                                                            @elseif($customer->PaymentMethod)
                                                                <p>{{ $customer->PaymentMethod->account_name }}</p>
                                                            @else
                                                                <p class='text-danger'>Not Payed</p>
                                                            @endif
                                                        </td>
                                                        <td>{!! $customer->description ?? 'N/A' !!}</td>
                                                        <td>{{ $customer->discount ?? 0.0 }}</td>
                                                        <td>{{ (int) $customer->customer_billing_amount }}
                                                        <td>{{ (int) $customer->pay_amount ?? 0 }}
                                                        <td>{{ $customer->updated_at }}</td>
                                                        <td>{{ $customer->getBillinfBy->name ?? 'N/A' }}</td>
                                                        @if ($customer->status == 'unpaid')
                                                            <td><a href="{{ route('billcollect.duepay', $customer->id) }}"
                                                                    type="button" data-toggle='modal'
                                                                    data-target='#default'
                                                                    class="btn-info  btn-sm paymodel">Pay</a>
                                                            </td>
                                                        @elseif($customer->status == 'partial')
                                                            <td><a href="{{ route('billcollect.partial', $customer->id) }}"
                                                                    type="button" data-toggle='modal'
                                                                    data-target='#default'
                                                                    class="btn-info  btn-sm paymodel">Partial</a>
                                                            </td>
                                                        @else
                                                            <td><button type="button"
                                                                    class="btn-success btn-sm">Payed</button>
                                                            </td>
                                                        @endif
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    @endif
                                    @if ($customerPaymentDetails->isNotEmpty())
                                        <tfoot>
                                            <tr class="bg-secondary">
                                                <td colspan="8" class="text-right text-white">Total</td>
                                                <td class="text-white">{{ $totalpay }}</td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    @endif

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="basic-modal">
        <div class="modal fade text-left" id="default" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel1">Payment Bill</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <form action="" method="get">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <h5>Select Method</h5>
                                    <select name="payment_method_id" class="select2" id="payment_method">
                                        <option disabled selected>Select Payment</option>
                                        @foreach ($paymentMethods as $account)
                                            <option value="{{ $account->id }}">{{ $account->account_name }}</option>
                                            @if ($account->subAccount->isNotEmpty())
                                                @foreach ($account->subAccount as $subaccount)
                                                    <option value="{{ $subaccount->id }}">
                                                        -{{ $subaccount->account_name }}
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
                                <div class="col-md-12">
                                    <h5>Invoice Name</h5>
                                    <input type="text" name="invoice_name" class="form-control">
                                </div>
                                <div class="col-md-12">
                                    <h5>Bill Amount</h5>
                                    <input type="number" name="amount" class="form-control">
                                </div>
                                <div class="col-md-12">
                                    <h5>Discount</h5>
                                    <input type="number" name="discount" class="form-control">
                                </div>
                                <div class="col-md-12">
                                    <h5>Remarks</h5>
                                    <textarea name="remarks" class="form-control" id="" cols="20" rows="5"></textarea>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-check">
                                        <input class="form-check-input" name="extend" type="checkbox" value="yes"
                                            id="flexCheckDefault">
                                        <label class="form-check-label" for="flexCheckDefault">
                                            Extend Date ?
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Pay Bill</button>
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
        $(document).on('click', '.paymodel', function() {
            let url = $(this).attr('href');
            $('form').attr('action', url);
        })


        function payMonth() {
            let pay = $('#paymentMonth option:selected').attr('payamount');
            let amount = $('#paymentMonth option:selected').attr('amount');
            let total = Number(amount) - (pay ? Number(pay) : 0);
            $('.dueInpute').val(total);
        }

        function payType(e) {
            if (e == "full_pay") {
                $(".payMonth").hide();
                $(".payAmount").hide();
                $(".discountfile").hide();
                $(".extendDate").hide();
            } else if (e == "partial") {
                $(".payMonth").show();
                $(".payAmount").show();
                $(".discountfile").show();
                $(".extendDate").show();

            }
        }
    </script>
@endpush
