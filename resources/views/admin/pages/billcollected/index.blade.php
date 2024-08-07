{{-- @dd($columns) --}}
@extends('admin.master')

@section('content')

    <section id="ajax-datatable">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <x-alert></x-alert>
                    <div class="card-header border-bottom">
                        <h4 class="card-title">{{ $page_heading ?? 'List' }}</h4>
                        <!-- <a href="{{ route('nopaidcustomer.index') }}" class="btn btn-rounded btn-info text-right">
                                                                                                                            <span class="btn-icon-start text-white">
                                                                                                                                <i class="fa fa-plus"></i>
                                                                                                                            </span>
                                                                                                                            Not Paid
                                                                                                                        </a> -->
                        @if (isset($create_url) && $create_url)
                            <a href="{{ $create_url ?? '#' }}" class="btn btn-rounded btn-info text-right">
                                <span class="btn-icon-start text-white">
                                    <i class="fa fa-plus"></i>
                                </span>
                                Add
                            </a>
                        @endif
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <form action="">
                                        <div class="row">
                                            <div class="col-md-4 mb-1">
                                                <label for="">Customer</label>
                                                <select name="" class="select2 form-control mb-1" id="customer_id">
                                                    <option selected disabled>Select Customer</option>
                                                    @foreach ($customers as $customer)
                                                        <option value="{{ $customer->id }}">{{ $customer->name }}
                                                            ({{ $customer->username }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4 mb-1">
                                                <label for="">Employees</label>
                                                <select name="" class="select2 form-control mb-1" id="employee_id">
                                                    <option selected disabled>Select Employe</option>
                                                    @foreach ($employees as $employee)
                                                        <option value="{{ $employee->id }}">{{ $employee->name }}
                                                            ({{ $employee->username }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4 mb-1">
                                                <label for="">Search by Date</label>
                                                <input type="date" class="form-control" id="searchByDate">
                                            </div>
                                            <div class="col-md-4 mb-1">
                                                <label for="">Form Date</label>
                                                <input type="date" class="form-control" id="fromByDate">
                                            </div>
                                            <div class="col-md-4 mb-1">
                                                <label for="">TO Date</label>
                                                <input type="date" class="form-control" id="toByDate">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-datatable table-responsive">
                        <div class="row">
                            <div class="col-md-12">
                                <div id="buttons">
                                </div>
                            </div>
                        </div>
                        <table id="server_side_lode" class="table table-striped table-bordered">
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
                                    <th colspan="6" class="text-right">Total</th>
                                    <th class="text-right" id="totalamount"></th>
                                    <th colspan="2" class="text-right"></th>
                                    <th colspan="2" class="text-right"></th>
                                    <th colspan="2" class="text-right"></th>
                                </tr>
                            </tfoot>
                        </table>
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
                let amount = parseFloat(data.pay_amount);
                sum += amount;
                $('#totalamount').text(sum);
            }
        })


        var buttons = new $.fn.dataTable.Buttons(table, {
            buttons: [{
                    extend: 'print',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6,7,8,11,12]
                    }
                },
                {
                    extend: 'csv',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6,7,8,11,12]
                    }

                },
                {
                    extend: 'excel',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6,7,8,11,12]
                    }
                }
            ]
        }).container().appendTo($('#buttons'));

        $(document).on('click', '.paymodel', function() {
            let url = $(this).attr('href');
            $('form').attr('action', url);
        })

        table.columns(8).visible(false);
        table.columns(9).visible(false);
        table.columns(10).visible(false);


        $('#customer_id').change(function() {
            table.columns(1).search(this.value).draw();
        });

        $('#searchByDate').change(function() {
            table.columns(7).search(this.value).draw();
        });

        $('#employee_id').change(function() {
            table.columns(8).search(this.value).draw();
        });

        $('#fromByDate').change(function() {
            table.columns(9).search(this.value).draw();
        });

        $('#toByDate').change(function() {
            table.columns(10).search(this.value).draw();
        });
    </script>
@endsection
