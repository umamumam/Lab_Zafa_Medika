<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_order',
        'tgl_order',
        'user_id',
        'sampling_id',
        'pasien_id',
        'jenis_pasien',
        'dokter_id',
        'ruangan_id',
        'diagnosa',
        'jenis_order',
        'total_tagihan',
        'total_diskon',
        'voucher_id',
        'metodebyr_id',
        'dibayar',
        'status_pembayaran',
        'status_order',
        'paket_id',
    ];

    protected $casts = [
        'tgl_order' => 'datetime',
        'total_tagihan' => 'integer',
        'total_diskon' => 'integer',
        'dibayar' => 'integer'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function sampler()
    {
        return $this->belongsTo(User::class, 'sampling_id');
    }

    public function pasien()
    {
        return $this->belongsTo(Pasien::class);
    }

    public function dokter()
    {
        return $this->belongsTo(Dokter::class);
    }

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class);
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    public function metodePembayaran()
    {
        return $this->belongsTo(Metodebyr::class, 'metodebyr_id');
    }

    public function visitTests()
    {
        return $this->hasMany(VisitTest::class);
    }

    public function calculateTotal()
    {
        $subtotal = 0;
        if ($this->paket_id && $this->paket) {
            if ($this->jenis_pasien === 'Umum') {
                $subtotal = $this->paket->harga_umum;
            } else {
                $subtotal = $this->paket->harga_bpjs;
            }
        } else {
            $subtotal = $this->visitTests->sum('subtotal');
        }
        $diskon = 0;
        if ($this->voucher_id && $this->voucher) {
            $diskon = $this->voucher->calculateDiscount($subtotal);
        }
        $this->total_diskon = $diskon;
        $this->total_tagihan = $subtotal - $diskon;
        if ($this->dibayar >= $this->total_tagihan && $this->total_tagihan > 0) {
            $this->status_pembayaran = 'Lunas';
        } else {
            $this->status_pembayaran = 'Belum Lunas';
        }
        $this->save();
    }
    protected static function boot()
    {
        parent::boot();

        static::updated(function ($model) {
            if ($model->isDirty('voucher_id')) {
                $model->calculateTotal();
            }
        });
    }
    public function penerimaan()
    {
        return $this->hasOne(Penerimaan::class);
    }
    public function paket()
    {
        return $this->belongsTo(Paket::class);
    }
}
