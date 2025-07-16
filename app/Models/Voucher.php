<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'nama',
        'value',
        'status',
        'keterangan'
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 'Aktif');
    }
    public function visits()
    {
        return $this->hasMany(Visit::class);
    }
    public function calculateDiscount($amount)
    {
        return $amount * $this->value / 100;
    }
}
