<?php

use Illuminate\Database\Eloquent\Model;

class PedidoDetalle extends Model {

    // Nombre de la tabla en la base
    protected $table = 'pedido_detalles';

    // Campos que se pueden llenar masivamente
    protected $fillable = [
        'pedido_id',
        'producto_id',
        'producto_nombre',
        'cantidad',
        'precio_unitario',
        'subtotal',
    ];
}
