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
                                            <div class="col-md-3 mb-1">
                                                <label for="">Server</label>
                                                <select name="" class="select2 form-control mb-1" id="serverSearch">
                                                    <option selected disabled>Select Server</option>
                                                    @foreach ($servers as $server)
                                                        <option value="{{ $server->id }}">{{ $server->user_name }}
                                                            ({{ $server->server_ip }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-3 mb-1">
                                                <label for="">Zone</label>
                                                <select class="select2 form-control mb-1" id="zone_search">
                                                    <option selected disabled>Select Zone</option>
                                                    @foreach ($zones as $value)
                                                        <option value="{{ $value->id }}">
                                                            {{ $value->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3 mb-1">
                                                <label for="">Zone Subzone</label>
                                                <select class="select2 form-control mb-1" id="sub_zone_search">
                                                    <option selected disabled>Select Zone Subzone</option>
                                                    @foreach ($subzones as $value)
                                                        <option value="{{ $value->id }}">
                                                            {{ $value->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3 mb-1">
                                                <label for="">Client Type</label>
                                                <select class="select2 form-control mb-1" id="clienttyps">
                                                    <option selected disabled>Select Client Type</option>
                                                    @foreach ($clienttyps as $clienttyp)
                                                        <option value="{{ $clienttyp->id }}">
                                                            {{ $clienttyp->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            {{-- <div class="col-md-3 mb-1">
                                            <label for="">Connection Type</label>
                                            <select class="select2 form-control mb-1" id="connection_type">
                                                <option selected disabled>Select Connection Typs</option>
                                                @foreach ($connectiontypes as $value)
                                                <option value="{{$value->id}}">
                                                    {{$value->name}}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div> --}}
                                            {{-- <div class="col-md-3 mb-1">
                                                <label for="">Protocole Type</label>
                                                <select class="select2 form-control mb-1" id="protocol_type">
                                                    <option selected disabled>Select Protocole Type</option>
                                                    @foreach ($protocoltypes as $value)
                                                        <option value="{{ $value->id }}">
                                                            {{ $value->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div> --}}
                                            <div class="col-md-3 mb-1">
                                                <label for="">Package</label>
                                                <select class="select2 form-control mb-1" id="package_id">
                                                    <option selected disabled>Select Package </option>
                                                    @foreach ($package2s as $value)
                                                        <option value="{{ $value->id }}">
                                                            {{ $value->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3 mb-1">
                                                <label for="">Billing Status</label>
                                                <select class="select2 form-control mb-1" id="billing_id">
                                                    <option selected disabled>Select Billing Status</option>
                                                    @foreach ($billingStatus as $value)
                                                        <option value="{{ $value->id }}">
                                                            {{ $value->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-datatable table-responsive">
                        <form action method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div id="buttons">
                                    </div>
                                </div>
                            </div>
                            <table id="server_side_lode" class="table">
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
                            <div class="col-dm-3">
                                <button href="{{ route('customers.multi.delete') }}"
                                    onclick="return confirm('Are You Sure')"
                                    class="dynamicUrl btn btn-danger btn-sm ml-2">Delete</button>
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
            "columns": {{ \Illuminate\Support\Js::from($columns) }}
        })

        var buttons = new $.fn.dataTable.Buttons(table, {
            buttons: [{
                    extend: 'print',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 7, 8]
                    }
                },
                {
                    extend: 'csv',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 7, 8]
                    }

                },
                {
                    extend: 'excel',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 7, 8]
                    }
                }
            ]
        }).container().appendTo($('#buttons'));

        $('#serverSearch').change(function() {
            table.columns(13).search(this.value).draw();
        });
        $('#zone_search').change(function() {
            table.columns(5).search(this.value).draw();
        });
        $('#sub_zone_search').change(function() {
            table.columns(15).search(this.value).draw();
        });
        $('#clienttyps').change(function() {
            table.columns(7).search(this.value).draw();
        });
        $('#connection_type').change(function() {
            table.columns(6).search(this.value).draw();
        });
        $('#protocol_type').change(function() {
            table.columns(8).search(this.value).draw();
        });
        $('#package_id').change(function() {
            table.columns(10).search(this.value).draw();
        });
        $('#billing_status_id').change(function() {
            table.columns(12).search(this.value).draw();
        });

        $('.dynamicUrl').click('click', function() {
            let url = $(this).attr('href');
            $('form').attr('action', url);
            $('form').submit();
        })

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
    </script>
@endsection
