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
    <link rel="stylesheet" href="{{ asset('css/base/modal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/base/font.css') }}">
    <link rel="stylesheet" href="{{ asset('css/base/form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    
<div class="absolute top-0 left-0 right-0 p-2">
    <div class="flex row item-center gap-20">
        <div class="flex column grow-1">
            <div class="text small muted">History Visits</div>
            <div>{{ $myData->name }}</div>
        </div>
        <div class="flex row item-center">
            <div class="text size-10 p-05 pl-1 pr-1 bg-primary rounded relative" style="left: 10px">Total Visits</div>
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

    {{-- @if ($myData->appointment_eligible)
        <a href="{{ route('visitor.makeAppointment') }}" class="bg-primary rounded p-2 flex row item-center gap-20 mb-2">
            <div class="h-40 ratio-1-1 rounded-max flex centerize bg-white">
                <i class="bx bx-plus text primary"></i>
            </div>
            <div class="flex column grow-1">
                <div class="text bold size-18">Buat Appointment</div>
                <div class="text size-12">dengan exhibitor</div>
            </div>
        </a>
    @endif --}}

    @if ($histories->count() == 0)
        <h3 class="mt-2 mb-0">No data available</h3>
        <div class="mt-05 text muted">
            Use your phone's camera to scan at the booth 5 times and get attractive merchandise
        </div>
    @else
        @foreach ($histories as $item)
            <div class="flex row bg-white border bottom-6 blue transparent pt-2 pb-2 gap-20">
                <img 
                    class="h-60 ratio-1-1 rounded bg-grey cover"
                    src="{{ asset('storage/seller_logos/' . $item->seller->logo) }}" 
                    alt="{{ $item->seller->name }}"
                >
                <div class="flex column grow-1">
                    <div class="text size-18 bold">{{ $item->seller->name }}</div>
                    <div class="text small muted mt-05">Visited on {{ Carbon::parse($item->created_at)->format('d M, H:i') }}</div>
                </div>
            </div>
        @endforeach
    @endif

    <div style="height: 80px;"></div>
</div>

<div class="fixed bottom-0 left-0 right-0 flex row item-center gap-20 h-70 shadow pl-2 pr-2 bg-white">
    <div class="flex column grow-1" style="flex-basis: 50%">
        <div class="ProgressArea flex row item-center">
            <div class="Steps Initial"></div>
            @for ($i = 0; $i < $histories->count(); $i++)
                <div class="Steps"></div>
            @endfor
        </div>
        @if ($histories->count() == 0)
            <div class="text size-14 muted mt-05">Scan seller's QR to gets exclusive gift</div>
        @else
            @if (1 - $histories->count() == 0)
                <div class="text size-14 muted mt-05">Claim your mystery gift now!</div>
            @else
                <div class="text size-14 muted mt-05">Scan {{ 1 - $histories->count() }}x more to claim mystery gift</div>
            @endif
        @endif
    </div>

    <button class="small primary" onclick="modal('#giftClaim').show()">Claim</button>
    {{-- <div class="flex column grow-1">
        ssdsds
    </div> --}}
</div>

<div class="modal" id="giftClaim">
    <div class="modal-body">
        <div class="modal-title">
            Gift Claim
            <span class="bx bx-x modal-close" hide></span>
        </div>
        <div class="modal-content">
            <div class="flex row item-center gap-30">
                <div class="flex column grow-1">
                    <div class="text bold size-18">Techno Area Coupon</div>
                    <div class="text size-12 muted">Get coupon to play on our techno area</div>
                </div>
                @if ($myData->techno_area != null && $myData->techno_area->is_accepted)
                    <div class="text muted size-14 bold">Claimed</div>
                @else
                    <button class="primary small" onclick="claim('techno_area')">Claim</button>
                @endif
            </div>
            <div class="flex row item-center gap-30 mt-4">
                <div class="flex column grow-1">
                    <div class="text bold size-18">Exclusive Gift</div>
                    <div class="text size-12 muted">Scan at any sellers for one time</div>
                </div>
                @if ($histories->count() >= 1 && $myData->exclusive_claim == null)
                    <a href="{{ route('visitor.claimExclusiveGift') }}">
                        <button class="primary small">Claim</button>
                    </a>
                @elseif ($histories->count() >= 1 && $myData->exclusive_claim != null && !$myData->exclusive_claim->is_accepted)
                    <div class="text muted size-14 bold">In Progress</div>
                @else
                    @if ($myData->exclusive_claim == null)
                        <div class="text muted size-14 bold">Not eligible</div>
                    @else
                        <div class="text muted size-14 bold">Claimed</div>
                    @endif
                @endif
            </div>
            <div class="flex row item-center gap-30 mt-4">
                <div class="flex column grow-1">
                    <div class="text bold size-18">Mystery Gift</div>
                    <div class="text size-12 muted">Scan at any sellers for 5 more times</div>
                </div>
                @if ($histories->count() >= 1 && $myData->mystery_claim == null)
                    <a href="{{ route('visitor.claim') }}">
                        <button class="primary small">Claim</button>
                    </a>
                @elseif ($histories->count() >= 1 && $myData->mystery_claim != null && !$myData->mystery_claim->is_accepted)
                    <div class="text muted size-14 bold">In Progress</div>
                @else
                    @if ($myData->mystery_claim == null)
                        <div class="text muted size-14 bold">Not eligible</div>
                    @else
                        <div class="text muted size-14 bold">Claimed</div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>

<div class="modal" id="technoAreaClaim">
    <div class="modal-body">
        <div class="modal-title">
            Techno Area Coupon
            <span class="bx bx-x modal-close" hide></span>
        </div>
        <div class="modal-content">
            <div>
                Please go to our booth (KTO booth) and ask our staffs to claim your techno area coupon.
            </div>

            <div class="mt-3 text primary bold pointer right" onclick="modal('#technoAreaClaim').hide()">Close</div>
        </div>
    </div>
</div>

<script src="{{ asset('js/base.js') }}"></script>
<script>
    // modal('#giftClaim').show();

    const claim = type => {
        modal('#giftClaim').hide();
        if (type == 'techno_area') {
            modal('#technoAreaClaim').show();
        }
    }
</script>

</body>
</html>