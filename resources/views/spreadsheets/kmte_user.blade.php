<table>
    <thead>
        <tr>
            <th colspan="6" style="background-color: #dedede;text-align: center;"><span style="color: #fff;">KMTE User</span></th>
        </tr>
        <tr>
            <th style="font-weight: bold;">Name</th>
            <th style="font-weight: bold;">Email</th>
            <th style="font-weight: bold;">Phone</th>
            <th style="font-weight: bold;">Company Referer</th>
            <th style="font-weight: bold;">Willing to Get Called</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->phone }}</td>
                <td>{{ $user->referer }}</td>
                <td>
                    @if ($user->eligible)
                        Yes 
                    @else
                        No
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>