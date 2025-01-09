<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    public $table = 'menu';
    public $timestamps = true;
    protected $fillable = [
        'nama_makanan',
        'harga',
        'jenis',
        'foto',
        'deskripsi',
        'id_stan',
    ];

    public function stan()
    {
        return $this->belongsTo(Stan::class, 'id_stan');
    }

    public function diskons()
    {
        return $this->belongsToMany(Diskon::class, 'menu_diskon', 'id_menu', 'id_diskon');
    }

    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'id_menu');
    }
}
