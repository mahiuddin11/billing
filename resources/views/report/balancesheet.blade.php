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
                        <h4 class="card-title">Balance Sheet</h4>
                        {{-- <a href="" class="btn btn-dark">Cash Book</a> --}}
                        <hr>

                    </div>
                    <div class="card-datatable ">
                        <div class="treeview w-20 border">
                            <ul class="mb-1 pl-3 pb-2">
                                <form action="{{ route('report.balancesheet') }}" method="post" class="mt-3">
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
                                    <h3 class="text-center">Balance Sheet Statement for {{ $request->yearpicker }}</h3>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <td width="10%"><strong>Particulars</strong></td>
                                                <td width="10%" align="right">
                                                    <strong>Amount</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="10%"><strong>Asset</strong></td>
                                                <td width="10%" align="right">
                                                </td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $assetTotal = 0;
                                            @endphp
                                            @if (isset($assets))
                                                @foreach ($assets as $asset)
                                                    <tr class="table_data">
                                                        <td>
                                                            <strong>{{ $asset->account->account_name }}({{ $asset->account->head_code ?? '0000' }})</strong>
                                                        </td>
                                                        <td align="right">
                                                            {{ $asset->credit }}
                                                        </td>
                                                    </tr>
                                                    <?php
                                                    $assetTotal += $asset->credit;
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
                                                <td align="right"><strong>Total asset</strong>
                                                </td>

                                                <td align="right">
                                                    <strong>{{ $assetTotal }}</strong>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <div class="table-responsive mt-2">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <td width="10%"><strong>Liabilities & Equity</strong></td>
                                                <td width="10%" align="right">
                                                </td>
                                            </tr>

                                        </thead>
                                        <tbody>
                                            @php
                                                $otherTotal = 0;
                                            @endphp
                                            @if (isset($others))
                                                @foreach ($others as $other)
                                                    <tr class="table_data">
                                                        <td>
                                                            <strong>{{ $other->account->account_name }}({{ $other->account->head_code ?? '0000' }})</strong>
                                                        </td>
                                                        <td align="right">
                                                            {{ $other->debit }}
                                                        </td>
                                                    </tr>
                                                    <?php
                                                    $otherTotal += $other->debit;
                                                    ?>
                                                @endforeach
                                                <tr class="table_data">
                                                    <td class="text-left"><strong>Net Profit</strong>
                                                    </td>
                                                    <td class="text-right">
                                                        <strong>{{ $assetTotal - $otherTotal }}</strong>
                                                    </td>
                                                </tr>
                                            @else
                                                <tr class="table_data">
                                                    <td class="text-left"><strong>Net Profit</strong>
                                                    </td>
                                                    <td class="text-right">
                                                        <strong>{{ $assetTotal - $otherTotal }}</strong>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="8" class="text-center">No Data Found</td>
                                                </tr>
                                            @endif
                                        </tbody>

                                        <tfoot>
                                            <tr class="table_data">
                                                <td align="right"><strong>Total Liabilities & Equity</strong>
                                                </td>
                                                <td align="right">
                                                    <strong>{{ $assetTotal - $otherTotal + $otherTotal }}</strong>
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

    <script>
        var startYear = 2000;
        // $('#yearpicker').append($('<option>Select Year'));
        for (i = new Date().getFullYear(); i > startYear; i--) {
            $('#yearpicker').append($('<option />').val(i).html(i));
        }
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('.treeview').mdbTreeview();
        });
    </script>
@endsection
