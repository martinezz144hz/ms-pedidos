<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require __DIR__ . '/controladores/PedidoControlador.php';
require __DIR__ . '/middleware/AuthMiddleware.php';

$pedidoControlador = new PedidoControlador();
$authMiddleware    = new AuthMiddleware();

$app->get('/pedidos', function (Request $request, Response $response) use ($pedidoControlador) {
    return $pedidoControlador->listar($request, $response);
})->add($authMiddleware);

$app->get('/pedidos/{id}', function (Request $request, Response $response, array $args) use ($pedidoControlador) {
    return $pedidoControlador->detalle($request, $response, $args);
})->add($authMiddleware);

$app->post('/pedidos', function (Request $request, Response $response) use ($pedidoControlador) {
    return $pedidoControlador->crear($request, $response);
})->add($authMiddleware);

$app->put('/pedidos/{id}/estado', function (Request $request, Response $response, array $args) use ($pedidoControlador) {
    return $pedidoControlador->cambiarEstado($request, $response, $args);
})->add($authMiddleware);

// endpoint verificar que este activo
$app->get('/', function (Request $request, Response $response) {
    $response->getBody()->write(json_encode([
        'servicio' => 'ms-pedidos',
        'estado'   => 'activo',
        'puerto'   => 3040
    ]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
});