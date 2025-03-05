<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $table = 'Pedidos';
    protected $fillable = ['cantidad', 'cliente_id', 'comercial_id', 'estatus_id', 'UsuCrea', 'UsuActualiza'];
}
