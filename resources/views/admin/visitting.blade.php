@extends('layouts.admin')
@php
    use Carbon\Carbon;
@endphp

@section('title', "Kunjungan Booth")
    
@section('content')
<div class="bg-white p-4 rounded-more shadow">
    <div class="flex row justify-end">
        <form>
            <div class="group">
                <input type="text" id="search" name="q" value="{{ $request->q }}">
                <label for="search">Cari :</label>
                @if ($request->q != "")
                    <a href="{{ route('admin.visitting') }}" class="text red absolute right-5 top-0 mt-3 pointer">
                        <i class="bx bx-x"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
    <table>
        <thead>
            <tr>
                <th>Visitor</th>
                <th>Exhibitor</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($visits as $item)
                <tr>
                    <td>{{ $item->visitor->name }}</td>
                    <td>{{ $item->exhibitor->name }}</td>
                    <td>
                        {{ Carbon::parse($item->created_at)->format('d M, H:i') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-2">
        {{ $visits->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection