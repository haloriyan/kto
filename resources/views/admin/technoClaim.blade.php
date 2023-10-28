@extends('layouts.admin')

@section('title', "Techno Area Claim")
    
@section('content')
@if ($message != "")
    <div class="bg-green rounded p-2 mb-2">
        {{ $message }}
    </div>
@endif

<div class="bg-white rounded p-4 border">
    <div class="flex row item-center justify-end gap-20">
        <div class="flex row grow-1 border primary rounded p-05 gap-10">
            <div class="bg-primary rounded flex grow-1 h-40 centerize">
                Techno Area Coupon
            </div>
            <a href="{{ route('admin.exclusiveGift.claim') }}" class="rounded flex grow-1 h-40 centerize text primary">
                Exclusive Gift Claim
            </a>
            <a href="{{ route('admin.claim') }}" class="flex grow-1 text primary h-40 centerize">
                Mystery Gift Claim
            </a>
        </div>
        <form>
            <div class="group">
                <input type="text" id="search" name="q" value="{{ $request->q }}">
                <label for="search">Cari :</label>
                @if ($request->q != "")
                    <a href="{{ route('admin.exclusiveGift.claim') }}" class="text red absolute right-5 top-0 mt-3 pointer">
                        <i class="bx bx-x"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="flex row item-center gap-20 justify-end mb-2 mt-2">
        <a href="{{ route('admin.technoGift.export') }}" class="flex justify-end">
            <button class="green small">Download Excel</button>
        </a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Peserta</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($claims as $item)
                <tr>
                    <td>{{ $item->visitor->name }} <div class="text muted small">({{ $item->visitor->email }})</div></td>
                    <td>
                        @if (!$item->is_accepted)
                            <a href="{{ route('admin.technoGift.claim.accept', $item->id) }}">
                                <button class="green small">
                                    <i class="bx bx-check"></i>
                                </button>
                            </a>
                        @else
                            <b>Claimed</b>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection