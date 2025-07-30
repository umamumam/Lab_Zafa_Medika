@extends('layouts1.app')

@section('content')
<div class="page-inner">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Edit Test: {{ $test->nama }}</h4>
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
                                <label for="nilai_normal" class="form-label">Nilai Normal (Deskripsi Umum)</label>
                                <input type="text" class="form-control @error('nilai_normal') is-invalid @enderror"
                                    id="nilai_normal" name="nilai_normal" value="{{ old('nilai_normal', $test->nilai_normal) }}" required>
                                @error('nilai_normal')
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
                                    id="harga_umum" name="harga_umum" value="{{ old('harga_umum', $test->harga_umum) }}" min="0" required>
                                @error('harga_umum')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="harga_bpjs" class="form-label">Harga BPJS</label>
                                <input type="number" class="form-control @error('harga_bpjs') is-invalid @enderror"
                                    id="harga_bpjs" name="harga_bpjs" value="{{ old('harga_bpjs', $test->harga_bpjs) }}" min="0" required>
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
                                    @php
                                        $grupTests = ['Hematologi', 'Kimia Klinik', 'Imunologi / Serologi', 'Mikrobiologi', 'Khusus', 'Lainnya'];
                                    @endphp
                                    @foreach($grupTests as $grup)
                                        <option value="{{ $grup }}" {{ (old('grup_test', $test->grup_test) == $grup) ? 'selected' : '' }}>
                                            {{ $grup }}
                                        </option>
                                    @endforeach
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
                                    @php
                                        $subGrups = ['Cairan dan Parasitologi (E1)', 'Elektrometri (D1)', 'Endokrin Metabolik (B1)', 'Faal Ginjal (B3)', 'Faal Hati (B2)', 'Faal Hemotsasis (A2)', 'Faal Tiroid (B5)', 'Hematologi (A1)', 'Imunologi / Serologi (B4)', 'Marker Infeksi / Inflamasi (C1)', 'Marker Jantung (C2)', 'Lain - Lain (D2)'];
                                    @endphp
                                    @foreach($subGrups as $subGrup)
                                        <option value="{{ $subGrup }}" {{ (old('sub_grup', $test->sub_grup) == $subGrup) ? 'selected' : '' }}>
                                            {{ $subGrup }}
                                        </option>
                                    @endforeach
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
                                    @php
                                        $jenisSampels = ['Whole Blood EDTA', 'Whole Blood Heparin', 'Serum', 'Plasma Citrat', 'Urin', 'Feaces', 'Sputum', 'Cairan', 'LCS', 'Preparat', 'Swab'];
                                    @endphp
                                    @foreach($jenisSampels as $sampel)
                                        <option value="{{ $sampel }}" {{ (old('jenis_sampel', $test->jenis_sampel) == $sampel) ? 'selected' : '' }}>
                                            {{ $sampel }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('jenis_sampel')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status"
                                    name="status" required>
                                    <option value="Aktif" {{ (old('status', $test->status) == 'Aktif') ? 'selected' : '' }}>Aktif</option>
                                    <option value="Tidak Aktif" {{ (old('status', $test->status) == 'Tidak Aktif') ? 'selected' : '' }}>Tidak Aktif</option>
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
                                    id="interpretasi" name="interpretasi" rows="3">{{ old('interpretasi', $test->interpretasi) }}</textarea> {{-- Diisi dari $test->interpretasi --}}
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
                                    @forelse($test->normalValues as $index => $normalValue)
                                    <div class="nilai-normal-item mb-3 p-3 border rounded bg-light">
                                        <div class="row g-3 align-items-end">
                                            <div class="col-md-3">
                                                <label class="form-label">Jenis Kelamin</label>
                                                <select class="form-select" name="nilai_normals_data[{{ $index }}][jenis_kelamin]" required>
                                                    <option value="Umum" {{ (old("nilai_normals_data.$index.jenis_kelamin", $normalValue->jenis_kelamin) == 'Umum') ? 'selected' : '' }}>Umum</option>
                                                    <option value="Laki-laki" {{ (old("nilai_normals_data.$index.jenis_kelamin", $normalValue->jenis_kelamin) == 'Laki-laki') ? 'selected' : '' }}>Laki-laki</option>
                                                    <option value="Perempuan" {{ (old("nilai_normals_data.$index.jenis_kelamin", $normalValue->jenis_kelamin) == 'Perempuan') ? 'selected' : '' }}>Perempuan</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Usia Min (tahun)</label>
                                                <input type="number" class="form-control"
                                                    name="nilai_normals_data[{{ $index }}][usia_min]"
                                                    value="{{ old("nilai_normals_data.$index.usia_min", $normalValue->usia_min) }}" min="0">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Usia Max (tahun)</label>
                                                <input type="number" class="form-control"
                                                    name="nilai_normals_data[{{ $index }}][usia_max]"
                                                    value="{{ old("nilai_normals_data.$index.usia_max", $normalValue->usia_max) }}" min="0">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Tipe Nilai</label>
                                                <select class="form-select nilai-type"
                                                    name="nilai_normals_data[{{ $index }}][type]" required>
                                                    <option value="Single" {{ (old("nilai_normals_data.$index.type", $normalValue->type) == 'Single') ? 'selected' : '' }}>Single</option>
                                                    <option value="Range" {{ (old("nilai_normals_data.$index.type", $normalValue->type) == 'Range') ? 'selected' : '' }}>Range</option>
                                                </select>
                                            </div>
                                            <div class="col-md-1 range-field"
                                                style="{{ (old("nilai_normals_data.$index.type", $normalValue->type) == 'Range') ? '' : 'display: none;' }}"> {{-- Kontrol tampilan --}}
                                                <label class="form-label">Min</label>
                                                <input type="number" step="0.01" class="form-control"
                                                    name="nilai_normals_data[{{ $index }}][min]"
                                                    value="{{ old("nilai_normals_data.$index.min", $normalValue->min) }}">
                                            </div>
                                            <div class="col-md-1 range-field"
                                                style="{{ (old("nilai_normals_data.$index.type", $normalValue->type) == 'Range') ? '' : 'display: none;' }}"> {{-- Kontrol tampilan --}}
                                                <label class="form-label">Max</label>
                                                <input type="number" step="0.01" class="form-control"
                                                    name="nilai_normals_data[{{ $index }}][max]"
                                                    value="{{ old("nilai_normals_data.$index.max", $normalValue->max) }}">
                                            </div>
                                            <div class="col-md-1">
                                                <button type="button"
                                                    class="btn btn-danger btn-sm remove-nilai-normal w-100">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    <div class="nilai-normal-item mb-3 p-3 border rounded bg-light">
                                        <div class="row g-3 align-items-end">
                                            <div class="col-md-3">
                                                <label class="form-label">Jenis Kelamin</label>
                                                <select class="form-select" name="nilai_normals_data[0][jenis_kelamin]" required>
                                                    <option value="Umum" {{ old('nilai_normals_data.0.jenis_kelamin')=='Umum' ? 'selected' : '' }}>Umum</option>
                                                    <option value="Laki-laki" {{ old('nilai_normals_data.0.jenis_kelamin')=='Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                                    <option value="Perempuan" {{ old('nilai_normals_data.0.jenis_kelamin')=='Perempuan' ? 'selected' : '' }}>Perempuan</option>
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
                                                    <option value="Single" {{ old('nilai_normals_data.0.type')=='Single' ? 'selected' : '' }}>Single</option>
                                                    <option value="Range" {{ old('nilai_normals_data.0.type')=='Range' ? 'selected' : '' }}>Range</option>
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
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save mr-1"></i> Perbarui
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
        let nilaiNormalIndex = {{ $test->normalValues->count() > 0 ? $test->normalValues->count() : 1 }};
        const container = document.getElementById('nilai-normal-container');
        const addButton = document.getElementById('add-nilai-normal');
        function setupTypeChangeListener(item) {
            const typeSelect = item.querySelector('.nilai-type');
            if (typeSelect) {
                typeSelect.addEventListener('change', function() {
                    const rangeFields = item.querySelectorAll('.range-field');
                    if (this.value === 'Range') {
                        rangeFields.forEach(field => field.style.display = 'block');
                        item.querySelector('[name$="[min]"]').setAttribute('required', 'required');
                        item.querySelector('[name$="[max]"]').setAttribute('required', 'required');
                    } else {
                        rangeFields.forEach(field => {
                            field.style.display = 'none';
                            field.querySelector('input').removeAttribute('required');
                            field.querySelector('input').value = '';
                        });
                    }
                });
                typeSelect.dispatchEvent(new Event('change'));
            }
        }

        document.querySelectorAll('.nilai-normal-item').forEach(item => {
            setupTypeChangeListener(item);
        });

        addButton.addEventListener('click', function() {
            const defaultTemplate = document.querySelector('.nilai-normal-item');
            const template = defaultTemplate ? defaultTemplate.cloneNode(true) : createNewNilaiNormalTemplate();
            const newIndex = nilaiNormalIndex++;

            template.querySelectorAll('[name]').forEach(input => {
                const name = input.getAttribute('name');
                input.setAttribute('name', name.replace(/\[\d+\]/, `[${newIndex}]`));
            });

            // Reset nilai input
            template.querySelectorAll('input').forEach(input => {
                input.value = '';
                input.removeAttribute('required');
            });

            // Reset select dan atur nilai default
            template.querySelectorAll('select').forEach(select => {
                if (select.classList.contains('nilai-type')) {
                    select.value = 'Single';
                } else {
                    select.value = select.querySelector('option').value;
                }
            });

            template.querySelectorAll('.range-field').forEach(field => {
                field.style.display = 'none';
            });

            setupTypeChangeListener(template);

            container.appendChild(template);
        });

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

        function createNewNilaiNormalTemplate() {
            const div = document.createElement('div');
            div.classList.add('nilai-normal-item', 'mb-3', 'p-3', 'border', 'rounded', 'bg-light');
            div.innerHTML = `
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Jenis Kelamin</label>
                        <select class="form-select" name="nilai_normals_data[0][jenis_kelamin]" required>
                            <option value="Umum">Umum</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Usia Min (tahun)</label>
                        <input type="number" class="form-control" name="nilai_normals_data[0][usia_min]" min="0">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Usia Max (tahun)</label>
                        <input type="number" class="form-control" name="nilai_normals_data[0][usia_max]" min="0">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Tipe Nilai</label>
                        <select class="form-select nilai-type" name="nilai_normals_data[0][type]" required>
                            <option value="Single">Single</option>
                            <option value="Range">Range</option>
                        </select>
                    </div>
                    <div class="col-md-1 range-field" style="display: none;">
                        <label class="form-label">Min</label>
                        <input type="number" step="0.01" class="form-control" name="nilai_normals_data[0][min]">
                    </div>
                    <div class="col-md-1 range-field" style="display: none;">
                        <label class="form-label">Max</label>
                        <input type="number" step="0.01" class="form-control" name="nilai_normals_data[0][max]">
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger btn-sm remove-nilai-normal w-100">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            return div;
        }
    });
</script>
@endsection
