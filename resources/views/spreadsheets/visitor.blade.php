@php
    use Carbon\Carbon;
@endphp
<table>
    <thead>
        <tr>
            <th colspan="4" style="background-color: #dedede;text-align: center;"><span style="color: #fff;">KMTE Visitors</span></th>
        </tr>
        <tr>
            <th style="font-weight: bold;">No</th>
            <th style="font-weight: bold;">Name</th>
            <th style="font-weight: bold;">Email</th>
            <th style="font-weight: bold;">Registered At</th>
            <th style="font-weight: bold;">Sellers Visited</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($visitors as $i => $visitor)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $visitor->name }}</td>
                <td>{{ $visitor->email }}</td>
                <td>{{ Carbon::parse($visitor->created_at)->format('d M Y') }}</td>
                <td>{{ implode(",", $visitor->seller_visits) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>