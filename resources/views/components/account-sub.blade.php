@foreach ($subacounts as $account)
    <ul class="nested">
        @if (!in_array($account->id, [8, 14, 10]))
            <li><a href="{{ route('accounts.destroy', $account->id) }}" onclick="return confirm('Are You Sure')"><i
                        class="fas fa-trash" style="color: red"></i> </a>
                <i class="fas fa-folder-open folder-icone rotate"></i>
                <a class="text-dark" href="{{ route('accounts.edit', $account->id) }}">
                    <span>{{ $account->head_code ? $account->head_code . ' -' : '' }}
                        {{ $account->account_name }} - ({{ $account->amount_account() }}) </span>
                </a>
                @if ($account->subAccount->isNotEmpty())
                    <x-account-sub :subacounts="$account->subAccount" />
                @endif
            </li>
        @endif
    </ul>
@endforeach
