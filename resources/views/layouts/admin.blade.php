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
    <link rel="stylesheet" href="{{ asset('css/base/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/base/pagination.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    @yield('head')
</head>
<body>
    
<header class="fixed top-0 left-0 right-0 bg-white flex row item-center pl-4 pr-4 h-60 border bottom gap-20 index-4">
    <div class="pointer text size-24" onclick="toggleLeftMenu()">
        <i class="bx bx-menu"></i>
    </div>
    <h1 class="text size-20 m-0 flex grow-1">@yield('title')</h1>
    <div class="flex row item-center gap-10">
        <div class="h-40 ratio-1-1 rounded-max bg-primary flex centerize">
            {{ $myData->initial }}
        </div>
        <div>{{ $myData->name }}</div>
    </div>
</header>

<div class="LeftMenuOverlay bg-black transparent fixed top-0 left-0 right-0 bottom-0 d-none index-2" onclick="toggleLeftMenu()"></div>
<nav class="LeftMenu fixed bottom-0 w-20 bg-white index-4" style="top: 61px">
    <div class="h-30"></div>
    @php
        $routeName = Route::currentRouteName();
    @endphp
    <a href="{{ route('admin.dashboard') }}" class="MenuItem flex row gap-20 item-center pl-2 pr-2 h-50 text {{ $routeName == 'admin.dashboard' ? 'active' : 'black' }}">
        <i class="bx bx-home"></i>
        <div class="flex grow-1">Dashboard</div>
        <i class="bx bx-chevron-right"></i>
    </a>
    <a href="{{ route('admin.exhibitor') }}" class="MenuItem flex row gap-20 item-center pl-2 pr-2 h-50 text {{ $routeName == 'admin.exhibitor' ? 'active' : 'black' }}">
        <i class="bx bx-store"></i>
        <div class="flex grow-1">Exhibitor</div>
        <i class="bx bx-chevron-right"></i>
    </a>
    <a href="{{ route('admin.visitor') }}" class="MenuItem flex row gap-20 item-center pl-2 pr-2 h-50 text {{ ($routeName == 'admin.visitor' || $routeName == 'admin.visitor.detail') ? 'active' : 'black' }}">
        <i class="bx bx-group"></i>
        <div class="flex grow-1">Peserta</div>
        <i class="bx bx-chevron-right"></i>
    </a>
    <a href="{{ route('admin.schedule') }}" class="MenuItem flex row gap-20 item-center pl-2 pr-2 h-50 text {{ $routeName == 'admin.schedule' ? 'active' : 'black' }}">
        <i class="bx bx-calendar"></i>
        <div class="flex grow-1">Jadwal Appointment</div>
        <i class="bx bx-chevron-right"></i>
    </a>
    <a href="{{ route('admin.admin') }}" class="MenuItem flex row gap-20 item-center pl-2 pr-2 h-50 text {{ $routeName == 'admin.admin' ? 'active' : 'black' }}">
        <i class="bx bx-group"></i>
        <div class="flex grow-1">Administrator</div>
        <i class="bx bx-chevron-right"></i>
    </a>
    <a href="{{ route('admin.settings') }}" class="MenuItem flex row gap-20 item-center pl-2 pr-2 h-50 text {{ $routeName == 'admin.settings' ? 'active' : 'black' }}">
        <i class="bx bx-cog"></i>
        <div class="flex grow-1">Pengaturan</div>
        <i class="bx bx-chevron-right"></i>
    </a>

    {{-- <div class="text size-12 muted mt-4 ml-2 mb-1">Data & Statistik</div> --}}
    <div class="h-40"></div>
    <a href="{{ route('admin.kmtmUser') }}" class="MenuItem flex row gap-20 item-center pl-2 pr-2 h-50 text {{ $routeName == 'admin.kmtmUser' ? 'active' : 'black' }}">
        <i class="bx bx-group"></i>
        <div class="flex grow-1">Peserta KMTM</div>
        <i class="bx bx-chevron-right"></i>
    </a>
    <a href="{{ route('admin.visitting') }}" class="MenuItem flex row gap-20 item-center pl-2 pr-2 h-50 text {{ $routeName == 'admin.visitting' ? 'active' : 'black' }}">
        <i class="bx bx-group"></i>
        <div class="flex grow-1">Kunjungan Booth</div>
        <i class="bx bx-chevron-right"></i>
    </a>
    <a href="{{ route('admin.appointment') }}" class="MenuItem flex row gap-20 item-center pl-2 pr-2 h-50 text {{ $routeName == 'admin.appointment' ? 'active' : 'black' }}">
        <i class="bx bx-timer"></i>
        <div class="flex grow-1">Appointment</div>
        <i class="bx bx-chevron-right"></i>
    </a>
    <a href="{{ route('admin.claim') }}" class="MenuItem flex row gap-20 item-center pl-2 pr-2 h-50 text {{ $routeName == 'admin.claim' ? 'active' : 'black' }}">
        <i class="bx bx-gift"></i>
        <div class="flex grow-1">Klaim Hadiah</div>
        <i class="bx bx-chevron-right"></i>
    </a>

    <div class="h-40"></div>
    <a href="{{ route('admin.logout') }}" class="MenuItem flex row gap-20 item-center pl-2 pr-2 h-50 text textblack red">
        <i class="bx bx-log-out"></i>
        <div class="flex grow-1">Logout</div>
    </a>
</nav>

<div class="content absolute left-0 right-0">
    @yield('content')
</div>

<script src="{{ asset('js/base.js') }}"></script>
<script>
    const toggleLeftMenu = () => {
        let LeftMenu = select(".LeftMenu");
        let overlay = select(".LeftMenuOverlay");
        if (LeftMenu.classList.contains('active')) {
            overlay.classList.add('d-none');
        } else {
            overlay.classList.remove('d-none');
        }

        LeftMenu.classList.toggle('active');
    }
</script>
@yield('javascript')

</body>
</html>