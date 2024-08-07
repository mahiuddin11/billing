@extends('admin.master')

@section('content')
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ $page_heading ?? 'Profile' }}</h4>
                    <a href="{{ $back_url ?? 'javascript:;' }}" class="btn btn-dark">Back</a>
                </div>
                <div class="card-body">

                    <x-alert></x-alert>

                    <div class="basic-form">
                        <form action="{{ route('update.change.password') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-4 mb-1">
                                    <label>User Name</label>
                                    <input type="text" class="form-control input-rounded"
                                        value="{{ auth()->user()->username }}" name="username" placeholder="username">
                                </div>
                                <div class="col-md-4 mb-1">
                                    <label>Password</label>
                                    <input type="password" class="form-control input-rounded" name="password"
                                        placeholder="Password">
                                </div>
                                <div class="col-md-4 mb-1">
                                    <label>Confirmation Password</label>
                                    <input type="password" class="form-control input-rounded" name="password_confirmation"
                                        placeholder="Re-enter your password">
                                </div>
                            </div>
                            <div class="mb-1 form-group">
                                <button type="submit" class="btn btn-primary">Change</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
