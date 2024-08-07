{{-- @dd($columns) --}}
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
                    <form action="{{ route('todays_billings') }}" method="post">
                        <div class="card-header">
                            @csrf
                            <div class="col-12">
                                <div class="row justify-content-center">

                                    <div class="col-4">
                                        <div class="form-group row align-items-center">
                                            <label for="" class="col-2">Search</label>
                                            <div class="col-10">
                                                <select name="employee_id" class="select2 form-control mb-1">
                                                    <option selected disabled>Select Employe</option>
                                                    @foreach ($employees as $employee)
                                                        <option {{$user == $employee->id ? "selected":""}} value="{{ $employee->id }}">{{ $employee->name }}
                                                            ({{ $employee->username }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group row align-items-center">
                                            <label for="" class="col-2">Search</label>
                                            <div class="col-10">
                                                <input type="date" value="{{ $req->searchPayment ?? '' }}"
                                                    name="searchPayment" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <button type="submit" class="btn btn-info">Search</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="card-datatable table-responsive">
                        <table id="server_side_lode" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Package</th>
                                    <th>Amount</th>
                                    <th>Billing By</th>
                                    <th>Billing Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total = 0;
                                @endphp
                                @foreach ($billings as $key => $billing)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $billing->getCustomer->username }}</td>
                                    <td>{{ $billing->getCustomer->phone }}</td>
                                    <td>{{ $billing->getProfile->name ?? 'null' }}</td>
                                    <td>{{ $billing->pay_amount ?? 'null' }}</td>
                                    <td>{{ $billing->getBiller->username ?? '' }}</td>
                                    <td>{{ Carbon\Carbon::parse($billing->created_at)->format('Y-M-d h:i:s') ?? 'null' }}
                                    </td>
                                </tr>
                                @php
                                    $total +=$billing->pay_amount;
                                @endphp
                            @endforeach
                            </tbody>
                           <tfoot>
                               <tr>
                                <td ></td>
                                <td ></td>
                                <td ></td>
                                <td >Total</td>
                                <td >{{$total}}</td>
                                <td ></td>
                                <td ></td>
                               </tr>
                           </tfoot>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection
