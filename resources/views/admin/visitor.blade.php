@extends('layouts.admin')

@section('title', "Peserta Event")

@php
    use Carbon\Carbon;
@endphp
    
@section('content')
<div class="bg-white p-4 rounded-more shadow">
    <div class="flex row justify-end">
        <div class="flex column grow-1">
            <div class="text small">Total Peserta :</div>
            <h2 class="text size-36 mt-0">{{ $total_visitor }}</h2>
        </div>
        <form>
            <div class="group">
                <input type="text" id="search" name="q" value="{{ $request->q }}">
                <label for="search">Cari :</label>
                @if ($request->q != "")
                    <a href="{{ route('admin.visitor') }}" class="text red absolute right-5 top-0 mt-3 pointer">
                        <i class="bx bx-x"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Terdaftar Pada</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($visitors as $visitor)
                <tr>
                    <td>
                        <a href="{{ route('admin.visitor.detail', $visitor->id) }}" class="text underline primary">
                            {{ $visitor->name }}
                        </a>
                    </td>
                    <td>{{ $visitor->email }}</td>
                    <td>{{ Carbon::parse($visitor->created_at)->format('d M, H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-3">
        {{ $visitors->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection