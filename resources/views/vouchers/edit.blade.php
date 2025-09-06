@extends('layouts1.app')

@section('content')
<div class="page-inner">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h4>Edit Voucher</h4>
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
            <form action="{{ route('vouchers.update', $voucher->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="kode">Kode Voucher</label>
                            <input type="text" class="form-control @error('kode') is-invalid @enderror" id="kode" name="kode"
                                value="{{ old('kode', $voucher->kode) }}" maxlength="5" required>
                            @error('kode')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama">Nama Voucher</label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama"
                                value="{{ old('nama', $voucher->nama) }}" required>
                            @error('nama')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tipe">Tipe Diskon</label>
                            <select class="form-control @error('tipe') is-invalid @enderror" id="tipe" name="tipe" required>
                                <option value="persen" {{ old('tipe', $voucher->tipe) == 'persen' ? 'selected' : '' }}>Persentase (%)</option>
                                <option value="nominal" {{ old('tipe', $voucher->tipe) == 'nominal' ? 'selected' : '' }}>Nominal (Rp)</option>
                            </select>
                            @error('tipe')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label id="value-label" for="value">Nilai Voucher</label>
                            <input type="number" step="1" class="form-control @error('value') is-invalid @enderror"
                                id="value" name="value" value="{{ old('value', $voucher->value) }}" min="0" required>
                            @error('value')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                        <option value="Aktif" {{ old('status', $voucher->status) == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Tidak Aktif" {{ old('status', $voucher->status) == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                    @error('status')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="keterangan">Keterangan</label>
                    <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan"
                                name="keterangan" rows="3">{{ old('keterangan', $voucher->keterangan) }}</textarea>
                    @error('keterangan')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('vouchers.index') }}" class="btn btn-danger">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tipeSelect = document.getElementById('tipe');
        const valueInput = document.getElementById('value');
        const valueLabel = document.getElementById('value-label');

        function updateValueField() {
            const selectedTipe = tipeSelect.value;
            if (selectedTipe === 'persen') {
                valueLabel.innerText = 'Nilai Voucher (%)';
                valueInput.min = 0;
                valueInput.max = 100;
                valueInput.step = 1;
            } else if (selectedTipe === 'nominal') {
                valueLabel.innerText = 'Nilai Voucher (Rp)';
                valueInput.min = 1;
                valueInput.removeAttribute('max');
                valueInput.step = 1;
            }
        }

        tipeSelect.addEventListener('change', updateValueField);

        // Panggil saat halaman dimuat untuk mengatur status awal
        updateValueField();
    });
</script>
@endsection
