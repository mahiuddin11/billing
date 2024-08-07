@extends('admin.master')

@section('content')

    <section id="ajax-datatable">
        <form action="{{ route('mikrotiklist.importCustomer') }}" method="post">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="pt-2 pr-2 pl-2 pt-1">
                            <h4 class="card-title">{{ $page_heading ?? 'List' }}</h4>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-3"></div>
                                            <div class="col-md-6">
                                                <label for="">Server</label>
                                                <select class="select2 form-control mb-1" name="server_id"
                                                    id="serverSearch">
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
                                    </div>
                                </div>
                            </div>
                        </div>
                        @csrf
                        <div class="card-datatable table-responsive">
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
                                <tbody></tbody>
                            </table>
                            {{-- <button type="submit" class="btn btn-success float-right mb-2 mr-2">Import</button> --}}
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>

@endsection

@section('datatablescripts')
    <!-- Datatable -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script type="text/javascript">
        $('#serverSearch').change(function() {
            let server = $(this).val();
            axios({
                method: 'get',
                url: `{{ route('activeconnections.dataProcessing') }}`,
                params: {
                    server_id: server,
                }

            }).then(function(response) {
                console.log(response.data);
                $('tbody').html(response.data);
                $('#server_side_lode').DataTable();
            }).catch((error) => {
                alert(error)
                $('tbody').html('');
            });
        })
    </script>
@endsection
