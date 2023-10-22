@php
    use Carbon\Carbon;
@endphp

<table border="1">
    <thead>
        <tr>
            <th style="font-weight: bold;">APPOINTMENT SCHEDULE</th>
            @foreach ($schedules as $schedule)
                <th style="font-weight: bold;">{{ $schedule }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($sellers as $seller)
            <tr>
                <td>{{ $seller->name }}</td>
                @foreach ($seller->appointments as $a => $app)
                    @if ($app != null && $schedules[$a] == Carbon::parse($app->schedule->date)->format('H:i')) 
                        <td>{{ $app->buyer->name }}</td>
                    @else
                        <td></td>
                    @endif
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>