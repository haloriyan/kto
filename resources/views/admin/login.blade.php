@extends('layouts.auth')

@section('title', "Login Admin")

@section('content')
<form action="{{ route('admin.login') }}" method="POST">
    {{ csrf_field() }}
    <input type="hidden" name="r" value="{{ $request->r }}">
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

    @if ($message != "")
        <div class="bg-green transparent rounded p-1 pl-2 pr-2 mt-2">
            {{ $message }}
        </div>
    @endif

    <button class="primary w-100 mt-2">Login</button>
</form>
@endsection