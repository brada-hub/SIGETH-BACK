<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NivelAcademico extends Model
{
    protected $table = 'niveles_academicos';
    public $timestamps = false;
    protected $fillable = ['nombre', 'orden'];
}
