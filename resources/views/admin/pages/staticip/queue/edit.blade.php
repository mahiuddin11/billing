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

                            <div class="row">
                                <div class="col-md-4 mb-1">
                                    <label for="">Name <span class="text-danger">★</span></label>
                                    <input type="text" class="form-control queueName" name="queue_name"
                                        value="{{ old('queue_name') ?? $editinfo->queue_name }}">
                                </div>
                                <div class="col-md-4 mb-1">
                                    <label for="">Target <span class="text-danger">★</span></label>
                                    <input type="text" class="form-control queueTarget" name="queue_target"
                                        value="{{ old('queue_target') ?? $editinfo->queue_target }}">
                                </div>
                                <div class="col-md-4 mb-1">
                                    <label for="">Dst</label>
                                    <input type="text" class="form-control queueDst" name="queue_dst"
                                        value="{{ old('queue_dst') ?? $editinfo->queue_dst }}">
                                </div>
                                <div class="col-md-6 mb-1">
                                    <label for="">Max Upload</label>
                                    <input type="text" class="form-control queueMaxUpload" name="queue_max_upload"
                                        value="{{ old('queue_max_upload') ?? $editinfo->queue_max_upload }}">
                                </div>
                                <div class="col-md-6 mb-1">
                                    <label for="">Max Download</label>
                                    <input type="text" class="form-control queueMaxDownload" name="queue_max_download"
                                        value="{{ old('queue_max_download') ?? $editinfo->queue_max_download }}">
                                </div>
                                <div class="col-md-6 mb-1">
                                    <label for="">Server Name <span class="text-danger">★</span></label>
                                    <select name="server_id" class="form-control select2">
                                        <option selected disabled>Select Option</option>
                                        @foreach ($servers as $server)
                                            ;
                                            <option {{ $editinfo->server_id == $server->id ? 'selected' : '' }}
                                                value="{{ $server->id }}">{{ $server->user_name }}
                                                ({{ $server->server_ip }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 mb-1">
                                    <label for="">Amount</label>
                                    <input type="text" class="form-control queueMaxDownload" name="amount"
                                        value="{{ old('amount') ?? $editinfo->amount }}">
                                </div>

                            </div>

                            <div class="mb-1 form-group">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function availableBalance() {
            let balance = $('.payMeth option:selected').attr('available');
            $('.balance-message').text('Available balance is ' + balance);
        }
        availableBalance();
    </script>
@endpush
