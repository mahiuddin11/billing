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
                        <a href="{{ $back_url ?? 'javascript:;' }}" class="btn btn-dark">Back</a>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-1">
                                <label for="">Full Name <span class="text-danger">★</span></label>
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
                                <label for="">Nid</label>
                                <input type="text" class="form-control" name="nid" value="{{ old('nid') }}"
                                    placeholder="Nid">
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
                                    value="{{ auth()->user()->company->prefix }}{{ old('client_id') }}"
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
                            <div class="col-md-6 mb-1">
                                <label for="">Zone</label>

                                <select name="zone_id" id="zone_id" class="form-control select2">
                                    <option selected="" disabled>-- Select Zone --</option>
                                    @foreach ($zones as $item)
                                        <option value="{{ $item->id }}"
                                            {{ old('zone_id') == $item->id ? 'selected' : '' }}>{{ $item->name }}
                                        </option>
                                    @endforeach
                                </select>

                            </div>
                            <div class="col-md-6 mb-1">
                                <label for="">Splitter</label>

                                <select name="splitter_id" id="splitter_id" class="form-control select2">
                                    <option value="">Select Option</option>
                                </select>

                            </div>
                            <div class="col-md-6 mb-1">
                                <label for="">Splitter Core</label>

                                <select name="splitter_core_id" id="splitter_core_id" class="form-control select2">
                                    <option value="">Select Option</option>
                                </select>

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

                            <div class="col-md-6 mb-1">
                                <label for="">Server Name <span class="text-danger">★</span></label>
                                <select name="server_id" class="form-control select2">
                                    <option selected disabled>Select Option</option>
                                    @foreach ($servers as $server)
                                        <option value="{{ $server->id }}">{{ $server->user_name }}
                                            ({{ $server->server_ip }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h2>Service Information</h2>
                        <p>Fill Up All Required(<span class="text-danger fw-bold fs-4">*</span>) Field Data</p>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-1">
                                <div class="form-group">
                                    <label for="">Queue Name <span class="text-danger">★</span></label>
                                    <input type="text" class="form-control queueName" name="queue_name"
                                        value="{{ old('queue_name') }}">
                                </div>
                                <div class="form-group">
                                    <label for="">Max Upload <span class="text-danger">★</span></label>
                                    <input type="text" class="form-control queueMaxUpload" name="queue_max_upload"
                                        value="{{ old('queue_max_upload') }}">
                                </div>

                                <div class="form-group">
                                    <label for="">Dst</label>
                                    <input type="text" class="form-control queueDst" name="queue_dst"
                                        value="{{ old('queue_dst') }}">
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
                                    <label for="">Connection Date <span class="text-danger">★</span></label>
                                    <input type="date" class="form-control" name="connection_date"
                                        value="{{ old('connection_date') }}">
                                </div>
                                <div class="form-group">
                                    <label for="">Skip Month</label>
                                    <input type="number" min="1" class="form-control" name="duration"
                                        value="{{ 1 ?? $editinfo->duration }}" placeholder="Skip Month">
                                </div>

                                <div class="form-group">
                                    <label for="">Bill Collection Days</label>
                                    <input type="number" min="0" value="0" max="31"
                                        class="form-control" name="bill_collection_date"
                                        value="{{ old('bill_collection_date') }}" placeholder="Bill Collection Date">
                                </div>
                            </div>

                            <div class="col-md-6 mb-1">
                                <div class="form-group">
                                    <label for="">Target <span class="text-danger">★</span></label>
                                    <input type="text" class="form-control queueTarget" name="queue_target"
                                        value="{{ old('queue_target') }}">
                                </div>
                                <div class="form-group">
                                    <label for="">Max Download <span class="text-danger">★</span></label>
                                    <input type="text" class="form-control queueMaxDownload" name="queue_max_download"
                                        value="{{ old('queue_max_download') }}">
                                </div>
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

                                <div class="form-group">
                                    <label for="">Billing Person </label>
                                    <select name="billing_person" id="billing_person" class="form-control select2">
                                        <option value="">Select Billing Person</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
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


        function Queues() {
            let profile = $('.queueValue option:selected').attr('amount');
            $('.billAmount').val(profile);
        }

        $(document).on('change', '#zone_id', function() {
            $.ajax({
                url: "{{ route('api.subzones') }}",
                method: "get",
                data: {
                    zone_id: $(this).val()
                },
                success: function(data) {
                    $('#subzone_id').empty();
                    $('#subzone_id').html(data);
                }
            });
        });

        $(document).on('change', '#subzone_id', function() {
            $.ajax({
                url: "{{ route('api.new_tj') }}",
                method: "POST",
                data: {
                    subzone_id: $(this).val()
                },
                success: function(data) {
                    $('#tj_id').empty();
                    $('#tj_id').html(data);
                }
            });
        });
        $(document).on('change', '#tj_id', function() {
            $.ajax({
                url: "{{ route('api.new_cores') }}",
                method: "POST",
                data: {
                    model_name: "App\\Models\\Tj",
                    model_id: $(this).val()
                },
                success: function(data) {
                    $('#tj_core_id').empty();
                    $('#tj_core_id').html(data);
                }
            });
        });
        $(document).on('change', '#tj_core_id', function() {
            $.ajax({
                url: "{{ route('api.new_splitters') }}",
                method: "POST",
                data: {
                    column_name: "tj_core_id",
                    id: $(this).val()
                },
                success: function(data) {
                    $('#splitter_id').empty();
                    $('#splitter_id').html(data);
                }
            });
        });
        $(document).on('change', '#splitter_id', function() {
            $.ajax({
                url: "{{ route('api.new_cores') }}",
                method: "POST",
                data: {
                    model_name: "App\\Models\\Splitter",
                    model_id: $(this).val()
                },
                success: function(data) {
                    $('#splitter_core_id').empty();
                    $('#splitter_core_id').html(data);
                }
            });
        });
    </script>
@endpush
