@extends('layouts.admin')

@section('title', "Exclusive Gift Claim")
    
@section('content')
@if ($message != "")
    <div class="bg-green rounded p-2 mb-2">
        {{ $message }}
    </div>
@endif

<div class="bg-white rounded p-4 border">
    <div class="flex row item-center justify-end gap-20">
        <div class="flex row grow-1 border primary rounded p-05 gap-10">
            <a href="{{ route('admin.technoGift.claim') }}" class="rounded flex grow-1 h-40 centerize text primary">
                Techno Area Coupon
            </a>
            <div class="bg-primary rounded flex grow-1 h-40 centerize">
                Exclusive Gift Claim
            </div>
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
        <a href="{{ route('admin.exclusiveGift.export') }}" class="flex justify-end">
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
                            <a href="{{ route('admin.exclusiveGift.claim.accept', $item->id) }}">
                                <button class="green small">
                                    <i class="bx bx-check"></i>
                                </button>
                            </a>
                        @endif
                        <button class="primary small" onclick="seeDetail('{{ $item }}')">
                            Riwayat Kunjungan
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-3">
        {{ $claims->links('pagination::bootstrap-4') }}
    </div>
</div>

<div class="modal" id="detail">
    <div class="modal-body">
        <div class="modal-title">
            Riwayat Kunjungan <span id="name"></span>
            <span class="bx bx-x modal-close" hide></span>
        </div>
        <div class="modal-content">
            {{ csrf_field() }}
            <input type="hidden" id="id" name="id">
            <div id="render" class="flex column gap-20"></div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script src="https://momentjs.com/downloads/moment.js"></script>
<script>
    const seeDetail = data => {
        data = JSON.parse(data);
        const visits = data.visitor.visits;
        console.log(visits);
        modal('#detail').show();
        select("#detail #name").innerText = data.visitor.name;
        select("#detail #render").innerHTML = '';
        
        visits.forEach(item => {
            const exhibitor = item.exhibitor;
            let el = document.createElement('div');
            el.classList.add('flex', 'row', 'item-center', 'gap-20')
            el.innerHTML = `<img src="/storage/seller_logos/${exhibitor.logo}" class="h-60 ratio-1-1 rounded-max bg-white" />
            <div class="flex column grow-1">
                <h4 class="m-0">${exhibitor.name}</h4>
                <div class="text size-12 muted"><i class="bx bx-time"></i> ${moment(item.created_at).format('DD MMM - HH:mm')}</div>
            </div>`;
            select("#detail #render").appendChild(el);
        })
    }
</script>
@endsection