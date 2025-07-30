@extends('layouts1.form')

@section('content')
<div class="page-inner" style="margin-top: 2.5cm">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h4>Edit Detail Test</h4>
            <a href="{{ route('detail_tests.index') }}" class="btn btn-secondary btn-round">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
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
        <div class="card-body" style="margin-bottom: 2cm">
            <form action="{{ route('detail_tests.update', $detailTest->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="test_id" class="form-label">Test / Pemeriksaan</label>
                        <select class="form-select select2 @error('test_id') is-invalid @enderror" id="test_id"
                            name="test_id" required>
                            <option value="">Pilih Nama Pemeriksaan</option>
                            @foreach($tests as $test)
                            <option value="{{ $test->id }}" {{ old('test_id', $detailTest->test_id) == $test->id ?
                                'selected' : '' }}>
                                {{ $test->nama }}
                            </option>
                            @endforeach
                        </select>
                        @error('test_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label for="urutan" class="form-label">Urutan</label>
                        <input type="number" class="form-control @error('urutan') is-invalid @enderror" id="urutan"
                            name="urutan" value="{{ old('urutan', $detailTest->urutan) }}" min="0" required>
                        @error('urutan')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-control @error('status') is-invalid @enderror" id="status" name="status"
                            required>
                            <option value="Aktif" {{ old('status', $detailTest->status) == 'Aktif' ? 'selected' : ''
                                }}>Aktif</option>
                            <option value="Tidak Aktif" {{ old('status', $detailTest->status) == 'Tidak Aktif' ?
                                'selected' : '' }}>Tidak Aktif</option>
                        </select>
                        @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="nama" class="form-label">Nama Detail Test</label>
                        <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama"
                            name="nama" value="{{ old('nama', $detailTest->nama) }}" required>
                        @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label for="nilai_normal" class="form-label">Nilai Rujukan Umum</label>
                        <input type="text" class="form-control @error('nilai_normal') is-invalid @enderror"
                            id="nilai_normal" name="nilai_normal"
                            value="{{ old('nilai_normal', $detailTest->nilai_normal) }}" required>
                        @error('nilai_normal')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label for="satuan" class="form-label">Satuan</label>
                        <input type="text" class="form-control @error('satuan') is-invalid @enderror" id="satuan"
                            name="satuan" value="{{ old('satuan', $detailTest->satuan) }}" required>
                        @error('satuan')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <hr>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Detail Nilai Rujukan (Per Gender dan Usia)</h5>
                    <button type="button" class="btn btn-info btn-sm" id="add-nilai-normal">
                        <i class="fas fa-plus mr-1"></i> Tambah Nilai Normal
                    </button>
                </div>
                <div id="nilai-normal-container">
                    @forelse(old('nilai_normals_data', $detailTest->normalValues ?? []) as $index => $data)
                    <div class="card mb-3 nilai-normal-row" id="nilai-normal-row-{{ $index }}"
                        style="border-radius: 8px; border: 1px solid #e0e0e0;">
                        <div class="card-body" style="background-color: #f8f9fa; padding: 1.25rem;">
                            <div class="row">
                                <div class="col-md-2">
                                    <label for="nilai_normals_data_{{ $index }}_jenis_kelamin" class="form-label">Jenis
                                        Kelamin</label>
                                    <select
                                        class="form-control @error('nilai_normals_data.' . $index . '.jenis_kelamin') is-invalid @enderror"
                                        id="nilai_normals_data_{{ $index }}_jenis_kelamin"
                                        name="nilai_normals_data[{{ $index }}][jenis_kelamin]" required
                                        style="background-color: white; border-radius: 6px;">
                                        <option value="Umum" {{ (old('nilai_normals_data.' . $index . '.jenis_kelamin'
                                            , $data->jenis_kelamin ?? '') == 'Umum') ? 'selected' : '' }}>Umum</option>
                                        <option value="Laki-laki" {{ (old('nilai_normals_data.' . $index
                                            . '.jenis_kelamin' , $data->jenis_kelamin ?? '') == 'Laki-laki') ?
                                            'selected' : '' }}>Laki-laki</option>
                                        <option value="Perempuan" {{ (old('nilai_normals_data.' . $index
                                            . '.jenis_kelamin' , $data->jenis_kelamin ?? '') == 'Perempuan') ?
                                            'selected' : '' }}>Perempuan</option>
                                    </select>
                                    @error('nilai_normals_data.' . $index . '.jenis_kelamin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2">
                                    <label for="nilai_normals_data_{{ $index }}_usia_min" class="form-label">Usia Min
                                        (Thn)</label>
                                    <input type="number"
                                        class="form-control @error('nilai_normals_data.' . $index . '.usia_min') is-invalid @enderror"
                                        id="nilai_normals_data_{{ $index }}_usia_min"
                                        name="nilai_normals_data[{{ $index }}][usia_min]"
                                        value="{{ old('nilai_normals_data.' . $index . '.usia_min', $data->usia_min ?? '') }}"
                                        min="0" style="background-color: white; border-radius: 6px;">
                                    @error('nilai_normals_data.' . $index . '.usia_min')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2">
                                    <label for="nilai_normals_data_{{ $index }}_usia_max" class="form-label">Usia Max
                                        (Thn)</label>
                                    <input type="number"
                                        class="form-control @error('nilai_normals_data.' . $index . '.usia_max') is-invalid @enderror"
                                        id="nilai_normals_data_{{ $index }}_usia_max"
                                        name="nilai_normals_data[{{ $index }}][usia_max]"
                                        value="{{ old('nilai_normals_data.' . $index . '.usia_max', $data->usia_max ?? '') }}"
                                        min="0" style="background-color: white; border-radius: 6px;">
                                    @error('nilai_normals_data.' . $index . '.usia_max')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2">
                                    <label for="nilai_normals_data_{{ $index }}_type" class="form-label">Tipe
                                        Nilai</label>
                                    <select
                                        class="form-control @error('nilai_normals_data.' . $index . '.type') is-invalid @enderror"
                                        id="nilai_normals_data_{{ $index }}_type"
                                        name="nilai_normals_data[{{ $index }}][type]" required
                                        style="background-color: white; border-radius: 6px;">
                                        <option value="Single" {{ (old('nilai_normals_data.' . $index . '.type' ,
                                            $data->type ?? '') == 'Single') ? 'selected' : '' }}>Single</option>
                                        <option value="Range" {{ (old('nilai_normals_data.' . $index . '.type' , $data->
                                            type ?? '') == 'Range') ? 'selected' : '' }}>Range</option>
                                    </select>
                                    @error('nilai_normals_data.' . $index . '.type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <div class="row" id="range-fields-{{ $index }}"
                                        style="display: {{ (old('nilai_normals_data.' . $index . '.type', $data->type ?? '') == 'Range') ? 'flex' : 'none' }}; align-items: flex-end;">
                                        <div class="col-md-6">
                                            <label for="nilai_normals_data_{{ $index }}_min"
                                                class="form-label">Min</label>
                                            <input type="number" step="0.01"
                                                class="form-control @error('nilai_normals_data.' . $index . '.min') is-invalid @enderror"
                                                id="nilai_normals_data_{{ $index }}_min"
                                                name="nilai_normals_data[{{ $index }}][min]"
                                                value="{{ old('nilai_normals_data.' . $index . '.min', $data->min ?? '') }}"
                                                style="background-color: white; border-radius: 6px;">
                                            @error('nilai_normals_data.' . $index . '.min')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="nilai_normals_data_{{ $index }}_max"
                                                class="form-label">Max</label>
                                            <input type="number" step="0.01"
                                                class="form-control @error('nilai_normals_data.' . $index . '.max') is-invalid @enderror"
                                                id="nilai_normals_data_{{ $index }}_max"
                                                name="nilai_normals_data[{{ $index }}][max]"
                                                value="{{ old('nilai_normals_data.' . $index . '.max', $data->max ?? '') }}"
                                                style="background-color: white; border-radius: 6px;">
                                            @error('nilai_normals_data.' . $index . '.max')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-1 d-flex align-items-end">
                                    <button type="button" class="btn btn-danger btn-sm remove-nilai-normal-btn"
                                        id="remove-nilai-normal-{{ $index }}" style="border-radius: 6px;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="card mb-3 nilai-normal-row" id="nilai-normal-row-0"
                        style="border-radius: 8px; border: 1px solid #e0e0e0;">
                        <div class="card-body" style="background-color: #f8f9fa; padding: 1.25rem;">
                            <div class="row">
                                <div class="col-md-2">
                                    <label for="nilai_normals_data_0_jenis_kelamin" class="form-label">Jenis
                                        Kelamin</label>
                                    <select class="form-control" id="nilai_normals_data_0_jenis_kelamin"
                                        name="nilai_normals_data[0][jenis_kelamin]" required
                                        style="background-color: white; border-radius: 6px;">
                                        <option value="Umum">Umum</option>
                                        <option value="Laki-laki">Laki-laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="nilai_normals_data_0_usia_min" class="form-label">Usia Min (Thn)</label>
                                    <input type="number" class="form-control" id="nilai_normals_data_0_usia_min"
                                        name="nilai_normals_data[0][usia_min]" value="" min="0"
                                        style="background-color: white; border-radius: 6px;">
                                </div>
                                <div class="col-md-2">
                                    <label for="nilai_normals_data_0_usia_max" class="form-label">Usia Max (Thn)</label>
                                    <input type="number" class="form-control" id="nilai_normals_data_0_usia_max"
                                        name="nilai_normals_data[0][usia_max]" value="" min="0"
                                        style="background-color: white; border-radius: 6px;">
                                </div>
                                <div class="col-md-2">
                                    <label for="nilai_normals_data_0_type" class="form-label">Tipe Nilai</label>
                                    <select class="form-control" id="nilai_normals_data_0_type"
                                        name="nilai_normals_data[0][type]" required
                                        style="background-color: white; border-radius: 6px;">
                                        <option value="Single">Single</option>
                                        <option value="Range">Range</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <div class="row" id="range-fields-0" style="display: none; align-items: flex-end;">
                                        <div class="col-md-6">
                                            <label for="nilai_normals_data_0_min" class="form-label">Min</label>
                                            <input type="number" step="0.01" class="form-control"
                                                id="nilai_normals_data_0_min" name="nilai_normals_data[0][min]" value=""
                                                style="background-color: white; border-radius: 6px;">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="nilai_normals_data_0_max" class="form-label">Max</label>
                                            <input type="number" step="0.01" class="form-control"
                                                id="nilai_normals_data_0_max" name="nilai_normals_data[0][max]" value=""
                                                style="background-color: white; border-radius: 6px;">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-1 d-flex align-items-end">
                                    <button type="button" class="btn btn-danger btn-sm remove-nilai-normal-btn"
                                        id="remove-nilai-normal-0" style="border-radius: 6px;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforelse
                </div>
                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i> Simpan
                        Perubahan</button>
                    <a href="{{ route('detail_tests.index') }}" class="btn btn-secondary"><i
                            class="fas fa-ban me-2"></i> Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script type="text/template" id="nilai-normal-template">
    <div class="card mb-3 nilai-normal-row" id="nilai-normal-row-__INDEX__" style="border-radius: 8px; border: 1px solid #e0e0e0;">
        <div class="card-body" style="background-color: #f8f9fa; padding: 1.25rem;">
            <div class="row">
                <div class="col-md-2">
                    <label for="nilai_normals_data___INDEX___jenis_kelamin" class="form-label">Jenis Kelamin</label>
                    <select class="form-control"
                        id="nilai_normals_data___INDEX___jenis_kelamin"
                        name="nilai_normals_data[__INDEX__][jenis_kelamin]"
                        required
                        style="background-color: white; border-radius: 6px;">
                        <option value="Umum">Umum</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="nilai_normals_data___INDEX___usia_min" class="form-label">Usia Min (Thn)</label>
                    <input type="number" class="form-control"
                        id="nilai_normals_data___INDEX___usia_min"
                        name="nilai_normals_data[__INDEX__][usia_min]"
                        value=""
                        min="0"
                        style="background-color: white; border-radius: 6px;">
                </div>
                <div class="col-md-2">
                    <label for="nilai_normals_data___INDEX___usia_max" class="form-label">Usia Max (Thn)</label>
                    <input type="number" class="form-control"
                        id="nilai_normals_data___INDEX___usia_max"
                        name="nilai_normals_data[__INDEX__][usia_max]"
                        value=""
                        min="0"
                        style="background-color: white; border-radius: 6px;">
                </div>
                <div class="col-md-2">
                    <label for="nilai_normals_data___INDEX___type" class="form-label">Tipe Nilai</label>
                    <select class="form-control"
                        id="nilai_normals_data___INDEX___type"
                        name="nilai_normals_data[__INDEX__][type]"
                        required
                        style="background-color: white; border-radius: 6px;">
                        <option value="Single">Single</option>
                        <option value="Range">Range</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="row" id="range-fields-__INDEX__" style="display: none; align-items: flex-end;">
                        <div class="col-md-6">
                            <label for="nilai_normals_data___INDEX___min" class="form-label">Min</label>
                            <input type="number" step="0.01" class="form-control"
                                id="nilai_normals_data___INDEX___min"
                                name="nilai_normals_data[__INDEX__][min]"
                                value=""
                                style="background-color: white; border-radius: 6px;">
                        </div>
                        <div class="col-md-6">
                            <label for="nilai_normals_data___INDEX___max" class="form-label">Max</label>
                            <input type="number" step="0.01" class="form-control"
                                id="nilai_normals_data___INDEX___max"
                                name="nilai_normals_data[__INDEX__][max]"
                                value=""
                                style="background-color: white; border-radius: 6px;">
                        </div>
                    </div>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm remove-nilai-normal-btn"
                        id="remove-nilai-normal-__INDEX__"
                        style="border-radius: 6px;">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let nilaiNormalIndex = 0;
        document.querySelectorAll('.nilai-normal-row').forEach(row => {
            const idParts = row.id.split('-');
            const index = parseInt(idParts[idParts.length - 1]);
            if (!isNaN(index) && index >= nilaiNormalIndex) {
                nilaiNormalIndex = index + 1;
            }
        });

        function toggleRangeFields(typeSelectElement) {
            const idParts = typeSelectElement.id.split('_');
            const index = idParts[idParts.length - 2];

            const isRange = typeSelectElement.value === 'Range';
            const rangeFieldsDiv = document.getElementById(`range-fields-${index}`);
            const minInput = document.getElementById(`nilai_normals_data_${index}_min`);
            const maxInput = document.getElementById(`nilai_normals_data_${index}_max`);

            if (rangeFieldsDiv) {
                rangeFieldsDiv.style.display = isRange ? 'flex' : 'none';
            }
            if (minInput) {
                minInput.required = isRange;
                if (!isRange) minInput.value = '';
            }
            if (maxInput) {
                maxInput.required = isRange;
                if (!isRange) maxInput.value = '';
            }
        }

        document.querySelectorAll('.nilai-normal-row select[id$="_type"]').forEach(function(select) {
            toggleRangeFields(select);
            select.addEventListener('change', function() {
                toggleRangeFields(this);
            });
        });

        document.getElementById('add-nilai-normal').addEventListener('click', function() {
            const template = document.getElementById('nilai-normal-template').innerHTML;
            const newFields = template.replace(/__INDEX__/g, nilaiNormalIndex);
            document.getElementById('nilai-normal-container').insertAdjacentHTML('beforeend', newFields);

            const newTypeSelect = document.getElementById(`nilai_normals_data_${nilaiNormalIndex}_type`);
            if (newTypeSelect) {
                newTypeSelect.addEventListener('change', function() {
                    toggleRangeFields(this);
                });
                toggleRangeFields(newTypeSelect);
            }

            const newRemoveButton = document.getElementById(`remove-nilai-normal-${nilaiNormalIndex}`);
            if (newRemoveButton) {
                newRemoveButton.addEventListener('click', function() {
                    this.closest('.nilai-normal-row').remove();
                });
            }

            nilaiNormalIndex++;
        });

        document.getElementById('nilai-normal-container').addEventListener('click', function(event) {
            if (event.target && event.target.matches('.remove-nilai-normal-btn, .remove-nilai-normal-btn *')) {
                const button = event.target.closest('.remove-nilai-normal-btn');
                button.closest('.nilai-normal-row').remove();
            }
        });
    });
</script>
@endpush
@endsection
