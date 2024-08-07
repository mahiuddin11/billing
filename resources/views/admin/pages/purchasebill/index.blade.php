{{-- @dd($columns) --}}
@extends('admin.master')

@section('content')

    <section id="ajax-datatable">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-bottom">
                        <h4 class="card-title">{{ $page_heading ?? 'List' }}</h4>
                        <a href="{{ route('purchasebill.pay') }}" class="btn btn-rounded btn-info text-right">
                            <span class="btn-icon-start text-white">
                                <i class="fa fa-plus"></i>
                            </span>
                            PayBill
                        </a>
                        @if (isset($create_url) && $create_url)
                            <a href="{{ $create_url ?? '#' }}" class="btn btn-rounded btn-info text-right">
                                <span class="btn-icon-start text-white">
                                    <i class="fa fa-plus"></i>
                                </span>
                                Add
                            </a>
                        @endif
                    </div>
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
                        </table>
                    </div>
                </div>
            </div>
        </div>


        <div class="basic-modal">
            <!-- Modal -->
            <div class="modal fade text-left" id="default" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myModalLabel1">Payment Bill</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="get">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12 mb-1">
                                        <label>Accounts * :</label>
                                        <select class="form-control select2 getAccount" onchange="getBalance()"
                                            name="account_id">
                                            <option selected disabled value="">--Select Account--</option>
                                            @foreach ($accounts as $value)
                                                <option amount="{{ $value->amount }}" value="{{ $value->id }}">
                                                    {{ $value->account_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span class="text-success available-balance"></span>
                                    </div>

                                    <div class="col-md-12 mb-1">
                                        <label>Amount * :</label>
                                        <input type="text" class="form-control" name="amount">
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
        let table = $('#server_side_lode').dataTable({
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

        $(document).on('click', '.paymodel', function() {
            let url = $(this).attr('href');
            $('form').attr('action', url);
        })

        function getBalance() {
            let amount = $('.getAccount option:selected').attr('amount');
            $('.available-balance').text('Available Balance ' + amount);
        }
    </script>
@endsection
