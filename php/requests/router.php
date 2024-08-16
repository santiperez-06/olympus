<?php

include(__DIR__."/../DAOs/daoPedido.php");
include(__DIR__."/../DAOs/daoProducto.php");
include(__DIR__."/../DAOs/daoUser.php");

define("HTML", __DIR__."/../../src/views");
define("CSS", __DIR__."/../../public/styles");
$path = parse_url($_SERVER["REQUEST_URI"]);
$method = $_SERVER["REQUEST_METHOD"];

$daoPedido = new PedidoDAO($pdo);
$daoProducto = new ProductDAO($pdo);
$daoUser = new UserDAO($pdo);

function validateJson($response){
    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(500);
        $error = ["error" => "Failed to encode JSON"];
        echo json_encode($error);
    } else {
        http_response_code(200);
        echo $response;
    }
}

if($method === "GET"){ 
    switch($path["path"]){ //Acá listadas en el switch todas las direcciones que usen GET
        case '/':
            include HTML.'/index.html';
            break;
        case '/getUserById':
            $args = [];
            parse_str($path["query"], $args);
            $user = $daoUser->getUserById($args["id"]);
            header('Content-Type: application/json');
            $response = json_encode($user);
            validateJson($response);
            break;
        case '/getUserByEmail':
            $args = [];
            parse_str($path["query"], $args);
            $user = $daoUser->getUserByEmail($args["id"]);
            header('Content-Type: application/json');
            $response = json_encode($user);
            validateJson($response);
            break;
        case '/getProductoById':
            $args = [];
            parse_str($path["query"], $args);
            $producto = $daoProducto->getProductoById($args["id"]);
            header('Content-Type: application/json');
            $response = json_encode($pedido);
            validateJson($response);
            break;
        case '/getPedidoById':
            $args = [];
            parse_str($path["query"], $args);
            $pedido = $daoPedido->getPedidoById($args["id"]);
            header('Content-Type: application/json');
            $response = json_encode($pedido);
            validateJson($response);
            break;
        case '/getAllUsers':
            $users = $daoUser->getAllUsers();
            header('Content-Type: application/json');
            $response = json_encode($users);
            validateJson($response);
            break;
        case '/getAllPedidos':
            $pedidos = $daoPedido->getAllPedidos();
            header('Content-Type: application/json');
            $response = json_encode($pedidos);
            validateJson($response);
            break;
        case '/getAllProductos':
            $productos = $daoProducto->getAllProductos();
            header('Content-Type: application/json');
            $response = json_encode($productos);
            validateJson($response);
            break;
    }
}


if($method === "POST"){
    $requestBody = file_get_contents('php://input');
    switch($path["path"]){ //Acá listadas en el switch todas las direcciones que usen GET
        case '/crearUser':
            $user = json_decode($requestBody);
            $response = $daoUser->createUser($user->nombre, $user->correo, $user->password, $user->tipo_de_usuario);
            validateJson($response);
            break;
        case '/crearPedido':
            $pedido = json_decode($requestBody);
            $response = $daoPedido->createPedido($pedido->id_usuario, $pedido->fecha_pendiente, $pedido->fecha_entregado, $pedido->estado);
            validateJson($response);
            break;
        case '/crearProducto':
            $producto = json_decode($requestBody);
            $response = $daoProducto->createProducto($producto->precio, $producto->descripcion, $producto->stock);
            validateJson($response);
            break;
    }
}


if($method === "DELETE"){
    $requestBody = file_get_contents('php://input');
    switch($path["path"]){
        case '/borrarUser':
            $args = [];
            parse_str($path["query"], $args);
            $user = $daoUser->deleteUser($args["id"]);
            $response = json_encode($user);
            validateJson($response);
            break;
        case '/borrarPedido':
            $args = [];
            parse_str($path["query"], $args);
            $pedido = $daoPedido->deletePedido($args["id"]);
            $response = json_encode($pedido);
            validateJson($response);
            break;
        case '/borrarProducto':
            $args = [];
            parse_str($path["query"], $args);
            $producto = $daoProducto->deleteProducto($args["id"]);
            $response = json_encode($producto);
            validateJson($response);
            break;
    }
}

?>