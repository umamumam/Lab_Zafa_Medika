<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'nama',
        'dokter_id',
        'kontak',
        'keterangan'
    ];

    public function dokter()
    {
        return $this->belongsTo(Dokter::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->kode = $model->generateKodeRuangan();
        });
    }

    public function generateKodeRuangan()
    {
        $prefix = 'RG';
        $randomNumber = str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);
        $kode = $prefix . $randomNumber;

        while (self::where('kode', $kode)->exists()) {
            $randomNumber = str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);
            $kode = $prefix . $randomNumber;
        }

        return $kode;
    }
    public function visits()
    {
        return $this->hasMany(Visit::class);
    }
}
