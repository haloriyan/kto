<style>
    html {
        margin: 0;
        padding: 0;
    }
    body {
        background-image: url("{{ public_path('images/qr_temp.jpg') }}");
        background-size: cover;
    }
</style>
<div style="font-family: sans-serif">
    
    <div style="text-align: center;">
        <img
            src="data:image/png;base64,{{ base64_encode($qrCode) }}"
            style="width: 250px;margin-top: 340px;margin-left: 20px;"
        />
    </div>
</div>