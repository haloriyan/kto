@php
    use Carbon\Carbon;
@endphp

<table>
    <thead>
        <tr>
            <th style="font-weight: bold;">APPOINTMENT SCHEDULE</th>
            @foreach ($schedules as $schedule)
                <th style="font-weight: bold;">{{ Carbon::parse($schedule->date)->format('H:i') }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($sellers as $seller)
            <tr>
                <td>{{ $seller->name }}</td>
                @foreach ($times as $time)
                    @foreach ($seller->appointments as $item)
                        @if (Carbon::parse($item->schedule->date)->format('H:i') == $time)
                            <td>{{ $item->buyer->name }}</td>
                        @endif
                    @endforeach
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>