@extends('layouts.admin')

@section('title', "User Administrator")
    
@section('content')
@if ($message != "")
    <div class="bg-green rounded p-2 mb-2">
        {{ $message }}
    </div>
@endif

<div class="bg-white rounded p-4 border">
    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($admins as $admin)
                <tr>
                    <td>{{ $admin->name }}</td>
                    <td>{{ $admin->email }}</td>
                    <td>
                        <button class="small blue" onclick="changePass('{{ $admin }}')">
                            Ubah Password
                        </button>
                        @if ($admin->id != $myData->id)
                            <button class="small red" onclick="del('{{ $admin }}')">
                                <i class="bx bx-trash"></i>
                            </button>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<button class="FAB primary" onclick="modal('#add').show()">
    <i class="bx bx-plus"></i>
</button>
<div class="modal" id="add">
    <div class="modal-body">
        <div class="modal-title">
            Tambah Administrator
            <span class="bx bx-x modal-close" hide></span>
        </div>
        <form action="{{ route('admin.admin.store') }}" class="modal-content" method="POST">
            {{ csrf_field() }}
            <div class="group">
                <input type="text" id="name" name="name" required>
                <label for="name">Nama</label>
            </div>
            <div class="group">
                <input type="text" id="email" name="email" required>
                <label for="email">Email</label>
            </div>
            <div class="group">
                <input type="password" id="password" name="password" required>
                <label for="password">Password</label>
            </div>

            <button class="w-100 mt-3 primary">Tambahkan</button>
        </form>
    </div>
</div>

<div class="modal" id="delete">
    <div class="modal-body">
        <div class="modal-title">
            Hapus User Administrator
            <span class="bx bx-x modal-close" hide></span>
        </div>
        <form action="{{ route('admin.admin.delete') }}" class="modal-content" method="POST">
            {{ csrf_field() }}
            <input type="hidden" id="id" name="id">
            <div class="text size-14">Yakin ingin menghapus <span id="name"></span>? Tindakan ini tidak dapat dibatalkan</div>

            <div class="mt-3 flex row item-center justify-end gap-20">
                <div class="pointer text muted" onclick="modal('#delete').hide()">Batal</div>
                <div class="pointer text red bold" onclick="select('#delete form').submit()">Hapus</div>
            </div>
        </form>
    </div>
</div>

<div class="modal" id="pass">
    <div class="modal-body">
        <div class="modal-title">
            Hapus User Administrator
            <span class="bx bx-x modal-close" hide></span>
        </div>
        <form action="{{ route('admin.admin.changePassword') }}" class="modal-content" method="POST">
            {{ csrf_field() }}
            <input type="hidden" id="id" name="id">
            <div class="group">
                <input type="password" id="password" name="password" required>
                <label for="password">Password baru :</label>
            </div>

            <button class="primary w-100 mt-3">
                Simpan Password Baru
            </button>
        </form>
    </div>
</div>
@endsection

@section('javascript')
<script>
    const del = data => {
        data = JSON.parse(data);
        modal("#delete").show();
        select("#delete #id").value = data.id;
        select("#delete #name").innerText = data.name;
    }
    const changePass = data => {
        data = JSON.parse(data);
        modal("#pass").show();
        select("#pass #id").value = data.id;
        select("#pass #name").innerText = data.name;
    }
</script>
@endsection