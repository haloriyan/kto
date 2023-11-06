@extends('layouts.admin')

@section('title', "Seller")

@php
    use Illuminate\Support\Arr;
    use App\Http\Controllers\SellerController;
@endphp
    
@section('content')
@if ($message != "")
    <div class="bg-green rounded p-3 mb-2">
        {{ $message }}
    </div>
@endif
<div class="flex row gap-20 wrap">
    @foreach ($sellers as $seller)
        <div class="flex column grow-1 basis-4 bg-white rounded border bottom-6 primary transparent relative">
            <img 
                src="{{ asset('storage/seller_logos/' . $seller->logo) }}" 
                class="w-100 ratio-5-2 rounded-top-left rounded-top-right bg-grey cover"
                alt="{{ SellerController::payload($seller->payloads, 'name_en') }}"
            >
            <div class="p-2">
                <div class="text bold">{{ SellerController::payload($seller->payloads, 'name_en') }}</div>
                <div class="flex row gap-20 mt-2">
                    <a href="{{ route('seller.edit', $seller->id) }}" class="flex grow-1">
                        <button class="flex grow-1 gap-10 centerize green transparent text size-14">
                            <i class="bx bx-edit"></i>
                            Edit
                        </button>
                    </a>
                    <button class="flex grow-1 gap-10 centerize red transparent text size-14" onclick="del('{{ $seller }}')">
                        <i class="bx bx-trash"></i>
                        Hapus
                    </button>
                </div>
            </div>

            <div class="absolute top-0 right-0 m-05 hover-to-show flex row item-center gap-10">
                <a href="{{ route('seller.qr', $seller->id) }}" class="bg-blue p-05 pl-2 pr-2 rounded flex row item-center gap-10 pointer text size-12">
                    <i class="bx bx-qr"></i>
                    QR
                </a>
                <a href="{{ route('admin.visitting.export', $seller->id) }}" class="bg-green p-05 pl-2 pr-2 rounded flex row item-center gap-10 pointer text size-12">
                    <i class="bx bx-qr"></i>
                    Scan Report
                </a>
            </div>
        </div>
    @endforeach
</div>

<a href="{{ route('seller.create') }}">
    <button class="FAB primary">
        <i class="bx bx-plus"></i>
    </button>
</a>

<div class="modal" id="delete">
    <div class="modal-body">
        <div class="modal-title">
            Hapus Seller
            <span class="bx bx-x modal-close" hide></span>
        </div>
        <form action="{{ route('seller.delete') }}" class="modal-content" method="POST">
            {{ csrf_field() }}
            <input type="hidden" id="id" name="id">
            <div class="text size-14">Yakin ingin menghapus seller ini? Tindakan ini akan menghapus semua data yang terkait dan tidak dapat dibatalkan</div>

            <div class="mt-3 flex row item-center justify-end gap-20">
                <div class="pointer text muted" onclick="modal('#delete').hide()">Batal</div>
                <div class="pointer text red bold" onclick="select('#delete form').submit()">Hapus</div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('javascript')
<script>
    const del = data => {
        data = JSON.parse(data);
        modal("#delete").show();
        select("#delete #id").value = data.id;
    }
</script>
@endsection