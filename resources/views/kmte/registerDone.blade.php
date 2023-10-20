@extends('layouts.auth')

@section('title', "Success Register to KMTE")
    
@section('content')
<div>
    Thank you, {{ explode(" ", $user->name)[0] }}, for participating to Korea Medical Tourism Exhibition.
    @if ($user->eligible)
        Our team will reach you soon.
    @endif
</div>
@endsection