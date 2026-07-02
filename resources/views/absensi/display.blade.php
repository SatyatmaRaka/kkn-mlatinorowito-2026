<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>QR Absensi Hari Ini - KKN Mlatinorowito</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { margin: 0; min-height: 100vh; display: flex; align-items: center; justify-content: center; background: #0f172a; color: #fff; font-family: system-ui, sans-serif; }
        .wrap { text-align: center; padding: 2rem; }
        h1 { font-size: 1.75rem; margin-bottom: 0.5rem; }
        .meta { opacity: 0.8; margin-bottom: 2rem; }
        #qrcode { display: inline-block; padding: 1rem; background: #fff; border-radius: 1rem; }
    </style>
</head>
<body>
    <div class="wrap">
        <h1>Absensi Posko KKN</h1>
        <p class="meta">Scan QR ini untuk absensi · Jam {{ $windowLabel }}</p>
        <div id="qrcode"></div>
        <p class="meta mt-4 mb-0">QR diperbarui otomatis setiap hari · {{ now()->locale('id')->translatedFormat('d F Y') }}</p>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        new QRCode(document.getElementById('qrcode'), {
            text: @json($checkInUrl),
            width: 320,
            height: 320,
            correctLevel: QRCode.CorrectLevel.H
        });
        setTimeout(() => location.reload(), 300000);
    </script>
</body>
</html>
