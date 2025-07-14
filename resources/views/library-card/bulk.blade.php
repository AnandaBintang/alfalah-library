{{-- filepath: resources/views/library-card/bulk.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Perpustakaan - Bulk Print</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            margin: 15mm;
            size: A4 portrait;
        }

        body {
            font-family: 'Times New Roman', serif;
            background: white;
            padding: 5mm;
        }

        .cards-container {
            display: flex;
            flex-wrap: wrap;
            gap: 8mm;
            justify-content: flex-start;
            align-content: flex-start;
        }

        .card {
            width: 85.6mm;
            height: 53.98mm;
            background: white;
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            position: relative;
            border: 1.5px solid #16a34a;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        /* Layout untuk A4: 2 kolom × 4 baris = 8 kartu per halaman */
        .card:nth-child(8n+1) {
            page-break-before: always;
        }

        .card:first-child {
            page-break-before: auto;
        }

        .header {
            background: linear-gradient(135deg, #16a34a 0%, #22c55e 100%);
            color: white;
            padding: 3px 6px;
            text-align: center;
            position: relative;
            height: 18mm;
        }

        .logo {
            position: absolute;
            left: 4px;
            top: 50%;
            transform: translateY(-50%);
            width: 14mm;
            height: 14mm;
            background: #15803d;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #ffd700;
        }

        .logo::before {
            content: "🕌";
            font-size: 8px;
        }

        .header-text {
            margin-left: 16mm;
            padding-right: 2px;
        }

        .title {
            font-size: 6px;
            font-weight: bold;
            letter-spacing: 0.3px;
            margin-bottom: 1px;
            line-height: 1.1;
        }

        .school-name {
            font-size: 8px;
            font-weight: bold;
            letter-spacing: 0.5px;
            margin-bottom: 1px;
            line-height: 1.1;
        }

        .address {
            font-size: 4px;
            opacity: 0.9;
            line-height: 1.1;
        }

        .content {
            padding: 4px 6px;
            background: white;
            height: calc(100% - 18mm);
            position: relative;
            display: flex;
            flex-direction: column;
        }

        .member-info {
            flex: 1;
            margin-bottom: 3px;
        }

        .info-row {
            display: flex;
            margin-bottom: 1.5px;
            font-size: 6px;
            align-items: baseline;
        }

        .label {
            width: 18mm;
            font-weight: bold;
            color: #333;
            flex-shrink: 0;
        }

        .colon {
            width: 3mm;
            text-align: center;
            font-weight: bold;
            flex-shrink: 0;
        }

        .value {
            flex: 1;
            color: #333;
            word-break: break-word;
        }

        .bottom-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: auto;
            border-top: 1px dashed #16a34a;
            padding-top: 2px;
        }

        .barcode {
            width: 24mm;
            height: 8mm;
            border: 1px solid #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
        }

        .barcode img {
            max-width: 100%;
            max-height: 100%;
        }

        .signature-section {
            text-align: center;
            font-size: 4px;
            line-height: 1.1;
        }

        .date {
            margin-bottom: 1px;
            color: #333;
        }

        .position {
            margin-bottom: 6px;
            color: #333;
        }

        .signature {
            font-style: italic;
            color: #666;
            border-bottom: 0.5px solid #333;
            padding-bottom: 1px;
            min-width: 24mm;
            font-size: 4px;
        }

        .stamp {
            position: absolute;
            right: 3px;
            bottom: 8px;
            width: 10mm;
            height: 10mm;
            border: 1px solid #16a34a;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3px;
            color: #16a34a;
            text-align: center;
            opacity: 0.7;
            line-height: 1;
        }

        .accreditation-badge {
            position: absolute;
            top: 2px;
            right: 3px;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            padding: 1px 3px;
            border-radius: 2px;
            font-size: 4px;
            font-weight: bold;
        }

        /* Print info header */
        .print-header {
            text-align: center;
            margin-bottom: 10mm;
            padding: 8mm;
            border-bottom: 2px solid #16a34a;
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border-radius: 8px;
        }

        .print-header h2 {
            color: #166534;
            font-size: 18px;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .print-header .school-info {
            color: #16a34a;
            font-size: 12px;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .print-header p {
            color: #374151;
            font-size: 11px;
        }

        @media print {
            .print-header {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="print-header">
        <h2>🕌 KARTU ANGGOTA PERPUSTAKAAN</h2>
        <div class="school-info">SMP AL FALAH DARUSSALAM</div>
        <div style="font-size: 10px; color: #666; margin-bottom: 8px;">
            Jl. Melati No.9, Tropodo Wetan, Waru, Sidoarjo 61256<br>
            Akreditasi Unggul
        </div>
        <p>Total: {{ count($cardsData) }} kartu | Dicetak: {{ date('d F Y H:i') }}</p>
    </div>

    <div class="cards-container">
        @foreach ($cardsData as $cardData)
            <div class="card">
                <div class="header">
                    <div class="logo"></div>
                    <div class="header-text">
                        <div class="title">KARTU ANGGOTA PERPUSTAKAAN</div>
                        <div class="school-name">SMP AL FALAH DARUSSALAM</div>
                        <div class="address">Jl. Melati No.9, Tropodo Wetan, Tropodo, Kec. Waru, Kabupaten Sidoarjo,
                            Jawa Timur 61256</div>
                    </div>
                    <div class="accreditation-badge">
                        AKREDITASI UNGGUL
                    </div>
                </div>

                <div class="content">
                    <div class="member-info">
                        <div class="info-row">
                            <span class="label">Nama</span>
                            <span class="colon">:</span>
                            <span class="value">{{ Str::limit($cardData['cardData']['name'], 25) }}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">No Anggota</span>
                            <span class="colon">:</span>
                            <span class="value">{{ $cardData['cardData']['nis'] }}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">Kelas</span>
                            <span class="colon">:</span>
                            <span class="value">{{ $cardData['cardData']['class'] }}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">Jenis Kelamin</span>
                            <span class="colon">:</span>
                            <span
                                class="value">{{ $cardData['cardData']['gender'] === 'L' ? 'Laki-laki' : ($cardData['cardData']['gender'] === 'P' ? 'Perempuan' : $cardData['cardData']['gender']) }}</span>
                        </div>
                    </div>

                    <div class="bottom-section">
                        <div class="barcode">
                            <img src="{{ $cardData['barcodeImage'] }}"
                                alt="Barcode {{ $cardData['cardData']['barcode_data'] }}">
                        </div>
                        <div class="signature-section">
                            <div class="date">Sidoarjo, {{ date('d M Y') }}</div>
                            <div class="position">Kepala Perpustakaan</div>
                            <div class="signature">Ahmad Fauzi, M.Pd.</div>
                        </div>
                    </div>

                    <div class="stamp">
                        STEMPEL<br>SEKOLAH
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</body>

</html>
