<!DOCTYPE html>
<html>
<head>
    <style>
        @page {
            margin: 10px;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            font-size: 8pt;
        }
        .label-table {
            width: 100%;
            border-collapse: collapse;
            page-break-inside: avoid;
        }
        .label-header {
            text-align: center;
            font-weight: bold;
            font-size: 9pt;
            padding-bottom: 2pt;
            border-bottom: 0.5pt solid #ccc;
        }
        .data-cell {
            width: 70%;
            padding: 2pt 4pt;
            vertical-align: top;
        }
        .barcode-cell {
            width: 30%;
            text-align: center;
            vertical-align: middle;
        }
        .patient-name {
            font-weight: bold;
            text-transform: uppercase;
            font-size: 8pt;
            margin-bottom: 1pt;
        }
        .patient-data {
            margin: 1pt 0;
            font-weight: bold;
            line-height: 1.2;
        }
        .patient-norm {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <table class="label-table">
        <tr>
            <td colspan="2" class="label-header">LABORATORIUM ZAFA MEDIKA</td>
        </tr>
        <tr>
            <td class="data-cell">
                <div class="patient-name">
                    {{ strtoupper($pasien->nama) }} ({{ substr($pasien->jenis_kelamin, 0, 1) }})
                </div>
                <div class="patient-data">
                    {{ $tglLahirFormatted }}/{{ floor($umur) }} Thn
                </div>
                <div class="patient-data patient-norm">
                    RM: {{ $pasien->norm }}
                </div>
                <div class="patient-data">
                    Status: {{ explode(' / ', $pasien->status_pasien)[0] }}
                </div>
                <div class="patient-data">
                    Tgl Register: {{ $tglRegisterFormatted }}
                </div>
            </td>
            <td class="barcode-cell">
                {!! $barcode !!}
            </td>
        </tr>
    </table>
</body>
</html>
