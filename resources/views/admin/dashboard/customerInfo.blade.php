@extends('admin.master')

@section('content')
    <section id="ajax-datatable">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <x-alert></x-alert>
                    <div class="card-header border-bottom">
                        <h4 class="card-title">{{ $headerTitle ?? 'N/A' }}</h4>
                    </div>
                    <div class="card-datatable table-responsive">
                        <table id="server_side_lode" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Package</th>
                                    <th>Amount</th>
                                    <th>Exp Date</th>
                                </tr>
                            </thead>

                            @foreach ($customers as $key => $active_customer)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $active_customer->username }}</td>
                                    <td>{{ $active_customer->phone }}</td>
                                    <td>{{ $active_customer->getMProfile->name ?? 'null' }}</td>
                                    <td>{{ (int) $active_customer->bill_amount ?? 'null' }}</td>
                                    <td>{{ date('d-m-y', strtotime($active_customer->exp_date ?? 'null')) }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                    <div class="d-flex justify-content-end">
                        {!! $customers->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
