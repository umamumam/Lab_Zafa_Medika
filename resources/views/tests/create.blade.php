@extends('layouts1.app')

@section('content')
<div class="page-inner">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Tambah Test Baru</h4>
                </div>
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <div class="card-body">
                    <form method="POST" action="{{ route('tests.store') }}">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="kode" class="form-label">Kode Test</label>
                                <input type="text" class="form-control @error('kode') is-invalid @enderror" id="kode"
                                    name="kode" value="{{ old('kode') }}" required>
                                @error('kode')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="nama" class="form-label">Nama Test</label>
                                <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama"
                                    name="nama" value="{{ old('nama') }}" required>
                                @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="metode" class="form-label">Metode</label>
                                <input type="text" class="form-control @error('metode') is-invalid @enderror"
                                    id="metode" name="metode" value="{{ old('metode') }}" required>
                                @error('metode')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="nilai_normal" class="form-label">Nilai Normal</label>
                                <input type="text" class="form-control @error('nilai_normal') is-invalid @enderror"
                                    id="nilai_normal" name="nilai_normal" value="{{ old('nilai_normal') }}" required>
                                @error('nilai_normal')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="satuan" class="form-label">Satuan</label>
                                <input type="text" class="form-control @error('satuan') is-invalid @enderror"
                                    id="satuan" name="satuan" value="{{ old('satuan') }}" required>
                                @error('satuan')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="harga_umum" class="form-label">Harga Umum</label>
                                <input type="number" class="form-control @error('harga_umum') is-invalid @enderror"
                                    id="harga_umum" name="harga_umum" value="{{ old('harga_umum') }}" min="0" required>
                                @error('harga_umum')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="harga_bpjs" class="form-label">Harga BPJS</label>
                                <input type="number" class="form-control @error('harga_bpjs') is-invalid @enderror"
                                    id="harga_bpjs" name="harga_bpjs" value="{{ old('harga_bpjs') }}" min="0" required>
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
                                    <option value="Hematologi" {{ old('grup_test')=='Hematologi' ? 'selected' : '' }}>
                                        Hematologi</option>
                                    <option value="Kimia Klinik" {{ old('grup_test')=='Kimia Klinik' ? 'selected' : ''
                                        }}>Kimia Klinik</option>
                                    <option value="Imunologi / Serologi" {{ old('grup_test')=='Imunologi / Serologi'
                                        ? 'selected' : '' }}>Imunologi / Serologi</option>
                                    <option value="Mikrobiologi" {{ old('grup_test')=='Mikrobiologi' ? 'selected' : ''
                                        }}>Mikrobiologi</option>
                                    <option value="Khusus" {{ old('grup_test')=='Khusus' ? 'selected' : '' }}>Khusus
                                    </option>
                                    <option value="Lainnya" {{ old('grup_test')=='Lainnya' ? 'selected' : '' }}>Lainnya
                                    </option>
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
                                    <option value="Cairan dan Parasitologi (E1)" {{
                                        old('sub_grup')=='Cairan dan Parasitologi (E1)' ? 'selected' : '' }}>Cairan dan
                                        Parasitologi (E1)</option>
                                    <option value="Elektrometri (D1)" {{ old('sub_grup')=='Elektrometri (D1)'
                                        ? 'selected' : '' }}>Elektrometri (D1)</option>
                                    <option value="Endokrin Metabolik (B1)" {{
                                        old('sub_grup')=='Endokrin Metabolik (B1)' ? 'selected' : '' }}>Endokrin
                                        Metabolik (B1)</option>
                                    <option value="Faal Ginjal (B3)" {{ old('sub_grup')=='Faal Ginjal (B3)' ? 'selected'
                                        : '' }}>Faal Ginjal (B3)</option>
                                    <option value="Faal Hati (B2)" {{ old('sub_grup')=='Faal Hati (B2)' ? 'selected'
                                        : '' }}>Faal Hati (B2)</option>
                                    <option value="Faal Hemotsasis (A2)" {{ old('sub_grup')=='Faal Hemotsasis (A2)'
                                        ? 'selected' : '' }}>Faal Hemotsasis (A2)</option>
                                    <option value="Faal Tiroid (B5)" {{ old('sub_grup')=='Faal Tiroid (B5)' ? 'selected'
                                        : '' }}>Faal Tiroid (B5)</option>
                                    <option value="Hematologi (A1)" {{ old('sub_grup')=='Hematologi (A1)' ? 'selected'
                                        : '' }}>Hematologi (A1)</option>
                                    <option value="Imunologi / Serologi (B4)" {{
                                        old('sub_grup')=='Imunologi / Serologi (B4)' ? 'selected' : '' }}>Imunologi /
                                        Serologi (B4)</option>
                                    <option value="Marker Infeksi / Inflamasi (C1)" {{
                                        old('sub_grup')=='Marker Infeksi / Inflamasi (C1)' ? 'selected' : '' }}>Marker
                                        Infeksi / Inflamasi (C1)</option>
                                    <option value="Marker Jantung (C2)" {{ old('sub_grup')=='Marker Jantung (C2)'
                                        ? 'selected' : '' }}>Marker Jantung (C2)</option>
                                    <option value="Lain - Lain (D2)" {{ old('sub_grup')=='Lain - Lain (D2)' ? 'selected'
                                        : '' }}>Lain - Lain (D2)</option>
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
                                    <option value="Whole Blood EDTA" {{ old('jenis_sampel')=='Whole Blood EDTA'
                                        ? 'selected' : '' }}>Whole Blood EDTA</option>
                                    <option value="Whole Blood Heparin" {{ old('jenis_sampel')=='Whole Blood Heparin'
                                        ? 'selected' : '' }}>Whole Blood Heparin</option>
                                    <option value="Serum" {{ old('jenis_sampel')=='Serum' ? 'selected' : '' }}>Serum
                                    </option>
                                    <option value="Plasma Citrat" {{ old('jenis_sampel')=='Plasma Citrat' ? 'selected'
                                        : '' }}>Plasma Citrat</option>
                                    <option value="Urin" {{ old('jenis_sampel')=='Urin' ? 'selected' : '' }}>Urin
                                    </option>
                                    <option value="Feaces" {{ old('jenis_sampel')=='Feaces' ? 'selected' : '' }}>Feaces
                                    </option>
                                    <option value="Sputum" {{ old('jenis_sampel')=='Sputum' ? 'selected' : '' }}>Sputum
                                    </option>
                                    <option value="Cairan" {{ old('jenis_sampel')=='Cairan' ? 'selected' : '' }}>Cairan
                                    </option>
                                    <option value="LCS" {{ old('jenis_sampel')=='LCS' ? 'selected' : '' }}>LCS</option>
                                    <option value="Preparat" {{ old('jenis_sampel')=='Preparat' ? 'selected' : '' }}>
                                        Preparat</option>
                                    <option value="Swab" {{ old('jenis_sampel')=='Swab' ? 'selected' : '' }}>Swab
                                    </option>
                                </select>
                                @error('jenis_sampel')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status"
                                    name="status" required>
                                    <option value="Aktif" {{ (old('status')=='Aktif' || old('status')==null)
                                        ? 'selected' : '' }}>Aktif</option>
                                    <option value="Tidak Aktif" {{ old('status')=='Tidak Aktif' ? 'selected' : '' }}>
                                        Tidak Aktif</option>
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
                                    id="interpretasi" name="interpretasi" rows="3">{{ old('interpretasi') }}</textarea>
                                @error('interpretasi')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <hr>
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h5>Nilai Normal</h5>
                                    <button type="button" id="add-nilai-normal" class="btn btn-secondary btn-sm">
                                        <i class="fas fa-plus"></i> Tambah Nilai Normal
                                    </button>
                                </div>
                                <p class="mb-3">Anda bisa menambahkan satu atau beberapa nilai normal berdasarkan jenis
                                    kelamin dan rentang usia.</p>
                                <div id="nilai-normal-container">
                                    <div class="nilai-normal-item mb-3 p-3 border rounded bg-light">
                                        <div class="row g-3 align-items-end">
                                            <div class="col-md-3">
                                                <label class="form-label">Jenis Kelamin</label>
                                                <select class="form-select" name="nilai_normals_data[0][jenis_kelamin]"
                                                    required>
                                                    <option value="Umum" {{
                                                        old('nilai_normals_data.0.jenis_kelamin')=='Umum' ? 'selected'
                                                        : '' }}>Umum</option>
                                                    <option value="Laki-laki" {{
                                                        old('nilai_normals_data.0.jenis_kelamin')=='Laki-laki'
                                                        ? 'selected' : '' }}>Laki-laki</option>
                                                    <option value="Perempuan" {{
                                                        old('nilai_normals_data.0.jenis_kelamin')=='Perempuan'
                                                        ? 'selected' : '' }}>Perempuan</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Usia Min (tahun)</label>
                                                <input type="number" class="form-control"
                                                    name="nilai_normals_data[0][usia_min]"
                                                    value="{{ old('nilai_normals_data.0.usia_min') }}" min="0">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Usia Max (tahun)</label>
                                                <input type="number" class="form-control"
                                                    name="nilai_normals_data[0][usia_max]"
                                                    value="{{ old('nilai_normals_data.0.usia_max') }}" min="0">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Tipe Nilai</label>
                                                <select class="form-select nilai-type"
                                                    name="nilai_normals_data[0][type]" required>
                                                    <option value="Single" {{ old('nilai_normals_data.0.type')=='Single'
                                                        ? 'selected' : '' }}>Single</option>
                                                    <option value="Range" {{ old('nilai_normals_data.0.type')=='Range'
                                                        ? 'selected' : '' }}>Range</option>
                                                </select>
                                            </div>
                                            <div class="col-md-1 range-field"
                                                style="{{ old('nilai_normals_data.0.type') == 'Range' ? '' : 'display: none;' }}">
                                                <label class="form-label">Min</label>
                                                <input type="number" step="0.01" class="form-control"
                                                    name="nilai_normals_data[0][min]"
                                                    value="{{ old('nilai_normals_data.0.min') }}">
                                            </div>
                                            <div class="col-md-1 range-field"
                                                style="{{ old('nilai_normals_data.0.type') == 'Range' ? '' : 'display: none;' }}">
                                                <label class="form-label">Max</label>
                                                <input type="number" step="0.01" class="form-control"
                                                    name="nilai_normals_data[0][max]"
                                                    value="{{ old('nilai_normals_data.0.max') }}">
                                            </div>
                                            <div class="col-md-1">
                                                <button type="button"
                                                    class="btn btn-danger btn-sm remove-nilai-normal w-100">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-0">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save mr-1"></i> Simpan
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
        // Menambahkan nilai normal baru
        let nilaiNormalIndex = 1;
        const container = document.getElementById('nilai-normal-container');
        const addButton = document.getElementById('add-nilai-normal');

        // Fungsi untuk menambahkan event listener pada type select
        function setupTypeChangeListener(item) {
            const typeSelect = item.querySelector('.nilai-type');
            typeSelect.addEventListener('change', function() {
                const rangeFields = item.querySelectorAll('.range-field');
                if (this.value === 'Range') {
                    rangeFields.forEach(field => field.style.display = 'block');
                } else {
                    rangeFields.forEach(field => field.style.display = 'none');
                }
            });
        }

        // Setup untuk item pertama
        const firstItem = document.querySelector('.nilai-normal-item');
        setupTypeChangeListener(firstItem);

        // Menambahkan item baru
        addButton.addEventListener('click', function() {
            const template = document.querySelector('.nilai-normal-item').cloneNode(true);
            const newIndex = nilaiNormalIndex++;

            // Update semua nama input dengan index baru
            template.querySelectorAll('[name]').forEach(input => {
                const name = input.getAttribute('name');
                input.setAttribute('name', name.replace('[0]', `[${newIndex}]`));
            });

            // Reset nilai input
            template.querySelectorAll('input').forEach(input => {
                input.value = '';
            });

            // Reset select
            template.querySelectorAll('select').forEach(select => {
                if (select.classList.contains('nilai-type')) {
                    select.value = 'Single';
                } else {
                    select.value = select.querySelector('option').value;
                }
            });

            // Sembunyikan range field jika ada
            template.querySelectorAll('.range-field').forEach(field => {
                field.style.display = 'none';
            });

            // Setup event listener untuk item baru
            setupTypeChangeListener(template);

            container.appendChild(template);
        });

        // Menghapus nilai normal
        container.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-nilai-normal') ||
                e.target.closest('.remove-nilai-normal')) {
                const item = e.target.closest('.nilai-normal-item');
                if (document.querySelectorAll('.nilai-normal-item').length > 1) {
                    item.remove();
                } else {
                    alert('Minimal harus ada satu nilai normal');
                }
            }
        });
    });
</script>
@endsection
