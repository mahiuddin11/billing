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
                        <h4 class="card-title">Trial Balance</h4>
                        {{-- <a href="" class="btn btn-dark">Cash Book</a> --}}
                        <hr>

                    </div>
                    <div class="card-datatable ">
                        <div class="treeview w-20 border">
                            <ul class="mb-1 pl-3 pb-2">
                                <form action="{{ route('report.trialbalance') }}" method="post" class="mt-3">
                                    @csrf
                                    <section>

                                        <div style="float:left;margin-right:20px; width:40%">
                                            <label for="">From Date:</label>
                                            <input type="date" value="{{ $request->from_date }}" name="from_date"
                                                class="form-control">
                                        </div>

                                        <div style="float:left; width:40%">
                                            <label for="">To Date:</label>
                                            <input type="date" value="{{ $request->to_date }}" name="to_date"
                                                class="form-control">

                                        </div>
                                        <div style="float:left;margin:20px 0px 0px 10px;">

                                            <input type="submit" value="Find" class="form-control">
                                        </div>

                                        <br style="clear:both;" />

                                    </section>
                                </form>
                                <div class="table-responsive mt-2">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="text-center" height="25" width="5%"><strong>Code.</strong>
                                                </th>
                                                <th class="text-center" width="10%"><strong>Account Name</strong></th>
                                                <th class="text-center" width="12%"><strong>Debit</strong></th>
                                                <th class="text-center" width="12%"><strong>Credit</strong></th>
                                                {{-- <td width="12%"><strong>Balance</strong></td> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $total = 0;
                                                $debit = 0;
                                                $credit = 0;
                                            @endphp
                                            @if (isset($findreports))
                                                @foreach ($findreports as $findreport)
                                                    <tr>
                                                        <td>{{ $findreport->head_code ?? '0000' }}</td>
                                                        <td>{{ $findreport->account_name ?? 'N/A' }}
                                                        </td>
                                                        <td class="text-right">{{ $findreport->debit ?? 0.0 }}</td>
                                                        <td class="text-right">{{ $findreport->credit ?? 0.0 }}</td>
                                                        <?php
                                                        $account = $findreport->debit - $findreport->credit;
                                                        $total += $account;
                                                        $debit += $findreport->debit;
                                                        $credit += $findreport->credit;
                                                        ?>
                                                        {{-- <td>{{ $account }}</td> --}}
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
                                                <td colspan="2" align="right"><strong>Total</strong>
                                                </td>
                                                <td align="right">
                                                    <strong>{{ $debit }}&nbsp;</strong>
                                                </td>
                                                <td align="right">
                                                    <strong>{{ $credit }}&nbsp;</strong>
                                                </td>
                                                {{-- <td align="right">
                                                <strong>{{ $total }}</strong>
                                            </td> --}}
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
