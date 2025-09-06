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
        'tipe',
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
        if ($this->tipe === 'persen') {
            $discount = $amount * ($this->value / 100);
        } elseif ($this->tipe === 'nominal') {
            $discount = $this->value;
        } else {
            $discount = 0;
        }
        return min($discount, $amount);
    }
}
