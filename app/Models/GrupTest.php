<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GrupTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
    ];

    public function tests(): HasMany
    {
        return $this->hasMany(Test::class);
    }
}
