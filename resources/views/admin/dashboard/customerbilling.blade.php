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
                                                        <option {{ $req->employee_id == $employee->id ? 'selected' : '' }}
                                                            value="{{ $employee->id }}">{{ $employee->name }}
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
                                    <th>Note</th>
                                    <th>Collected By</th>
                                    <th>Billing By</th>
                                    <th>Billing Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total = 0;
                                @endphp
                                @foreach ($billings as $key => $billing)
                                    @php
                                        $bill =
                                            \App\Models\AccountTransaction::where(
                                                'company_id',
                                                auth()->user()->company_id,
                                            )
                                                ->where('account_id', '!=', '5')
                                                ->where('type', 4)
                                                ->whereDate('created_at', date('Y-m-d'))
                                                ->where('table_id', $billing->id)
                                                ->sum('debit') ?? 0;
                                    @endphp
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $billing->getCustomer->username }}</td>
                                        <td>{{ $billing->getCustomer->phone }}</td>
                                        <td>{{ $billing->getProfile->name ?? 'null' }}</td>
                                        <td>{{ $bill }}</td>
                                        <th>{{ $billing->description }}</th>
                                        <th>{{ $billing->payment_method_id == 500 ? 'Advance Pay' : $billing->PaymentMethod->account_name ?? '' }}
                                        </th>
                                        <td>{{ $billing->getBiller->username ?? '' }}</td>
                                        <td>{{ Carbon\Carbon::parse($billing->updated_at)->format('Y-M-d h:i:s') ?? 'null' }}
                                        </td>
                                    </tr>
                                    @php
                                        $total += $bill;
                                    @endphp
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>Total</td>
                                    <td>{{ $total }}</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection
