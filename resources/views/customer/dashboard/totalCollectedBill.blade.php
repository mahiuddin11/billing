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
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <form action="">
                                        <div class="row">
                                            <div class="col-md-3 mb-1">
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
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-datatable table-responsive">
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
            "columns": {{ \Illuminate\Support\Js::from($columns) }}
        })


        $(document).on('click', '.paymodel', function() {
            let url = $(this).attr('href');
            $('form').attr('action', url);
        })

        $('#customer_id').change(function() {
            table.columns(1).search(this.value).draw();
        });
    </script>
@endsection
