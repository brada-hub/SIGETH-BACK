<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoReconocimiento extends Model
{
    protected $table = 'tipos_reconocimiento';
    public $timestamps = false;
    protected $fillable = ['nombre'];
}
