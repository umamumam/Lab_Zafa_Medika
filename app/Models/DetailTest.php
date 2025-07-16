<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_id',
        'urutan',
        'nama',
        'nilai_normal',
        'type',
        'min',
        'max',
        'satuan',
        'status'
    ];

    protected $casts = [
        'min' => 'decimal:2',
        'max' => 'decimal:2',
        'urutan' => 'integer'
    ];

    // Relasi ke Test
    public function test()
    {
        return $this->belongsTo(Test::class);
    }
    public function visitTests()
    {
        return $this->hasMany(VisitTest::class);
    }
    public function hasilLabs()
    {
        return $this->hasMany(HasilLab::class, 'detail_test_id');
    }
}
