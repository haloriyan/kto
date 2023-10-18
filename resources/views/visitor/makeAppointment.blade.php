<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Make Appointment - {{ env('APP_NAME') }}</title>
    <link rel="stylesheet" href="{{ asset('css/base/column.css') }}">
    <link rel="stylesheet" href="{{ asset('css/base/color.css') }}">
    <link rel="stylesheet" href="{{ asset('css/base/button.css') }}">
    <link rel="stylesheet" href="{{ asset('css/base/font.css') }}">
    <link rel="stylesheet" href="{{ asset('css/base/form.css') }}">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    
<div class="absolute top-0 left-0 right-0 p-2">
    <h2 class="m-0">Buat Janji Temu</h2>
    <div class="h-40"></div>
    <input type="hidden" id="exhibitor_id" value="{{ $exhibitor == null ? '' : $exhibitor->id }}">
    @if ($request->exid == null)
        @php
            $isGettingSchedule = false;
        @endphp
        <div class="text">Pilih Exhibitor yang ingin Anda temui</div>
        <div class="flex column gap-20 mt-2">
            @foreach ($exhibitors as $exhibitor)
                <a href="{{ route('visitor.makeAppointment', ['exid' => $exhibitor->id]) }}" class="flex row item-center gap-20 border rounded p-2 text black">
                    <img 
                        src="{{ asset('storage/exhibitor_icons/' . $exhibitor->icon) }}" alt="{{ $exhibitor->name }}"
                        class="h-50 ratio-1-1 rounded-max bg-grey"
                    >
                    <div class="flex column grow-1">
                        <div class="text bold">{{ $exhibitor->name }}</div>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        @php
            $isGettingSchedule = true;
        @endphp
        <div class="text">Pilih jadwal janji temu dengan {{ $exhibitor->name }}</div>
        <div id="renderSchedule" class="flex column gap-20 mt-2"></div>
    @endif
</div>

<script>
    let isGettingSchedule = "{{ $isGettingSchedule }}";
    let schedules = [];
    let exhibitorID = document.querySelector("#exhibitor_id").value;
    const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    const renderSchedules = () => {
        let parent = document.querySelector("#renderSchedule");
        parent.innerHTML = "";
        schedules.forEach(schedule => {
            let dt = new Date(schedule.date);
            let endpoint = `{{ route('visitor.makeAppointment', ['exid' => $exhibitor->id]) }}&schedule_id=${schedule.id}`
            
            let el = document.createElement('a');
            el.setAttribute('href', endpoint)
            el.classList.add('flex', 'row', 'item-center', 'gap-20', 'border', 'p-2', 'text', 'black');
            el.innerHTML = `<div class="flex grow-1 text size-18 bold">${dt.getDate()} ${months[dt.getMonth()]}</div>
            <div class="text size-16"><i class="bx bx-time"></i> ${dt.getHours().toString().padStart(2,'0')}:${dt.getMinutes().toString().padStart(2,'0')}</div>`;
            parent.appendChild(el);
            console.log(cok);
        })
    }
    const getSchedules = () => {
        fetch("{{ route('api.AppointmentSchedule') }}", {
            method: "POST",
            headers: {"Content-Type": "application/json"},
            body: JSON.stringify({
                exhibitor_id: exhibitorID,
            })
        })
        .then(res => res.json())
        .then(res => {
            schedules = res.schedules;
            renderSchedules()
        })
    }
    
    console.log(isGettingSchedule);
    if (isGettingSchedule) {
        setInterval(() => {
            getSchedules()
        }, 1000);
    }
</script>

</body>
</html>