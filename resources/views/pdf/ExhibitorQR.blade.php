<div style="font-family: sans-serif">
    <img 
        src="{{ public_path() }}/storage/exhibitor_covers/{{ $exhibitor->cover }}" 
        alt=="{{ $exhibitor->name }}"
        style="width: 100%;height: 400px;background: #ddd;object-fit: cover;border-radius: 18px;"
    >
    <div style="text-align: center;">
        <img 
            src="{{ public_path() }}/storage/exhibitor_icons/{{ $exhibitor->icon }}" 
            alt="tes"
            style="width: 200px;height: 200px;border-radius: 999px;border: 12px solid #fff;margin-top: -100px;object-fit: cover;"
        >
        <h2 style="font-size: 32px;">{{ $exhibitor->name }}</h2>
        <img
            src="data:image/png;base64,{{ base64_encode($qrCode) }}"
            style="width: 250px;margin-top: 50px;"
        />

        <div style="font-size: 16px;color: #555;margin-top: 80px;">
            Scan QR Code untuk mendapatkan gift merchandise menarik
        </div>
    </div>
</div>