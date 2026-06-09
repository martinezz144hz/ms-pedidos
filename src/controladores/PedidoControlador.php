<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require __DIR__ . '/../modelos/Pedido.php';
require __DIR__ . '/../modelos/PedidoDetalle.php';

class PedidoControlador {

    // ============================================
    // LISTAR PEDIDOS
    // ============================================
    public function listar(Request $request, Response $response): Response {
        $pedidos = Pedido::all();

        return $this->respuesta($response, $pedidos->toArray(), 200);
    }

    // ============================================
    // DETALLE DE UN PEDIDO
    // ============================================
    public function detalle(Request $request, Response $response, array $args): Response {
        $id     = $args['id'];
        $pedido = Pedido::find($id);

        if (!$pedido) {
            return $this->respuesta($response, [
                'message' => 'Pedido no encontrado.'
            ], 404);
        }

        $detalles = PedidoDetalle::where('pedido_id', $id)->get();

        return $this->respuesta($response, [
            'pedido'   => $pedido->toArray(),
            'detalles' => $detalles->toArray()
        ], 200);
    }

    // ============================================
    // CREAR PEDIDO
    // ============================================
    public function crear(Request $request, Response $response): Response {
        $datos    = $request->getParsedBody();
        $mesa_id  = $datos['mesa_id']  ?? '';
        $products = $datos['productos'] ?? [];

        if (empty($mesa_id) || empty($products)) {
            return $this->respuesta($response, [
                'message' => 'Mesa y productos son requeridos.'
            ], 400);
        }

        // Calcular total
        $total = 0;
        foreach ($products as $p) {
            $total += $p['precio_unitario'] * $p['cantidad'];
        }

        // Crear el pedido
        $pedido = Pedido::create([
            'mesa_id' => $mesa_id,
            'estado'  => 'pendiente',
            'total'   => $total,
        ]);

        // Crear los detalles
        foreach ($products as $p) {
            PedidoDetalle::create([
                'pedido_id'       => $pedido->id,
                'producto_id'     => $p['producto_id'],
                'producto_nombre' => $p['producto_nombre'],
                'cantidad'        => $p['cantidad'],
                'precio_unitario' => $p['precio_unitario'],
                'subtotal'        => $p['precio_unitario'] * $p['cantidad'],
            ]);
        }

        // Obtener detalles recién creados
        $detalles = PedidoDetalle::where('pedido_id', $pedido->id)->get();

        return $this->respuesta($response, [
            'message'  => 'Pedido creado correctamente.',
            'pedido'   => $pedido->toArray(),
            'detalles' => $detalles->toArray()
        ], 201);
    }

    // ============================================
    // CAMBIAR ESTADO DEL PEDIDO
    // ============================================
    public function cambiarEstado(Request $request, Response $response, array $args): Response {
        $id     = $args['id'];
        $datos  = $request->getParsedBody();
        $estado = $datos['estado'] ?? '';

        if (empty($estado)) {
            return $this->respuesta($response, [
                'message' => 'El estado es requerido.'
            ], 400);
        }

        $pedido = Pedido::find($id);

        if (!$pedido) {
            return $this->respuesta($response, [
                'message' => 'Pedido no encontrado.'
            ], 404);
        }

        $pedido->estado = $estado;
        $pedido->save();

        return $this->respuesta($response, [
            'message' => 'Estado actualizado correctamente.',
            'pedido'  => $pedido->toArray()
        ], 200);
    }

    // ============================================
    // HELPER — respuesta JSON
    // ============================================
    private function respuesta(Response $response, array $datos, int $codigo): Response {
        $response->getBody()->write(json_encode($datos));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($codigo);
    }
}