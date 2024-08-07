{{-- @dd($columns) --}}
@extends('admin.master')

@section('content')
    <section id="ajax-datatable">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-bottom">
                        <h4 class="card-title">{{ $page_heading ?? 'List' }}</h4>
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
                                            <div class="mb-1" style="width: 20%;">
                                                <label for="">Server</label>
                                                <select class="select2 form-control mb-1" id="serverSearch">
                                                    <option selected>Select Server</option>
                                                    @foreach ($servers as $server)
                                                        <option value="{{ $server->id }}">{{ $server->user_name }}
                                                            ({{ $server->server_ip }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="mb-1" style="width: 20%;">
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
                                            <div class="mb-1" style="width: 20%;">
                                                <label for="">Subzone</label>
                                                <select class="select2 form-control mb-1" id="sub_zone_search">
                                                    <option selected>Select Subzone</option>

                                                </select>
                                            </div>
                                            <div class="mb-1" style="width: 20%;">
                                                <label for="">Tj</label>
                                                <select class="select2 form-control mb-1" id="tj_search">
                                                    <option selected>Select Zone Subzone</option>

                                                </select>
                                            </div>
                                            <div class="mb-1" style="width: 20%;">
                                                <label for="">Spliter</label>
                                                <select class="select2 form-control mb-1" id="spliter_search">
                                                    <option selected>Select Zone Subzone</option>

                                                </select>
                                            </div>
                                            <div class="mb-1" style="width: 20%;">
                                                <label for="">Boxs</label>
                                                <select class="select2 form-control mb-1" id="box_search">
                                                    <option selected>Select Box</option>
                                                </select>
                                            </div>
                                            <div class="mb-1" style="width: 20%;">
                                                <label for="">Connection Type</label>
                                                <select class="select2 form-control mb-1" id="connection_type">
                                                    <option selected>Select Client</option>
                                                    @foreach ($clienttyps as $clienttyp)
                                                        <option value="{{ $clienttyp->id }}">
                                                            {{ $clienttyp->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="mb-1" style="width: 20%;">
                                                <label for="">Devices </label>
                                                <select class="select2 form-control mb-1" id="device_search">
                                                    <option selected>Select Device</option>
                                                    @foreach ($devices as $value)
                                                        <option value="{{ $value->id }}">
                                                            {{ $value->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="mb-1" style="width: 20%;">
                                                <label for="">Package</label>
                                                <select class="select2 form-control mb-1" id="package_id">
                                                    <option selected>Select Package </option>
                                                    @foreach ($package2s as $value)
                                                        <option value="{{ $value->id }}">
                                                            {{ $value->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-1" style="width: 20%;">
                                                <label for="">Billing Status</label>
                                                <select class="select2 form-control mb-1" id="billing_status_id">
                                                    <option selected>Select Billing Status</option>
                                                    @foreach ($billingStatus as $value)
                                                        <option value="{{ $value->id }}">
                                                            {{ $value->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="" style="width: 20%;">
                                                <label for="">Single Billing Search</label>
                                                <input type="date" id="single_search" class="form-control">
                                            </div>
                                            <div class="" style="width: 20%;">
                                                <label for="">From Date Search</label>
                                                <input type="date" id="from_search" class="form-control">
                                            </div>
                                            <div class="" style="width: 20%;">
                                                <label for="">To Date Search</label>
                                                <input type="date" id="to_search" class="form-control">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="card-datatable table-responsive">
                        <x-alert></x-alert>
                        <form action method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div id="buttons">
                                    </div>
                                </div>
                            </div>
                            <table id="server_side_lode" class="table  table-bordered">
                                <thead>
                                    <tr>
                                        @if (isset($columns) && $columns)
                                            @foreach ($columns as $column)
                                                @if ($column['label'] == 'Sl')
                                                    <th><input type="checkbox" id="checkAll"
                                                            class="mr-1">{{ $column['label'] }}</th>
                                                @else
                                                    <th>{{ $column['label'] }}</th>
                                                @endif
                                            @endforeach
                                        @endif
                                    </tr>
                                </thead>
                            </table>
                            <div class="row">
                                <div class="col-dm-3 ml-1">
                                    <button href="{{ route('customers.multi.messagesend') }}"
                                        onclick="return confirm('Are You Sure')"
                                        class="dynamicUrl btn btn-success btn-sm ml-2">Send
                                        Message</button>
                                </div>
                                <div class="col-dm-3">
                                    <button href="{{ route('add.mac.address') }}"
                                        onclick="return confirm('Are You Sure')"
                                        class="dynamicUrl btn btn-success btn-sm ml-2">Add Mac
                                        Address</button>
                                </div>
                                <div class="col-dm-3">
                                    <button href="{{ route('customers.multi.delete') }}"
                                        onclick="return confirm('Are You Sure')"
                                        class="dynamicUrl btn btn-danger btn-sm ml-2">Delete</button>
                                </div>
                            </div>
                        </form>
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
            order: [
                [0, 'desc']
            ],
            dom: '<"d-flex justify-content-between align-items-center mx-1 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
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
            "columnDefs": [{
                "orderable": false,
                "targets": 0
            }],
            "columns": {{ \Illuminate\Support\Js::from($columns) }}
        })

        var buttons = new $.fn.dataTable.Buttons(table, {
            buttons: [{
                    extend: 'print',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                    }
                },
                {
                    extend: 'csv',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                    }

                },
                {
                    extend: 'excel',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                    }
                }
            ]
        }).container().appendTo($('#buttons'));

        table.on('draw', function() {
            $('tr').each(function() {
                let attr = $(this).find('.custom-control-input').attr('checked');
                if (typeof attr == 'undefined') {
                    $(this).addClass('bg-danger');
                    $(this).find('td').addClass('text-white');
                }
            })
        });

        $('.dynamicUrl').click('click', function() {
            let url = $(this).attr('href');
            $('form').attr('action', url);
            $('form').submit();
        })

        $(document).on('click', '.delete_customer', function(e) {
            e.preventDefault()
            let url = $(this).attr('href');
            $.ajax({
                "url": url,
                "type": "GET",
                success: (data) => {
                    table.ajax.reload();
                    $(function() {
                        'use strict';
                        var isRtl = $("html").attr('data-textdirection') === 'rtl',
                            clearToastObj;
                        toastr['success']("Delete Successfully", {
                            closeButton: true,
                            tapToDismiss: false,
                            rtl: isRtl
                        });
                    });
                }
            });
        });

        $("#checkAll").click(function() {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });

        table.columns.adjust();
        // table.columns(9).visible(false);
        table.columns(11).visible(false);
        table.columns(12).visible(false);
        table.columns(13).visible(false);
        table.columns(14).visible(false);
        table.columns(15).visible(false);
        table.columns(16).visible(false);
        table.columns(17).visible(false);
        table.columns(18).visible(false);
        table.columns(19).visible(false);
        table.columns(20).visible(false);

        // $('#box_search').change(function() {
        //     table.columns(5).search(this.value).draw();
        // });
        $('#package_id').change(function() {
            table.columns(7).search(this.value).draw();
        });
        $('#billing_status_id').change(function() {
            table.columns(9).search(this.value).draw();
        });
        $('#serverSearch').change(function() {
            table.columns(11).search(this.value).draw();
        });
        $('#zone_search').change(function() {
            table.columns(12).search(this.value).draw();
        });
        $('#sub_zone_search').change(function() {
            table.columns(13).search(this.value).draw();
        });
        $('#connection_type').change(function() {
            table.columns(14).search(this.value).draw();
        });
        $('#device_search').change(function() {
            table.columns(15).search(this.value).draw();
        });
        $('#tj_search').change(function() {
            table.columns(16).search(this.value).draw();
        });
        $('#spliter_search').change(function() {
            table.columns(17).search(this.value).draw();
        });

        $('#single_search').change(function() {
            table.columns(5).search(this.value).draw();
        });

        $('#from_search').change(function() {
            table.columns(19).search(this.value).draw();
        });
        $('#to_search').change(function() {
            table.columns(20).search(this.value).draw();
        });



        $(document).on('change', '.expdate', function() {
            let exp = $(this).val();
            let id = $(this).data('id');
            $.ajax({
                url: `{{ route('customers.update_expire_date') }}`,
                type: "post",
                data: {
                    "_token": "{{ csrf_token() }}",
                    date: exp,
                    id: id,
                },
                success: function(data) {
                    table.ajax.reload();
                    $(function() {
                        'use strict';
                        var isRtl = $("html").attr('data-textdirection') === 'rtl',
                            clearToastObj;
                        toastr['success']('Date Updated Successfully', 'Success!', {
                            closeButton: true,
                            tapToDismiss: false,
                            rtl: isRtl
                        });
                    });
                }
            })
        });

        $(document).on('change', '#zone_search', function() {
            let self = $(this);
            $.ajax({
                "url": "{{ route('api.subzones') }}",
                "type": "GET",
                "data": {
                    zone_id: self.val()
                },
                cache: false,
                success: function(data) {
                    $('#sub_zone_search').empty();
                    $('#sub_zone_search').html(data);
                }
            });
        });

        $(document).on('change', '#sub_zone_search', function() {
            let self = $(this);
            $.ajax({
                "url": "{{ route('api.tjs') }}",
                "type": "GET",
                "data": {
                    subzone_id: self.val()
                },
                cache: false,
                success: function(data) {
                    $('#tj_id').empty();
                    $('#tj_id').html(data);
                }
            });
        });

        $(document).on('change', '#sub_zone_search', function() {
            let self = $(this);
            $.ajax({
                "url": "{{ route('api.tjs') }}",
                "type": "GET",
                "data": {
                    subzone_id: self.val()
                },
                cache: false,
                success: function(data) {
                    $('#tj_id').empty();
                    $('#tj_search').html(data);
                }
            });
        });


        $(document).on('change', '#tj_search', function() {
            let self = $(this);
            $.ajax({
                "url": "{{ route('api.splitter') }}",
                "type": "GET",
                "data": {
                    model_name: "App\\Models\\Splitter",
                    model_id: self.val(),
                    column_name: "tj_id",
                },
                cache: false,
                success: function(data) {
                    console.log(data)
                    $('#spliter_search').empty();
                    $('#spliter_search').html(data);
                }
            });
        });


        $(document).on('change', '#spliter_search', function() {
            let self = $(this);
            $.ajax({
                "url": "{{ route('api.box') }}",
                "type": "GET",
                "data": {
                    model_name: "App\\Models\\Box",
                    model_id: self.val(),
                    column_name: "splitter_id",
                },
                cache: false,
                success: function(data) {
                    $('#box_search').empty();
                    $('#box_search').html(data);
                }
            });
        });
    </script>
@endsection
