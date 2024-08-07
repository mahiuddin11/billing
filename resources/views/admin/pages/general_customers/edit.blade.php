@extends('admin.master')

@section('content')
<div class="row">
    <div class="col-xl-12 col-lg-12">
        <form action="{{ $update_url ?? '#' }}" method="POST" enctype="multipart/form-data">
            @csrf
            <x-alert></x-alert>
            <div class="card">
                <div class="card-header">
                    <h4>Personal Information</h4>
                    <p>Fill Up All Required(<span class="text-red fw-bold fs-4">*</span>) Field Data</p>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-1">
                            <label for="">Full Name</label>
                            <input type="text" class="form-control input-rounded" name="name"
                                value="{{ old('name') ?? $editinfo->name }}" placeholder="Your full name">
                        </div>
                        <div class="col-md-6 mb-1">
                            <label for="">Spouse Name</label>
                            <input type="text" class="form-control input-rounded" name="spouse_name"
                                value="{{ old('spouse_name') ?? $editinfo->spouse_name }}" placeholder="Spouse Name">
                        </div>
                        <div class="col-md-6 mb-1">
                            <label for="">Nid</label>
                            <input type="text" class="form-control input-rounded" name="nid"
                                value="{{ old('nid') ?? $editinfo->nid }}" placeholder="Nid">
                        </div>
                        <div class="col-md-6 mb-1">
                            <label for="">Doc Image</label>
                            <input type="file" name="doc_image" value="{{ old('doc_image') }}"
                                class="form-file-input form-control ">

                        </div>
                        <div class="col-md-6 mb-1">
                            <label for="">Date Of Birth</label>
                            <input type="date" name="dob" value="{{ old('dob') ?? $editinfo->dob }}"
                                class="form-file-input form-control ">

                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4>Contact Information</h4>
                    <p>Fill Up All Required(<span class="text-red fw-bold fs-4">*</span>) Field Data</p>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-1">
                            <label for="">Email</label>
                            <input type="email" class="form-control input-rounded" name="email"
                                value="{{ old('email') ?? $editinfo->email }}" placeholder="Email">
                        </div>
                        <div class="col-md-6 mb-1">
                            <label for="">Phone</label>
                            <input type="text" class="form-control input-rounded" name="phone"
                                value="{{ old('phone') ?? $editinfo->phone }}" placeholder="Phone">
                        </div>
                        <div class="col-md-6 mb-1">
                            <label for="">Address</label>
                            <input type="text" class="form-control input-rounded" name="address"
                                value="{{ old('address') ?? $editinfo->address }}" placeholder="Address">
                        </div>
                        <div class="col-md-6 mb-1">
                            <label for="">Reference</label>
                            <input type="text" class="form-control input-rounded" name="reference"
                                value="{{ old('reference') ?? $editinfo->reference }}" placeholder="Reference">
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
    protocoltype();


    function Queues() {
        let profile = $('.queueValue option:selected').attr('amount');
        $('.billAmount').val(profile);
    }

    Queues();

    $(document).on('change', '#zone_id', function () {
        $.ajax({
            url: "{{route('api.subzones')}}",
            method: "get",
            data: {
                zone_id: $(this).val()
            },
            success: function (data) {
                $('#subzone_id').empty();
                $('#subzone_id').html(data);
            }
        });
    });

    $(document).on('change', '#subzone_id', function () {
        $.ajax({
            url: "{{route('api.new_tj')}}",
            method: "POST",
            data: {
                subzone_id: $(this).val()
            },
            success: function (data) {
                $('#tj_id').empty();
                $('#tj_id').html(data);
            }
        });
    });
    $(document).on('change', '#tj_id', function () {
        $.ajax({
            url: "{{route('api.new_cores')}}",
            method: "POST",
            data: {
                model_name: "App\\Models\\Tj",
                model_id: $(this).val()
            },
            success: function (data) {
                $('#tj_core_id').empty();
                $('#tj_core_id').html(data);
            }
        });
    });
    $(document).on('change', '#tj_core_id', function () {
        $.ajax({
            url: "{{route('api.new_splitters')}}",
            method: "POST",
            data: {
                column_name: "tj_core_id",
                id: $(this).val()
            },
            success: function (data) {
                $('#splitter_id').empty();
                $('#splitter_id').html(data);
            }
        });
    });
    $(document).on('change', '#splitter_id', function () {
        $.ajax({
            url: "{{route('api.new_cores')}}",
            method: "POST",
            data: {
                model_name: "App\\Models\\Splitter",
                model_id: $(this).val()
            },
            success: function (data) {
                $('#splitter_core_id').empty();
                $('#splitter_core_id').html(data);
            }
        });
    });
</script>
@endpush
