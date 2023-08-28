@extends('layouts.admin')

@section('title', "Exhibitor")
    
@section('content')
<div class="flex row gap-20 wrap">
    @foreach ($exhibitors as $exhibitor)
        <div class="flex column grow-1 basis-4 bg-white rounded border bottom-6 primary transparent">
            <img 
                src="{{ asset('storage/exhibitor_covers/' . $exhibitor->cover) }}" 
                class="w-100 ratio-5-2 rounded-top-left rounded-top-right bg-grey cover"
                alt="{{ $exhibitor->name }}"
            >
            <div class="flex row ml-1" style="margin-top: -40px;">
                <img 
                    src="{{ asset('storage/exhibitor_icons/' . $exhibitor->icon) }}"
                    class="h-80 border border-10 white rounded-max ratio-1-1 bg-grey"
                >
            </div>
            <div class="p-2 pt-1">
                <div class="text bold">{{ $exhibitor->name }}</div>
                <div class="flex row gap-20 mt-2">
                    <a href="{{ route('exhibitor.qr', $exhibitor->slug) }}">
                        <button class="small green">QR Code</button>
                    </a>
                    <button class="small red" onclick="del('{{ $exhibitor }}')">Hapus</button>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="modal" id="add">
    <div class="modal-body">
        <div class="modal-title">
            Tambah Exhibitor
            <span class="bx bx-x modal-close" hide></span>
        </div>
        <form action="{{ route('exhibitor.store') }}" class="modal-content" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="group">
                <div class="flex column grow-1 ratio-5-2 rounded bg-grey CoverPreview"></div>
                <input type="file" id="cover" name="cover" onchange="inputFile(this, '.CoverPreview')">
            </div>
            <div class="flex column item-center" style="margin-top: -75px">
                <div class="group h-150 ratio-1-1 rounded-max">
                    <div class="IconPreview h-150 ratio-1-1 rounded-max bg-grey border border-10 white"></div>
                    <input type="file" id="icon" name="icon" onchange="inputFile(this, '.IconPreview')">
                </div>
            </div>
            <div class="group">
                <input type="text" id="name" name="name" required>
                <label for="name">Nama</label>
            </div>
            <div class="group">
                <textarea id="description" name="description" required></textarea>
                <label for="description">Deskripsi :</label>
            </div>
            <div class="group">
                <input type="email" id="email" name="email" required>
                <label for="email">Kontak Email :</label>
            </div>

            <button class="primary mt-2 w-100">Submit</button>
        </form>
    </div>
</div>

<div class="modal" id="delete">
    <div class="modal-body">
        <div class="modal-title">
            Hapus Exhibitor
            <span class="bx bx-x modal-close" hide></span>
        </div>
        <form action="{{ route('exhibitor.delete') }}" class="modal-content" method="POST">
            {{ csrf_field() }}
            <input type="hidden" id="id" name="id">
            <div class="text size-14">Yakin ingin menghapus <span id="name"></span>? Tindakan ini akan menghapus semua data yang terkait dan tidak dapat dibatalkan</div>

            <div class="mt-3 flex row item-center justify-end gap-20">
                <div class="pointer text muted" onclick="modal('#delete').hide()">Batal</div>
                <div class="pointer text red bold" onclick="select('#delete form').submit()">Hapus</div>
            </div>
        </form>
    </div>
</div>

<button class="FAB primary" onclick="modal('#add').show()">
    <i class="bx bx-plus"></i>
</button>
@endsection

@section('javascript')
<script>
    // modal('#add').show()
    const del = data => {
        data = JSON.parse(data);
        modal('#delete').show();
        select("#delete #name").innerHTML = data.name;
        select("#delete #id").value = data.id;
    }
</script>
@endsection