@php
    use Carbon\Carbon;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Home - {{ env('APP_NAME') }}</title>
    <link rel="stylesheet" href="{{ asset('css/base/column.css') }}">
    <link rel="stylesheet" href="{{ asset('css/base/color.css') }}">
    <link rel="stylesheet" href="{{ asset('css/base/button.css') }}">
    <link rel="stylesheet" href="{{ asset('css/base/font.css') }}">
    <link rel="stylesheet" href="{{ asset('css/base/form.css') }}">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    
<div class="absolute top-0 left-0 right-0 p-2">
    <div class="flex row item-center gap-20">
        <div class="flex column grow-1">
            <div class="text small muted">Riwayat Kunjungan Exhibitor</div>
            <div>{{ $myData->name }}</div>
        </div>
        <div class="flex row item-center">
            <div class="text size-10 p-05 pl-1 pr-1 bg-primary rounded relative" style="left: 10px">Total Kunjungan</div>
            <div class="h-50 ratio-1-1 rounded-max bg-primary flex centerize">
                {{ $histories->count() }}
            </div>
        </div>
    </div>

    <div class="h-50"></div>
    
    @if ($errors->count() > 0)
        @foreach ($errors->all() as $err)
            <div class="bg-red rounded p-2 mb-2">
                {{ $err }}
            </div>
        @endforeach
    @endif

    @if ($message != "")
        <div class="bg-green rounded p-2 mb-2">
            {{ $message }}
        </div>
    @endif

    @if ($myData->appointment_eligible)
        <a href="{{ route('visitor.makeAppointment') }}" class="bg-primary rounded p-2 flex row item-center gap-20 mb-2">
            <div class="h-40 ratio-1-1 rounded-max flex centerize bg-white">
                <i class="bx bx-plus text primary"></i>
            </div>
            <div class="flex column grow-1">
                <div class="text bold size-18">Buat Appointment</div>
                <div class="text size-12">dengan exhibitor</div>
            </div>
        </a>
    @endif

    @if ($histories->count() == 0)
        <h3 class="mt-4 mb-0">Tidak ada data</h3>
        <div class="mt-05 text muted">
            Gunakan kamera ponselmu untuk scan di booth sebanyak 5 kali dan dapatkan merchandise menarik
        </div>
    @else
        @foreach ($histories as $item)
            <div class="flex row bg-white border bottom-6 blue transparent pt-2 pb-2 gap-20">
                <img 
                    class="h-60 ratio-1-1 rounded bg-grey"
                    src="{{ asset('storage/exhibitor_icons/' . $item->exhibitor->icon) }}" 
                    alt="{{ $item->exhibitor->name }}"
                >
                <div class="flex column grow-1">
                    <div class="text size-18 bold">{{ $item->exhibitor->name }}</div>
                    <div class="text small muted mt-05">Berkunjung pada {{ Carbon::parse($item->created_at)->format('d M, H:i') }}</div>
                </div>
            </div>
        @endforeach
    @endif

    @if ($histories->count() >= env('MIN_TO_CLAIM'))
        @if (!$myData->claim()->exists())
            <a href="{{ route('visitor.claim') }}">
                <button class="primary mt-3 w-100">Klaim Hadiah</button>
            </a>
        @endif
    @endif
</div>

</body>
</html>