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
                        <h4 class="card-title">Income statement</h4>
                        {{-- <a href="" class="btn btn-dark">Cash Book</a> --}}
                        <hr>

                    </div>
                    <div class="card-datatable ">
                        <div class="treeview w-20 border">
                            <ul class="mb-1 pl-3 pb-2">
                                <form action="{{ route('report.incomestatement') }}" method="post" class="mt-3">
                                    @csrf
                                    <section>
                                        <div style="float:left;margin-right:20px; width:25%">
                                            <label for="">Year:</label>
                                            <select name="yearpicker" name="year" class="form-control"
                                                id="yearpicker"></select>
                                        </div>
                                        <div style="float:left;margin:20px 0px 0px 10px;">
                                            <button type="submit" class="btn btn-info">Submit</button>
                                        </div>

                                        <br style="clear:both;" />

                                    </section>
                                </form>
                                <div class="table-responsive mt-2">
                                    <h3 class="text-center">Income Statement for {{ $request->yearpicker }}</h3>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <td width="10%"><strong>Particulars</strong></td>
                                                <td width="10%" align="right">
                                                    <strong>Amount</strong>
                                                </td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $incomeTotal = 0;
                                            @endphp
                                            @if (isset($incomes))
                                                @foreach ($incomes as $income)
                                                    <tr class="table_data">
                                                        <td>
                                                            <strong>{{ $income->account->account_name }}({{ $income->account->head_code ?? '0000' }})</strong>
                                                        </td>
                                                        <td align="right">
                                                            {{ $income->credit }}
                                                        </td>
                                                    </tr>
                                                    <?php
                                                    $incomeTotal += $income->credit;
                                                    ?>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="8" class="text-center">No Data Found</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                        <tfoot>
                                            <tr class="table_data">
                                                <td align="right"><strong>Total Income</strong>
                                                </td>

                                                <td align="right">
                                                    <strong>{{ $incomeTotal }}</strong>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <div class="table-responsive mt-2">
                                    <h3 class="text-center">Expenses Statement for {{ $request->yearpicker }}</h3>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <td width="10%"><strong>Particulars</strong></td>
                                                <td width="10%" align="right">
                                                    <strong>Amount</strong>
                                                </td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $expensTotal = 0;
                                            @endphp
                                            @if (isset($expense))
                                                @foreach ($expense as $expens)
                                                    <tr class="table_data">
                                                        <td>
                                                            <strong>{{ $expens->account->account_name }}({{ $expens->account->head_code ?? '0000' }})</strong>
                                                        </td>
                                                        <td align="right">
                                                            {{ $expens->debit }}
                                                        </td>
                                                    </tr>
                                                    <?php
                                                    $expensTotal += $expens->debit;
                                                    ?>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="8" class="text-center">No Data Found</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                        <tfoot clas>
                                            <tr class="table_data">
                                                <th align="right"><strong>Net Profit</strong>
                                                </th>
                                                <th align="right">
                                                    <strong>{{ $incomeTotal - $expensTotal }}</strong>
                                                </th>
                                            </tr>
                                        </tfoot>
                                        <tfoot>
                                            <tr class="table_data">
                                                <td align="right"><strong>Total Expenses</strong>
                                                </td>
                                                <td align="right">
                                                    <strong>{{ $expensTotal }}</strong>
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
    <script>
        var startYear = 2000;
        // $('#yearpicker').append($('<option>Select Year'));
        for (i = new Date().getFullYear(); i > startYear; i--) {
            $('#yearpicker').append($('<option />').val(i).html(i));
        }
    </script>
    <!-- Datatable -->
    <script type="text/javascript">
        $(document).ready(function() {
            $('.treeview').mdbTreeview();
        });
    </script>
@endsection
