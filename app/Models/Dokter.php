<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokter extends Model
{
    use HasFactory;

    protected $table = 'dokters';
    protected $primaryKey = 'id';
    protected $fillable = [
        'kode',
        'nama',
        'spesialis',
        'no_hp',
        'alamat',
        'status',
        'jenis_kelamin'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->kode = $model->generateKodeDokter();
        });
    }
    public function generateKodeDokter()
    {
        $prefix = 'DR';
        $randomNumber = str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);
        $kode = $prefix . $randomNumber;
        while (self::where('kode', $kode)->exists()) {
            $randomNumber = str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);
            $kode = $prefix . $randomNumber;
        }

        return $kode;
    }
    public function ruangans()
    {
        return $this->hasMany(Ruangan::class);
    }
    public function visits()
    {
        return $this->hasMany(Visit::class);
    }
}
