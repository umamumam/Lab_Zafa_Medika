<!DOCTYPE html>
<html>

<head>
    <style>
        @page {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 0;
            width: 250px;
        }

        .label-container {
            border: 1px solid #000;
            margin: 5px;
            padding: 5px;
            width: 245px;
            text-align: center;
        }

        .no-order {
            font-size: 12px;
            margin-bottom: 5px;
        }

        .barcode {
            margin: 5px 10px;
            width: fit-content;
        }

        .info-text {
            font-size: 10px;
            margin-top: 3px;
        }
    </style>
</head>

<body>
    <div class="label-container">
        <div class="no-order">{{ $visit->no_order }}</div>

        <div class="barcode">
            {!! $barcode !!}
        </div>

        <div class="info-text">
            {{ strtoupper($visit->pasien->nama) }} &nbsp; (RM: {{ $visit->pasien->norm }})
        </div>
    </div>
</body>

</html>
