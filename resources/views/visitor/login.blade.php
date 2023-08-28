@extends('layouts.auth')

@section('title', "Login")
    
@section('content')
<form action="{{ route('visitor.login') }}" method="POST" id="LoginForm" onsubmit="LoggingIn(event)">
    {{ csrf_field() }}
    
    <input type="hidden" name="r" id="r" value="{{ $request->r }}">
    <div class="group">
        <input type="text" name="name" id="name" required>
        <label for="name">Nama :</label>
    </div>
    <div class="group">
        <input type="email" name="email" id="email" required>
        <label for="email">Email :</label>
    </div>

    @if ($errors->count() > 0)
        @foreach ($errors->all() as $err)
            <div class="bg-red transparent rounded p-1 pl-2 pr-2 mt-1">
                {{ $err }}
            </div>
        @endforeach
    @endif

    <button class="primary w-100 mt-2">Berikutnya</button>
</form>
@endsection

@section('javascript')
<script>
    const deg2rad = deg =>  deg * (Math.PI/180);
    const calculate = (firstPoint, secondPoint) => {
        let R = 6371
        let dLat = deg2rad(secondPoint.lat - firstPoint.lat)
        let dLon = deg2rad(secondPoint.lng - firstPoint.lng)
        let a =
        Math.sin(dLat/2) * Math.sin(dLat/2) +
        Math.cos(deg2rad(firstPoint.lat)) *  Math.cos(deg2rad(secondPoint.lat)) *
        Math.sin(dLon) * Math.sin(dLon/2);
        let c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a))
        let d = R * c;
        d = d * 1000;
        return d // in km
    }
    const select = dom => document.querySelector(dom);

    const getMyLocation = () => {
        navigator.geolocation.getCurrentPosition(pos => {
            console.log(pos);
            let lat = pos.coords.latitude;
            let lng = pos.coords.longitude;

            let dist = calculate(
                {lat, lng},
                {lat: -7.256188, lng: 112.737142}
            );
            let maxDistance = parseInt("{{ env('MAX_DISTANCE') }}");
            if (dist > maxDistance) {
                // 
            }
        })
    }

    let r = select("#r").value;
    let redirectTo = r === null ? '' : r;

    const LoggingIn = e => {
        let name = select("#name").value;
        let email = select("#email").value;

        fetch("{{ route('api.visitor.login') }}", {
            method: "POST",
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                email: email,
                name: name,
            })
        })
        .then(res => res.json())
        .then(res => {
            console.log(res);
            window.localStorage.setItem('visitor_token', res.visitor.token);
            window.location.href = `{{ route('visitor.authorize') }}/${res.visitor.token}/${redirectTo}`;
        })

        e.preventDefault();
    }

    const Authorize = () => {
        let token = window.localStorage.getItem('visitor_token');
        console.log(token);
        if (token !== null || token !== undefined) {
            window.location.href = `{{ route('visitor.authorize') }}/${token}/${redirectTo}`;
        }
    }

    Authorize();

    // getMyLocation();
</script>
@endsection