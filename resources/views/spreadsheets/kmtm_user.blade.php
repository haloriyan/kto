<table>
    <thead>
        <tr>
            <th colspan="6" style="background-color: #dedede;text-align: center;"><span style="color: #fff;">KMTM Buyer</span></th>
        </tr>
        <tr>
            <th style="font-weight: bold;">Name</th>
            <th style="font-weight: bold;">Email</th>
            <th style="font-weight: bold;">Phone</th>
            <th style="font-weight: bold;">Brand / Company</th>
            <th style="font-weight: bold;">Line of Business</th>
            <th style="font-weight: bold;">Established Year</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->phone }}</td>
                <td>
                    @if ($user->join_type == "personal")
                        Personal
                    @else
                        {{ $user->from_company }}
                    @endif
                </td>
                <td>
                    @if ($user->join_type == "personal")
                        -
                    @else
                        {{ $user->line_of_business }}
                    @endif
                </td>
                <td>
                    @if ($user->join_type == "personal")
                        -
                    @else
                        {{ $user->company_established }}
                    @endif
                </td>
                @if ($user->custom_field != null)
                    @foreach (json_decode($user->custom_field, false) as $item)
                        <td>{{ $item->body }} : {{ $item->answer == null ? "-" : $item->answer }}</td>
                    @endforeach
                @endif
            </tr>
        @endforeach
    </tbody>
</table>