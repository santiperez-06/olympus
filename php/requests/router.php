<?php

include(__DIR__."/../DAOs/daoPedido.php");
include(__DIR__."/../DAOs/daoProducto.php");
include(__DIR__."/../DAOs/daoUser.php");

define("HTML", __DIR__."/../../html");
$path = parse_url($_SERVER["REQUEST_URI"]);
$method = $_SERVER["REQUEST_METHOD"];

$daoPedido = new PedidoDAO($pdo);
$daoProducto = new ProductDAO($pdo);
$daoUser = new UserDAO($pdo);

if($method = "GET"){ 
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
            if (json_last_error() !== JSON_ERROR_NONE) {
                http_response_code(500);
                $error = ["error" => "Failed to encode JSON"];
                echo json_encode($error);
            } else {
                http_response_code(200);
                echo $response;
            }
            break;
        case '/getUserByEmail':
            $args = [];
            parse_str($path["query"], $args);
            $user = $daoUser->getUserByEmail($args["id"]);
            header('Content-Type: application/json');
            $response = json_encode($user);
            if (json_last_error() !== JSON_ERROR_NONE) {
                http_response_code(500);
                $error = ["error" => "Failed to encode JSON"];
                echo json_encode($error);
            } else {
                http_response_code(200);
                echo $response;
            }
            break;
        case '/getProductoById':
            $args = [];
            parse_str($path["query"], $args);
            $producto = $daoProducto->getProductoById($args["id"]);
            header('Content-Type: application/json');
            $response = json_encode($pedido);
            if (json_last_error() !== JSON_ERROR_NONE) {
                http_response_code(500);
                $error = ["error" => "Failed to encode JSON"];
                echo json_encode($error);
            } else {
                http_response_code(200);
                echo $response;
            }
            break;
        case '/getPedidoById':
            $args = [];
            parse_str($path["query"], $args);
            $pedido = $daoPedido->getPedidoById($args["id"]);
            header('Content-Type: application/json');
            $response = json_encode($pedido);
            if (json_last_error() !== JSON_ERROR_NONE) {
                http_response_code(500);
                $error = ["error" => "Failed to encode JSON"];
                echo json_encode($error);
            } else {
                http_response_code(200);
                echo $response;
            }
            break;
        case '/getAllUsers':
            $users = $daoUser->getAllUsers();
            header('Content-Type: application/json');
            $response = json_encode($users);
            if (json_last_error() !== JSON_ERROR_NONE) {
                http_response_code(500);
                $error = ["error" => "Failed to encode JSON"];
                echo json_encode($error);
            } else {
                http_response_code(200);
                echo $response;
            }
            break;
        case '/getAllPedidos':
            $pedidos = $daoPedido->getAllPedidos();
            header('Content-Type: application/json');
            $response = json_encode($pedidos);
            if (json_last_error() !== JSON_ERROR_NONE) {
                http_response_code(500);
                $error = ["error" => "Failed to encode JSON"];
                echo json_encode($error);
            } else {
                http_response_code(200);
                echo $response;
            }
            break;
        case '/getAllProductos':
            $productos = $daoProducto->getAllProductos();
            header('Content-Type: application/json');
            $response = json_encode($productos);
            if (json_last_error() !== JSON_ERROR_NONE) {
                http_response_code(500);
                $error = ["error" => "Failed to encode JSON"];
                echo json_encode($error);
            } else {
                http_response_code(200);
                echo $response;
            }
            break;
    }
}



if($method = "POST"){
    switch($path["path"]){
        
    }
}

?>