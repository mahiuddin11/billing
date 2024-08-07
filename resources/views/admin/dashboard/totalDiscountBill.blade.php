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

                    <div class="card-datatable table-responsive">
                        <table id="server_side_lode" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <td>Sl</td>
                                    <td>Name</td>
                                    <td>Username</td>
                                    <td>Phone</td>
                                    <td>Profile</td>
                                    <td>Billing Amount</td>
                                    <td>Discount Amount</td>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $amount = 0;
                                    $discount = 0;
                                @endphp
                                @foreach ($this_monthly_discount_bill as $key => $value)
                                    @php
                                        $customer = $value->getCustomer;
                                        $amount += $customer->bill_amount ?? 0;
                                        $discount += $value->discount ?? 0;
                                    @endphp
                                    <tr>
                                        <th>{{ $key + 1 }}</th>
                                        <th>{{ $customer->name ?? '' }}</th>
                                        <th>{{ $customer->username ?? '' }}</th>
                                        <th>{{ $customer->phone ?? '' }}</th>
                                        <th>{{ $customer->getMProfile->name ?? '' }}</th>
                                        <th>{{ $customer->bill_amount ?? '' }}</th>
                                        <th>{{ $value->discount ?? '' }}</th>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="5" class="text-right">Total</th>
                                    <th>{{ $amount }}</th>
                                    <th>{{ $discount }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
