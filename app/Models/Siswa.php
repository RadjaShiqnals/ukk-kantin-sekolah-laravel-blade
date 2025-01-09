<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    public $table = 'siswa';
    public $timestamps = true;
    protected $fillable = [
        'nama_siswa',
        'alamat',
        'telp',
        'id_user',
        'foto',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'id_siswa');
    }
}
