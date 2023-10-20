<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>2023 Korea Medical Tourism Festival</title>
    <link rel="stylesheet" href="{{ asset('css/base/column.css') }}">
    <link rel="stylesheet" href="{{ asset('css/base/color.css') }}">
    <link rel="stylesheet" href="{{ asset('css/base/button.css') }}">
    <link rel="stylesheet" href="{{ asset('css/base/font.css') }}">
    <style>
        .ButtonArea {
            width: 45%;
        }
        .btn {
            display: flex;
            flex-grow: 1;
            padding: 20px;
            align-items: center;
            justify-content: center;
        }
        @media (max-width: 480px) {
            .ButtonArea {
                width: 100%;
                margin-top: 40px;
                flex-direction: column !important;
            }
        }
    </style>
</head>
<body>
    
<div class="flex column centerize mt-4">
    <h1>@lang('welcome_title')</h1>
    <div class="flex row gap-20 ButtonArea">
        <a href="/kmtm/login" class="btn pointer rounded-more bg-primary">
            Login to KMTM
        </a>
        {{-- <a href="/login" class="btn pointer rounded-more border text primary">
            Login to KMTE
        </a> --}}
    </div>
</div>

</body>
</html>