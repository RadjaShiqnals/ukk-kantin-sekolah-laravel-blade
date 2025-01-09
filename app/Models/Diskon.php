<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Diskon extends Model
{
    public $table = 'diskon';
    public $timestamps = true;

    protected $fillable = [
        'nama_diskon',
        'persentase_diskon',
        'tanggal_awal',
        'tanggal_akhir',
    ];

    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'menu_diskon', 'id_diskon', 'id_menu');
    }
}
