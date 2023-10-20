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
                @foreach ($schedules as $schedule)
                    @foreach ($schedule->appointments as $item)
                        @if ($item->seller_id == $seller->id)
                            <td>
                                {{ $item->buyer->name }}
                            </td>
                        @else
                            <td></td>
                        @endif
                    @endforeach
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>