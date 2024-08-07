@extends('admin.master')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Bill History</h4>
                    {{-- <a href="" class="btn btn-dark">Cash Book</a> --}}
                    <hr>

                </div>
                <div class="card-datatable table-responsive">
                    <x-alert></x-alert>
                    <div class="treeview w-20 border">
                        <ul class="mb-1 pl-3 pb-2">

                            <form action="{{ route('reports.bill.index') }}" method="POST" class="mt-3">
                                @csrf
                                <section>
                                    <div style="float:left;margin-right:20px; width:25%">
                                        <label for="">Customer:</label>
                                        <select name="customer" class="form-control select2" id="">
                                            <option selected value="all">All</option>
                                            @foreach ($customers as $customer)
                                                <option {{ $request->customer == $customer->id ? 'selected' : '' }}
                                                    value="{{ $customer->id }}">{{ $customer->client_id }}
                                                    {{ $customer->name }}
                                                    - ({{ $customer->username }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div style="float:left;margin-right:20px; width:25%">
                                        <label for="">Method:</label>
                                        <select name="method" class="form-control select2" id="">
                                            <option selected value="all">All</option>
                                            @foreach ($paymentMethods as $value)
                                                <option {{ $request->method == $value->id ? 'selected' : '' }}
                                                    value="{{ $value->id }}">
                                                    {{ $value->account_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div style="float:left;margin-right:20px; width:20%">
                                        <label for="">Month:</label>
                                        <input type="month" value="{{ $request->month }}" class="form-control"
                                            name="month">
                                    </div>

                                    <div style="float:left;margin-right:20px; width:15%">
                                        <label for="">Status:</label>
                                        <select name="status" class="form-control" id="">
                                            <option selected value="all">All</option>
                                            <option {{ $request->status == 'paid' ? 'selected' : '' }} value="paid">Paid
                                            </option>
                                            <option {{ $request->status == 'partial' ? 'selected' : '' }} value="partial">
                                                partial</option>
                                            <option {{ $request->status == 'unpaid' ? 'selected' : '' }} value="unpaid">
                                                unpaid</option>
                                        </select>
                                    </div>
                                    <div style="float:left;margin-right:20px; width:14%">
                                        <label for="">Type:</label>
                                        <select name="type" class="form-control" id="">
                                            <option selected value="all">All</option>
                                            <option {{ $request->type == '3' ? 'selected' : '' }} value="3">PPPOP
                                            </option>
                                            <option {{ $request->type == '1' ? 'selected' : '' }} value="1">STATIC
                                            </option>
                                        </select>
                                    </div>


                                    <div style="float:left;margin:20px 0px 0px 10px;">

                                        <input type="submit" value="Find" class="form-control">
                                    </div>

                                    <br style="clear:both;" />

                                </section>
                                {{-- <div style="margin-top:10px">
                                    <input type="button" class="btn btn-primary" style="margin-left: 200px" onclick="printDiv('billReport')"
                                        value="Download" />
                                </div> --}}

                                <div class="csv-btn  mt-1 mr-1">
                                    <button type="btn" class="btn btn-primary">CSV</button>
                                </div>
                            </form>

                            {{-- table --}}
                            <div class="table-responsive mt-2" id="billReport">
                                <table width="100%" id="table"
                                    class="table table-bordered table-stripped print-font-size" cellpadding="6"
                                    cellspacing="1">
                                    <thead>
                                        <tr>
                                            <td height="25" width="5%"><strong>SL.</strong></td>
                                            <td width="10%"><strong>Date</strong></td>
                                            <td width="10%"><strong>Customer</strong></td>
                                            <td width="10%"><strong>Address</strong></td>
                                            <td width="10%"><strong>Phone</strong></td>
                                            <td width="12%"><strong>Head Name</strong></td>
                                            <td width="12%"><strong>Remark</strong></td>
                                            <td width="10%" align="right">
                                                <strong>Billing</strong>
                                            </td>
                                            <td width="10%" align="right">
                                                <strong>Payed</strong>
                                            </td>
                                            <td width="10%" align="right">
                                                <strong>Discount</strong>
                                            </td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $count = 0;
                                            $bill = 0;
                                            $payed = 0;
                                            $discount = 0;
                                        @endphp

                                        @if (isset($monthlybill))
                                            @foreach ($monthlybill as $values)
                                                @php($count++)
                                                <tr class="table_data">
                                                    <td align="right">
                                                        <strong>{{ $count }}</strong>
                                                    </td>
                                                    <td>
                                                        <strong>{{ date('m-Y', strtotime($values->date_)) }}</strong>
                                                    </td>
                                                    <td>
                                                        {{ $values->name ?? '' }}
                                                        {{ $values->username ?? '' }}
                                                    </td>
                                                    <td>
                                                        {{ $values->address ?? '' }}
                                                    </td>
                                                    <td>
                                                        {{ $values->phone ?? '' }}
                                                    </td>
                                                    <td align="right">
                                                        <strong>{{ $values->PaymentMethod->account_name ?? '' }}</strong>
                                                    </td>
                                                    <td align="right">
                                                        <strong>{{ $values->description }}</strong>
                                                    </td>
                                                    <?php
                                                    $bill += intval($values->customer_billing_amount) ?? 0;
                                                    $payed += intval($values->pay_amount) ?? 0;
                                                    $discount += intval($values->discount) ?? 0;
                                                    ?>
                                                    <td align="right">
                                                        {{ intval($values->customer_billing_amount) ?? 0 }}
                                                    </td>
                                                    <td align="right">
                                                        {{ intval($values->pay_amount) ?? 0 }}
                                                    </td>
                                                    <td align="right">
                                                        {{ intval($values->discount) ?? 0 }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="8" class="text-center">No Data Found</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                    <tfoot>
                                        <tr class="table_data">
                                            <td colspan="7" align="right">
                                            </td>
                                            {{-- <td align="right">
                                                <strong>tk&nbsp;</strong>
                                            </td>
                                            <td align="right">
                                                <strong>tk&nbsp;</strong>
                                            </td> --}}
                                            <td align="right">
                                                <strong>{{ $bill ?? '00' }}</strong>
                                            </td>
                                            <td align="right">
                                                <strong>{{ $payed ?? '00' }}</strong>
                                            </td>
                                            <td align="right">
                                                {{ $discount ?? '00' }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('admin_assets/js/scripts/sheet.js') }}"></script>
    <script type="text/javascript">
        var btnCsv = document.querySelectorAll('.csv-btn button')[0]
        btnCsv.onclick = () => exportData('csv')

        function exportData(type) {
            const fileName = 'exported-sheet.' + type
            const table = document.getElementById("table")
            const wb = XLSX.utils.table_to_book(table)
            XLSX.writeFile(wb, fileName)
        }
    </script>
@endsection
