@extends('layouts.admin')

@section('title', "Dashboard")
    
@section('content')
<div class="flex row gap-20">
    <a href="{{ route('admin.visitor') }}" class="flex row grow-1 item-center rounded bg-primary p-3">
        <div class="flex column grow-1">
            <div class="h3 text size-24 bold m-0">{{ $total_visitor }}</div>
            <div class="text size-12">Peserta Event</div>
        </div>
        <div class="h-70 rounded-max ratio-1-1 flex centerize bg-white dark">
            <i class="bx bx-group text size-32"></i>
        </div>
    </a>
    <a href="{{ route('admin.appointment') }}" class="flex row grow-1 item-center rounded bg-green p-3">
        <div class="flex column grow-1">
            <div class="h3 text size-24 bold m-0">{{ $appointments->count() }}</div>
            <div class="text size-12">Jadwal Janji Temu</div>
        </div>
        <div class="h-70 rounded-max ratio-1-1 flex centerize bg-green dark">
            <i class="bx bx-home text size-32"></i>
        </div>
    </a>
    <div class="flex row grow-1 item-center rounded bg-blue p-3">
        <div class="flex column grow-1">
            <div class="h3 text size-24 bold m-0">24</div>
            <div class="text size-12">Jadwal Janji Temu</div>
        </div>
        <div class="h-70 rounded-max ratio-1-1 flex centerize bg-blue dark">
            <i class="bx bx-home text size-32"></i>
        </div>
    </div>
</div>

<div class="flex row item-center">
    <h2 class="mt-4 text size-32 flex grow-1 mr-2">Peserta Event</h2>
    <a href="{{ route('admin.visitor') }}" class="border text primary rounded p-1 pl-2 pr-2 pointer small">
        Lihat Semua
    </a>
</div>

<table>
    <thead>
        <tr>
            <th>Nama</th>
            <th>Email</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($visitors as $visitor)
            <tr>
                <td>{{ $visitor->name }}</td>
                <td>{{ $visitor->email }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection