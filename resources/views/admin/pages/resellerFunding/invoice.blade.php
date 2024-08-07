@extends('admin.master')

@section('content')
    <div class="row">
        <div class="col-md-12">

            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Invoice</h3>

                </div>
                <div class="card-body">
                    <div class="row no-print">
                        <div class="col-12">
                            <a onclick='printDiv("DivIdToPrint")' target="_blank" class="btn btn-default float-right my-2"><i
                                    class="fas fa-print"></i>
                                Print</a>
                        </div>
                    </div>
                    <div class="invoice p-3 mb-3" id="DivIdToPrint">
                        <!-- title row -->
                        <!-- info row -->
                        <div class="row invoice-info">
                            <div class="col-sm-4 " style="">
                                @if (isset($companyInfo->invoice_logo))
                                    {!! $companyInfo->invoice_logo ?? '' !!}
                                @endif
                            </div>
                            <div class="col-sm-4 invoice-col" style="text-align: center">
                                <b style="font-size : 20px">{{ $companyInfo->company_name ?? 'N/A' }}</b>
                                <address>
                                    Phone : <strong>{{ $companyInfo->phone ?? 'N/A' }}</strong><br>
                                    Email: <strong>{{ $companyInfo->email ?? 'N/A' }}</strong>
                                </address>
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4 invoice-col" style="text-align:right">
                                <b style="text-decoration: underline">Invoice Info</b><br>
                                Invoice: {{ str_pad($invoice->id . date('mY', strtotime($resellerCustomerBills->groupBy('date_'))) + 1, 5, "0", STR_PAD_LEFT); }} <br>
                                Name: {{ $invoice->company_name ?? 'N/A' }} <br>
                                Phone: {{ $invoice->phone ?? 'N/A' }} <br>
                                Address : {{ $invoice->address ?? 'N/A' }}<br>
                                Month: {{ $req->year .'-'. date('F', mktime(null, null, null, $req->month)) }}
                            </div>

                        </div><br>
                        <!-- /.row -->

                        <!-- Table row -->
                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table  table-bordered">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Full Name</th>
                                            <th>Username</th>
                                            <th>Package</th>
                                            <th>Expire Date</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $total = 0;
                                        @endphp
                                        @foreach ($resellerCustomerBills as $key => $resellerCustomerBill)

                                            @if ($resellerCustomerBill->charge != '0' && isset($resellerCustomerBill->customer) && ($resellerCustomerBill->customer->disabled ?? "false") == "false")
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $resellerCustomerBill->customer->name ?? '' }}</td>
                                                    <td>{{ $resellerCustomerBill->customer->username ?? '' }}</td>
                                                    <td>{{ $resellerCustomerBill->customer->getMProfile->name ?? 'N/A' }}
                                                    <td>{{ $resellerCustomerBill->customer->exp_date ?? 'N/A' }}
                                                    </td>
                                                    <td>{{ $resellerCustomerBill->charge }}</td>
                                                </tr>
                                                @php
                                                    $package2 = App\Models\Package2::where('');
                                                    $total += $resellerCustomerBill->charge ?? 0;
                                                @endphp
                                            @endif
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="5" class="text-right">Total :
                                            </th>
                                            <th>{{ $total }}
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>



                            <div class="col-md-4 text-center float-left">
                                <br>
                                <br>

                                <p>Received by:_____________<br />
                                    Date:____________________
                                </p>
                            </div>
                            <div class="col-md-4 text-center">
                            </div>
                            <div class="col-md-4 text-center float-right">
                                <br>
                                <br>
                                <p>Authorized by:________________<br />
                                    Date:_________________</p>
                            </div>

                            <hr>


                            <div class="col-md-12 bg-success text-white" style="text-align: center">
                                Thank you for choosing {{ $companyInfo->company_name }} Company products.
                                We believe you will be satisfied by our services.
                            </div>
                            <!-- /.col -->
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->

                        <!-- this row will not appear when printing -->

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
<script>
    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();

        document.body.innerHTML = originalContents;
    }
</script>
