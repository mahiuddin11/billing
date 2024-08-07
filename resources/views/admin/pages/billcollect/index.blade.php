{{-- @dd($columns) --}}
@extends('admin.master')

@section('content')
    <style>
        li.select2-results__option {
            font-weight: bold;
            color: black;
        }
    </style>
    <section id="ajax-datatable">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <x-alert></x-alert>
                    <div class="card-header border-bottom">
                        <h4 class="card-title">{{ $page_heading ?? 'List' }}</h4>

                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <form action="">
                                        <div class="row">
                                            <div class="col-md-6 mb-1">
                                                <label for="">Customer</label>
                                                <select name="" class="select2 form-control mb-1" id="customer_id">
                                                    <option selected disabled>Select Customer</option>
                                                    @foreach ($customers as $customer)
                                                        <option value="{{ $customer->id }}"><b>{{ $customer->client_id }}
                                                                {{ $customer->name }}
                                                                ({{ $customer->username }})
                                                                -
                                                                ({{ $customer->phone }})
                                                            </b>
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3 mb-1">
                                                <label for="">Zone</label>
                                                <select class="select2 form-control mb-1" id="zone_search">
                                                    <option selected>Select Zone</option>
                                                    @foreach ($zones as $value)
                                                        <option value="{{ $value->id }}">
                                                            {{ $value->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            {{-- <div class="col-md-3 mb-1">
                                                <label for="">Customer Type</label>
                                                <select class="select2 form-control mb-1" id="customerType">
                                                    <option selected value="3">PPPOE</option>
                                                    <option value="1">Static</option>
                                                </select>
                                            </div> --}}
                                            <div class="col-md-3 mb-1">
                                                <label for="">Search by Date</label>
                                                <input type="date" class="form-control" id="searchByDate">
                                            </div>
                                            <div class="col-md-3 mb-1">
                                                <label for="">Active Customer</label>
                                                <select class="select2 form-control mb-1" id="status">
                                                    <option selected>Select Status</option>
                                                    <option value="5">Active</option>
                                                    <option value="4">Inactive</option>
                                                </select>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-datatable ">
                        <x-alert></x-alert>
                        <form  method="post">
                            @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div id="buttons">
                                </div>
                            </div>
                        </div>
                        <table id="server_side_lode" class="table table-striped table-bordered table-responsive">
                            <thead>
                                <tr>
                                    @if (isset($columns) && $columns)
                                        @foreach ($columns as $column)
                                            <th>{{ $column['label'] }}</th>
                                        @endforeach
                                    @endif
                                </tr>
                            </thead>
                            <tfoot>
                                <tr class="table-danger">
                                    <th colspan="9" class="text-right">Total</th>
                                    <th class="text-right" id="totalamount"></th>
                                    <th colspan="1" class="text-right"></th>
                                </tr>
                            </tfoot>
                        </table>
                        <div class="row">
                            <div class="col-dm-3 ml-1">
                                <button href="{{ route('billcollect.multi.messagesend') }}"
                                    onclick="return confirm('Are You Sure')"
                                    class="dynamicUrl btn btn-success btn-sm ml-2">Send
                                    Message</button>
                            </div>
                        </div>
                    </form>
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
                            <h4 class="modal-title" id="myModalLabel1">Payment Bill <span id="username"></span></h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="get">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5>Select Method</h5>
                                        <select name="payment_method_id" class="select2" id="payment_method">
                                            <option disabled selected>Select Payment</option>
                                            @foreach ($accounts as $account)
                                                <option {{ $account->id == 3 ? 'selected' : '' }}
                                                    value="{{ $account->id }}">{{ $account->account_name }}</option>

                                                @if ($account->subAccount->isNotEmpty())
                                                    @foreach ($account->subAccount as $subaccount)
                                                        <option value="{{ $subaccount->id }}">
                                                            -{{ $subaccount->account_name }}
                                                        </option>

                                                        @if ($subaccount->subAccount->isNotEmpty())
                                                            @foreach ($subaccount->subAccount as $subaccount2)
                                                                <option value="{{ $subaccount2->id }}">
                                                                    --{{ $subaccount2->account_name }}</option>
                                                                @if ($subaccount2->subAccount->isNotEmpty())
                                                                    @foreach ($subaccount2->subAccount as $subaccount3)
                                                                        <option value="{{ $subaccount3->id }}" disabled>
                                                                            ---{{ $subaccount3->account_name }}
                                                                        </option>
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
                                        <h5>Discount</h5>
                                        <input type="number" name="discount" value="0" min="0"
                                            class="form-control">
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
    </section>


@endsection

@section('datatablescripts')
    <!-- Datatable -->
    <script type="text/javascript">
        let table = $('#server_side_lode').DataTable({
            dom: '<"d-flex justify-content-between align-items-center mx-1 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            // "scrollY": "655px",
            "autoWidth": false,
            "processing": true,
            "serverSide": true,
            retrieve: true,
            "ajax": {
                "url": "{{ $ajax_url ?? '' }}",
                "dataType": "json",
                "type": "GET",
            },
            pageLength: 100,
            aLengthMenu: [
                [10, 25, 50, 100, 200, 100000],
                [10, 25, 50, 100, 200, "All"]
            ],
            "columns": {{ \Illuminate\Support\Js::from($columns) }},
            "rowCallback": function(row, data, index) {
                if (index == 0) {
                    sum = 0;
                }
                let amount = parseFloat(data.customer_billing_amount);
                sum += amount;
                $('#totalamount').text(sum);
            }
        })


        var buttons = new $.fn.dataTable.Buttons(table, {
            buttons: [{
                    extend: 'print',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 6, 7, 8, 9]
                    }
                },
                {
                    extend: 'csv',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
                    }

                },
                {
                    extend: 'excel',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
                    }
                }
            ]
        }).container().appendTo($('#buttons'));

        $(document).on('click', '.paymodel', function() {
            let username = $(this).closest('tr').find('td:nth-child(2)').text()
            $('#username').text(username);
            let url = $(this).attr('href');
            $('form').attr('action', url);
        })

        $('.dynamicUrl').click('click', function() {
            let url = $(this).attr('href');
            $(this).closest("form").attr('action', url);
            $(this).closest("form").submit();
        })

        $('#customer_id').change(function() {
            table.columns(2).search(this.value).draw();
        });

        $('#zone_search').change(function() {
            table.columns(0).search(this.value).draw();
        });

        $('#searchByDate').change(function() {
            table.columns(6).search(this.value).draw();
        });
        $('#customerType').change(function() {
            table.columns(1).search(this.value).draw();
        });
        $('#status').change(function() {
            table.columns(10).search(this.value).draw();
        });
    </script>
@endsection
