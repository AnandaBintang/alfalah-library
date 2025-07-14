{{-- filepath: resources/views/library-card/single.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Anggota Perpustakaan - {{ $cardData['name'] }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            margin: 20mm;
            size: A4 portrait;
        }

        body {
            font-family: 'Times New Roman', serif;
            background: white;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .card {
            width: 85.6mm;
            height: 53.98mm;
            background: white;
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
            position: relative;
            border: 1.5px solid #16a34a;
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

        /* Print instructions */
        .print-instructions {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #16a34a;
            page-break-inside: avoid;
        }

        .cutting-guide {
            margin-top: 15px;
            padding: 15px;
            border: 2px dashed #16a34a;
            border-radius: 8px;
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            text-align: center;
            font-size: 10px;
            color: #166534;
        }

        @media print {
            body {
                background: white;
            }

            .print-instructions,
            .cutting-guide {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="header">
            <div class="logo"></div>
            <div class="header-text">
                <div class="title">KARTU ANGGOTA PERPUSTAKAAN</div>
                <div class="school-name">SMP AL FALAH DARUSSALAM</div>
                <div class="address">Jl. Melati No.9, Tropodo Wetan, Tropodo, Kec. Waru, Kabupaten Sidoarjo, Jawa Timur
                    61256</div>
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
                    <span class="value">{{ Str::limit($cardData['name'], 25) }}</span>
                </div>
                <div class="info-row">
                    <span class="label">No Anggota</span>
                    <span class="colon">:</span>
                    <span class="value">{{ $cardData['nis'] }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Kelas</span>
                    <span class="colon">:</span>
                    <span class="value">{{ $cardData['class'] }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Jenis Kelamin</span>
                    <span class="colon">:</span>
                    <span
                        class="value">{{ $cardData['gender'] === 'L' ? 'Laki-laki' : ($cardData['gender'] === 'P' ? 'Perempuan' : $cardData['gender']) }}</span>
                </div>
            </div>

            <div class="bottom-section">
                <div class="barcode">
                    <img src="{{ $barcodeImage }}" alt="Barcode {{ $cardData['barcode_data'] }}">
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

    <div class="print-instructions">
        <h3 style="color: #16a34a; margin-bottom: 10px;">🕌 Petunjuk Pencetakan Kartu Perpustakaan</h3>
        <p><strong>SMP Al Falah Darussalam</strong></p>
        <p>1. Gunakan kertas A4 berkualitas baik (minimal 80gsm)</p>
        <p>2. Set printer ke mode "Actual Size" atau "100%"</p>
        <p>3. Potong sesuai dengan garis batas hijau</p>
        <p>4. Laminating disarankan untuk daya tahan kartu</p>
    </div>

    <div class="cutting-guide">
        <strong>📏 Ukuran Kartu: 85.6mm × 53.98mm (Standar ID Card)</strong><br>
        <small>🗣️ <em>"Dan bacalah! Tuhanmu Yang Maha Mulia"</em> - QS. Al-Alaq: 3</small><br>
        <small style="margin-top: 5px; display: block;">Gunting dengan hati-hati mengikuti border hijau</small>
    </div>
</body>

</html>
