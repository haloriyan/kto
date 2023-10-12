<div style="font-family: sans-serif">
    <img 
        src="{{ public_path() }}/storage/seller_logos/{{ $seller->logo }}" 
        alt="{{ $seller->name }}"
        style="width: 100%;height: 400px;background: #ddd;object-fit: cover;border-radius: 18px;"
    >
    <div style="text-align: center;">
        <h2 style="font-size: 32px;">{{ $seller->name }}</h2>
        <img
            src="data:image/png;base64,{{ base64_encode($qrCode) }}"
            style="width: 250px;margin-top: 50px;"
        />

        <div style="font-size: 16px;color: #555;margin-top: 80px;">
            Scan QR Code untuk mendapatkan gift merchandise menarik
        </div>
    </div>
</div>