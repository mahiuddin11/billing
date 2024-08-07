@extends('admin.master')

@section('content')
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <form action="{{ $store_url ?? '#' }}" method="POST" enctype="multipart/form-data">
                @csrf
                <x-alert></x-alert>
                <div class="card">
                    <div class="card-header">
                        <h2>Personal Information</h2>
                        <p>Fill Up All Required(<span class="text-danger fw-bold fs-4">★</span>) Field Data</p>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-1">
                                <label for="">Full Name<span class="text-danger">★</span></label>
                                <input type="text" class="form-control" name="name" value="{{ old('name') }}"
                                    placeholder="Your full name">
                            </div>
                            <div class="col-md-6 mb-1">
                                <label for="">Father Name</label>
                                <input type="text" class="form-control" name="father_name"
                                    value="{{ old('father_name') }}" placeholder="Father Name">
                            </div>
                            <div class="col-md-6 mb-1">
                                <label for="">Mother Name</label>
                                <input type="text" class="form-control" name="mother_name"
                                    value="{{ old('mother_name') }}" placeholder="Mother Name">
                            </div>
                            <div class="col-md-6 mb-1">
                                <label for="">Spouse Name</label>
                                <input type="text" class="form-control" name="spouse_name"
                                    value="{{ old('spouse_name') }}" placeholder="Spouse Name">
                            </div>
                            <div class="col-md-6 mb-1">
                                <label for="">Nid Number</label>
                                <input type="text" class="form-control" name="nid" value="{{ old('nid') }}"
                                    placeholder="Nid">
                            </div>
                            <div class="col-md-6 mb-1">
                                <label for="">Nid Front Image</label>
                                <div class="form-file">
                                    <input type="file" name="nid_front" value="{{ old('nid_front') }}"
                                        class="form-file-input form-control ">
                                </div>

                            </div>
                            <div class="col-md-6 mb-1">
                                <label for="">Nid Back Image</label>
                                <div class="form-file">
                                    <input type="file" name="nid_back" value="{{ old('nid_back') }}"
                                        class="form-file-input form-control ">
                                </div>

                            </div>
                            <div class="col-md-6 mb-1">
                                <label for="">Doc Image</label>
                                <div class="form-file">
                                    <input type="file" name="doc_image" value="{{ old('doc_image') }}"
                                        class="form-file-input form-control ">
                                </div>

                            </div>

                            <div class="col-md-6 mb-1">
                                <label for="">Date Of Birth</label>
                                <div class="form-file">
                                    <input type="date" name="dob" value="{{ old('dob') }}"
                                        class="form-file-input form-control ">
                                </div>

                            </div>
                            <div class="col-md-6 mb-1">
                                <label for="">Client ID <span class="text-danger">★</span></label>
                                <input type="text" class="form-control" name="client_id"
                                    value="{{ auth()->user()->company->prefix }}{{ old('client_id') ?? $code + 1 }}"
                                    placeholder="customer Id">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h2>Contact Information</h2>
                        <p>Fill Up All Required(<span class="text-danger fw-bold fs-4">*</span>) Field Data</p>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-1">
                                <label for="">Email</label>
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}"
                                    placeholder="Email">
                            </div>
                            <div class="col-md-6 mb-1">
                                <label for="">Phone <span class="text-danger">★</span></label>
                                <input type="number" class="form-control" name="phone" value="{{ old('phone') }}"
                                    placeholder="Phone">
                            </div>
                            <div class="col-md-6 mb-1">
                                <label for="">Address</label>
                                <input type="text" class="form-control" name="address" value="{{ old('address') }}"
                                    placeholder="Address">
                            </div>
                            <div class="col-md-6 mb-1">
                                <label for="">Reference</label>
                                <input type="text" class="form-control" name="reference"
                                    value="{{ old('reference') }}" placeholder="Reference">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h2>Network & Product Information</h2>
                        <p>Fill Up All Required(<span class="text-danger fw-bold fs-4">*</span>) Field Data</p>
                        {{-- <h4 class="card-title">{{$page_heading ?? 'Create'}}</h4> --}}
                        {{-- <a href="{{$back_url ?? 'javascript:;'}}" class="btn btn-dark">Back</a> --}}
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="mb-1 col-md-6">
                                <label for="">Zone</label>
                                <select class="select2 form-control mb-1" name="zone_id" id="zone_id">
                                    <option selected value="0">Select Zone</option>
                                    @foreach ($zones as $value)
                                        <option value="{{ $value->id }}">
                                            {{ $value->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-1 col-md-6">
                                <label for="">Subzone</label>
                                <select class="select2 form-control mb-1" name="subzone_id" id="sub_zone_id">
                                    <option selected value="0">Select Subzone</option>

                                </select>
                            </div>
                            <div class="mb-1 col-md-6">
                                <label for="">Tj</label>
                                <select class="select2 form-control mb-1" name="tj_id" id="tjid">
                                    <option selected value="0">Select Zone Subzone</option>

                                </select>
                            </div>
                            <div class="mb-1 col-md-6">
                                <label for="">Spliter</label>
                                <select class="select2 form-control mb-1" name="splitter_id" id="splitterid">
                                    <option selected value="0">Select Spliter</option>
                                </select>
                            </div>
                            <div class="mb-1 col-md-6">
                                <label for="">Boxs</label>
                                <select class="select2 form-control mb-1" name="box_id" id="boxid">
                                    <option selected value="0">Select Box</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-1">
                                <label for="">Mac Address</label>
                                <input type="text" class="form-control" name="mac_address"
                                    value="{{ old('mac_address') }}" placeholder="Mac Address">
                            </div>
                            <div class="col-md-6 mb-1">
                                <label for="">Local Address</label>
                                <input type="text" class="form-control" name="ip_address"
                                    value="{{ old('ip_address') }}" placeholder="Ip Address">
                            </div>
                            <div class="col-md-6 mb-1">
                                <label for="">Remote Address</label>
                                <input type="text" class="form-control" name="remote_address"
                                    value="{{ old('remote_address') }}" placeholder="Ip Address">
                            </div>

                            <div class="col-md-6 mb-1">
                                <label for="device_id">Device <span class="text-danger">*</span></label>
                                <select name="device_id" id="device_id" class="form-control select2">
                                    <option selected="" disabled>-- Select Device --</option>
                                    @foreach ($devices as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- <div class="col-md-6 mb-1">
                                                                                                <label for="connection_type_id">Connection Type <samp class="text-danger">*</samp></label>
                                                                                                <select name="connection_type_id" id="connection_type_id" class="form-control select2">
                                                                                                    <option selected="" disabled>-- Select Connection --</option>
                                                                                                    @foreach ($connectionType as $item)
    <option value="{{ $item->id }}">{{ $item->name }}</option>
    @endforeach
                                                                                                </select>
                                                                                            </div> -->

                            <!-- <div class="col-md-6 mb-1">
                                                                                                <label for="protocol_type_id">Protocol Type <span class="text-danger">★</span></label>
                                                                                                <select name="protocol_type_id" id="protocol_type_id" onchange="protocoltype()"
                                                                                                    class="form-control select2">
                                                                                                    <option selected="" disabled>-- Select Protocol --</option>
                                                                                                    @foreach ($protocolTypes as $item)
    <option value="{{ $item->id }}">{{ $item->name }}</option>
    @endforeach
                                                                                                </select>
                                                                                            </div> -->
                            <div class="col-md-6 mb-1">
                                <label for="">Server Name <span class="text-danger">★</span></label>
                                <select name="server_id" class="form-control select2">
                                    <option selected disabled>Select Option</option>
                                    @foreach ($servers as $server)
                                        ;
                                        <option value="{{ $server->id }}">{{ $server->user_name }}
                                            ({{ $server->server_ip }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>
                </div>
                {{-- <div class="card staticIpDiv">
                <div class="card-header">
                    <h2>Static Ip</h2>
                    <p>Fill Up All Required(<span class="text-danger fw-bold fs-4">*</span>) Field Data</p>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-1">
                            <label for="">Name <span class="text-danger">★</span></label>
                            <input type="text" class="form-control queueName" name="queue_name"
                                value="{{ old('queue_name') }}">
                        </div>
                        <div class="col-md-4 mb-1">
                            <label for="">Target <span class="text-danger">★</span></label>
                            <input type="text" class="form-control queueTarget" name="queue_target"
                                value="{{ old('queue_target') }}">
                        </div>
                        <div class="col-md-4 mb-1">
                            <label for="">Dst</label>
                            <input type="text" class="form-control queueDst" name="queue_dst"
                                value="{{ old('queue_dst') }}">
                        </div>
                        <div class="col-md-6 mb-1">
                            <label for="">Max Upload</label>
                            <input type="text" class="form-control queueMaxUpload" name="queue_max_upload"
                                value="{{ old('queue_max_upload') }}">
                        </div>
                        <div class="col-md-6 mb-1">
                            <label for="">Max Download</label>
                            <input type="text" class="form-control queueMaxDownload" name="queue_max_download"
                                value="{{ old('queue_max_download') }}">
                        </div>
                    </div>
                </div>
            </div> --}}
                <div class="card">
                    <div class="card-header">
                        <h2>Service Information</h2>
                        <p>Fill Up All Required(<span class="text-danger fw-bold fs-4">*</span>) Field Data</p>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-1">
                                <div class="form-group">
                                    <label for="">Username <span class="text-danger">★</span></label>
                                    <input type="text" class="form-control" name="username"
                                        value="{{ old('username') }}">
                                </div>
                                <div class="form-group pppprofile">
                                    <label for="">P P P Profile <span class="text-danger">★</span></label>
                                    <select name="m_p_p_p_profile" onchange="profileId()"
                                        class="form-control select2 pppprofileval">
                                        <option selected disabled>Select Package</option>
                                        @foreach ($profiles as $value)
                                            <option amount="{{ $value->amount }}" value="{{ $value->id }}">
                                                {{ $value->name }} </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Billing Status <span class="text-danger">★</span></label>
                                    <select name="billing_status_id" class="form-control select2">
                                        <option selected disabled>Select Billing Status</option>
                                        @foreach ($billingStatus as $value)
                                            <option value="{{ $value->id }}">{{ $value->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="">Joining Date <span class="text-danger">★</span></label>
                                    <input type="date" class="form-control" name="connection_date"
                                        value="{{ old('connection_date') }}">
                                </div>
                                <div class="form-group">
                                    <label for="">Password <span class="text-danger">★</span></label>
                                    <input type="password" class="form-control" name="password"
                                        value="{{ old('password') }}">
                                </div>
                                <div class="form-group">
                                    <label for="">Confirm Password <span class="text-danger">★</span></label>
                                    <input type="password" class="form-control" name="password_confirmation"
                                        value="{{ old('new-password') }}">
                                </div>
                                <div class="form-group">
                                    <label for="">Skip Month</label>
                                    <input type="number" min="1" class="form-control" name="duration"
                                        value="{{ 1 ?? $editinfo->duration }}" placeholder="Skip Month">
                                </div>
                                <!-- <div class="form-group">
                                                                                                    <label for="">Disabled</label>
                                                                                                    <select name="disabled" class="form-control select2">
                                                                                                        <option value="true">Yes</option>
                                                                                                        <option selected value="false">No</option>
                                                                                                    </select>
                                                                                                </div> -->

                            </div>

                            <div class="col-md-6 mb-1">
                                <div class="form-group">
                                    <label for="">Client Type <span class="text-danger">★</span></label>
                                    <select name="client_type_id" class="form-control select2">
                                        <option selected disabled>Select Client</option>
                                        @foreach ($clientType as $value)
                                            <option value="{{ $value->id }}">{{ $value->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Started date</label>
                                    <input type="date" class="form-control speedcls" name="start_date"
                                        value="{{ old('start_date') }}" placeholder="Speed">
                                </div>
                                <div class="form-group">
                                    <label for="">Billing Period <span class="text-danger">★</span></label>
                                    <select name="billing_type" class="form-control select2">
                                        <option selected value="month_to_month">Month To Month</option>
                                        <option value="day_to_day">Day To Day</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="">Bill Amount <span class="text-danger">★</span></label>
                                    <input type="text" class="form-control billAmount" name="bill_amount"
                                        value="{{ old('bill_amount') }}" placeholder="Bill Amount">
                                </div>
                                <!-- <div class="form-group mb-1">
                                                                                                    <label for="">Installation Fee</label>
                                                                                                    <input type="number" class="form-control input-rounded" name="installation_fee"
                                                                                                        value="{{ old('installation_fee') }}" placeholder="Installation fee">
                                                                                                </div> -->
                                <div class="form-group">
                                    <label for="">Billing Person <span class="text-danger">★</span></label>
                                    <select name="billing_person" id="billing_person" class="form-control select2">
                                        <option value="">Select Billing Person</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="">Comment</label>
                                    <input type="text" class="form-control" name="comment"
                                        value="{{ old('comment') }}">

                                </div>

                                <div class="form-group">
                                    <label for="">Bill collection day</label>
                                    <input type="number" min="0" value="0" max="31"
                                        class="form-control" name="bill_collection_date"
                                        value="{{ old('bill_collection_date') }}" placeholder="Bill Collection Date">
                                </div>
                                <div class="form-group">
                                    <label for="">Auto Line Off</label>
                                    <select name="auto_line_off" id="" class="form-control">
                                        <option selected value="yes">Yes</option>
                                        <option value="no">No</option>
                                    </select>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <button class="btn btn-primary"> Save</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function protocoltype() {
            let protoclVal = $('#protocol_type_id option:selected').val();
            if (protoclVal == 1) {
                $('.staticIpDiv').show();
                $('.pppprofile').hide();

            } else {
                $('.staticIpDiv').hide();
                $('.pppprofile').show();

                $('.queueName').val('');
                $('.queueTarget').val('');
                $('.queueDst').val('');
                $('.queueMaxUpload').val('');
                $('.queueMaxDownload').val('');
            }
        }


        function profileId(e) {
            let profile = $('.pppprofileval option:selected').attr('amount');
            $('.billAmount').val(profile);
        }
        $(document).on('change', '#zone_id', function() {
            let self = $(this);
            $.ajax({
                "url": "{{ route('api.subzones') }}",
                "type": "GET",
                "data": {
                    zone_id: self.val()
                },
                cache: false,
                success: function(data) {
                    $('#sub_zone_id').empty();
                    $('#sub_zone_id').html(data);
                }
            });
        });

        $(document).on('change', '#sub_zone_id', function() {
            let self = $(this);
            $.ajax({
                "url": "{{ route('api.tjs') }}",
                "type": "GET",
                "data": {
                    subzone_id: self.val()
                },
                cache: false,
                success: function(data) {
                    $('#tj_id').empty();
                    $('#tj_id').html(data);
                }
            });
        });

        $(document).on('change', '#sub_zone_id', function() {
            let self = $(this);
            $.ajax({
                "url": "{{ route('api.tjs') }}",
                "type": "GET",
                "data": {
                    subzone_id: self.val()
                },
                cache: false,
                success: function(data) {
                    $('#tj_id').empty();
                    $('#tjid').html(data);
                }
            });
        });


        $(document).on('change', '#tjid', function() {
            let self = $(this);
            $.ajax({
                "url": "{{ route('api.splitter') }}",
                "type": "GET",
                "data": {
                    model_name: "App\\Models\\Splitter",
                    model_id: self.val(),
                    column_name: "tj_id",
                },
                cache: false,
                success: function(data) {
                    console.log(data)
                    $('#splitterid').empty();
                    $('#splitterid').html(data);
                }
            });
        });

        $(document).on('change', '#splitterid', function() {
            let self = $(this);
            $.ajax({
                "url": "{{ route('api.box') }}",
                "type": "GET",
                "data": {
                    model_name: "App\\Models\\Box",
                    model_id: self.val(),
                    column_name: "splitter_id",
                },
                cache: false,
                success: function(data) {
                    $('#boxid').empty();
                    $('#boxid').html(data);
                }
            });
        });
    </script>
@endpush
