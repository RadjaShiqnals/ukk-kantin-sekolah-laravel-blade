<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stan extends Model
{
    public $table = 'stan';
    public $timestamps = true;
    protected $fillable = [
        'nama_stan',
        'nama_pemilik',
        'telp',
        'id_user',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function menus()
    {
        return $this->hasMany(Menu::class, 'id_stan');
    }

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'id_stan');
    }
}
