@extends('layouts1.app')

@section('content')
<div class="page-inner">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Edit Test</h4>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('tests.update', $test->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="kode" class="form-label">Kode Test</label>
                                <input type="text" class="form-control @error('kode') is-invalid @enderror" id="kode"
                                    name="kode" value="{{ old('kode', $test->kode) }}" required>
                                @error('kode')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="nama" class="form-label">Nama Test</label>
                                <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama"
                                    name="nama" value="{{ old('nama', $test->nama) }}" required>
                                @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="metode" class="form-label">Metode</label>
                                <input type="text" class="form-control @error('metode') is-invalid @enderror"
                                    id="metode" name="metode" value="{{ old('metode', $test->metode) }}" required>
                                @error('metode')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="nilai_normal" class="form-label">Nilai Normal</label>
                                <input type="text" class="form-control @error('nilai_normal') is-invalid @enderror"
                                    id="nilai_normal" name="nilai_normal"
                                    value="{{ old('nilai_normal', $test->nilai_normal) }}" required>
                                @error('nilai_normal')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="type" class="form-label">Tipe Nilai</label>
                                <select class="form-select @error('type') is-invalid @enderror" id="type" name="type"
                                    required>
                                    <option value="">Pilih Tipe</option>
                                    <option value="Single" {{ old('type', $test->type) == 'Single' ? 'selected' : ''
                                        }}>Single</option>
                                    <option value="Range" {{ old('type', $test->type) == 'Range' ? 'selected' : ''
                                        }}>Range</option>
                                </select>
                                @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 range-fields"
                                style="{{ old('type', $test->type) == 'Range' ? '' : 'display: none;' }}">
                                <label for="min" class="form-label">Nilai Minimum</label>
                                <input type="text" class="form-control @error('min') is-invalid @enderror" id="min"
                                    name="min" value="{{ old('min', $test->min ?? '') }}" inputmode="decimal"
                                    pattern="[0-9]*[.,]?[0-9]*">
                                {{-- <input type="number" class="form-control @error('min') is-invalid @enderror"
                                    id="min" name="min" value="{{ old('min', $test->min) }}"> --}}
                                @error('min')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 range-fields"
                                style="{{ old('type', $test->type) == 'Range' ? '' : 'display: none;' }}">
                                <label for="max" class="form-label">Nilai Maksimum</label>
                                <input type="text" class="form-control @error('max') is-invalid @enderror" id="max"
                                    name="max" value="{{ old('max', $test->max ?? '') }}" inputmode="decimal"
                                    pattern="[0-9]*[.,]?[0-9]*">
                                {{-- <input type="number" class="form-control @error('max') is-invalid @enderror"
                                    id="max" name="max" value="{{ old('max', $test->max) }}"> --}}
                                @error('max')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="satuan" class="form-label">Satuan</label>
                                <input type="text" class="form-control @error('satuan') is-invalid @enderror"
                                    id="satuan" name="satuan" value="{{ old('satuan', $test->satuan) }}" required>
                                @error('satuan')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="harga_umum" class="form-label">Harga Umum</label>
                                <input type="number" class="form-control @error('harga_umum') is-invalid @enderror"
                                    id="harga_umum" name="harga_umum" value="{{ old('harga_umum', $test->harga_umum) }}"
                                    min="0" required>
                                @error('harga_umum')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="harga_bpjs" class="form-label">Harga BPJS</label>
                                <input type="number" class="form-control @error('harga_bpjs') is-invalid @enderror"
                                    id="harga_bpjs" name="harga_bpjs" value="{{ old('harga_bpjs', $test->harga_bpjs) }}"
                                    min="0" required>
                                @error('harga_bpjs')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="grup_test" class="form-label">Grup Test</label>
                                <select class="form-select @error('grup_test') is-invalid @enderror" id="grup_test"
                                    name="grup_test" required>
                                    <option value="">Pilih Grup Test</option>
                                    <option value="Hematologi" {{ old('grup_test', $test->grup_test) == 'Hematologi' ?
                                        'selected' : '' }}>Hematologi</option>
                                    <option value="Kimia Klinik" {{ old('grup_test', $test->grup_test) == 'Kimia Klinik'
                                        ? 'selected' : '' }}>Kimia Klinik</option>
                                    <option value="Imunologi / Serologi" {{ old('grup_test', $test->grup_test) ==
                                        'Imunologi / Serologi' ? 'selected' : '' }}>Imunologi / Serologi</option>
                                    <option value="Mikrobiologi" {{ old('grup_test', $test->grup_test) == 'Mikrobiologi'
                                        ? 'selected' : '' }}>Mikrobiologi</option>
                                    <option value="Khusus" {{ old('grup_test', $test->grup_test) == 'Khusus' ?
                                        'selected' : '' }}>Khusus</option>
                                    <option value="Lainnya" {{ old('grup_test', $test->grup_test) == 'Lainnya' ?
                                        'selected' : '' }}>Lainnya</option>
                                </select>
                                @error('grup_test')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="sub_grup" class="form-label">Sub Grup</label>
                                <select class="form-select @error('sub_grup') is-invalid @enderror" id="sub_grup"
                                    name="sub_grup" required>
                                    <option value="">Pilih Sub Grup</option>
                                    <option value="Cairan dan Parasitologi (E1)" {{ old('sub_grup', $test->sub_grup) ==
                                        'Cairan dan Parasitologi (E1)' ? 'selected' : '' }}>Cairan dan Parasitologi (E1)
                                    </option>
                                    <option value="Elektrometri (D1)" {{ old('sub_grup', $test->sub_grup) ==
                                        'Elektrometri (D1)' ? 'selected' : '' }}>Elektrometri (D1)</option>
                                    <option value="Endokrin Metabolik (B1)" {{ old('sub_grup', $test->sub_grup) ==
                                        'Endokrin Metabolik (B1)' ? 'selected' : '' }}>Endokrin Metabolik (B1)</option>
                                    <option value="Faal Ginjal (B3)" {{ old('sub_grup', $test->sub_grup) == 'Faal Ginjal
                                        (B3)' ? 'selected' : '' }}>Faal Ginjal (B3)</option>
                                    <option value="Faal Hati (B2)" {{ old('sub_grup', $test->sub_grup) == 'Faal Hati
                                        (B2)' ? 'selected' : '' }}>Faal Hati (B2)</option>
                                    <option value="Faal Hemotsasis (A2)" {{ old('sub_grup', $test->sub_grup) == 'Faal
                                        Hemotsasis (A2)' ? 'selected' : '' }}>Faal Hemotsasis (A2)</option>
                                    <option value="Faal Tiroid (B5)" {{ old('sub_grup', $test->sub_grup) == 'Faal Tiroid
                                        (B5)' ? 'selected' : '' }}>Faal Tiroid (B5)</option>
                                    <option value="Hematologi (A1)" {{ old('sub_grup', $test->sub_grup) == 'Hematologi
                                        (A1)' ? 'selected' : '' }}>Hematologi (A1)</option>
                                    <option value="Imunologi / Serologi (B4)" {{ old('sub_grup', $test->sub_grup) ==
                                        'Imunologi / Serologi (B4)' ? 'selected' : '' }}>Imunologi / Serologi (B4)
                                    </option>
                                    <option value="Marker Infeksi / Inflamasi (C1)" {{ old('sub_grup', $test->sub_grup)
                                        == 'Marker Infeksi / Inflamasi (C1)' ? 'selected' : '' }}>Marker Infeksi /
                                        Inflamasi (C1)</option>
                                    <option value="Marker Jantung (C2)" {{ old('sub_grup', $test->sub_grup) == 'Marker
                                        Jantung (C2)' ? 'selected' : '' }}>Marker Jantung (C2)</option>
                                    <option value="Lain - Lain (D2)" {{ old('sub_grup', $test->sub_grup) == 'Lain - Lain
                                        (D2)' ? 'selected' : '' }}>Lain - Lain (D2)</option>
                                </select>
                                @error('sub_grup')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="jenis_sampel" class="form-label">Jenis Sampel</label>
                                <select class="form-select @error('jenis_sampel') is-invalid @enderror"
                                    id="jenis_sampel" name="jenis_sampel" required>
                                    <option value="">Pilih Jenis Sampel</option>
                                    <option value="Whole Blood EDTA" {{ old('jenis_sampel', $test->jenis_sampel) ==
                                        'Whole Blood EDTA' ? 'selected' : '' }}>Whole Blood EDTA</option>
                                    <option value="Whole Blood Heparin" {{ old('jenis_sampel', $test->jenis_sampel) ==
                                        'Whole Blood Heparin' ? 'selected' : '' }}>Whole Blood Heparin</option>
                                    <option value="Serum" {{ old('jenis_sampel', $test->jenis_sampel) == 'Serum' ?
                                        'selected' : '' }}>Serum</option>
                                    <option value="Plasma Citrat" {{ old('jenis_sampel', $test->jenis_sampel) == 'Plasma
                                        Citrat' ? 'selected' : '' }}>Plasma Citrat</option>
                                    <option value="Urin" {{ old('jenis_sampel', $test->jenis_sampel) == 'Urin' ?
                                        'selected' : '' }}>Urin</option>
                                    <option value="Feaces" {{ old('jenis_sampel', $test->jenis_sampel) == 'Feaces' ?
                                        'selected' : '' }}>Feaces</option>
                                    <option value="Sputum" {{ old('jenis_sampel', $test->jenis_sampel) == 'Sputum' ?
                                        'selected' : '' }}>Sputum</option>
                                    <option value="Cairan" {{ old('jenis_sampel', $test->jenis_sampel) == 'Cairan' ?
                                        'selected' : '' }}>Cairan</option>
                                    <option value="LCS" {{ old('jenis_sampel', $test->jenis_sampel) == 'LCS' ?
                                        'selected' : '' }}>LCS</option>
                                    <option value="Preparat" {{ old('jenis_sampel', $test->jenis_sampel) == 'Preparat' ?
                                        'selected' : '' }}>Preparat</option>
                                    <option value="Swab" {{ old('jenis_sampel', $test->jenis_sampel) == 'Swab' ?
                                        'selected' : '' }}>Swab</option>
                                </select>
                                @error('jenis_sampel')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status"
                                    name="status" required>
                                    <option value="">Pilih Status</option>
                                    <option value="Aktif" {{ old('status', $test->status) == 'Aktif' ? 'selected' : ''
                                        }}>Aktif</option>
                                    <option value="Tidak Aktif" {{ old('status', $test->status) == 'Tidak Aktif' ?
                                        'selected' : '' }}>Tidak Aktif</option>
                                </select>
                                @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="interpretasi" class="form-label">Interpretasi</label>
                                <textarea class="form-control @error('interpretasi') is-invalid @enderror"
                                    id="interpretasi" name="interpretasi"
                                    rows="3">{{ old('interpretasi', $test->interpretasi) }}</textarea>
                                @error('interpretasi')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save mr-1"></i> Simpan Perubahan
                                </button>
                                <a href="{{ route('tests.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times mr-1"></i> Batal
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('type');
        const rangeFields = document.querySelectorAll('.range-fields');

        typeSelect.addEventListener('change', function() {
            if (this.value === 'Range') {
                rangeFields.forEach(field => field.style.display = 'block');
            } else {
                rangeFields.forEach(field => field.style.display = 'none');
            }
        });
    });
</script>
@endsection
