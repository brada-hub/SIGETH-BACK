<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NivelIdioma extends Model
{
    protected $table = 'niveles_idioma';
    public $timestamps = false;
    protected $fillable = ['nombre', 'orden'];
}
