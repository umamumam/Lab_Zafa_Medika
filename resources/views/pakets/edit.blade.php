@extends('layouts1.form')

@section('content')
<div class="page-inner" style="margin-top: 2cm">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-box me-2"></i>Form Edit Paket Pemeriksaan</h5>
                    <a href="{{ route('pakets.index') }}" class="btn btn-warning">
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

                    <form action="{{ route('pakets.update', $paket->id) }}" method="POST" id="paketForm">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            {{-- INFORMASI PAKET --}}
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2 mb-3">INFORMASI PAKET</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="kode" class="form-label">Kode Paket <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('kode') is-invalid @enderror"
                                                id="kode" name="kode" value="{{ old('kode', $paket->kode) }}" required
                                                maxlength="255">
                                            @error('kode')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="nama" class="form-label">Nama Paket <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                                id="nama" name="nama" value="{{ old('nama', $paket->nama) }}" required
                                                maxlength="255">
                                            @error('nama')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="deskripsi" class="form-label">Deskripsi</label>
                                    <textarea class="form-control @error('deskripsi') is-invalid @enderror"
                                        id="deskripsi" name="deskripsi"
                                        rows="3">{{ old('deskripsi', $paket->deskripsi) }}</textarea>
                                    @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- HARGA & STATUS --}}
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2 mb-3">HARGA & STATUS</h6>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="harga_umum" class="form-label">Harga Umum <span
                                                    class="text-danger">*</span></label>
                                            <input type="number"
                                                class="form-control @error('harga_umum') is-invalid @enderror"
                                                id="harga_umum" name="harga_umum"
                                                value="{{ old('harga_umum', $paket->harga_umum) }}" min="0" required>
                                            @error('harga_umum')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="harga_bpjs" class="form-label">Harga BPJS <span
                                                    class="text-danger">*</span></label>
                                            <input type="number"
                                                class="form-control @error('harga_bpjs') is-invalid @enderror"
                                                id="harga_bpjs" name="harga_bpjs"
                                                value="{{ old('harga_bpjs', $paket->harga_bpjs) }}" min="0" required>
                                            @error('harga_bpjs')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="status" class="form-label">Status <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select @error('status') is-invalid @enderror"
                                                id="status" name="status" required>
                                                <option value="Aktif" @selected(old('status', $paket->status) ==
                                                    'Aktif')>Aktif</option>
                                                <option value="Tidak Aktif" @selected(old('status', $paket->status) ==
                                                    'Tidak Aktif')>Tidak Aktif</option>
                                            </select>
                                            @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2 mb-3">ITEM PEMERIKSAAN DALAM PAKET</h6>

                                <div class="table-responsive mb-3">
                                    <table class="table table-bordered table-sm">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="50%">Nama Pemeriksaan</th>
                                                <th width="20%">Jumlah</th>
                                                <th width="20%">Grup Test</th>
                                                <th width="10%">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="paketTestItems">
                                            @foreach($paket->paketItems as $index => $item)
                                            <tr>
                                                <td>
                                                    <input type="hidden" name="tests[{{ $index }}][id]"
                                                        value="{{ $item->id }}">
                                                    <input type="hidden" name="tests[{{ $index }}][test_id]"
                                                        value="{{ $item->test_id }}">
                                                    <div class="fw-bold test-name">{{ $item->test->nama }}</div>
                                                </td>
                                                <td class="align-middle">
                                                    <input type="number"
                                                        class="form-control form-control-sm test-jumlah"
                                                        name="tests[{{ $index }}][jumlah]" min="1"
                                                        value="{{ old('tests.' . $index . '.jumlah', $item->jumlah) }}">
                                                </td>
                                                <td class="test-grup align-middle">{{ $item->test->grup_test }} - {{
                                                    $item->test->sub_grup }}</td>
                                                <td class="align-middle text-center">
                                                    <button type="button"
                                                        class="btn btn-danger btn-sm remove-paket-test">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td>
                                                    <select class="form-select select2" id="test_id_paket">
                                                        <option value="">Pilih Pemeriksaan</option>
                                                        @foreach($tests as $test)
                                                        <option value="{{ $test->id }}"
                                                            data-grup="{{ $test->grup_test }}"
                                                            data-subgrup="{{ $test->sub_grup }}">
                                                            {{ $test->kode }} - {{ $test->nama }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control" id="jumlah_paket" min="1"
                                                        value="1">
                                                </td>
                                                <td>&nbsp;</td> {{-- Kolom Grup Test kosong di footer --}}
                                                <td>
                                                    <button type="button" class="btn btn-primary btn-sm w-100"
                                                        id="addPaketTest">
                                                        <i class="fas fa-plus me-1"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i> Perbarui Paket
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

{{-- Template for Test Item in Paket --}}
<template id="paketTestTemplate">
    <tr>
        <td>
            <input type="hidden" name="tests[][test_id]" value="">
            <div class="fw-bold test-name"></div>
        </td>
        <td class="align-middle">
            <input type="number" class="form-control form-control-sm test-jumlah" name="tests[][jumlah]" min="1"
                value="1">
        </td>
        <td class="test-grup align-middle"></td>
        <td class="align-middle text-center">
            <button type="button" class="btn btn-danger btn-sm remove-paket-test">
                <i class="fas fa-trash-alt"></i>
            </button>
        </td>
    </tr>
</template>
@endsection

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

        $('#addPaketTest').click(function() {
            const testSelect = $('#test_id_paket');
            const testId = testSelect.val();
            const testOption = testSelect.find('option:selected');

            if (!testId) {
                alert('Pilih pemeriksaan terlebih dahulu');
                return;
            }

            // Cek apakah tes sudah ada di tabel
            if ($(`#paketTestItems input[name^="tests["][name$="[test_id]"][value="${testId}"]`).length > 0) {
                alert('Pemeriksaan ini sudah ditambahkan ke paket.');
                return;
            }

            const jumlah = $('#jumlah_paket').val() || 1;
            const testName = testOption.text().split(' - ')[1].trim();
            const testGrup = `${testOption.data('grup')} - ${testOption.data('subgrup')}`;

            const nextIndex = $('#paketTestItems tr').length;
            const $newRow = $($('#paketTestTemplate').html());

            $newRow.find('input[name^="tests["][name$="[test_id]"]').attr('name', `tests[${nextIndex}][test_id]`).val(testId);
            $newRow.find('input[name^="tests["][name$="[jumlah]"]').attr('name', `tests[${nextIndex}][jumlah]`).val(jumlah);
            $newRow.find('.test-name').text(testName);
            $newRow.find('.test-grup').text(testGrup);

            $('#paketTestItems').append($newRow);

            // Reset select and quantity
            testSelect.val('').trigger('change');
            $('#jumlah_paket').val(1);

            // Event listener for removing test item
            $newRow.find('.remove-paket-test').click(function() {
                $(this).closest('tr').remove();
                reindexPaketTests();
            });
        });

        // Function to reindex input names after removal
        function reindexPaketTests() {
            $('#paketTestItems tr').each(function(index) {
                $(this).find('input[name^="tests["][name$="[id]"]').attr('name', `tests[${index}][id]`);
                $(this).find('input[name^="tests["][name$="[test_id]"]').attr('name', `tests[${index}][test_id]`);
                $(this).find('input[name^="tests["][name$="[jumlah]"]').attr('name', `tests[${index}][jumlah]`);
            });
        }

        // Initial reindex for existing items (useful for edit page)
        reindexPaketTests();
    });
</script>
@endpush
