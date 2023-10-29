<table>
    <thead>
        <tr>
            <th colspan="4" style="background-color: #dedede;text-align: center;"><span style="color: #fff;">Techno Coupon Claim</span></th>
        </tr>
        <tr>
            <th style="font-weight: bold;">No</th>
            <th style="font-weight: bold;">Name</th>
            <th style="font-weight: bold;">Email</th>
            <th style="font-weight: bold;">Status</th>
        </tr>
    </thead>
    <tbody>
        @php
            use Carbon\Carbon;
            $dayCounter = [];
        @endphp
        @if (!in_array(Carbon::parse($item->created_at)->format('Y-m-d'), $dayCounter))
            <tr>
                <th colspan="4" style="background-color: #dedede;text-align: center;">Day {{ count($dayCounter) + 1 }} - {{ Carbon::parse($item->created_at)->format('d M Y') }}</th>
            </tr>
            @php
                array_push($dayCounter, Carbon::parse($item->created_at)->format('Y-m-d'));
            @endphp
        @endif
        @foreach ($claims as $i => $item)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $item->visitor->name }}</td>
                <td>{{ $item->visitor->email }}</td>
                <td>
                    @if ($item->is_accepted)
                        Accepted
                    @else
                        Not accepted yet
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>