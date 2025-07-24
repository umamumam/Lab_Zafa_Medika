<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
    public function metodeBayar(): BelongsTo
    {
        return $this->belongsTo(Metodebyr::class, 'metodebyr_id');
    }
}
