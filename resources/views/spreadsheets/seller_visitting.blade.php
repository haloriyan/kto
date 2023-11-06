<table>
    <thead>
        <tr>
            <th colspan="4" style="background-color: #dedede;text-align: center;"><span style="color: #fff;">Scan Report for {{ $seller->name }}</span></th>
        </tr>
        <tr>
            <th style="font-weight: bold;">No</th>
            <th style="font-weight: bold;">Name</th>
            <th style="font-weight: bold;">Email</th>
            <th style="font-weight: bold;">Timestamp</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($scans as $i => $item)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $item->visitor->name }}</td>
                <td>{{ $item->visitor->email }}</td>
                <td>{{ $item->created_at }}</td>
            </tr>
        @endforeach
    </tbody>
</table>