<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{
    public $table = 'detail_transaksi';
    public $timestamps = true;
    protected $fillable = [
        'id_transaksi',
        'id_menu',
        'qty',
        'harga_beli',
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'id_transaksi');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'id_menu');
    }
}
