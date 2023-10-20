@php
    use Carbon\Carbon;
@endphp
<table>
    <thead>
        <tr>
            <th style="font-weight: bold;">APPOINTMENT SCHEDULE</th>
            @foreach ($sellers as $seller)
                <th style="font-weight: bold;">{{ $seller->name }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($schedules as $i => $schedule)
            <tr>
                <td>{{ Carbon::parse($schedule->date)->format('H:i') }} - {{ Carbon::parse($schedule->date)->addMinutes(20)->format('H:i') }}</td>
                @foreach ($sellers as $seller)
                    @foreach ($seller->appointments as $item)
                        @if ($item->schedule_id == $schedule->id)
                            <td>{{ $item->buyer->name }}
                                @if ($item->buyer->join_type == "company")
                                    ({{ $item->buyer->from_company }})
                                @endif
                            </td>
                        @endif
                    @endforeach
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>