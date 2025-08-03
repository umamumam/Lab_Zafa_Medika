<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Hasil Pemeriksaan Jasmani MCU</title>
    <style>
        @page {
            margin-top: 0.5cm;
            margin-left: 1cm;
            margin-right: 1cm;
            margin-bottom: 1cm;
        }

        body {
            font-family: sans-serif;
            font-size: 12px;
            line-height: 1;
        }

        .kop-container {
            width: 100%;
            text-align: center;
            margin: 0;
            padding: 0;
            position: relative;
        }

        .kop-image {
            width: 100%;
            max-height: 150px;
            object-fit: contain;
            display: block;
            margin: 0;
            padding: 0;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 5px;
        }

        .info-table {
            width: 100%;
        }

        .info-table th,
        .info-table td {
            border: none;
            padding: 3px;
            text-align: left;
            vertical-align: top;
        }

        .info-table th {
            width: 30%;
        }

        .result-section {
            margin-top: 0;
        }

        .result-section h6 {
            color: #007bff;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 5px;
            margin-bottom: 10px;
            font-size: 12px;
        }

        .result-section p {
            margin-bottom: 5px;
            margin-left: 10px;
        }

        .title {
            text-align: center;
            margin: 15px 0;
            font-size: 14px;
            font-weight: bold;
            color: #007bff;
        }

        .divider {
            border-top: 1px solid #000;
            margin: 5px 0;
        }

        .barcode-container {
            position: relative;
            width: 100%;
            text-align: center;
            margin-top: 10px;
        }

        .barcode-adjustable {
            display: inline-block;
            position: relative;
            left: 0;
            transform: translateX(0);
            margin: 5px 0;
        }

        .signature-table {
            margin-top: 30px;
        }

        .signature-table td {
            padding: 5px;
        }

        .fw-bold {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="kop-container">
        <img src="{{ public_path('kopp.png') }}" class="kop-image">
    </div>

    <table class="info-table">
        <tr>
            <td width="50%" style="vertical-align: top;">
                <table>
                    <tr>
                        <th>No. Lab</th>
                        <td>: {{ $jasmaniMcu->visitTest->visit->no_order ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Nomor RM</th>
                        <td>: {{ $jasmaniMcu->pasien->norm ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Nama Pasien</th>
                        <td>: {{ $jasmaniMcu->pasien->nama ?? '-' }} ({{ ($jasmaniMcu->pasien->jenis_kelamin ?? '') == 'Laki - Laki' ? 'L' : 'P' }})</td>
                    </tr>
                    <tr>
                        <th>Tanggal Lahir</th>
                        <td>: {{ \Carbon\Carbon::parse($jasmaniMcu->pasien->tgl_lahir ?? now())->format('d-m-Y') }}</td>
                    </tr>
                </table>
            </td>
            <td width="50%" style="vertical-align: top;">
                <table>
                    <tr>
                        <th>Tanggal MCU</th>
                        <td>: {{ \Carbon\Carbon::parse($jasmaniMcu->tanggal_pemeriksaan ?? now())->format('d-m-Y') }}</td>
                    </tr>
                    <tr>
                        <th>Dokter</th>
                        <td>: {{ $jasmaniMcu->dokterPemeriksa->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Fasyankes</th>
                        <td>: {{ $jasmaniMcu->visitTest->visit->ruangan->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>: {{ $jasmaniMcu->visitTest->visit->jenis_pasien }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="divider"></div>
    <div class="title">HASIL PEMERIKSAAN JASMANI MCU</div>

    <div class="result-section">
        <h6 class="text-primary">PEMERIKSAAN FISIK</h6>
        <table>
            <tr>
                <td><span class="fw-bold">Keluhan Saat Ini :</span> {{ $jasmaniMcu->keluhan_saat_ini ?? '-' }}</td>
            </tr>
        </table>
        <table>
            <tr>
                <td style="width: 33%;"><span class="fw-bold">Berat Badan (kg):</span> {{ $jasmaniMcu->berat_badan ?? '-' }}</td>
                <td style="width: 33%;"><span class="fw-bold">Tinggi Badan (cm):</span> {{ $jasmaniMcu->tinggi_badan ?? '-' }}</td>
                <td style="width: 34%;"><span class="fw-bold">BMI:</span> {{ $jasmaniMcu->bmi ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <table>
        <tr>
            <td style="width: 50%; vertical-align: top;">
                <div class="result-section">
                    <h6 class="text-primary">HIDUNG</h6>
                    <p><span class="fw-bold">Hidung:</span> {{ $jasmaniMcu->hidung ?? '-' }}</p>
                </div>
            </td>
            <td style="width: 50%; vertical-align: top;">
                <div class="result-section">
                    <h6 class="text-primary">MULUT</h6>
                    <p><span class="fw-bold">Gigi:</span> {{ $jasmaniMcu->gigi ?? '-' }}</p>
                </div>
            </td>
        </tr>
    </table>

    <div class="result-section">
        <h6 class="text-primary">MATA</h6>
        <table>
            <tr>
                <td style="width: 50%;"><span class="fw-bold">Tajam Mata Tanpa Kacamata Kiri:</span> {{ $jasmaniMcu->mata_tanpa_kacamata_kiri ?? '-' }}</td>
                <td style="width: 50%;"><span class="fw-bold">Tajam Mata Tanpa Kacamata Kanan:</span> {{ $jasmaniMcu->mata_tanpa_kacamata_kanan ?? '-' }}</td>
            </tr>
            <tr>
                <td style="width: 50%;"><span class="fw-bold">Tajam Mata Dengan Kacamata Kiri:</span> {{ $jasmaniMcu->mata_dengan_kacamata_kiri ?? '-' }}</td>
                <td style="width: 50%;"><span class="fw-bold">Tajam Mata Dengan Kacamata Kanan:</span> {{ $jasmaniMcu->mata_dengan_kacamata_kanan ?? '-' }}</td>
            </tr>
            <tr>
                <td style="50%;"><span class="fw-bold">Buta Warna:</span> {{ $jasmaniMcu->buta_warna ?? '-' }}</td>
                <td style="50%;"><span class="fw-bold">Lapang Pandang:</span> {{ $jasmaniMcu->lapang_pandang ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <div class="result-section">
        <h6 class="text-primary">TELINGA</h6>
        <table>
            <tr>
                <td style="width: 50%;"><span class="fw-bold">Liang Telinga Kiri:</span> {{ $jasmaniMcu->liang_telinga_kiri ?? '-' }}</td>
                <td style="width: 50%;"><span class="fw-bold">Liang Telinga Kanan:</span> {{ $jasmaniMcu->liang_telinga_kanan ?? '-' }}</td>
            </tr>
            <tr>
                <td style="width: 50%;"><span class="fw-bold">Gendang Telinga Kiri:</span> {{ $jasmaniMcu->gendang_telinga_kiri ?? '-' }}</td>
                <td style="width: 50%;"><span class="fw-bold">Gendang Telinga Kanan:</span> {{ $jasmaniMcu->gendang_telinga_kanan ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <table>
        <tr>
            <td style="width: 50%; vertical-align: top;">
                <div class="result-section">
                    <h6 class="text-primary">THORAX</h6>
                    <p><span class="fw-bold">Ritme Pernapasan:</span> {{ $jasmaniMcu->ritme_pernapasan ?? '-' }}</p>
                    <p><span class="fw-bold">Pergerakan Dada:</span> {{ $jasmaniMcu->pergerakan_dada ?? '-' }}</p>
                    <p><span class="fw-bold">Suara Pernapasan:</span> {{ $jasmaniMcu->suara_pernapasan ?? '-' }}</p>
                </div>
            </td>
            <td style="width: 50%; vertical-align: top;">
                <div class="result-section">
                    <h6 class="text-primary">ABDOMEN</h6>
                    <p><span class="fw-bold">Peristaltik:</span> {{ $jasmaniMcu->peristaltik ?? '-' }}</p>
                    <p><span class="fw-bold">Abdominal Mass:</span> {{ $jasmaniMcu->abdominal_mass ?? '-' }}</p>
                    <p><span class="fw-bold">Bekas Operasi:</span> {{ $jasmaniMcu->bekas_operasi ?? '-' }}</p>
                </div>
            </td>
        </tr>
    </table>

    <table style="width: 100%;">
        <tr>
            <td style="width: 70%; vertical-align: top;">
                <div class="result-section">
                    <h6 class="text-primary">KARDIOVASKULAR</h6>
                    <p><span class="fw-bold">Tekanan Darah:</span> {{ $jasmaniMcu->tekanan_darah ?? '-' }}</p>
                    <p><span class="fw-bold">Frekuensi Jantung:</span> {{ $jasmaniMcu->frekuensi_jantung ?? '-' }}</p>
                    <p><span class="fw-bold">Bunyi Jantung:</span> {{ $jasmaniMcu->bunyi_jantung ?? '-' }}</p>
                </div>

                <div class="result-section">
                    <h6 class="text-primary">KESIMPULAN MEDIS</h6>
                    <p><span class="fw-bold">Kesimpulan Medis:</span> {{ $jasmaniMcu->kesimpulan_medis ?? '-' }}</p>
                    <p><span class="fw-bold">Temuan:</span> {{ $jasmaniMcu->temuan ?? '-' }}</p>
                    <p><span class="fw-bold">Rekomendasi Dokter:</span> {{ $jasmaniMcu->rekomendasi_dokter ?? '-' }}</p>
                </div>
            </td>
            <td style="width: 30%; text-align: center; vertical-align: top;">
                <p>&nbsp;</p> <p>&nbsp;</p> <p>Banjarbaru Utara, {{ \Carbon\Carbon::parse($jasmaniMcu->tanggal_pemeriksaan ?? now())->format('d-m-Y') }}</p>
                <label>Dokter Pemeriksa</label>
                <div class="barcode-container">
                    <div class="barcode-adjustable" style="left: 0;">
                        {!! $barcode !!}
                    </div>
                </div>
                <label><strong>{{ $jasmaniMcu->dokterPemeriksa->nama ?? '-' }}</strong></label>
            </td>
        </tr>
    </table>

</body>

</html>
