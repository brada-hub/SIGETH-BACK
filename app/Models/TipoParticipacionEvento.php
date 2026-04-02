<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoParticipacionEvento extends Model
{
    protected $table = 'tipos_participacion_evento';
    public $timestamps = false;
    protected $fillable = ['nombre'];
}
