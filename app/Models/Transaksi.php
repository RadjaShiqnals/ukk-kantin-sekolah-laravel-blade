<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    public $table = 'transaksi';
    public $timestamps = true;
    protected $fillable = [
        'tanggal',
        'id_stan',
        'id_siswa',
        'status',
    ];

    public function stan()
    {
        return $this->belongsTo(Stan::class, 'id_stan');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa');
    }

    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'id_transaksi');
    }
}
