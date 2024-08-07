@extends('admin.master')

<style>
    body {
        margin-top: 10px;
        background: #eee;
    }

    .invoice {
        border: 1px solid rgb(161, 161, 161)
    }

    * {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
</style>

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
                                    Address : <strong><em>{{ $companyInfo->address ?? 'N/A' }}</em></strong><br>
                                    Email: <strong>{{ $companyInfo->email ?? 'N/A' }}</strong>
                                </address>
                            </div>
                            <!-- /.col -->

                            <!-- /.col -->
                            <div class="col-sm-4 invoice-col" style="text-align:right">
                                <b style="text-decoration: underline">Invoice Info</b><br>
                                {{ $invoice->provider->company_name ?? 'N/A' }} -
                                ({{ $invoice->provider->contact_person ?? 'N/A' }})<br>
                                {{ $invoice->provider->phone ?? 'N/A' }} <br>
                                {{ $invoice->provider->address ?? 'N/A' }}

                            </div>
                            <!-- /.col -->
                        </div><br>
                        <!-- /.row -->

                        <!-- Table row -->
                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table  table-bordered">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Item</th>
                                            <th>description</th>
                                            <th>Unit</th>
                                            <th>Qty</th>
                                            <th>Rate</th>
                                            <th>Vat</th>
                                            <th>From date</th>
                                            <th>To date</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $total = 0;
                                        @endphp
                                        @foreach ($invoice->billDetails as $detail)
                                            <tr>
                                                <td>{{ 1 }}</td>
                                                <td>{{ $detail->getItem->name }}</td>
                                                <td>{{ $detail->description }}</td>
                                                <td>{{ $detail->unit }}</td>
                                                <td>{{ $detail->qty }}</td>
                                                <td>{{ $detail->rate }}</td>
                                                <td>{{ $detail->vat }}</td>
                                                <td>{{ $detail->from_date }}</td>
                                                <td>{{ $detail->to_date }}</td>
                                                <td>{{ $detail->total }}</td>
                                            </tr>
                                            @php
                                                $total += $detail->total;
                                            @endphp
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="9" class="text-right">Total :
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
