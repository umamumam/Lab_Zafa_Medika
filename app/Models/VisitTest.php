<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'visit_id',
        'test_id',
        'detail_test_id',
        'harga',
        'jumlah',
        'subtotal'
    ];

    protected $casts = [
        'harga' => 'integer',
        'jumlah' => 'integer',
        'subtotal' => 'integer'
    ];

    // Relasi ke Visit
    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    // Relasi ke Test
    public function test()
    {
        return $this->belongsTo(Test::class);
    }

    // Relasi ke DetailTest
    public function detailTest()
    {
        return $this->belongsTo(DetailTest::class);
    }

    // Hitung subtotal otomatis sebelum menyimpan
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->subtotal = $model->harga * $model->jumlah;
        });

        static::saved(function ($model) {
            $model->visit->calculateTotal();
        });

        static::deleted(function ($model) {
            $model->visit->calculateTotal();
        });
    }
    public function hasilLabs()
    {
        return $this->hasMany(HasilLab::class, 'visit_test_id');
    }
}
