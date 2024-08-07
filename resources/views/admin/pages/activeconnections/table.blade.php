    @forelse  ($data as $table)
        <tr>
            <td>{{ $table['name'] }}</td>
            <td>{{ $table['service'] }}</td>
            <td>{{ $table['caller-id'] }}</td>
            <td>{{ $table['address'] }}</td>
            <td>{{ $table['uptime'] }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="5" class="text-center">No data found</td>
        </tr>
    @endforelse
