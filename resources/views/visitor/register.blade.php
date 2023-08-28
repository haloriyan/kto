@extends('layouts.auth')

@section('title', "Register")
    
@section('content')
<form action="{{ route('visitor.register') }}" method="POST">
    {{ csrf_field() }}
    <div class="group">
        <input type="name" name="name" id="name" required>
        <label for="name">Nama :</label>
    </div>
    <div class="group">
        <input type="email" name="email" id="email" required>
        <label for="email">Email :</label>
    </div>
    <div class="group">
        <input type="password" name="password" id="password" required>
        <label for="password">Password :</label>
    </div>

    @if ($errors->count() > 0)
        @foreach ($errors->all() as $err)
            <div class="bg-red transparent rounded p-1 pl-2 pr-2 mt-1">
                {{ $err }}
            </div>
        @endforeach
    @endif

    <button class="primary w-100 mt-2">Register</button>

    <div class="flex row centerize mt-2 gap-5 text small">
        Sudah punya akun?
        <a href="{{ route('visitor.loginPage') }}" class="text primary">Login</a>
        saja
    </div>
</form>
@endsection