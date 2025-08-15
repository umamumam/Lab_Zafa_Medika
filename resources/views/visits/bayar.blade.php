@extends('layouts1.app')

@section('content')
<div class="page-inner">
    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h4>Daftar Pembayaran & Klaim</h4>
        </div>
        <div class="card-body">
            {{-- Bagian notifikasi Bootstrap dihapus, diganti dengan SweetAlert2 di bagian script --}}
            <div class="table-responsive">
                <table class="display table table-striped table-hover" id="basic-datatables">
                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>No. Order</th>
                            <th>Nama Pasien</th>
                            <th>Tgl Order</th>
                            <th>Total</th>
                            <th>Dibayar</th>
                            <th>Metode</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @foreach($visits as $visit)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $visit->no_order }}</td>
                            <td>{{ $visit->pasien->nama }}</td>
                            <td>{{ $visit->tgl_order->format('d/m/Y H:i') }}</td>
                            <td>Rp {{ number_format($visit->total_tagihan) }}</td>
                            <td>Rp {{ number_format($visit->dibayar) }}</td>
                            <td>{{ $visit->metodePembayaran->nama ?? '-' }}</td>
                            <td>
                                <span class="badge badge-{{ $visit->status_pembayaran == 'Lunas' ? 'success' : 'warning' }}">
                                    {{ $visit->status_pembayaran }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex flex-wrap align-items-center" style="gap: 5px;">

                                    {{-- Tombol Bayar --}}
                                    @if($visit->status_pembayaran == 'Belum Lunas')
                                    <a href="{{ route('visits.show', $visit->id) }}#form-bayar" class="btn btn-sm btn-warning" title="Bayar">
                                        <i class="fas fa-money-bill"></i>
                                    </a>
                                    @endif

                                    {{-- Tombol Edit dan Hapus (hanya untuk Admin) --}}
                                    @if($visit->status_order == 'Sampling' && auth()->user()->role == 'admin')
                                        <a class="btn btn-sm btn-info" href="{{ route('visits.edit', $visit->id) }}" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('visits.destroy', $visit->id) }}" method="POST"
                                            class="d-inline sweet-alert-form">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="action_type" value="hapus">
                                            <button type="submit" class="btn btn-sm btn-danger sweet-alert-button" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Tombol Klaim --}}
                                    @if ($visit->status_pembayaran == 'Lunas' && !$visit->penerimaan)
                                    <form action="{{ route('penerimaan.store') }}" method="POST" class="d-inline sweet-alert-form">
                                        @csrf
                                        <input type="hidden" name="visit_id" value="{{ $visit->id }}">
                                        <input type="hidden" name="action_type" value="klaim">
                                        <button type="submit" class="btn btn-primary btn-sm sweet-alert-button" title="Klaim">
                                            Klaim
                                        </button>
                                    </form>
                                    @elseif($visit->penerimaan)
                                    <span class="badge bg-success">Sudah Diklaim</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 3000
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('error') }}',
                showConfirmButton: false,
                timer: 3000
            });
        @endif

        const sweetAlertButtons = document.querySelectorAll('.sweet-alert-button');

        sweetAlertButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();

                const form = this.closest('.sweet-alert-form');
                const actionType = form.querySelector('input[name="action_type"]').value;
                let title = '';
                let text = '';
                let confirmButtonText = '';
                let confirmButtonColor = '';

                if (actionType === 'hapus') {
                    title = 'Apakah Anda yakin?';
                    text = 'Data ini akan dihapus secara permanen!';
                    confirmButtonText = 'Ya, hapus!';
                    confirmButtonColor = '#d33';
                } else if (actionType === 'klaim') {
                    title = 'Konfirmasi Klaim';
                    text = 'Klaim pembayaran untuk Visit ini?';
                    confirmButtonText = 'Ya, klaim!';
                    confirmButtonColor = '#3085d6';
                }

                Swal.fire({
                    title: title,
                    text: text,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: confirmButtonColor,
                    cancelButtonColor: '#aaa',
                    confirmButtonText: confirmButtonText,
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
