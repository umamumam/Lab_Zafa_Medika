@extends('layouts1.form')

@section('content')
<div class="page-inner" style="margin-top: 2cm">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-notes-medical me-2"></i>Form Pemeriksaan Jasmani MCU</h5>
                    <a href="/visits/pemeriksaan" class="btn btn-warning">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form
                        action="{{ isset($jasmaniMcu) ? route('jasmani-mcu.update', $jasmaniMcu->id) : route('jasmani-mcu.store', $visitTest->id) }}"
                        method="POST">
                        @csrf
                        @if(isset($jasmaniMcu))
                        @method('PUT')
                        @endif

                        <div class="row g-3">
                            {{-- DATA PASIEN & PEMERIKSAAN --}}
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2 mb-3">DATA PASIEN & PEMERIKSAAN</h6>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group mb-3">
                                            <label class="form-label">No. Order</label>
                                            <input type="text" class="form-control bg-light"
                                                value="{{ $visitTest->visit->no_order }}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Nomor RM</label>
                                            <input type="text" class="form-control bg-light" value="{{ $pasien->norm }}"
                                                disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="tanggal_pemeriksaan" class="form-label">Tanggal
                                                Pemeriksaan</label>
                                            <input type="date"
                                                class="form-control @error('tanggal_pemeriksaan') is-invalid @enderror"
                                                id="tanggal_pemeriksaan" name="tanggal_pemeriksaan"
                                                value="{{ old('tanggal_pemeriksaan', $jasmaniMcu->tanggal_pemeriksaan ?? date('Y-m-d')) }}">
                                            @error('tanggal_pemeriksaan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Nama Pasien</label>
                                            <input type="text" class="form-control bg-light" value="{{ $pasien->nama }}"
                                                disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="dokter_pemeriksa_id" class="form-label">Dokter Pemeriksa</label>
                                            <select
                                                class="form-select select2 @error('dokter_pemeriksa_id') is-invalid @enderror"
                                                id="dokter_pemeriksa_id" name="dokter_pemeriksa_id">
                                                <option value="">Pilih Dokter</option>
                                                @foreach($dokters as $dokter)
                                                <option value="{{ $dokter->id }}" @selected(old('dokter_pemeriksa_id',
                                                    $jasmaniMcu->dokter_pemeriksa_id ?? '') == $dokter->id)>
                                                    {{ $dokter->nama }} - {{ $dokter->spesialis }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('dokter_pemeriksa_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- KELUHAN SAAT INI - PEMERIKSAAN FISIK --}}
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2 mb-3">KELUHAN SAAT INI - PEMERIKSAAN FISIK
                                </h6>
                                <div class="form-group mb-3">
                                    <label for="keluhan_saat_ini" class="form-label">Keluhan Saat Ini</label>
                                    <textarea class="form-control @error('keluhan_saat_ini') is-invalid @enderror"
                                        id="keluhan_saat_ini" name="keluhan_saat_ini"
                                        rows="3">{{ old('keluhan_saat_ini', $jasmaniMcu->keluhan_saat_ini ?? '') }}</textarea>
                                    @error('keluhan_saat_ini')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="berat_badan" class="form-label">Berat Badan (kg)</label>
                                    <input type="number" step="0.01"
                                        class="form-control @error('berat_badan') is-invalid @enderror" id="berat_badan"
                                        name="berat_badan"
                                        value="{{ old('berat_badan', $jasmaniMcu->berat_badan ?? '') }}">
                                    @error('berat_badan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="tinggi_badan" class="form-label">Tinggi Badan (cm)</label>
                                    <input type="number" step="0.01"
                                        class="form-control @error('tinggi_badan') is-invalid @enderror"
                                        id="tinggi_badan" name="tinggi_badan"
                                        value="{{ old('tinggi_badan', $jasmaniMcu->tinggi_badan ?? '') }}">
                                    @error('tinggi_badan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="bmi" class="form-label">BMI</label>
                                    <input type="text" class="form-control @error('bmi') is-invalid @enderror" id="bmi"
                                        name="bmi" value="{{ old('bmi', $jasmaniMcu->bmi ?? '') }}">
                                    @error('bmi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- HIDUNG --}}
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2 mb-3">HIDUNG</h6>
                                <div class="form-group mb-3">
                                    <label for="hidung" class="form-label">Hidung</label>
                                    <input type="text" class="form-control @error('hidung') is-invalid @enderror"
                                        id="hidung" name="hidung"
                                        value="{{ old('hidung', $jasmaniMcu->hidung ?? '') }}">
                                    @error('hidung')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- MATA --}}
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2 mb-3">MATA</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="mata_tanpa_kacamata_kiri" class="form-label">Tajam Mata Tanpa
                                                Kacamata
                                                Kiri</label>
                                            <input type="text"
                                                class="form-control @error('mata_tanpa_kacamata_kiri') is-invalid @enderror"
                                                id="mata_tanpa_kacamata_kiri" name="mata_tanpa_kacamata_kiri"
                                                value="{{ old('mata_tanpa_kacamata_kiri', $jasmaniMcu->mata_tanpa_kacamata_kiri ?? '') }}">
                                            @error('mata_tanpa_kacamata_kiri')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="mata_tanpa_kacamata_kanan" class="form-label">Tajam Mata Tanpa
                                                Kacamata
                                                Kanan</label>
                                            <input type="text"
                                                class="form-control @error('mata_tanpa_kacamata_kanan') is-invalid @enderror"
                                                id="mata_tanpa_kacamata_kanan" name="mata_tanpa_kacamata_kanan"
                                                value="{{ old('mata_tanpa_kacamata_kanan', $jasmaniMcu->mata_tanpa_kacamata_kanan ?? '') }}">
                                            @error('mata_tanpa_kacamata_kanan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="mata_dengan_kacamata_kiri" class="form-label">Tajam Mata Dengan
                                                Kacamata
                                                Kiri</label>
                                            <input type="text"
                                                class="form-control @error('mata_dengan_kacamata_kiri') is-invalid @enderror"
                                                id="mata_dengan_kacamata_kiri" name="mata_dengan_kacamata_kiri"
                                                value="{{ old('mata_dengan_kacamata_kiri', $jasmaniMcu->mata_dengan_kacamata_kiri ?? '') }}">
                                            @error('mata_dengan_kacamata_kiri')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="mata_dengan_kacamata_kanan" class="form-label">Tajam Mata Dengan
                                                Kacamata
                                                Kanan</label>
                                            <input type="text"
                                                class="form-control @error('mata_dengan_kacamata_kanan') is-invalid @enderror"
                                                id="mata_dengan_kacamata_kanan" name="mata_dengan_kacamata_kanan"
                                                value="{{ old('mata_dengan_kacamata_kanan', $jasmaniMcu->mata_dengan_kacamata_kanan ?? '') }}">
                                            @error('mata_dengan_kacamata_kanan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="buta_warna" class="form-label">Buta Warna</label>
                                            <input type="text"
                                                class="form-control @error('buta_warna') is-invalid @enderror"
                                                id="buta_warna" name="buta_warna"
                                                value="{{ old('buta_warna', $jasmaniMcu->buta_warna ?? '') }}">
                                            @error('buta_warna')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="lapang_pandang" class="form-label">Lapang Pandang</label>
                                            <input type="text"
                                                class="form-control @error('lapang_pandang') is-invalid @enderror"
                                                id="lapang_pandang" name="lapang_pandang"
                                                value="{{ old('lapang_pandang', $jasmaniMcu->lapang_pandang ?? '') }}">
                                            @error('lapang_pandang')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- TELINGA --}}
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2 mb-3">TELINGA</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="liang_telinga_kiri" class="form-label">Liang Telinga
                                                Kiri</label>
                                            <input type="text"
                                                class="form-control @error('liang_telinga_kiri') is-invalid @enderror"
                                                id="liang_telinga_kiri" name="liang_telinga_kiri"
                                                value="{{ old('liang_telinga_kiri', $jasmaniMcu->liang_telinga_kiri ?? '') }}">
                                            @error('liang_telinga_kiri')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="liang_telinga_kanan" class="form-label">Liang Telinga
                                                Kanan</label>
                                            <input type="text"
                                                class="form-control @error('liang_telinga_kanan') is-invalid @enderror"
                                                id="liang_telinga_kanan" name="liang_telinga_kanan"
                                                value="{{ old('liang_telinga_kanan', $jasmaniMcu->liang_telinga_kanan ?? '') }}">
                                            @error('liang_telinga_kanan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="gendang_telinga_kiri" class="form-label">Gendang Telinga
                                                Kiri</label>
                                            <input type="text"
                                                class="form-control @error('gendang_telinga_kiri') is-invalid @enderror"
                                                id="gendang_telinga_kiri" name="gendang_telinga_kiri"
                                                value="{{ old('gendang_telinga_kiri', $jasmaniMcu->gendang_telinga_kiri ?? '') }}">
                                            @error('gendang_telinga_kiri')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="gendang_telinga_kanan" class="form-label">Gendang Telinga
                                                Kanan</label>
                                            <input type="text"
                                                class="form-control @error('gendang_telinga_kanan') is-invalid @enderror"
                                                id="gendang_telinga_kanan" name="gendang_telinga_kanan"
                                                value="{{ old('gendang_telinga_kanan', $jasmaniMcu->gendang_telinga_kanan ?? '') }}">
                                            @error('gendang_telinga_kanan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- THORAX --}}
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2 mb-3">THORAX</h6>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="ritme_pernapasan" class="form-label">Ritme Pernapasan</label>
                                            <input type="text"
                                                class="form-control @error('ritme_pernapasan') is-invalid @enderror"
                                                id="ritme_pernapasan" name="ritme_pernapasan"
                                                value="{{ old('ritme_pernapasan', $jasmaniMcu->ritme_pernapasan ?? '') }}">
                                            @error('ritme_pernapasan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="pergerakan_dada" class="form-label">Pergerakan Dada</label>
                                            <input type="text"
                                                class="form-control @error('pergerakan_dada') is-invalid @enderror"
                                                id="pergerakan_dada" name="pergerakan_dada"
                                                value="{{ old('pergerakan_dada', $jasmaniMcu->pergerakan_dada ?? '') }}">
                                            @error('pergerakan_dada')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="suara_pernapasan" class="form-label">Suara Pernapasan</label>
                                            <input type="text"
                                                class="form-control @error('suara_pernapasan') is-invalid @enderror"
                                                id="suara_pernapasan" name="suara_pernapasan"
                                                value="{{ old('suara_pernapasan', $jasmaniMcu->suara_pernapasan ?? '') }}">
                                            @error('suara_pernapasan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- KARDIOVASKULAR --}}
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2 mb-3">KARDIOVASKULAR</h6>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="tekanan_darah" class="form-label">Tekanan Darah</label>
                                            <input type="text"
                                                class="form-control @error('tekanan_darah') is-invalid @enderror"
                                                id="tekanan_darah" name="tekanan_darah"
                                                value="{{ old('tekanan_darah', $jasmaniMcu->tekanan_darah ?? '') }}">
                                            @error('tekanan_darah')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="frekuensi_jantung" class="form-label">Frekuensi Jantung</label>
                                            <input type="text"
                                                class="form-control @error('frekuensi_jantung') is-invalid @enderror"
                                                id="frekuensi_jantung" name="frekuensi_jantung"
                                                value="{{ old('frekuensi_jantung', $jasmaniMcu->frekuensi_jantung ?? '') }}">
                                            @error('frekuensi_jantung')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="bunyi_jantung" class="form-label">Bunyi Jantung</label>
                                            <input type="text"
                                                class="form-control @error('bunyi_jantung') is-invalid @enderror"
                                                id="bunyi_jantung" name="bunyi_jantung"
                                                value="{{ old('bunyi_jantung', $jasmaniMcu->bunyi_jantung ?? '') }}">
                                            @error('bunyi_jantung')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- MULUT --}}
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2 mb-3">MULUT</h6>
                                <div class="form-group mb-3">
                                    <label for="gigi" class="form-label">Gigi</label>
                                    <input type="text" class="form-control @error('gigi') is-invalid @enderror"
                                        id="gigi" name="gigi" value="{{ old('gigi', $jasmaniMcu->gigi ?? '') }}">
                                    @error('gigi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- ABDOMEN --}}
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2 mb-3">ABDOMEN</h6>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="peristaltik" class="form-label">Peristaltik</label>
                                            <input type="text"
                                                class="form-control @error('peristaltik') is-invalid @enderror"
                                                id="peristaltik" name="peristaltik"
                                                value="{{ old('peristaltik', $jasmaniMcu->peristaltik ?? '') }}">
                                            @error('peristaltik')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="abdominal_mass" class="form-label">Abdominal Mass</label>
                                            <input type="text"
                                                class="form-control @error('abdominal_mass') is-invalid @enderror"
                                                id="abdominal_mass" name="abdominal_mass"
                                                value="{{ old('abdominal_mass', $jasmaniMcu->abdominal_mass ?? '') }}">
                                            @error('abdominal_mass')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="bekas_operasi" class="form-label">Bekas Operasi</label>
                                            <input type="text"
                                                class="form-control @error('bekas_operasi') is-invalid @enderror"
                                                id="bekas_operasi" name="bekas_operasi"
                                                value="{{ old('bekas_operasi', $jasmaniMcu->bekas_operasi ?? '') }}">
                                            @error('bekas_operasi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- KESIMPULAN MEDIS --}}
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2 mb-3">KESIMPULAN MEDIS</h6>
                                <div class="form-group mb-3">
                                    <label for="kesimpulan_medis" class="form-label">Kesimpulan Medis</label>
                                    <textarea class="form-control @error('kesimpulan_medis') is-invalid @enderror"
                                        id="kesimpulan_medis" name="kesimpulan_medis"
                                        rows="3">{{ old('kesimpulan_medis', $jasmaniMcu->kesimpulan_medis ?? '') }}</textarea>
                                    @error('kesimpulan_medis')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group mb-3">
                                    <label for="temuan" class="form-label">Temuan</label>
                                    <textarea class="form-control @error('temuan') is-invalid @enderror" id="temuan"
                                        name="temuan" rows="3">{{ old('temuan', $jasmaniMcu->temuan ?? '') }}</textarea>
                                    @error('temuan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group mb-3">
                                    <label for="rekomendasi_dokter" class="form-label">Rekomendasi Dokter</label>
                                    <textarea class="form-control @error('rekomendasi_dokter') is-invalid @enderror"
                                        id="rekomendasi_dokter" name="rekomendasi_dokter"
                                        rows="3">{{ old('rekomendasi_dokter', $jasmaniMcu->rekomendasi_dokter ?? '') }}</textarea>
                                    @error('rekomendasi_dokter')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i> Simpan Hasil
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
    rel="stylesheet" />

<script>
    $(document).ready(function() {
        $('.select2').select2({
            width: '100%',
            theme: 'bootstrap-5'
        });
    });
</script>
@endpush
@endsection
