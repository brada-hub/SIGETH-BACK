<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoEvento extends Model
{
    protected $table = 'tipos_evento';
    public $timestamps = false;
    protected $fillable = ['nombre'];
}
