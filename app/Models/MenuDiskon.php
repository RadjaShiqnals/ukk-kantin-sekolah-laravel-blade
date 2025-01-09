<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuDiskon extends Model
{
    public $table = 'menu_diskon';
    public $timestamps = true;
    protected $fillable = [
        'menu_id',
        'diskon_id',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'id_menu');
    }

    public function diskon()
    {
        return $this->belongsTo(Diskon::class, 'id_diskon');
    }
}
