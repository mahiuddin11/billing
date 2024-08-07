@extends('admin.master')
@section('content')

    <div class="">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            {{-- <div class="content-header row">
        </div> --}}
            <div class="content-body">
                <section class="invoice-preview-wrapper">
                    <div class="row invoice-preview">
                        <!-- Invoice -->
                        <div class="col-xl-9 col-md-8 col-12">
                            <div class="card invoice-preview-card" id="printableArea">
                                <div class="card-body invoice-padding pb-0">
                                    <!-- Header starts -->
                                    <div class="row">
                                        <div class="logo-wrapper">
                                            <h3 class="text-primary invoice-logo">{!! auth()->user()->company->invoice_logo ?? '' !!}
                                            </h3>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6 col-6">
                                            <h6 class="mb-2">Invoice To:</h6>
                                            <h4 class="invoice-title">
                                                Invoice
                                                <span class="invoice-number">#
                                                    @php echo invoiceNumber($billing->id) @endphp
                                                </span>
                                            </h4>
                                            <div class="invoice-date-wrapper">
                                                <p class="mb-25">Customer Name:
                                                    {{ optional($billing->getCustomer)->name ?? 'N/A' }}</p>
                                                <p class="card-text mb-25">Address :
                                                    {{ optional($billing->getCustomer)->address ?? 'N/A' }}</p>
                                                <p class="card-text mb-25">Email:
                                                    {{ optional($billing->getCustomer)->email ?? 'N/A' }}</p>
                                                <p class="card-text mb-25">Mobile: {{ $billing->customer_phone ?? 'N/A' }}
                                                </p>
                                            </div>

                                        </div>

                                        <div class="col-md-6 col-6 text-right mt-3">
                                            <p class="card-text mb-25" style="color: #000;">
                                                Support: {{ auth()->user()->company->phone }}<br>
                                                Address: {{ auth()->user()->company->address }} <br>
                                                Email: {{ auth()->user()->company->email }} <br>
                                                web: {{ auth()->user()->company->website }} </p>


                                        </div>

                                    </div>
                                    <!-- Header ends -->
                                </div>

                                <!-- Address and Contact starts -->
                                <div class="card-body invoice-padding pt-0">
                                    <div class="row" id="table-bordered">
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Sl No</th>
                                                                <th>Description</th>
                                                                <th>Month</th>
                                                                <th>Rate</th>
                                                                <th>Payed Amount</th>
                                                                <th>Due</th>
                                                            </tr>
                                                        </thead>
                                                        @if ($customerPaymentDetails)
                                                            <tbody>
                                                                @php
                                                                    $due = 0;
                                                                @endphp
                                                                @if ($customerPaymentDetails->isEmpty())
                                                                    <tr>
                                                                        <td colspan="9" class="text-center">No Data Found
                                                                        </td>
                                                                    </tr>
                                                                @else
                                                                    @foreach ($customerPaymentDetails as $key => $customer)
                                                                        @php
                                                                            $due += $customer->customer_billing_amount - $customer->pay_amount;
                                                                        @endphp
                                                                        <tr>
                                                                            <td>{{ $key + 1 }}</td>
                                                                            <td>Internet Bill
                                                                            </td>
                                                                            <td>{{ Carbon\Carbon::parse($customer->date_)->format('F-Y') }}
                                                                            </td>
                                                                            <td>{{ $customer->customer_billing_amount }}
                                                                            </td>
                                                                            <td>{{ $customer->pay_amount ?? 0 }}
                                                                            </td>
                                                                            <td>
                                                                                {{ $customer->customer_billing_amount - $customer->pay_amount }}
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach

                                                                    @foreach ($serviceCharges as $key => $serviceCharge)
                                                                        @php
                                                                            $due += $serviceCharge->amount - $serviceCharge->paid_amount;
                                                                        @endphp
                                                                        @if ($serviceCharge->amount > $serviceCharge->paid_amount)
                                                                            <tr>
                                                                                <td>{{ $key + 1 }}</td>
                                                                                <td>{{ $serviceCharge->description }}
                                                                                </td>
                                                                                <td>{{ Carbon\Carbon::parse($serviceCharge->date)->format('F-Y') }}
                                                                                </td>
                                                                                <td>{{ $serviceCharge->amount }}
                                                                                </td>
                                                                                <td>{{ $serviceCharge->paid_amount ?? 0 }}
                                                                                </td>
                                                                                <td>
                                                                                    {{ $serviceCharge->amount - $serviceCharge->paid_amount }}
                                                                                </td>
                                                                            </tr>
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            </tbody>
                                                        @endif
                                                        <tfoot>
                                                            <tr>
                                                                <th colspan="5" class="text-right">Total: </th>
                                                                <td>{{ $due }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th colspan="4" class="text-left text-danger">Note:
                                                                    Please
                                                                    Pay your bill
                                                                    before this date</th>
                                                                <th class="text-canter">Expiry Date:</th>
                                                                <td>{{ $billing->getCustomer->exp_date ?? 'N/A' }}</td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class=" invoice-spacing mt-0 ml-2 mb-2">
                                    <p class="card-text mb-25">Payment Instructions:</p>
                                    <p class="card-text mb-25">1. The bill is to be paid within the due date by cash or
                                        Cheque in favour of "{{$companyInif->company_name}}</p>
                                    <p class="card-text mb-25">2. Bank Account Information: {{$companyInif->account_info ?? ''}}</p>
                                    <p class="card-text mb-25">3. Mobile Banking Number : {{$companyInif->mobile_banking ?? ""}} </p>
                                    <p class="card-text mb-25">5. Failure to make payment within the due date may result
                                        disconnection of service without prior information.</p>
                                    <p class="card-text mb-25">6. This is Computer generated bill, it does not require
                                        signature.</p>
                                </div>

                                <div
                                    class="d-flex justify-content-between flex-md-row flex-column invoice-spacing mt-0 ml-2">
                                    <div class="mr-2">

                                        <p class="card-text mb-25"> {{$companyInif->email ?? ""}} &nbsp;
                                            {{$companyInif->website ?? ""}}</p>
                                    </div>
                                </div>

                                <hr class="invoice-spacing" />

                                <!-- Invoice Note starts -->
                                <div class="card-body invoice-padding pt-0">
                                    <div class="row">
                                        <div class="col-12">
                                            <span class="font-weight-bold">Note:</span>
                                            <span>It was a pleasure working with you and your team. We hope you will keep us
                                                in mind for future freelance
                                                projects. Thank You!</span>
                                        </div>
                                    </div>
                                </div>
                                <!-- Invoice Note ends -->
                            </div>
                        </div>
                        <!-- /Invoice -->

                        <!-- Invoice Actions -->
                        <div class="col-xl-3 col-md-4 col-12 invoice-actions mt-md-0 mt-2">
                            <div class="card">
                                <div class="card-body">
                                    <!-- <button class="btn btn-primary btn-block mb-75" data-toggle="modal"
                                                                                                                                                                                                                    data-target="#send-invoice-sidebar">
                                                                                                                                                                                                                    Send Invoice
                                                                                                                                                                                                                </button> -->
                                    <button
                                        class="btn btn-outline-secondary btn-block btn-download-invoice mb-75">Download</button>
                                    <a class="btn btn-outline-secondary btn-block mb-75 printPage" href="#"
                                        target="_blank" onclick="printDiv('printableArea')">
                                        Print
                                    </a>

                                    <!-- <a class="btn btn-outline-secondary btn-block mb-75" href="./app-invoice-edit.html">
                                                                                                                                                                                                                    Edit </a> -->
                                    <!-- <button class="btn btn-success btn-block" data-toggle="modal"
                                                                                                                                                                                                                    data-target="#add-payment-sidebar">
                                                                                                                                                                                                                    Add Payment
                                                                                                                                                                                                                </button> -->
                                </div>
                            </div>
                        </div>
                        <!-- /Invoice Actions -->
                    </div>
                </section>

                <!-- Send Invoice Sidebar -->

                <!-- /Send Invoice Sidebar -->

                <!-- Add Payment Sidebar -->
                <div class="modal modal-slide-in fade" id="add-payment-sidebar" aria-hidden="true">
                    <div class="modal-dialog sidebar-lg">
                        <div class="modal-content p-0">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                            <div class="modal-header mb-1">
                                <h5 class="modal-title">
                                    <span class="align-middle">Add Payment</span>
                                </h5>
                            </div>
                            <div class="modal-body flex-grow-1">
                                <form>
                                    <div class="form-group">
                                        <input id="balance" class="form-control" type="text"
                                            value="Invoice Balance: 5000.00" disabled />
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="amount">Payment Amount</label>
                                        <input id="amount" class="form-control" type="number" placeholder="$1000" />
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="payment-date">Payment Date</label>
                                        <input id="payment-date" class="form-control date-picker" type="text" />
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="payment-method">Payment Method</label>
                                        <select class="form-control" id="payment-method">
                                            <option value="" selected disabled>Select payment method</option>
                                            <option value="Cash">Cash</option>
                                            <option value="Bank Transfer">Bank Transfer</option>
                                            <option value="Debit">Debit</option>
                                            <option value="Credit">Credit</option>
                                            <option value="Paypal">Paypal</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="payment-note">Internal Payment Note</label>
                                        <textarea class="form-control" id="payment-note" rows="5" placeholder="Internal Payment Note"></textarea>
                                    </div>
                                    <div class="form-group d-flex flex-wrap mb-0">
                                        <button type="button" class="btn btn-primary mr-1"
                                            data-dismiss="modal">Send</button>
                                        <button type="button" class="btn btn-outline-secondary"
                                            data-dismiss="modal">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Add Payment Sidebar -->

            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script>
        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            window.print();

            document.body.innerHTML = originalContents;
        }
    </script>
@endsection
