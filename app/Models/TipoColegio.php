<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoColegio extends Model
{
    protected $table = 'tipos_colegio';
    public $timestamps = false;
    protected $fillable = ['nombre'];
}
