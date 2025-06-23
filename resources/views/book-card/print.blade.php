<!DOCTYPE html>
<html>

<head>
    <title>Print Book Card</title>
    <style>
        @page {
            size: 10cm 5cm;
            margin: 0;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 5mm;
            width: 10cm;
            height: 5cm;
            box-sizing: border-box;
        }

        .card {
            border: 1px solid #000;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 5mm;
            box-sizing: border-box;
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

        .publisher-code {
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
    <div class="card">
        <div class="classification-code">{{ $cardData['classification_code'] }}</div>
        <div class="title-code">{{ $cardData['title_code'] }}</div>
        <div class="publisher-code">{{ $cardData['publisher_code'] }}</div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>

</html>
