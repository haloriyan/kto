@extends('layouts.auth')

@section('title', "Register KMTE")

@section('content')
<form action="{{ route('kmte.register') }}" method="POST">
    {{ csrf_field() }}
    <div class="group">
        <input type="text" id="name" name="name" required>
        <label class="active" for="name">@lang('name') :</label>
    </div>
    <div class="group">
        <input type="text" id="email" name="email" required>
        <label class="active" for="email">Email :</label>
    </div>
    <div class="group">
        <input type="text" id="phone" name="phone" required>
        <label class="active" for="phone">@lang('phone') :</label>
    </div>
    <div class="group">
        <input type="text" id="website" name="website" placeholder="Facebook, Instagram link, etc" required>
        <label class="active" for="website">Website / SNS Profile URL :</label>
    </div>
    <div class="group">
        <input type="text" id="referer" name="referer">
        <label for="referer">Company Referer :</label>
    </div>

    <div class="flex row item-center gap-20 mt-2">
        <div class="text size-12">Are you available for our team to reach you by call?</div>
        <div class="flex grow-1 gap-20 item-center">
            <div class="flex item-center gap-10 group">
                <input type="radio" name="eligible" value="true" class="h-15 ratio-1-1 m-0 pointer" checked="checked">
                <div>Yes</div>
            </div>
            <div class="flex item-center gap-10 group">
                <input type="radio" name="eligible" value="false" class="h-15 ratio-1-1 m-0 pointer">
                <div>No</div>
            </div>
        </div>
    </div>

    @if ($errors->count() > 0)
        @foreach ($errors->all() as $err)
            <div class="bg-red p-1 pl-2 pr-2 rounded mt-2">
                {{-- @lang($err) --}}
                {{ $err }}
            </div>
        @endforeach
    @endif

    <button class="primary mt-2 w-100">Register to KMTE</button>
</form>
@endsection