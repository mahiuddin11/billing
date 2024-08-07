@extends('admin.master')

@section('content')
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ $billing->getCustomer->name }}({{ $billing->getCustomer->username }})
                        {{ $page_heading ?? 'Payment Information' }}</h4>
                    <a href="{{ $back_url ?? 'javascript:;' }}" class="btn btn-dark">Back</a>
                </div>
                <hr>
                <div class="card-body">

                    <x-alert></x-alert>

                    <div class="basic-form">

                        <div class="col-lg-12">
                            <h4 class="card-title">Due Information</h4>
                            <div class="table-responsive">
                                <form id="payment-form" action="" method="get">
                                    @csrf
                                    <table class="table table-bordered table-responsive-sm">
                                        <thead class="thead-primary">
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col"><input type="checkbox" id="select-all"></th>
                                                <th scope="col">Name</th>
                                                <th scope="col">Invoice</th>
                                                {{-- <th scope="col">Address</th> --}}
                                                <th scope="col">Mobile No</th>
                                                <th scope="col">Speed</th>
                                                <th scope="col">Date</th>
                                                <th scope="col">Amount</th>
                                                <th scope="col">Due</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $setPartial = 0;
                                            @endphp
                                            @foreach ($customerDetails as $key => $customer)
                                                @php
                                                    $setPartial += $customer->partial;
                                                @endphp
                                                <tr>
                                                    <th>{{ $key + 1 }}</th>
                                                    <td><input type="checkbox" class="customer-checkbox"
                                                            data-amount="{{ $customer->partial ?? $customer->customer_billing_amount - $customer->pay_amount }}"
                                                            value="{{ $customer->id }}"></td>
                                                    <td>{{ $customer->getCustomer->name ?? 'N/A' }}</td>
                                                    <td>{{ $customer->invoice_name ?? 'N/A' }}</td>
                                                    {{-- <td>{{ $customer->getCustomer->address ?? 'N/A' }}</td> --}}
                                                    <td>{{ $customer->getCustomer->phone ?? 'N/A' }}</td>
                                                    <td>{{ $customer->getCustomer->speed ?? 'N/A' }}</td>
                                                    <td>{{ Carbon\Carbon::parse($customer->date_)->format('F-Y') }}</td>
                                                    <td class="bill_amount">{{ $customer->customer_billing_amount }}</td>
                                                    <td>{{ $customer->partial ?? $customer->customer_billing_amount - $customer->pay_amount }}
                                                    </td>
                                                    @if ($customer->status == 'unpaid')
                                                        <td><a href="{{ route('billcollect.duepay', $customer->id) }}"
                                                                type="button" data-toggle='modal' data-target='#default'
                                                                class="btn-info btn-sm paymodel">Pay</a></td>
                                                    @elseif($customer->status == 'partial')
                                                        <td><a href="{{ route('billcollect.partial', $customer->id) }}"
                                                                type="button" data-toggle='modal' data-target='#default'
                                                                class="btn-info btn-sm paymodel">Partial</a></td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td></td>
                                                <td><button type="button" id="openPaymentModal"
                                                        class="btn btn-primary btn-sm">Pay
                                                    </button></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </form>
                            </div>
                        </div>

                        <hr>
                        <div class="col-lg-12">
                            <h4 class="card-title">Paid Bill</h4>
                            <table class="table table-bordered table-responsive">
                                <thead class="thead-primary">
                                    <tr>
                                        <th scope="col-2">#</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Invoice</th>
                                        <th scope="col">Month</th>
                                        <th scope="col">Payment Method</th>
                                        <th scope="col">Description</th>
                                        <th scope="col">Discount</th>
                                        <th scope="col">Pay Amount</th>
                                        <th scope="col">Billing By</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalpay = 0;
                                    @endphp
                                    @foreach ($customerPaymentDetails as $key => $customer)
                                        @php
                                            $totalpay += $customer->pay_amount;
                                        @endphp
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ Carbon\Carbon::parse($customer->updated_at)->format('h:i d,M,Y') }}
                                            </td>
                                            <td>{{ $customer->invoice_name ?? 'N/A' }}</td>
                                            <td>{{ Carbon\Carbon::parse($customer->date_)->format('F') }}</td>
                                            <td>
                                                @if ($customer->payment_method_id == 500)
                                                    <p>Advance Payed
                                                    </p>
                                                @else
                                                    <p>{{ $customer->PaymentMethod->head_code }}-{{ $customer->PaymentMethod->account_name }}
                                                    </p>
                                                @endif
                                            </td>
                                            <td>{!! $customer->description !!}</td>
                                            <td>{{ $customer->discount ?? '00' }}</td>
                                            <td>{{ $customer->pay_amount ?? '00' }}
                                            </td>
                                            <td>{{ $customer->getBillinfBy->name ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                @if ($customerPaymentDetails->isNotEmpty())
                                    <tfoot>
                                        <tr>
                                            <td colspan="7" class="text-right">Total:</td>
                                            <td>{{ $totalpay }} TK</td>
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
                        <form id="multiple-payment-form" action="" method="post">
                            @csrf

                            <div class="row">
                                <div class="col-md-12">
                                    <h5>Select Method</h5>
                                    <select name="payment_method_id" class="select2" id="payment_method_lo">
                                        <option disabled selected>Select Payment</option>
                                        @foreach ($paymentMethods as $account)
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
                                                                        ---{{ $subaccount3->account_name }}</option>
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
                                    <textarea name="remarks" class="form-control" cols="20" rows="5"></textarea>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-check">
                                        <input class="form-check-input" name="extend" type="checkbox" value="yes"
                                            id="flexCheckDefault">
                                        <label class="form-check-label" for="flexCheckDefault">
                                            Extend Date?
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
    <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLabel">Total Payment Amount</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="totalAmount">Total Amount: 0</p>
                    <form id="multiple-payment-form" action="{{ route('billcollect.multiplepay') }}" method="post">
                        @csrf
                        <input type="hidden" name="selected_customers" id="selected-customers">
                        <input type="hidden" name="total_amount" id="total-amount-hidden">
                        <div class="col-md-12">
                            <h5>Select Method</h5>
                            <select name="payment_method_id" class="select2" id="payment_method">
                                <option disabled selected>Select Payment</option>
                                @foreach ($paymentMethods as $account)
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
                                                                ---{{ $subaccount3->account_name }}</option>
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 mt-1">
                            <h5>How many months you want to extend ({{ $billing->getCustomer->exp_date }})</h5>
                            <input type="text" value="0" name="extend_month" class="form-control">

                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Pay Bill</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('openPaymentModal').addEventListener('click', () => {
            const selectedCustomers = Array.from(document.querySelectorAll('.customer-checkbox:checked'))
                .map(checkbox => ({
                    id: checkbox.value,
                    amount: parseFloat(checkbox.dataset.amount)
                }));

            const totalAmount = selectedCustomers.reduce((sum, customer) => sum + customer.amount, 0);

            document.getElementById('totalAmount').textContent = `Total Amount: ${totalAmount.toFixed(2)}`;
            document.getElementById('selected-customers').value = JSON.stringify(selectedCustomers.map(customer =>
                customer.id));
            document.getElementById('total-amount-hidden').value = totalAmount;

            $('#paymentModal').modal('show');
        });

        $(document).on('click', '.paymodel', function() {
            let url = $(this).attr('href');
            $('form').attr('action', url);
        })

        function discounts(e) {
            let amount = "{{ $data->getCustomer->bill_amount }}";
            if (Number(amount) < e) {
                return $('.discounts_val').val('');
            }
            let total = amount - e;
            document.getElementById('service_amount').value = total;
        }

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
