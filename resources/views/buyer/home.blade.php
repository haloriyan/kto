<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Home - KMTM Buyer</title>
    <link rel="stylesheet" href="{{ asset('css/base/column.css') }}">
    <link rel="stylesheet" href="{{ asset('css/base/color.css') }}">
    <link rel="stylesheet" href="{{ asset('css/base/button.css') }}">
    <link rel="stylesheet" href="{{ asset('css/base/font.css') }}">
    <link rel="stylesheet" href="{{ asset('css/base/form.css') }}">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    
<div class="p-2">
    <h1 class="m-0 text size-24">
        @lang('hello'), {{ $myData->name }}
    </h1>

    <div class="text size-12 mt-2">@lang('my_appointment')</div>
    @php
        use \App\Http\Controllers\BuyerController;
        use Carbon\Carbon;
    @endphp

    @if ($appointments->count() == 0)
        <div class="mt-2">Tidak ada data</div>
    @else
        @foreach ($appointments as $data)
            <div class="bg-white border rounded p-2 flex row item-center gap-20 mt-2">
                <img 
                    src="{{ asset('storage/seller_logos/' . $data->seller->logo) }}" alt="{{ $data->seller->name }}"
                    class="bg-grey ratio-1-1 rounded-max cover h-60"
                >
                <div class="flex column grow-1">
                    <div class="text bold size-18">{{ BuyerController::payload($data->seller, 'name_en') }}</div>
                    <div class="text size-14 mt-05"><i class="bx bx-time"></i> {{ Carbon::parse($data->schedule->date)->format('H:i') }}</div>
                </div>
            </div>
        @endforeach
    @endif

    <a href="{{ route('kmtm.makeAppointment') }}">
        <button class="mt-2 primary w-100">@lang('kmtm_home_button')</button>
    </a>
</div>

</body>
</html>