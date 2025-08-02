<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaketItem extends Model
{
    use HasFactory;
    protected $table = 'paket_items';
    protected $fillable = [
        'paket_id',
        'test_id',
        'jumlah',
    ];

    public function paket()
    {
        return $this->belongsTo(Paket::class);
    }

    public function test()
    {
        return $this->belongsTo(Test::class);
    }
}
