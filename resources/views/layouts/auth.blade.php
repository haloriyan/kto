<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') - {{ env('APP_NAME') }}</title>
    <link rel="stylesheet" href="{{ asset('css/base/column.css') }}">
    <link rel="stylesheet" href="{{ asset('css/base/color.css') }}">
    <link rel="stylesheet" href="{{ asset('css/base/button.css') }}">
    <link rel="stylesheet" href="{{ asset('css/base/modal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/base/font.css') }}">
    <link rel="stylesheet" href="{{ asset('css/base/form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
    
<div class="absolute top-0 left-0 right-0 bottom-0 flex row centerize">
    <img 
        src="{{ asset('images/GATE_2.jpg') }}"
        class="w-60 bg-red IllustrationContainer cover" style="height: 100%" 
    />
    <div class="flex column grow-1 FormContainer">
        <div class="flex centerize mb-4">
            <img 
                src="{{ asset('images/logo.png') }}" alt="Logo KMTF"
                class="w-50 ratio-5-2 rounded flex centerize"
            >
        </div>
        @yield('content')
        <div class="h-40"></div>
    </div>
</div>

@yield('javascript')

</body>
</html>