@extends('layouts.admin')

@section('title', "Detail Visitor")

@php
    use Carbon\Carbon;
    Carbon::setLocale('id-ID');
@endphp
    
@section('content')
<div class="bg-white rounded-more p-4 flex row item-center gap-20 border">
    <div class="h-80 ratio-1-1 rounded-max bg-primary flex centerize text size-24 bold">
        {{ $visitor->initial }}
    </div>
    <div class="flex column grow-1">
        <div class="text muted size-12">NAMA</div>
        <div class="text size-24 bold">{{ $visitor->name }}</div>
    </div>
    <div class="flex column grow-1">
        <div class="text muted size-12">EMAIL</div>
        <div class="text size-24 bold">{{ $visitor->email }}</div>
    </div>
</div>

<h2 class="mt-4">Riwayat Kunjungan Booth</h2>
<div class="bg-white rounded-more p-4 border">
    @if ($visitor->visits->count() == 0)
        <div>Tidak ada data</div>
    @else
        <table>
            <thead>
                <tr>
                    <th>Booth</th>
                    <th>Waktu Scan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($visitor->visits as $item)
                    <tr>
                        <td>{{ $item->exhibitor->name }}</td>
                        <td>{{ Carbon::parse($item->created_at)->isoFormat('D MMMM, HH:mm') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection