<!DOCTYPE html>
<html>

<head>
    <title>Print Book Cards</title>
    <style>
        @page {
            size: A4;
            margin: 10mm;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .cards-container {
            display: flex;
            flex-wrap: wrap;
            gap: 5mm;
        }

        .card {
            width: 10cm;
            height: 5cm;
            border: 1px solid #000;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 5mm;
            box-sizing: border-box;
            page-break-inside: avoid;
        }

        .classification-code {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .title-code {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .writer-code {
            font-size: 18px;
            font-weight: bold;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>
    <div class="cards-container">
        @foreach ($cardsData as $cardData)
            <div class="card">
                <div class="classification-code">{{ $cardData['classification_code'] }}</div>
                <div class="title-code">{{ $cardData['title_code'] }}</div>
                <div class="writer-code">{{ $cardData['writer_code'] }}</div>
            </div>
        @endforeach
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>

</html>
