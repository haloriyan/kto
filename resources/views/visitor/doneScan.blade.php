<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Berhasil Scan - {{ env('APP_NAME') }}</title>
    <link rel="stylesheet" href="{{ asset('css/base/column.css') }}">
    <link rel="stylesheet" href="{{ asset('css/base/color.css') }}">
    <link rel="stylesheet" href="{{ asset('css/base/button.css') }}">
    <link rel="stylesheet" href="{{ asset('css/base/font.css') }}">
    <link rel="stylesheet" href="{{ asset('css/base/form.css') }}">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    
<div class="fixed top-0 left-0 right-0 bottom-0 flex column centerize p-2">
    <div class="h-80 ratio-1-1 rounded-max bg-green transparent flex centerize">
        <i class="bx bx-check text size-28"></i>
    </div>

    <h2 class="text size-24 bold m-0 mt-4">Berhasil Scan</h2>
    <div class="text muted center mt-1">
        Anda terekam telah mengunjungi booth "{{ $seller->name }}"
    </div>

    <a href="{{ route('historyScan') }}" class="text primary pointer center bold mt-3">Lihat Riwayat Kunjungan Saya</a>
</div>

</body>
</html>