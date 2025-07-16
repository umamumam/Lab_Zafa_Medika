@extends('layouts1.form')

@section('content')
<div class="page-inner" style="margin-top: 2.5cm;">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h4>Edit Detail Test</h4>
            <a href="{{ route('detail_tests.index') }}" class="btn btn-secondary btn-round">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
        <div class="card-body" style="margin-bottom: 2cm">
            <form action="{{ route('detail_tests.update', $detailTest->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="test_id" class="form-label">Test Induk</label>
                        <select class="form-select select2 @error('test_id') is-invalid @enderror" id="test_id" name="test_id"
                            required>
                            <option value="">Pilih Test Induk</option>
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
                    <div class="col-md-6">
                        <label for="nilai_normal" class="form-label">Nilai Rujukan</label>
                        <input type="text" class="form-control @error('nilai_normal') is-invalid @enderror"
                            id="nilai_normal" name="nilai_normal"
                            value="{{ old('nilai_normal', $detailTest->nilai_normal) }}" required>
                        @error('nilai_normal')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="satuan" class="form-label">Satuan</label>
                        <input type="text" class="form-control @error('satuan') is-invalid @enderror" id="satuan"
                            name="satuan" value="{{ old('satuan', $detailTest->satuan) }}" required>
                        @error('satuan')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="type" class="form-label">Tipe Nilai</label>
                        <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                            <option value="Single" {{ old('type', $detailTest->type) == 'Single' ? 'selected' : ''
                                }}>Single</option>
                            <option value="Range" {{ old('type', $detailTest->type) == 'Range' ? 'selected' : ''
                                }}>Range</option>
                        </select>
                        @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3" id="range-fields" style="display: none; align-items: flex-end;">
                    <div class="col-md-6">
                        <label for="min" class="form-label">Nilai Minimum</label>
                        <input type="number" class="form-control @error('min') is-invalid @enderror" id="min" name="min"
                            value="{{ old('min', $detailTest->min) }}">
                        @error('min')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="max" class="form-label">Nilai Maksimum</label>
                        <input type="number" class="form-control @error('max') is-invalid @enderror" id="max" name="max"
                            value="{{ old('max', $detailTest->max) }}">
                        @error('max')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('detail_tests.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('type');
        const rangeFields = document.getElementById('range-fields');

        // Initial check - show range fields if type is Range
        if (typeSelect.value === 'Range') {
            rangeFields.style.display = 'flex';
            document.getElementById('min').required = true;
            document.getElementById('max').required = true;
        }

        // Change event handler
        typeSelect.addEventListener('change', function() {
            const isRange = this.value === 'Range';
            rangeFields.style.display = isRange ? 'flex' : 'none';
            document.getElementById('min').required = isRange;
            document.getElementById('max').required = isRange;
        });
    });
</script>
@endsection
