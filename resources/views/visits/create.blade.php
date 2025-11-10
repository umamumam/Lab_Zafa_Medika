@extends('layouts1.form')

@section('content')
<div class="page-inner" style="margin-top: 2cm">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0"><i class="fas fa-file-medical me-2"></i>Form Permintaan Pemeriksaan</h5>
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

                    <form action="{{ route('visits.store') }}" method="POST" id="orderForm">
                        @csrf

                        {{-- Input untuk paket_id --}}
                        <input type="hidden" name="paket_id" id="hidden_paket_id" value="{{ old('paket_id') }}">

                        <div class="mb-4 border-bottom pb-3">
                            <h6 class="text-primary mb-3">IDENTITAS SAMPEL</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">No. Order</label>
                                        <input type="text" class="form-control bg-light" name="no_order_preview"
                                            value="{{ $no_order }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tgl_order">Tanggal & Waktu Order</label>
                                        <input type="text" name="tgl_order" id="tgl_order" class="form-control"
                                            value="{{ old('tgl_order', $today) }}" placeholder="dd/mm/yyyy hh:ii">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Data Pasien & Pengirim (tetap sama) --}}
                        <div class="row g-3">
                            <div class="col-md-6">
                                <h6 class="text-primary border-bottom pb-2 mb-3">DATA PASIEN</h6>

                                <div class="form-group mb-3">
                                    <label for="pasien_id" class="form-label">Nomor RM / Nama Pasien <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select select2 @error('pasien_id') is-invalid @enderror"
                                        id="pasien_id" name="pasien_id" required>
                                        <option value="">Pilih Pasien</option>
                                        @foreach($pasiens as $pasien)
                                        <option value="{{ $pasien->id }}" data-jenis="{{ $pasien->status_pasien }}"
                                            data-tgl_lahir="{{ $pasien->tgl_lahir }}"
                                            data-jk="{{ $pasien->jenis_kelamin }}" @selected(old('pasien_id')==$pasien->
                                            id)>
                                            {{ $pasien->norm }} - {{ $pasien->nama }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('pasien_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Tanggal Lahir</label>
                                            <input type="text" class="form-control bg-light" id="tgl_lahir" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Jenis Kelamin</label>
                                            <input type="text" class="form-control bg-light" id="jenis_kelamin"
                                                disabled>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="jenis_pasien" class="form-label">Status Pasien <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select @error('jenis_pasien') is-invalid @enderror"
                                        id="jenis_pasien" name="jenis_pasien" required>
                                        <option value="Umum" @selected(old('jenis_pasien')=='Umum' )>Umum</option>
                                        <option value="BPJS" @selected(old('jenis_pasien')=='BPJS' )>BPJS</option>
                                    </select>
                                    @error('jenis_pasien')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h6 class="text-primary border-bottom pb-2 mb-3">DATA PENGIRIM</h6>

                                <div class="form-group mb-3">
                                    <label for="dokter_id" class="form-label">Dokter Pengirim</label>
                                    <select class="form-select select2 @error('dokter_id') is-invalid @enderror"
                                        id="dokter_id" name="dokter_id">
                                        <option value="">Pilih Dokter</option>
                                        @foreach($dokters as $dokter)
                                        <option value="{{ $dokter->id }}" @selected(old('dokter_id')==$dokter->id)>
                                            {{ $dokter->nama }} - {{ $dokter->spesialis }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('dokter_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="ruangan_id" class="form-label">Faskes/Klinik</label>
                                    <select class="form-select select2 @error('ruangan_id') is-invalid @enderror"
                                        id="ruangan_id" name="ruangan_id">
                                        <option value="">Pilih Ruangan</option>
                                        @foreach($ruangans as $ruangan)
                                        <option value="{{ $ruangan->id }}" data-dokter="{{ $ruangan->dokter_id }}"
                                            @selected(old('ruangan_id')==$ruangan->id)>
                                            {{ $ruangan->nama }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('ruangan_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="row mb-3">
                                    <div class="form-group mb-3 col-md-6">
                                        <label for="diagnosa" class="form-label">Diagnosa</label>
                                        <input type="text" class="form-control @error('diagnosa') is-invalid @enderror"
                                            id="diagnosa" name="diagnosa" value="{{ old('diagnosa') }}" maxlength="500">
                                        @error('diagnosa')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3 col-md-6">
                                        <label for="jenis_order" class="form-label">Jenis Order <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select @error('jenis_order') is-invalid @enderror"
                                            id="jenis_order" name="jenis_order" required>
                                            <option value="Reguler" @selected(old('jenis_order')=='Reguler' )>
                                                Reguler</option>
                                            <option value="Cito" @selected(old('jenis_order')=='Cito' )>Cito
                                            </option>
                                        </select>
                                        @error('jenis_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2 mb-3">ITEM PEMERIKSAAN</h6>

                                {{-- Dropdown untuk Pilih Paket --}}
                                <div class="form-group mb-3">
                                    <label for="paket_id_select" class="form-label">Pilih Paket Pemeriksaan</label>
                                    <select class="form-select select2" id="paket_id_select">
                                        <option value="">Pilih Paket</option>
                                        @foreach($pakets as $paket)
                                        <option value="{{ $paket->id }}" data-harga-umum="{{ $paket->harga_umum }}"
                                            data-harga-bpjs="{{ $paket->harga_bpjs }}">
                                            {{ $paket->kode }} - {{ $paket->nama }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="table-responsive mb-3">
                                    <table class="table table-bordered table-sm">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="45%">Nama Pemeriksaan</th>
                                                <th width="10%">Jumlah</th>
                                                <th width="15%">Harga</th>
                                                <th width="20%">Subtotal</th>
                                                <th width="10%">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="testItems">
                                            {{-- Items will be added here by JavaScript --}}
                                        </tbody>
                                        <tfoot id="individualTestControls">
                                            <tr>
                                                <td>
                                                    <select class="form-select select2" id="test_id">
                                                        <option value="">Pilih Pemeriksaan</option>
                                                        @foreach($tests as $test)
                                                        <option value="{{ $test->id }}"
                                                            data-harga-umum="{{ $test->harga_umum }}"
                                                            data-harga-bpjs="{{ $test->harga_bpjs }}"
                                                            data-grup="{{ $test->grupTest->nama }}"
                                                            data-subgrup="{{ $test->sub_grup }}">
                                                            {{ $test->kode }} - {{ $test->nama }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control" id="jumlah" min="1"
                                                        max="10" value="1">
                                                </td>
                                                <td colspan="2">&nbsp;</td>
                                                <td>
                                                    <button type="button" class="btn btn-primary btn-sm w-100"
                                                        id="addTest">
                                                        <i class="fas fa-plus me-1"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h6 class="text-primary border-bottom pb-2 mb-3">VOUCHER DISKON</h6>

                                <div class="form-group mb-3">
                                    <select class="form-select select2 @error('voucher_id') is-invalid @enderror"
                                        id="voucher_id" name="voucher_id">
                                        <option value="">Pilih Voucher</option>
                                        @foreach($vouchers as $voucher)
                                        <option value="{{ $voucher->id }}" data-value="{{ $voucher->value }}"
                                            data-tipe="{{ $voucher->tipe }}" @selected(old('voucher_id')==$voucher->id)>
                                            {{ $voucher->kode }} - {{ $voucher->nama }} (Diskon:
                                            @if($voucher->tipe === 'persen')
                                            {{ $voucher->value }}%)
                                            @else
                                            Rp {{ number_format($voucher->value, 0, ',', '.') }})
                                            @endif
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('voucher_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h6 class="text-primary border-bottom pb-2 mb-3">METODE PEMBAYARAN</h6>

                                <div class="form-group mb-3">
                                    <select class="form-select select2 @error('metodebyr_id') is-invalid @enderror"
                                        id="metodebyr_id" name="metodebyr_id">
                                        <option value="">Pilih Metode</option>
                                        @foreach($metodePembayarans as $metode)
                                        <option value="{{ $metode->id }}" @selected(old('metodebyr_id')==$metode->
                                            id)>
                                            {{ $metode->nama }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('metodebyr_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="bg-light p-3 rounded mb-3">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="d-flex justify-content-between mb-2">
                                                <strong>Total Tagihan:</strong>
                                                <span id="totalTagihan">Rp 0</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <strong>Diskon Voucher:</strong>
                                                <span>
                                                    <span id="totalDiskon">Rp 0</span>
                                                </span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <strong>Total Bayar:</strong>
                                                <span id="totalBayar" class="fw-bold">Rp 0</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="alert alert-warning mb-3">
                                    <small>
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        Pastikan sisa pembayaran "0" sebelum klik Simpan. Khusus untuk BPJS dan MOU.
                                    </small>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('visits.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-1"></i> Kembali
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i> Simpan Order
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

<template id="testTemplate">
    <tr>
        <td>
            <input type="hidden" class="test-id-input" name="tests[][test_id]" value="">
            <input type="hidden" class="test-from-paket" name="tests[][from_paket]" value="0">
            <div class="fw-bold test-name"></div>
            <small class="text-muted test-grup"></small>
        </td>
        <td class="align-middle">
            <input type="number" class="form-control form-control-sm test-jumlah" name="tests[][jumlah]" min="1"
                max="10" value="1">
        </td>
        <td class="test-harga align-middle"></td>
        <td class="test-subtotal align-middle"></td>
        <td class="align-middle text-center">
            <button type="button" class="btn btn-danger btn-sm remove-test">
                <i class="fas fa-trash-alt"></i>
            </button>
        </td>
    </tr>
</template>

<template id="paketTestTemplate">
    <tr class="table-info">
        <td>
            <input type="hidden" class="test-id-input" name="tests[][test_id]" value="">
            <input type="hidden" class="test-from-paket" name="tests[][from_paket]" value="1">
            <div class="fw-bold test-name"></div>
            <small class="text-muted test-grup"></small>
            <small class="badge bg-primary">Paket</small>
        </td>
        <td class="align-middle">
            <input type="number" class="form-control form-control-sm test-jumlah" name="tests[][jumlah]" min="1"
                max="10" value="1" readonly style="background-color: #e9ecef;">
        </td>
        <td class="test-harga align-middle"></td>
        <td class="test-subtotal align-middle"></td>
        <td class="align-middle text-center">
            <button type="button" class="btn btn-outline-secondary btn-sm" disabled>
                <i class="fas fa-lock"></i>
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
        let selectedPaket = null;

        $('.select2').select2({
            width: '100%',
            theme: 'bootstrap-5'
        });

        $('#pasien_id').change(function() {
            const selectedOption = $(this).find('option:selected');
            const jenisPasien = selectedOption.data('jenis');
            const tglLahir = selectedOption.data('tgl_lahir');
            const jk = selectedOption.data('jk');

            $('#jenis_pasien').val(jenisPasien === 'BPJS' ? 'BPJS' : 'Umum').trigger('change');

            $('#tgl_lahir').val(tglLahir ? formatDate(tglLahir) : '-');
            $('#jenis_kelamin').val(jk || '-');

            updateAllTestPrices();
            updateTotal();
        });

        $('#ruangan_id').change(function() {
            const dokterId = $(this).find('option:selected').data('dokter');
            if (dokterId) {
                $('#dokter_id').val(dokterId).trigger('change');
            }
        });

        $('#addTest').click(function() {
            const testSelect = $('#test_id');
            const testId = testSelect.val();
            const testOption = testSelect.find('option:selected');

            if (!testId) {
                console.error('Pilih pemeriksaan terlebih dahulu.');
                return;
            }

            if (isTestAlreadyAdded(testId)) {
                console.error('Pemeriksaan ini sudah ditambahkan.');
                return;
            }

            const hargaUmum = parseFloat(testOption.data('harga-umum')) || 0;
            const hargaBpjs = parseFloat(testOption.data('harga-bpjs')) || 0;

            addIndividualTestItem(
                testId,
                testOption.text(),
                hargaUmum,
                hargaBpjs,
                testOption.data('grup'),
                testOption.data('subgrup'),
                $('#jumlah').val() || 1
            );

            testSelect.val('').trigger('change');
            $('#jumlah').val(1);
            updateTotal();
        });

        $('#paket_id_select').change(function() {
            const paketId = $(this).val();
            const paketOption = $(this).find('option:selected');

            if (paketId) {
                selectedPaket = {
                    id: paketId,
                    nama: paketOption.text(),
                    harga_umum: parseFloat(paketOption.data('harga-umum')) || 0,
                    harga_bpjs: parseFloat(paketOption.data('harga-bpjs')) || 0
                };

                $('#hidden_paket_id').val(paketId);
                showPaketInfo();

                $.ajax({
                    url: `/api/pakets/${paketId}/tests`,
                    method: 'GET',
                    success: function(response) {
                        $('#testItems tr.paket-item').remove();

                        if (response.length > 0) {
                            response.forEach(function(test) {
                                addPaketTestItem(
                                    test.id,
                                    `${test.kode} - ${test.nama}`,
                                    parseFloat(test.harga_umum) || 0, // Pastikan angka
                                    parseFloat(test.harga_bpjs) || 0, // Pastikan angka
                                    test.grup_test,
                                    test.sub_grup,
                                    test.jumlah_paket
                                );
                            });
                        }
                        updateAllTestPrices();
                        updateTotal();
                    },
                    error: function(xhr, status, error) {
                        console.error('Gagal mengambil data paket:', error);
                        removePaket();
                    }
                });
            } else {
                removePaket();
            }
        });

        $('#removePaket').click(function() {
            removePaket();
        });

        function showPaketInfo() {
            const jenisPasien = $('#jenis_pasien').val();
            const harga = jenisPasien === 'BPJS' ? selectedPaket.harga_bpjs : selectedPaket.harga_umum;

            $('#paketNama').text(selectedPaket.nama);
            $('#paketHarga').text(formatRupiah(harga));
            $('#paketInfo').show();
        }

        function removePaket() {
            selectedPaket = null;
            $('#hidden_paket_id').val('');
            $('#paket_id_select').val('').trigger('change');
            $('#paketInfo').hide();
            $('#testItems tr.paket-item').remove();
            updateAllTestPrices();
            updateTotal();
        }

        function addPaketTestItem(testId, testFullName, hargaUmum, hargaBpjs, grup, subgrup, jumlah) {

            const nextIndex = $('#testItems tr').length;
            const $newRow = $($('#paketTestTemplate').html());

            $newRow.addClass('paket-item');
            $newRow.attr('data-test-id', testId);
            $newRow.find('input.test-id-input').attr('name', `tests[${nextIndex}][test_id]`).val(testId);
            $newRow.find('input.test-from-paket').attr('name', `tests[${nextIndex}][from_paket]`).val('1');

            $newRow.find('input.test-jumlah').attr('name', `tests[${nextIndex}][jumlah]`).val(jumlah);

            $newRow.find('.test-name').text(testFullName.split(' - ')[1].trim());
            $newRow.find('.test-grup').text(`${grup} - ${subgrup}`);

            $newRow.data('harga-umum', hargaUmum);
            $newRow.data('harga-bpjs', hargaBpjs);

            if ($('#testItems tr:not(.paket-item)').length > 0) {
                $('#testItems tr:not(.paket-item)').first().before($newRow);
            } else {
                $('#testItems').append($newRow);
            }
        }

        function addIndividualTestItem(testId, testFullName, hargaUmum, hargaBpjs, grup, subgrup, jumlah) {
            const jenisPasien = $('#jenis_pasien').val();
            const harga = jenisPasien === 'BPJS' ? hargaBpjs : hargaUmum;
            const subtotal = harga * jumlah;

            const nextIndex = $('#testItems tr').length;
            const $newRow = $($('#testTemplate').html());

            $newRow.attr('data-test-id', testId);
            $newRow.find('input.test-id-input').attr('name', `tests[${nextIndex}][test_id]`).val(testId);
            $newRow.find('input.test-from-paket').attr('name', `tests[${nextIndex}][from_paket]`).val('0');

            const $jumlahInput = $newRow.find('input.test-jumlah');
            $jumlahInput.attr('name', `tests[${nextIndex}][jumlah]`).val(jumlah);

            $newRow.data('harga-umum', hargaUmum);
            $newRow.data('harga-bpjs', hargaBpjs);

            $jumlahInput.change(function() {
                const newJumlah = $(this).val() || 1;
                const newSubtotal = harga * newJumlah;
                $(this).closest('tr').find('.test-subtotal').text(formatRupiah(newSubtotal));
                updateTotal();
            });

            $newRow.find('.remove-test').click(function() {
                $(this).closest('tr').remove();
                reindexTestItems();
                updateTotal();
            });

            $newRow.find('.test-name').text(testFullName.split(' - ')[1].trim());
            $newRow.find('.test-grup').text(`${grup} - ${subgrup}`);
            $newRow.find('.test-harga').text(formatRupiah(harga));
            $newRow.find('.test-subtotal').text(formatRupiah(subtotal));

            $('#testItems').append($newRow);
        }

        function isTestAlreadyAdded(testId) {
            return $(`#testItems input.test-id-input[value="${testId}"]`).length > 0;
        }

        function reindexTestItems() {
            let index = 0;
            $('#testItems tr').each(function() {
                $(this).find('input.test-id-input').attr('name', `tests[${index}][test_id]`);
                $(this).find('input.test-from-paket').attr('name', `tests[${index}][from_paket]`);
                $(this).find('input.test-jumlah').attr('name', `tests[${index}][jumlah]`);
                index++;
            });
        }

        function updateAllTestPrices() {
            const jenisPasien = $('#jenis_pasien').val();

            if (selectedPaket) {

                $('#testItems tr.paket-item').each(function() {
                    const hargaUmum = $(this).data('harga-umum');
                    const hargaBpjs = $(this).data('harga-bpjs');
                    const hargaTampilan = jenisPasien === 'BPJS' ? hargaBpjs : hargaUmum;

                    const jumlah = $(this).find('input.test-jumlah').val() || 1;
                    const subtotal = 0;

                    $(this).find('.test-harga').text(formatRupiah(hargaTampilan));
                    $(this).find('.test-subtotal').text(formatRupiah(subtotal));
                });
            }

            $('#testItems tr:not(.paket-item)').each(function() {
                const hargaUmum = $(this).data('harga-umum');
                const hargaBpjs = $(this).data('harga-bpjs');

                const harga = jenisPasien === 'BPJS' ? hargaBpjs : hargaUmum;

                const jumlah = $(this).find('input.test-jumlah').val() || 1;
                const subtotal = harga * jumlah;

                $(this).find('.test-harga').text(formatRupiah(harga));
                $(this).find('.test-subtotal').text(formatRupiah(subtotal));
            });

            updateTotal();
        }

        $('#jenis_pasien').change(function() {
            updateAllTestPrices();
        });

        $('#voucher_id').change(function() {
            updateTotal();
        });

        function formatDate(dateString) {
            if (!dateString) return '-';
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID');
        }

        function formatRupiah(angka) {
            if (typeof angka !== 'number') {
                angka = parseFloat(angka) || 0;
            }
            return 'Rp ' + Math.round(angka).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function updateTotal() {
            let subtotalPaket = 0;
            let subtotalIndividual = 0;
            const jenisPasien = $('#jenis_pasien').val();
            const voucherOption = $('#voucher_id option:selected');
            const voucherValue = parseFloat(voucherOption.data('value')) || 0;
            const voucherTipe = voucherOption.data('tipe');

            if (selectedPaket) {
                const hargaPaket = jenisPasien === 'BPJS' ? selectedPaket.harga_bpjs : selectedPaket.harga_umum;
                subtotalPaket = hargaPaket;
                showPaketInfo();
            }

            $('#testItems tr:not(.paket-item)').each(function() {
                const subtotalText = $(this).find('.test-subtotal').text();
                const subtotal = parseInt(subtotalText.replace(/\D/g, '')) || 0;
                subtotalIndividual += subtotal;
            });

            const totalTagihan = subtotalPaket + subtotalIndividual;

            let diskonNominal = 0;
            if (voucherTipe === 'persen') {
                diskonNominal = totalTagihan * (voucherValue / 100);
                diskonNominal = Math.min(diskonNominal, totalTagihan);
            } else {
                diskonNominal = voucherValue;
                diskonNominal = Math.min(diskonNominal, totalTagihan);
            }
            diskonNominal = Math.round(diskonNominal);

            let totalSetelahDiskon = totalTagihan - diskonNominal;

            $('#subtotalPaket').text(formatRupiah(subtotalPaket));
            $('#subtotalIndividual').text(formatRupiah(subtotalIndividual));
            $('#totalTagihan').text(formatRupiah(totalTagihan));
            $('#totalDiskon').text(formatRupiah(diskonNominal));
            $('#totalBayar').text(formatRupiah(totalSetelahDiskon));
        }

        updateTotal();
        if ($('#pasien_id').val()) {
            $('#pasien_id').trigger('change');
        }

        updateAllTestPrices();
    });
</script>
@endpush
