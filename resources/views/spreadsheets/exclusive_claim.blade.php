<table>
    <thead>
        <tr>
            <th colspan="3" style="background-color: #dedede;text-align: center;"><span style="color: #fff;">Exclusive Gift Claim</span></th>
        </tr>
        <tr>
            <th style="font-weight: bold;">Name</th>
            <th style="font-weight: bold;">Email</th>
            <th style="font-weight: bold;">Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($claims as $item)
            <tr>
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