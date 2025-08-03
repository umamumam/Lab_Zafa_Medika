<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Pasien extends Model
{
    use HasFactory;

    protected $fillable = [
        'norm',
        'nama',
        'tgl_lahir',
        'jenis_kelamin',
        'status_pasien',
        'nik',
        'no_bpjs',
        'email',
        'no_hp',
        'alamat'
    ];

    protected $casts = [
        'tgl_lahir' => 'date'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Cari norm terakhir
            $lastNorm = DB::table('pasiens')
                ->select('norm')
                ->orderBy('id', 'desc')
                ->first();
            $nextNumber = $lastNorm ? ((int)$lastNorm->norm) + 1 : 1;
            $model->norm = str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
        });
    }
    public function visits()
    {
        return $this->hasMany(Visit::class);
    }
    public function jasmaniMcus()
    {
        return $this->hasMany(JasmaniMcu::class, 'pasien_id');
    }
}
