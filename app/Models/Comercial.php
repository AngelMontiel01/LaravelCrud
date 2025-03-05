<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comercial extends Model
{
    protected $table = 'Comerciales';
    protected $fillable = ['nombre', 'apellido1', 'apellido2', 'ciudad_id', 'comision', 'UsuCrea', 'UsuActualiza'];
}
