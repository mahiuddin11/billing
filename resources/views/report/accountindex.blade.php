@extends('admin.master')
@section('content')
    <style>
        input.form-control[type="submit"] {
            background: #000;
            color: #fff;
        }

        .folder-icone {
            color: #D4AC0D;
        }

        input,
        label {
            display: block;
        }
    </style>

    <section id="ajax-datatable">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Cash Book</h4>
                        {{-- <a href="" class="btn btn-dark">Cash Book</a> --}}
                        <hr>

                    </div>
                    <div class="card-datatable table-responsive">
                        <x-alert></x-alert>
                        <div class="treeview w-20 border">
                            <ul class="mb-1 pl-3 pb-2">

                                <form action="{{ route('report.cashbooksearch') }}" method="GET" class="mt-3">
                                    <section>

                                        <div style="float:left;margin-right:20px; width:40%">
                                            <label for="">From Date:</label>
                                            <input type="date" name="from_date" value="{{ $request->from_date ?? '' }}"
                                                class="form-control">
                                        </div>

                                        <div style="float:left; width:40%">
                                            <label for="">To Date:</label>
                                            <input type="date" name="to_date" value="{{ $request->to_date ?? '' }}"
                                                class="form-control">

                                        </div>
                                        <div style="float:left;margin:20px 0px 0px 10px;">

                                            <input type="submit" value="Find" class="form-control">
                                        </div>

                                        <br style="clear:both;" />

                                    </section>
                                </form>

                                {{-- table --}}
                                <div class="table-responsive mt-2">
                                    <table width="100%" class="table table-bordered table-stripped print-font-size"
                                        cellpadding="6" cellspacing="1">
                                        <thead>
                                            <tr>
                                                <td height="25" width="5%"><strong>SL.</strong></td>
                                                <td width="10%"><strong>Date</strong></td>
                                                <td width="10%"><strong>Invoice No</strong></td>
                                                <td width="12%"><strong>Head Name</strong></td>
                                                <td width="12%"><strong>Remark</strong></td>
                                                <td width="10%" align="right">
                                                    <strong>Debit</strong>
                                                </td>
                                                <td width="10%" align="right">
                                                    <strong>Credit</strong>
                                                </td>
                                                <td width="10%" align="right">
                                                    <strong>Balance</strong>
                                                </td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $count = 0;
                                                $total = 0;
                                            @endphp
                                            <tr>
                                                <td colspan="7" align="center">Opening Balance</td>
                                                <td align="right">{{ $newOpeningBalance ?? '0.00' }}
                                                </td>
                                            </tr>
                                            @if (isset($findreports))
                                                @foreach ($findreports as $findreport)
                                                    @php($count++)
                                                    @if (!in_array($findreport->invoice, $getaccountInv) && $findreport->account_id == 3)
                                                        <tr class="table_data">
                                                            <td align="right">
                                                                <strong>{{ $count }}</strong>
                                                            </td>
                                                            <td align="right">
                                                                <strong>{{ $findreport->created_at }}</strong>
                                                            </td>
                                                            <td align="right">
                                                                <strong>{{ $findreport->invoice }}</strong>
                                                            </td>
                                                            <td align="right">
                                                                <strong>{{ $findreport->account->account_name }}</strong>
                                                            </td>
                                                            <td align="right">
                                                                <strong>{{ $findreport->remark }}</strong>
                                                            </td>

                                                            <td align="right">
                                                                @if (in_array($findreport->type, [1, 4, 5, 7]))
                                                                    <strong>
                                                                        {{ $findreport->credit }}</strong>
                                                                @else
                                                                    0
                                                                @endif
                                                            </td>
                                                            <td align="right">
                                                                @if (in_array($findreport->type, [2, 3, 6]))
                                                                    <strong>
                                                                        {{ $findreport->debit }}</strong>
                                                                @else
                                                                    0
                                                                @endif
                                                            </td>
                                                            <td align="right">
                                                                <strong>

                                                                    <strong>
                                                                        {{ in_array($findreport->type, [1, 4, 5, 7]) ? $findreport->debit : -$findreport->credit }}</strong>
                                                                </strong>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                        if (in_array($findreport->type, [1, 4, 5, 7])) {
                                                            $total += $findreport->debit;
                                                        } else {
                                                            $total -= $findreport->credit;
                                                        }
                                                        ?>
                                                    @endif
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="8" class="text-center">No Data Found</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                        <tfoot>
                                            <tr class="table_data">
                                                <td colspan="7" align="right"><strong>Total</strong>
                                                </td>
                                                {{-- <td align="right">
                                                <strong>tk&nbsp;</strong>
                                            </td>
                                            <td align="right">
                                                <strong>tk&nbsp;</strong>
                                            </td> --}}
                                                <td align="right">
                                                    <strong>{{ $total }}</strong>
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
    </section>
@endsection

@section('datatablescripts')
    <!-- Datatable -->
    <script type="text/javascript">
        $(document).ready(function() {
            $('.treeview').mdbTreeview();
        });
    </script>
@endsection
