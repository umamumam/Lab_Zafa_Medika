<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JasmaniMcu extends Model
{
    use HasFactory;

    protected $table = 'jasmani_mcus';

    protected $fillable = [
        'visit_test_id',
        'pasien_id',
        'dokter_pemeriksa_id',
        'tanggal_pemeriksaan',
        'keluhan_saat_ini',
        'berat_badan',
        'tinggi_badan',
        'bmi',
        'hidung',
        'mata_tanpa_kacamata_kiri',
        'mata_tanpa_kacamata_kanan',
        'mata_dengan_kacamata_kiri',
        'mata_dengan_kacamata_kanan',
        'buta_warna',
        'lapang_pandang',
        'liang_telinga_kiri',
        'liang_telinga_kanan',
        'gendang_telinga_kiri',
        'gendang_telinga_kanan',
        'ritme_pernapasan',
        'pergerakan_dada',
        'suara_pernapasan',
        'tekanan_darah',
        'frekuensi_jantung',
        'bunyi_jantung',
        'gigi',
        'peristaltik',
        'abdominal_mass',
        'bekas_operasi',
        'kesimpulan_medis',
        'temuan',
        'rekomendasi_dokter',
    ];

    protected $casts = [
        'tanggal_pemeriksaan' => 'date',
    ];

    public function visitTest()
    {
        return $this->belongsTo(VisitTest::class, 'visit_test_id');
    }

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'pasien_id');
    }

    public function dokterPemeriksa()
    {
        return $this->belongsTo(Dokter::class, 'dokter_pemeriksa_id');
    }

    public function visit()
    {
        return $this->hasOneThrough(Visit::class, VisitTest::class,
            'id', // Foreign key on VisitTest table
            'id', // Foreign key on Visit table
            'visit_test_id', // Local key on JasmaniMcu table
            'visit_id' // Local key on VisitTest table
        );
    }
}
