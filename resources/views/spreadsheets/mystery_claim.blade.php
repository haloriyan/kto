<table>
    <thead>
        <tr>
            <th colspan="4" style="background-color: #dedede;text-align: center;"><span style="color: #fff;">Mystery Gift Claim</span></th>
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
            $renderedEmail = [];
        @endphp
        @foreach ($claims as $i => $item)
            @if (!in_array(Carbon::parse($item->created_at)->format('Y-m-d'), $dayCounter))
                <tr>
                    <th colspan="4" style="background-color: #dedede;text-align: center;">Day {{ count($dayCounter) + 1 }} - {{ Carbon::parse($item->created_at)->format('d M Y') }}</th>
                </tr>
                @php
                    array_push($dayCounter, Carbon::parse($item->created_at)->format('Y-m-d'));
                @endphp
            @endif
            @if (!in_array($item->visitor->email, $renderedEmails))
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
                @php
                    array_push($renderedEmails, $item->visitor->email);
                @endphp
            @endif
        @endforeach
    </tbody>
</table>