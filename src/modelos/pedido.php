<?php

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model {

    // Nombre de la tabla en la base
    protected $table = 'pedidos';

    // Campos que se pueden llenar masivamente
    protected $fillable = [
        'mesa_id',
        'estado',
        'total',
    ];
}
