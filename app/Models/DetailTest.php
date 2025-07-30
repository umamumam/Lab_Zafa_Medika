<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DetailTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_id',
        'urutan',
        'nama',
        'nilai_normal',
        // 'type',
        // 'min',
        // 'max',
        'satuan',
        'status'
    ];

    protected $casts = [
        // 'min' => 'decimal:2',
        // 'max' => 'decimal:2',
        'urutan' => 'integer'
    ];

    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class);
    }

    public function visitTests(): HasMany
    {
        return $this->hasMany(VisitTest::class);
    }

    public function hasilLabs(): HasMany
    {
        return $this->hasMany(HasilLab::class, 'detail_test_id');
    }

    public function normalValues(): HasMany
    {
        return $this->hasMany(NilaiNormal::class, 'detail_test_id');
    }
}
