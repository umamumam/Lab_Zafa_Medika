<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilLab extends Model
{
    use HasFactory;

    protected $table = 'hasil_labs';
    protected $fillable = [
        'visit_test_id',
        'test_id',
        'detail_test_id',
        'flag',
        'hasil',
        'status',
        'validator_id',
        'validated_at',
        'kesan',
        'catatan'
    ];

    protected $casts = [
        'validated_at' => 'datetime',
    ];
    public function visitTest()
    {
        return $this->belongsTo(VisitTest::class, 'visit_test_id');
    }
    public function test()
    {
        return $this->belongsTo(Test::class, 'test_id');
    }
    public function detailTest()
    {
        return $this->belongsTo(DetailTest::class, 'detail_test_id');
    }
    public function validator()
    {
        return $this->belongsTo(User::class, 'validator_id');
    }
}
