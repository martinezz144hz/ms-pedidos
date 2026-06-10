<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require __DIR__ . '/controladores/PedidoControlador.php';
require __DIR__ . '/middleware/AuthMiddleware.php';

$pedidoControlador = new PedidoControlador();
$authMiddleware    = new AuthMiddleware();

//  listar todos los pedidos
$app->get('/pedidos', function (Request $request, Response $response) use ($pedidoControlador) {
    return $pedidoControlador->listar($request, $response);
})->add($authMiddleware);

//  ver detalle de un pedido
$app->get('/pedidos/{id}', function (Request $request, Response $response, array $args) use ($pedidoControlador) {
    return $pedidoControlador->detalle($request, $response, $args);
})->add($authMiddleware);

// crear pedido
$app->post('/pedidos', function (Request $request, Response $response) use ($pedidoControlador) {
    return $pedidoControlador->crear($request, $response);
})->add($authMiddleware);

//  cambiar estado del pedido
$app->put('/pedidos/{id}/estado', function (Request $request, Response $response, array $args) use ($pedidoControlador) {
    return $pedidoControlador->cambiarEstado($request, $response, $args);
})->add($authMiddleware);

// endpoint verificar que este activo
$app->get('/', function (Request $request, Response $response) {
    $response->getBody()->write(json_encode([
        'servicio' => 'ms-reservas',
        'estado'   => 'activo',
        'puerto'   => 3020
    ]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
});