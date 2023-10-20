@extends('layouts.admin')

@section('title', "Jadwal Appointment")

@php
    use Carbon\Carbon;
    Carbon::setLocale('id-ID');
@endphp

@section('head')
<link rel="stylesheet" href="{{ asset('js/flatpickr/dist/flatpickr.min.css') }}">
<link rel="stylesheet" href="{{ asset('js/flatpickr/dist/themes/airbnb.css') }}">
@endsection
    
@section('content')
@if ($message != "")
    <div class="bg-green rounded-more p-2 pl-4 pr-4 mb-3">
        {{ $message }}
    </div>
@endif

@if ($errors->count() > 0)
    @foreach ($errors->all() as $err)
        <div class="bg-red rounded-more p-2 pl-4 pr-4 mb-2 text small">
            {{ $err }}
        </div>
    @endforeach
@endif

<div class="bg-white rounded-more shadow p-4">
    @if ($schedules->count() == 0)
        <div class="flex row gap-20">
            <h2 class="m-0">Belum ada jadwal</h2>
            <button class="small primary" onclick="modal('#add').show()">Tambah</button>
        </div>
    @else
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Jam</th>
                    <th>Tipe</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($schedules as $item)
                    <tr>
                        <td>{{ Carbon::parse($item->date)->isoFormat('D MMMM') }}</td>
                        <td>{{ Carbon::parse($item->date)->isoFormat('HH:mm') }}</td>
                        <td>{{ strtoupper($item->type) }}</td>
                        <td>
                            <button onclick="edit('{{ $item }}')" class="blue small">Edit</button>
                            <button onclick="del('{{ $item }}')" class="red small">Hapus</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

<div class="modal" id="del">
    <div class="modal-body">
        <div class="modal-title">
            Hapus Jadwal
            <span class="bx bx-x modal-close" hide></span>
        </div>
        <form action="{{ route('schedule.delete') }}" class="modal-content" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type="hidden" id="id" name="id">
            <div class="text center size-14">Yakin ingin menghapus jadwal ini?</div>

            <div class="mt-3 flex row item-center justify-center gap-20">
                <div class="pointer text muted" onclick="modal('#del').hide()">Batal</div>
                <div class="pointer text red bold" onclick="select('#del form').submit()">Hapus</div>
            </div>
        </form>
    </div>
</div>

<div class="modal" id="add">
    <div class="modal-body">
        <div class="modal-title">
            Tambah Jadwal
            <span class="bx bx-x modal-close" hide></span>
        </div>
        <form action="{{ route('schedule.add') }}" class="modal-content" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="flex row gap-20">
                <div class="flex column grow-1">
                    <div class="group">
                        <input type="text" id="date" name="date" required>
                        <label class="active" for="date">Tanggal :</label>
                    </div>
                    <div class="text small muted">TAHUN-BULAN-TANGGAL</div>
                </div>
                <div class="flex column grow-1">
                    <div class="group">
                        <input type="text" id="time" name="time" required>
                        <label class="active" for="time">Waktu :</label>
                    </div>
                    <div class="text small muted">JAM:MENIT</div>
                </div>
            </div>

            <div class="group">
                <select name="type" id="type">
                    <option value="b2b">B2B</option>
                    <option value="b2c">B2C</option>
                </select>
                <label for="type" class="active">Tipe :</label>
            </div>

            <button class="primary w-100 mt-2">Submit</button>
        </form>
    </div>
</div>

<div class="modal" id="edit">
    <div class="modal-body">
        <div class="modal-title">
            Ubah Jadwal
            <span class="bx bx-x modal-close" hide></span>
        </div>
        <form action="{{ route('schedule.edit') }}" class="modal-content" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type="hidden" id="id" name="id">
            <div class="flex row gap-20">
                <div class="flex column grow-1">
                    <div class="group">
                        <input type="text" id="date" name="date" required>
                        <label class="active" for="date">Tanggal :</label>
                    </div>
                    <div class="text small muted">TAHUN-BULAN-TANGGAL</div>
                </div>
                <div class="flex column grow-1">
                    <div class="group">
                        <input type="text" id="time" name="time" required>
                        <label class="active" for="time">Waktu :</label>
                    </div>
                    <div class="text small muted">JAM:MENIT</div>
                </div>
            </div>

            <button class="primary w-100 mt-2">Submit</button>
        </form>
    </div>
</div>

<button class="FAB primary" onclick="modal('#add').show()">
    <i class="bx bx-plus"></i>
</button>
@endsection

@section('javascript')
<script src="{{ asset('js/flatpickr/dist/flatpickr.min.js') }}"></script>
<script>
    flatpickr("#date", {
        format: 'Y-m-d',
        minDate: "{{ date('Y-m-d') }}"
    });
    flatpickr("#time", {
        noCalendar: true,
        enableTime: true,
        time_24hr: true,
        format: 'H:i',
    })

    const del = data => {
        data = JSON.parse(data);
        modal("#del").show();
        select("#del #id").value = data.id;
    }
    const edit = data => {
        data = JSON.parse(data);
        modal("#edit").show();
        let dt = new Date(data.date);
        select("#edit #id").value = data.id;
        select("#edit #date").value = dt.toISOString().split("T")[0];
        select("#edit #time").value = `${dt.getHours().toString().padStart(2,'0')}:${dt.getMinutes().toString().padStart(2,'0')}`;
    }
</script>
@endsection