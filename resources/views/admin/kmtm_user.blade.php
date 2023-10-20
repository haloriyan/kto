@extends('layouts.admin')

@section('title', "Buyer KMTM")
    
@section('content')
@if ($message != "")
    <div class="bg-green rounded p-2 mb-2">
        {{ $message }}
    </div>
@endif

<div class="bg-white p-4 rounded-more shadow">
    <div class="flex row justify-end">
        <div class="flex column grow-1">
            <div class="text small">Total Buyer :</div>
            <h2 class="text size-36 mt-0">{{ $total }}</h2>
        </div>
        <form>
            <div class="group">
                <input type="text" id="search" name="q" value="{{ $request->q }}">
                <label for="search">Cari :</label>
                @if ($request->q != "")
                    <a href="{{ route('admin.kmtmUser') }}" class="text red absolute right-5 top-0 mt-3 pointer">
                        <i class="bx bx-x"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
    <div class="flex row item-center gap-20 justify-end mb-2">
        <a href="{{ route('admin.kmtmUser.export', ['type' => 'b2b']) }}" class="flex justify-end">
            <button class="green small">Download B2B Data</button>
        </a>
        <a href="{{ route('admin.kmtmUser.export', ['type' => 'b2c']) }}" class="flex justify-end">
            <button class="green small">Download B2C Data</button>
        </a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Telepon</th>
                <th>Brand / Company</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>
                        <a href="{{ route('admin.kmtmDetail', $user->id) }}" class="text primary underline">
                            {{ $user->name }}
                        </a>
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phone }}</td>
                    @if ($user->join_type == "personal")
                        <td>Personal</td>
                    @else
                        <td>{{ $user->from_company }} ({{ ucwords($user->join_type) }})</td>
                    @endif
                    <td style="display: flex;flex-direction: row; gap: 20px;">
                        @if ($user->eligible)
                            {{-- <button class="primary small">
                                Kirim Notifikasi
                            </button> --}}
                        @endif
                        <a href="{{ route('admin.kmtmEligible', $user->id) }}" class="switch {{ $user->eligible ? 'on' : '' }}">
                            <div></div>
                        </a>
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