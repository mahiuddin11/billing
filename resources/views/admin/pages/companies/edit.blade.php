@extends('admin.master')

@section('content')
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ $page_heading ?? 'Edit' }}</h4>
                    <a href="{{ $back_url ?? 'javascript:;' }}" class="btn btn-dark">Back</a>
                </div>
                <div class="card-body">

                    <x-alert></x-alert>

                    <div class="basic-form">
                        <form action="{{ $update_url ?? '#' }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row ">
                                <div class="col-md-6 mb-3">
                                    <label>Logo</label>
                                    <input type="file" class="form-control input-rounded" name="logo">
                                    @if ($editinfo->logo)
                                        {!! $editinfo->logo !!}
                                    @endif
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Favicon</label>
                                    <input type="file" class="form-control input-rounded" name="favicon">
                                    @if ($editinfo->favicon)
                                        {!! $editinfo->favicon !!}
                                    @endif
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Invoice Logo</label>
                                    <input type="file" class="form-control input-rounded" name="invoice_logo">
                                    @if ($editinfo->invoice_logo)
                                        {!! $editinfo->invoice_logo !!}
                                    @endif
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Company Name</label>
                                    <input type="text" class="form-control input-rounded"
                                        value="{{ old('company_name') ?? ($editinfo->company_name ?? '') }}"
                                        name="company_name" placeholder="company name">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Website</label>
                                    <input type="text" class="form-control input-rounded"
                                        value="{{ old('website') ?? ($editinfo->website ?? '') }}" name="website"
                                        placeholder="website">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Phone</label>
                                    <input type="text" class="form-control input-rounded"
                                        value="{{ old('phone') ?? ($editinfo->phone ?? '') }}" name="phone"
                                        placeholder="phone">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Email</label>
                                    <input type="text" class="form-control input-rounded"
                                        value="{{ old('email') ?? ($editinfo->email ?? '') }}" name="email"
                                        placeholder="email">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Address</label>
                                    <input type="text" class="form-control input-rounded"
                                        value="{{ old('address') ?? ($editinfo->address ?? '') }}" name="address"
                                        placeholder="address">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Url</label>
                                    <input type="text" class="form-control input-rounded"
                                        value="{{ old('url') ?? ($editinfo->url ?? '') }}" name="url" placeholder="url">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Api key</label>
                                    <input type="text" class="form-control input-rounded"
                                        value="{{ old('apikey') ?? ($editinfo->apikey ?? '') }}" name="apikey"
                                        placeholder="apikey">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Prefix</label>
                                    <input type="text" class="form-control input-rounded"
                                        value="{{ old('prefix') ?? ($editinfo->prefix ?? '') }}" name="prefix"
                                        placeholder="prefix">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Secret key</label>
                                    <input type="text" class="form-control input-rounded"
                                        value="{{ old('secretkey') ?? ($editinfo->secretkey ?? '') }}" name="secretkey"
                                        placeholder="secretkey">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Bank Account Number </label>
                                    <textarea name="account_info" class="form-control" id="" cols="5" rows="2">
                                      {{ $editinfo->account_info }}
                                    </textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Mobilie Banking Number </label>
                                    <textarea name="mobile_banking" class="form-control" id="" cols="5" rows="2">
                                        {{ $editinfo->mobile_banking }}
                                    </textarea>
                                </div>
                            </div>

                            <div class="row text-center">
                                <div class="col-md-12 mb-3">
                                        <h2 class="text-center mb-2">Message ShortCode</h2>
                                        <span class="bg-secondary py-1 text-white"> %clientid%</span> ,
                                        <span class="bg-secondary py-1 text-white"> %username% </span>,
                                        <span class="bg-secondary py-1 text-white"> %clientname% </span>,
                                        <span class="bg-secondary py-1 text-white"> %password% </span>,
                                        <span class="bg-secondary py-1 text-white"> %monthname% </span>,
                                        <span class="bg-secondary py-1 text-white"> %monthlybill% </span>,
                                        <span class="bg-secondary py-1 text-white"> %expdate% </span>,
                                        <span class="bg-secondary py-1 text-white"> %duebill% </span>,
                                        <span class="bg-secondary py-1 text-white"> %link% </span>,
                                        <span class="bg-secondary py-1 text-white"> %paymoney% </span>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Create Message</label>
                                    <textarea name="create_msg" class="form-control" id="" cols="5" rows="2">
                                        {{ $editinfo->create_msg }}
                                    </textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Bill Expire Message </label>
                                    <textarea name="billing_exp_msg" class="form-control" id="" cols="5" rows="2">
                                        {{ $editinfo->billing_exp_msg }}
                                    </textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Bill Paid Message </label>
                                    <textarea name="bill_paid_msg" class="form-control" id="" cols="5" rows="2">
                                        {{ $editinfo->bill_paid_msg }}
                                    </textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Bill Expire Warning Message </label>
                                    <textarea name="bill_exp_warning_msg" class="form-control" id="" cols="5" rows="2">
                                        {{ $editinfo->bill_exp_warning_msg }}
                                    </textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Monthly Sms</label>
                                    <textarea name="month_start_msg" class="form-control" id="" cols="5" rows="2">
                                        {{ $editinfo->month_start_msg }}
                                    </textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Partial Bill</label>
                                    <textarea name="partial_bill_msg" class="form-control" id="" cols="5" rows="2">
                                        {{ $editinfo->partial_bill_msg }}
                                    </textarea>
                                </div>
                            </div>

                            <div class="mb-3 form-group">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script></script>
@endsection
