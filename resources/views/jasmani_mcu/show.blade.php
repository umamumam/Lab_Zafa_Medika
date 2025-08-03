@extends('layouts1.app')

@section('title', 'Detail Pemeriksaan Jasmani MCU')

@section('content')
<div class="page-inner">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Detail Pemeriksaan Jasmani MCU - {{ $jasmaniMcu->visitTest->visit->no_order }}</h4>
                    <div>
                        <a href="{{ route('jasmani-mcu.print', $jasmaniMcu->id) }}" class="btn btn-info me-2" target="_blank"
                            title="Cetak Hasil Jasmani MCU">
                            <i class="fas fa-print"></i> Cetak MCU
                        </a>
                        <a href="/visits/pemeriksaan" class="btn btn-warning">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    {{-- Patient and Visit Information --}}
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-primary border-bottom pb-2 mb-3">Informasi Pasien</h6>
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <th style="width: 40%;">No Order Laboratorium</th>
                                    <td>: {{ $jasmaniMcu->visitTest->visit->no_order ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Nama Pasien</th>
                                    <td>: {{ $jasmaniMcu->pasien->nama ?? '-' }} ({{ ($jasmaniMcu->pasien->jenis_kelamin ?? '') == 'Laki - Laki' ? 'L' : 'P' }})</td>
                                </tr>
                                <tr>
                                    <th>Nomor RM</th>
                                    <td>: {{ $jasmaniMcu->pasien->norm ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Lahir</th>
                                    <td>: {{ \Carbon\Carbon::parse($jasmaniMcu->pasien->tgl_lahir ?? now())->format('d/m/Y') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary border-bottom pb-2 mb-3">Informasi Pemeriksaan</h6>
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <th style="width: 40%;">Tanggal Pemeriksaan</th>
                                    <td>: {{ \Carbon\Carbon::parse($jasmaniMcu->tanggal_pemeriksaan ?? now())->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Dokter Pemeriksa</th>
                                    <td>: {{ $jasmaniMcu->dokterPemeriksa->nama ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Keluhan Saat Ini</th>
                                    <td>: {{ $jasmaniMcu->keluhan_saat_ini ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Status Order Visit</th>
                                    <td>: <span class="badge badge-info">{{ $jasmaniMcu->visitTest->visit->status_order ?? '-' }}</span></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <hr class="my-4">

                    {{-- Physical Examination Sections --}}
                    <h5 class="text-primary border-bottom pb-2 mb-4 text-center">HASIL PEMERIKSAAN JASMANI</h5>

                    {{-- PEMERIKSAAN FISIK --}}
                    <h6 class="text-primary border-bottom pb-2 mb-3">PEMERIKSAAN FISIK</h6>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label fw-bold">Berat Badan (kg)</label>
                                <p class="form-control-static">{{ $jasmaniMcu->berat_badan ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label fw-bold">Tinggi Badan (cm)</label>
                                <p class="form-control-static">{{ $jasmaniMcu->tinggi_badan ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label fw-bold">BMI</label>
                                <p class="form-control-static">{{ $jasmaniMcu->bmi ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- HIDUNG --}}
                    <h6 class="text-primary border-bottom pb-2 mb-3">HIDUNG</h6>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label fw-bold">Hidung</label>
                                <p class="form-control-static">{{ $jasmaniMcu->hidung ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- MATA --}}
                    <h6 class="text-primary border-bottom pb-2 mb-3">MATA</h6>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label fw-bold">Tajam Mata Tanpa Kacamata Kiri</label>
                                <p class="form-control-static">{{ $jasmaniMcu->mata_tanpa_kacamata_kiri ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label fw-bold">Tajam Mata Tanpa Kacamata Kanan</label>
                                <p class="form-control-static">{{ $jasmaniMcu->mata_tanpa_kacamata_kanan ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label fw-bold">Tajam Mata Dengan Kacamata Kiri</label>
                                <p class="form-control-static">{{ $jasmaniMcu->mata_dengan_kacamata_kiri ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label fw-bold">Tajam Mata Dengan Kacamata Kanan</label>
                                <p class="form-control-static">{{ $jasmaniMcu->mata_dengan_kacamata_kanan ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label fw-bold">Buta Warna</label>
                                <p class="form-control-static">{{ $jasmaniMcu->buta_warna ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label fw-bold">Lapang Pandang</label>
                                <p class="form-control-static">{{ $jasmaniMcu->lapang_pandang ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- TELINGA --}}
                    <h6 class="text-primary border-bottom pb-2 mb-3">TELINGA</h6>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label fw-bold">Liang Telinga Kiri</label>
                                <p class="form-control-static">{{ $jasmaniMcu->liang_telinga_kiri ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label fw-bold">Liang Telinga Kanan</label>
                                <p class="form-control-static">{{ $jasmaniMcu->liang_telinga_kanan ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label fw-bold">Gendang Telinga Kiri</label>
                                <p class="form-control-static">{{ $jasmaniMcu->gendang_telinga_kiri ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label fw-bold">Gendang Telinga Kanan</label>
                                <p class="form-control-static">{{ $jasmaniMcu->gendang_telinga_kanan ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- THORAX --}}
                    <h6 class="text-primary border-bottom pb-2 mb-3">THORAX</h6>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label fw-bold">Ritme Pernapasan</label>
                                <p class="form-control-static">{{ $jasmaniMcu->ritme_pernapasan ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label fw-bold">Pergerakan Dada</label>
                                <p class="form-control-static">{{ $jasmaniMcu->pergerakan_dada ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label fw-bold">Suara Pernapasan</label>
                                <p class="form-control-static">{{ $jasmaniMcu->suara_pernapasan ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- KARDIOVASKULAR --}}
                    <h6 class="text-primary border-bottom pb-2 mb-3">KARDIOVASKULAR</h6>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label fw-bold">Tekanan Darah</label>
                                <p class="form-control-static">{{ $jasmaniMcu->tekanan_darah ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label fw-bold">Frekuensi Jantung</label>
                                <p class="form-control-static">{{ $jasmaniMcu->frekuensi_jantung ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label fw-bold">Bunyi Jantung</label>
                                <p class="form-control-static">{{ $jasmaniMcu->bunyi_jantung ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- MULUT --}}
                    <h6 class="text-primary border-bottom pb-2 mb-3">MULUT</h6>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label fw-bold">Gigi</label>
                                <p class="form-control-static">{{ $jasmaniMcu->gigi ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- ABDOMEN --}}
                    <h6 class="text-primary border-bottom pb-2 mb-3">ABDOMEN</h6>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label fw-bold">Peristaltik</label>
                                <p class="form-control-static">{{ $jasmaniMcu->peristaltik ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label fw-bold">Abdominal Mass</label>
                                <p class="form-control-static">{{ $jasmaniMcu->abdominal_mass ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label fw-bold">Bekas Operasi</label>
                                <p class="form-control-static">{{ $jasmaniMcu->bekas_operasi ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- KESIMPULAN MEDIS --}}
                    <h6 class="text-primary border-bottom pb-2 mb-3">KESIMPULAN MEDIS</h6>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label fw-bold">Kesimpulan Medis</label>
                                <p class="form-control-static">{{ $jasmaniMcu->kesimpulan_medis ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label fw-bold">Temuan</label>
                                <p class="form-control-static">{{ $jasmaniMcu->temuan ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label fw-bold">Rekomendasi Dokter</label>
                                <p class="form-control-static">{{ $jasmaniMcu->rekomendasi_dokter ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('jasmani-mcu.edit', $jasmaniMcu->id) }}" class="btn btn-primary me-2">
                            <i class="fas fa-edit me-1"></i> Edit Hasil
                        </a>
                        <form action="{{ route('jasmani-mcu.destroy', $jasmaniMcu->id) }}" method="POST"
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus hasil pemeriksaan ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash-alt me-1"></i> Hapus Hasil
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
