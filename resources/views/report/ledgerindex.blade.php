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
                        <h4 class="card-title">Ledger Book</h4>
                        {{-- <a href="" class="btn btn-dark">Cash Book</a> --}}
                        <hr>

                    </div>
                    <div class="card-datatable ">
                        <div class="treeview w-20 border">
                            <ul class="mb-1 pl-3 pb-2">
                                <form action="{{ route('report.ledgersearch') }}" method="post" class="mt-3">
                                    @csrf
                                    <section>
                                        <div style="float:left;margin-right:20px; width:25%">
                                            <label for="">Select Account:</label>
                                            <select name="account_id" class="custom-select select2" id="">
                                                <option disabled selected>Select Account</option>
                                                @foreach ($accounts as $account)
                                                    <option {{ $request->account_id == $account->id ? 'selected' : '' }}
                                                        value="{{ $account->id }}">{{ $account->account_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div style="float:left;margin-right:20px; width:25%">
                                            <label for="">From Date:</label>
                                            <input type="date" value="{{ $request->from_date }}" name="from_date"
                                                class="form-control">
                                        </div>

                                        <div style="float:left; width:30%">
                                            <label for="">To Date:</label>
                                            <input type="date" value="{{ $request->to_date }}" name="to_date"
                                                class="form-control">
                                        </div>
                                        <div style="float:left;margin:20px 0px 0px 10px;">
                                            <button type="submit" class="btn btn-info">Submit</button>
                                        </div>

                                        <br style="clear:both;" />

                                    </section>
                                </form>
                                <div class="table-responsive mt-2">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <td height="25" width="5%"><strong>SL.</strong></td>
                                                <td width="10%"><strong>Date Transaction</strong></td>
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
                                                $total = 0;
                                                $debit = 0;
                                                $credit = 0;
                                                $count = 0;
                                            @endphp
                                            <tr>
                                                <td colspan="7" align="center">Opening Balance</td>
                                                <td align="right">{{ $newOpeningBalance ?? '0.00' }}
                                                </td>
                                            </tr>
                                            @if (isset($findreports))
                                                @foreach ($findreports as $findreport)
                                                    @php($count++)
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
                                                            <?php
                                                            $accounthead = App\Models\AccountTransaction::where('invoice', $findreport->invoice)
                                                                ->where('account_id', '!=', $findreport->account_id)
                                                                ->first();
                                                            ?>

                                                            @if (!empty($accounthead->account_id))
                                                                <strong>{{ $accounthead->account->account_name ?? 'N/A' }}
                                                                    @if ($accounthead->account_id == 10)
                                                                       <span class="text-success"> {{ $accounthead->customer->username ?? '' }}</span>
                                                                    @endif </strong>
                                                            @endif
                                                        </td>
                                                        <td align="right">
                                                            <strong>{{ $findreport->remark }}</strong>
                                                        </td>

                                                        <td align="right">
                                                            <strong> {{ $findreport->credit ?? 0 }}</strong>
                                                        </td>
                                                        <td align="right">
                                                            <strong> {{ $findreport->debit ?? 0 }}</strong>
                                                        </td>
                                                        <td align="right">
                                                            <strong>
                                                                <?php
                                                                if ($findreport->type == 1) {
                                                                    $amount = $findreport->credit - $findreport->debit;
                                                                } else {
                                                                    $amount = $findreport->debit - $findreport->credit;
                                                                }
                                                                $debit += $findreport->debit;
                                                                $credit += $findreport->credit;
                                                                $total = $debit - $credit;
                                                                ?>

                                                                <strong> {{ $total }}</strong>
                                                            </strong>

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
                                                <td colspan="5" align="right"><strong>Total</strong>
                                                </td>
                                                <td align="right">
                                                    <strong>{{ $debit }} &nbsp;</strong>
                                                </td>
                                                <td align="right">
                                                    <strong> {{ $credit }} &nbsp;</strong>
                                                </td>
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
