<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NilaiNormal extends Model
{
    use HasFactory;
    protected $table = 'nilai_normals';
    protected $fillable = [
        'test_id',
        'detail_test_id',
        'jenis_kelamin',
        'usia_min',
        'usia_max',
        // 'satuan',
        'type',
        'min',
        'max',
        // 'nilai_normal',
        // 'interpretasi',
    ];

        protected $casts = [
        'min' => 'decimal:2',
        'max' => 'decimal:2',
    ];

    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class, 'test_id');
    }
    public function detailTest(): BelongsTo
    {
        return $this->belongsTo(DetailTest::class, 'detail_test_id');
    }
}
