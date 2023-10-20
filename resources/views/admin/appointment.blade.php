@extends('layouts.admin')

@section('title', "Janji-Temu")

@php
    use Carbon\Carbon;
@endphp
    
@section('content')
<div class="bg-white p-4 rounded-more shadow">
    <div class="flex row justify-end">
        <div class="flex column grow-1">
            <div class="text small">Total Data :</div>
            <h2 class="text size-36 mt-0">{{ $total_data->count() }}</h2>
        </div>
        <form>
            <div class="group">
                <input type="text" id="search" name="q" value="{{ $request->q }}">
                <label for="search">Cari :</label>
                @if ($request->q != "")
                    <a href="{{ route('admin.appointment') }}" class="text red absolute right-5 top-0 mt-3 pointer">
                        <i class="bx bx-x"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="flex row item-center gap-20 justify-end mb-2">
        <a href="{{ route('admin.appointment.export') }}" class="flex justify-end">
            <button class="green small">Download B2B Data</button>
        </a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nama Peserta</th>
                <th>Seller</th>
                <th>Jadwal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($appointments as $item)
                <tr>
                    <td>{{ $item->buyer->name }}
                        @if ($item->buyer->join_type == "company")
                            ({{ $item->buyer->from_company }})
                        @endif
                    </td>
                    <td>{{ $item->seller->name }}</td>
                    <td>
                        @if ($item->buyer->join_type == "company")
                            {{ Carbon::parse($item->schedule->date)->format('d M - H:i') }}
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-3">
        {{ $appointments->links('pagination::bootstrap-4') }}
    </div>

</div>
@endsection