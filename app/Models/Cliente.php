<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'Clientes';
    protected $fillable = ['nombre', 'apellido1', 'apellido2', 'ciudad_id', 'categoria_id', 'UsuCrea', 'UsuActualiza'];
}
