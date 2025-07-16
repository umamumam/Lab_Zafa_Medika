<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penerimaan extends Model
{
    use HasFactory;

    protected $fillable = [
        'visit_id',
        'jumlah',
        'status',
        'tgl_terima',
        'user_id',
        'metodebyr_id',
    ];
    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function metodeBayar()
    {
        return $this->belongsTo(Metodebyr::class, 'metodebyr_id');
    }
}
