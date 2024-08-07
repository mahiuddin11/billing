@extends('admin.master')

@section('content')

    <section id="ajax-datatable">
        <div class="card">
            <div class="row">
                <div class="col-md-12">
                    <div class="pt-2 pr-2 pl-2 pt-1">
                        <h4 class="card-title">{{ $page_heading ?? 'List' }}</h4>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="pt-1 pr-2 pl-2 pt-1">
                        <button class="btn btn-outline-primary mikrotik">Import PPPOE Customer</button>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="pt-1 pr-2 pl-2 pt-1">
                        <button class="btn btn-outline-primary static">Import Static Customer</button>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="pt-1 pr-2 pl-2 pt-1">
                        <button class="btn btn-outline-primary excel">Import By Excel</button>
                    </div>
                </div>
            </div>
            <hr>

            <div class="card mikrotik-div">
                <form action="{{ route('mikrotiklist.importCustomer') }}" method="post">
                    <x-alert></x-alert>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-3">
                                        <h3>PPPOE</h3>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="">Server</label>
                                        <select class="select2 form-control mb-1 serverSearchForPPpoe" name="server_id">
                                            <option selected>Select Server</option>
                                            @foreach ($servers as $server)
                                                <option value="{{ $server->id }}">{{ $server->user_name }}
                                                    ({{ $server->server_ip }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3"></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="">Check All</label>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" name="customers[]" class="custom-control-input selectAll"
                                                id="customCheck1">
                                            <label class="custom-control-label" for="customCheck1"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-datatable table-responsive">
                        <table id="server_side_lode" class="table">
                            @csrf
                            <thead>
                                <tr>
                                    @if (isset($columns) && $columns)
                                        @foreach ($columns as $column)
                                            <th>{{ $column['label'] }}</th>
                                        @endforeach
                                    @endif
                                </tr>
                            </thead>
                        </table>
                        <button type="submit" class="btn btn-success float-right mb-2 mr-2">Import</button>
                    </div>
                </form>
            </div>
            <div class="card mikrotik-static d-none">
                <form action="{{ route('mikrotiklist.importStaticCustomer') }}" method="post">
                    <x-alert></x-alert>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-3">
                                        <h3>Static</h3>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="">Static Queue</label>
                                        <select class="select2 form-control mb-1 serverSearchForStatic" name="server_id">
                                            <option selected>Select Server</option>
                                            @foreach ($servers as $server)
                                                <option value="{{ $server->id }}">{{ $server->user_name }}
                                                    ({{ $server->server_ip }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3"></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="">Check All</label>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" name="customers[]" class="custom-control-input selectAll"
                                                id="customCheck1">
                                            <label class="custom-control-label" for="customCheck1"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-datatable table-responsive">
                        <table id="server_side_lode_static" class="table">
                            @csrf
                            <thead>
                                <tr>
                                    @if (isset($columns) && $columns)
                                        @foreach ($columns as $column)
                                            <th>{{ $column['label'] }}</th>
                                        @endforeach
                                    @endif
                                </tr>
                            </thead>
                        </table>
                        <button type="submit" class="btn btn-success float-right mb-2 mr-2">Import</button>
                    </div>
                </form>
            </div>
            <div class="card excel-div d-none">
                <form action="{{ route('imports.customer.excel') }}" enctype="multipart/form-data" method="post">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="">Customer Type</label>
                                <select name="customer_type" class="form-control" id="">
                                    <option selected value="3">PPPOE Customer</option>
                                    <option value="1">Static Customer</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="">Excel</label>
                                <input type="file" name="file" class="form-control">
                            </div>
                            <div class="col-md-2 mt-2">
                                <button type="submit" class="btn btn-info">Import</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>

    </section>

@endsection

@section('datatablescripts')
    <!-- Datatable -->
    <script type="text/javascript">
        $('.serverSearchForPPpoe').change(function() {
            let table = $('#server_side_lode').DataTable({
                dom: '<"d-flex justify-content-between align-items-center mx-1 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                "processing": true,
                "serverSide": true,
                retrieve: true,
                "ajax": {
                    "url": "{{ route('mikrotiklist.dataProcessing', 3) }}",
                    "dataType": "json",
                    "type": "GET",
                },
                pageLength: 50000,
                aLengthMenu: [
                    [50000],
                    ["All"]
                ],
                "columns": {{ \Illuminate\Support\Js::from($columns) }}
            })

            table.on('draw', function() {
                $('tr').each(function() {
                    let attr = $(this).find('.custom-control-input').attr('disabled');
                    if (typeof attr !== 'undefined') {
                        $(this).addClass('bg-secondary');
                        $(this).find('td').addClass('text-white');
                    }
                })
            });


            table.columns(0).search(this.value).draw();
        });

        $(".selectAll").click(function() {
            $("input[type=checkbox]").prop('checked', $(this).prop('checked'));

        });

        $('.serverSearchForStatic').change(function() {
            let table = $('#server_side_lode_static').DataTable({
                dom: '<"d-flex justify-content-between align-items-center mx-1 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                "processing": true,
                "serverSide": true,
                retrieve: true,
                "ajax": {
                    "url": "{{ route('mikrotiklist.dataProcessing', 1) }}",
                    "dataType": "json",
                    "type": "GET",
                },
                pageLength: 50000,
                aLengthMenu: [
                    [50000],
                    ["All"]
                ],
                "columns": {{ \Illuminate\Support\Js::from($columns) }}
            })

            table.on('draw', function() {
                $('tr').each(function() {
                    let attr = $(this).find('.custom-control-input').attr('disabled');
                    if (typeof attr !== 'undefined') {
                        $(this).addClass('bg-secondary');
                        $(this).find('td').addClass('text-white');
                    }
                })
            });

            table.columns(0).search(this.value).draw();
        });

        $(document).on('click', '.mikrotik', function() {
            $('.mikrotik-div').removeClass('d-none');
            $('.mikrotik-static').addClass('d-none', true);
            $('.excel-div').addClass('d-none', true);
        })
        $(document).on('click', '.excel', function() {
            $('.mikrotik-static').addClass('d-none', true);
            $('.mikrotik-div').addClass('d-none', true);
            $('.excel-div').removeClass('d-none');
        })

        $(document).on('click', '.static', function() {
            $('.mikrotik-static').removeClass('d-none');
            $('.mikrotik-div').addClass('d-none', true);
            $('.excel-div').addClass('d-none', true);
        })

        $('.excel-div').addClass('d-none', true);
        $('.mikrotik-static').addClass('d-none', true);
    </script>
@endsection
