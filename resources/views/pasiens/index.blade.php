@extends('layouts1.app')

@section('content')
<div class="page-inner">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h4>Data Pasien</h4>
            <a href="{{ route('pasiens.create') }}" class="btn btn-primary btn-round ms-auto">
                <i class="fas fa-plus"></i> Tambah Pasien
            </a>
        </div>
        <div class="card-body">
            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table id="basic-datatables" class="display table table-striped table-hover dt-responsive nowrap"
                    style="width: 100%">
                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>No RM</th>
                            <th>Nama Pasien</th>
                            <th>Umur</th>
                            <th>JK</th>
                            <th>Tgl Lahir</th>
                            <th>NIK</th>
                            <th>No Hp</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @foreach($pasiens as $pasien)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $pasien->norm }}</td>
                            <td>{{ $pasien->nama }}</td>
                            <td class="umur-field"
                                data-tgl-lahir="{{ \Carbon\Carbon::parse($pasien->tgl_lahir)->format('Y-m-d') }}">
                                Menghitung umur...</td>
                            <td>{{ $pasien->jenis_kelamin }}</td>
                            <td>{{ \Carbon\Carbon::parse($pasien->tgl_lahir)->format('d-m-Y') }}</td>
                            <td>{{ $pasien->nik }}</td>
                            <td>{{ $pasien->no_hp }}</td>
                            <td>
                                <a href="{{ route('pasiens.edit', $pasien->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fa fa-pencil-alt"></i>
                                </a>
                                <form action="{{ route('pasiens.destroy', $pasien->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Hapus data pasien ini?')">
                                        <i class="fa fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        function calculateAge(birthDate) {
            const today = new Date();
            const birth = new Date(birthDate);
            let age = today.getFullYear() - birth.getFullYear();
            const monthDiff = today.getMonth() - birth.getMonth();
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) {
                age--;
            }
            return age;
        }
        document.querySelectorAll('.umur-field').forEach(field => {
            const birthDate = field.getAttribute('data-tgl-lahir');
            if (birthDate) {
                const age = calculateAge(birthDate);
                field.textContent = age + ' th';
            } else {
                field.textContent = '-';
            }
        });
    });
</script>
@endsection
