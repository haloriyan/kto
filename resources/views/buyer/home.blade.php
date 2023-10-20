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
    <link rel="stylesheet" href="{{ asset('css/base/modal.css') }}">
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

        $now = Carbon::now();
    @endphp

    @if ($appointments->count() == 0)
        <div class="mt-2">No appointment yet</div>
    @else
        @foreach ($appointments as $data)
            <div class="bg-white border rounded p-2 flex row item-center gap-20 mt-2">
                <img 
                    src="{{ asset('storage/seller_logos/' . $data->seller->logo) }}" alt="{{ $data->seller->name }}"
                    class="bg-grey ratio-1-1 rounded-max cover h-60"
                >
                <div class="flex column grow-1">
                    <div class="text bold size-18">{{ BuyerController::payload($data->seller, 'name_en') }}</div>
                    @if ($myData->join_type == "company")
                        <div class="text size-14 mt-05"><i class="bx bx-time"></i> {{ Carbon::parse($data->schedule->date)->format('H:i') }}</div>
                    @endif

                    @if (Carbon::parse(env('MAX_CANCEL_APPOINTMENT'))->diffInMinutes() > 0)
                        <div class="text primary mt-05 pointer" onclick="cancel('{{ $data }}')">
                            cancel
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    @endif

    @if ($appointments->count() > 0)
        <div class="mt-2 text size-14">You can visit our sellers at anytime between 15.30 - 17.10</div>
    @endif

    @if (($appointments->count() < 6 && $myData->join_type == "company") || $myData->join_type == "personal")
        <a href="{{ route('kmtm.makeAppointment') }}">
            <button class="mt-2 primary w-100">@lang('kmtm_home_button')</button>
        </a>
    @endif
</div>

<div class="modal" id="cancelModal">
    <div class="modal-body">
        <div class="modal-title">
            Cancel Appointment
            <span class="bx bx-x modal-close" hide></span>
        </div>
        <form action="{{ route('kmtm.cancel') }}" class="modal-content" method="POST">
            {{ csrf_field() }}
            <input type="hidden" id="id" name="id">
            <div>
                Are you sure want to cancel your appointment with <span id="seller_name"></span>?
                You will have {{ 6 - $myData->cancellation_count }} chance(s) to cancel your appointment
            </div>

            <button class="primary w-100 mt-3">
                Yes, Cancel My Appointment
            </button>
        </form>
    </div>
</div>

<script src="{{ asset('js/base.js') }}"></script>
<script>
    const cancel = data => {
        data = JSON.parse(data);
        console.log(data);
        modal("#cancelModal").show();
        select("#seller_name").innerHTML = data.seller.name;
        select("#cancelModal #id").value = data.id;
    }
</script>

</body>
</html>