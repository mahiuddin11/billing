@extends('admin.master')
@section('content')
    <style>
        .folder-icone {
            color: #D4AC0D;
        }
    </style>

    <section id="ajax-datatable">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ $page_heading ?? 'Create' }}</h4>
                        <a href="{{ $create_url }}" class="btn btn-dark">Create</a>
                    </div>
                    <div class="card-datatable table-responsive">
                        <x-alert />
                        <div class="treeview w-20 border">
                            <ul class="mb-1 pl-3 pb-2">
                                @foreach ($rootAccount as $account)
                                    <li><i class="fas fa-folder-open folder-icone rotate"></i>
                                        <a class="text-dark" href="{{ route('accounts.edit', $account->id) }}">
                                            <span>{{ $account->head_code ? $account->head_code . ' -' : '' }}
                                                {{ $account->account_name }}</span>
                                        </a>
                                        @if ($account->subAccount->isNotEmpty())
                                            <x-account-sub :subacounts="$account->subAccount" />
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('datatablescripts')
    <!-- Datatable -->
    <script type="text/javascript">
        $(document).ready(function() {
            $('.treeview').mdbTreeview();
        });
    </script>
@endsection
