<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Metodebyr extends Model
{
    use HasFactory;

    protected $table = 'metodebyrs';

    protected $fillable = [
        'nama',
    ];
    public function visits()
    {
        return $this->hasMany(Visit::class);
    }
    public function penerimaans()
    {
        return $this->hasMany(Penerimaan::class);
    }
}
