<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paket extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'nama',
        'deskripsi',
        'harga_umum',
        'harga_bpjs',
        'status',
    ];

    public function paketItems()
    {
        return $this->hasMany(PaketItem::class);
    }

    public function tests()
    {
        return $this->belongsToMany(Test::class, 'paket_items', 'paket_id', 'test_id')
            ->withPivot('jumlah');
    }
    public function visits()
    {
        return $this->hasMany(Visit::class);
    }
}
