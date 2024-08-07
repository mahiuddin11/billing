@extends('admin.master')

@section('content')
     <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ $page_heading ?? 'Edit' }}</h4>
                    <a href="{{ route('mactariffconfig.index') }}" class="btn btn-dark">Back</a>
                </div>
                <div class="card-body">

                    <x-alert></x-alert>

                    <div class="basic-form">
                        <form action="{{ route('mactariffconfig.tarifPackageUpdate',$package2->id)}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-1">
                                    <label for="">Package</label>
                                    <input type="text" class="form-control"
                                        value="{{ $editinfo->name }}" name="name">
                                </div>
                                <div class="col-md-6 mb-1">
                                    <label for="">PACKAGE NAME</label>
                                    <select id="mac_package_id" name="mac_package_id" class="select2 form-control">
                                        <option>Select Option</option>
                                        @foreach ($macpackages as $macpackage)
                                            <option {{$editinfo->mac_package_id == $macpackage->id ? 'selected' : ''}} value="{{ $macpackage->id }}">{{ $macpackage->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="package_error text-danger d-none">Please select a Package Name
                                    </span>
                                </div>
                                <div class="col-md-6 mb-1">
                                    <label for="">PROTOCOL TYPE</label>
                                    <select name="protocol_id" class="form-control">
                                        <option>Select Option</option>
                                        @foreach ($protocols as $protocol)
                                            <option value="{{ $protocol->id }}"{{$editinfo->protocol_id == $protocol->id ? 'selected' : ''}}>{{ $protocol->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="protocole_type_error text-danger d-none">Please select a server</span>
                                </div>

                                <div class="col-md-6 mb-1">
                                    <label for="">PACKAGE RATE</label>
                                    <input type="number" class="form-control" name="rate"
                                        value="{{ $editinfo->rate }}">
                                    <span class="package_rate_error text-danger d-none">Please provide a Package Rate</span>
                                </div>

                                <div class="col-md-6 mb-1">
                                    <label for="">SERVER NAME</label>
                                    <select name="server_id" class="form-control">
                                        <option>Select Option</option>
                                        @foreach ($servers as $server)
                                            <option value="{{ $server->id }}" {{ $editinfo->server_id == $server->id ? 'selected' : ''}}>{{ $server->user_name }}
                                                ({{ $server->server_ip }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 mb-1">
                                    <label for="">PROFILE (SPEED)</label>
                                    <select name="m_profile_id" class="form-control">
                                        @foreach ($mpppprofiles as $mpppprofile )
                                            <option value="{{$mpppprofile->id}}" {{$editinfo->m_profile_id == $mpppprofile->id ? 'selected' : ''}}>{{$mpppprofile->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12 mb-1" style="text-align:right">
                                    <input type="submit"  class="btn btn-success" value="Save Changes">
                                </div>
                            </div>
                            <hr>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
