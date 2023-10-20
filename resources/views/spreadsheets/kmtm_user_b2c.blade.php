<table>
    <thead>
        <tr>
            <th colspan="6" style="background-color: #dedede;text-align: center;"><span style="color: #fff;">KMTM Buyer (B2C)</span></th>
        </tr>
        <tr>
            <th style="font-weight: bold;">Name</th>
            <th style="font-weight: bold;">Email</th>
            <th style="font-weight: bold;">Phone</th>
            <th style="font-weight: bold;">Website / SNS</th>
            <th style="font-weight: bold;">Company Referer</th>
            <th style="font-weight: bold;">Want to visit sellers</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->phone }}</td>
                <td>{{ $user->website }}</td>
                <td>{{ $user->reference }}</td>
                <td>{{ $user->interesting_sellers }}</td>
            </tr>
        @endforeach
    </tbody>
</table>