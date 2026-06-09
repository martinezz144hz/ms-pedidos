<?php

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model {

    // Nombre de la tabla en la BD
    protected $table = 'pedidos';

    // Campos que se pueden llenar masivamente
    protected $fillable = [
        'mesa_id',
        'estado',
        'total',
    ];
}
