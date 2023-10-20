@extends('layouts.auth')

@section('title', "KMTM Buyer")

@section('content')
<form action="{{ route('kmtm.login') }}" method="POST">
    {{ csrf_field() }}
    <div class="group">
        <input type="text" id="name" name="name" required>
        <label for="name">@lang('name') :</label>
    </div>
    <div class="group">
        <input type="text" id="email" name="email" required>
        <label for="email">Email :</label>
    </div>

    @if ($errors->count() > 0)
        @foreach ($errors->all() as $err)
            <div class="bg-red p-1 pl-2 pr-2 rounded mt-2">
                {{-- @lang($err) --}}
                {{ $err }}
            </div>
        @endforeach
    @endif

    <button class="primary mt-2 w-100">@lang('buyer_login_button')</button>
</form>
@endsection