<!DOCTYPE html>
<html>
<head>
    <title>Cetak Laporan Kasir</title>
    <style>
        @page {
            margin: 1cm 0;
        }
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 90%;
            margin: 0 auto;
            padding: 1cm 0;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h3 {
            margin: 0;
            padding: 0;
        }
        .info {
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        .info div {
        }
        .info div.right {
            text-align: right;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .footer-signature {
            text-align: right;
            margin-top: 50px;
            padding-right: 50px;
        }
        .footer-signature p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h3>CETAK LAPORAN KASIR</h3>
        </div>

        <div class="info">
            <div>
                <strong>Tanggal:</strong> {{ $tanggal }}<br>
                <strong>Jam:</strong> {{ $waktu }}
            </div>
            <div class="right">
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 40%;">Nama</th>
                    <th style="width: 15%;">Jumlah</th>
                    <th style="width: 10%;">Metode</th>
                    <th style="width: 30%;">Kasir</th>
                </tr>
            </thead>
            <tbody>
                @forelse($laporan as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item['nama'] }}</td>
                    <td class="text-right">Rp {{ number_format($item['jumlah'], 0, ',', '.') }}</td>
                    <td class="text-center">{{ $item['metode'] }}</td>
                    <td>{{ $item['kasir'] }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak ada transaksi untuk tanggal ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="total-section">
            <p style="text-align: right; margin: 0;"><strong>TOTAL CASH ALL Rp {{ number_format($totalCash, 0, ',', '.') }}</strong></p>
            <p style="text-align: right; margin: 0;"><strong>TOTAL QRIS ALL Rp {{ number_format($totalQris, 0, ',', '.') }}</strong></p>
            <p style="text-align: right; margin: 0;"><strong>TOTAL TRANSFER ALL Rp {{ number_format($totalTransfer, 0, ',', '.') }}</strong></p>
        </div>

        <div class="footer-signature">
            <p>( {{ $kasirBertugas }} )</p>
        </div>
    </div>
</body>
</html>
