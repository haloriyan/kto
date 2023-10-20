@extends('layouts.admin')

@section('title', "KMTE User")
    
@section('content')
<div class="bg-white p-4 rounded-more shadow">
    <div class="flex row justify-end">
        <div class="flex column grow-1">
            <div class="text small">Total User :</div>
            <h2 class="text size-36 mt-0">{{ $total }}</h2>
        </div>
        <form>
            <div class="group">
                <input type="text" id="search" name="q" value="{{ $request->q }}">
                <label for="search">Cari :</label>
                @if ($request->q != "")
                    <a href="{{ route('admin.kmteUser') }}" class="text red absolute right-5 top-0 mt-3 pointer">
                        <i class="bx bx-x"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="flex row item-center gap-20 justify-end mb-2">
        <a href="{{ route('admin.kmteUser.export') }}" class="flex justify-end">
            <button class="green small">Download Excel</button>
        </a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Telepon</th>
                <th>Company Referer</th>
                <th>Willing to call?</th>
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

    <div class="mt-2">
        {{ $users->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection