<?php
session_start();
include(__DIR__."/../DAOs/daoPedido.php");
include(__DIR__."/../DAOs/daoProducto.php");
include(__DIR__."/../DAOs/daoUser.php");

define("HTML", __DIR__."/../../src/views");
//define("CSS", __DIR__."/../../public/styles");
$path = parse_url($_SERVER["REQUEST_URI"]);
$method = $_SERVER["REQUEST_METHOD"];

$daoPedido = new PedidoDAO();
$daoProducto = new ProductDAO();
$daoUser = new UserDAO();

function checkIfLoggedIn(){
    if (!(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true)){
        header('Location: /login');
        exit;
    }
}
function checkIfAdminPerms(){
    if (!isset($_SESSION['tipo_sesion']) || !($_SESSION['tipo_sesion'] === 'admin')){
        http_response_code(403);
        exit;
    }
    else{
        return true;
    }
}
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

if (file_exists(__DIR__.'/../../'.$path["path"]) && is_file(__DIR__.'/../../'.$path["path"])) {
    return false; 
}

if($method === "GET"){ 
    switch($path["path"]){ //Acá listadas en el switch todas las direcciones que usen GET
        case '/':
            include HTML.'/productos.html';
            exit;
            break;
        case '/productos':
            include HTML.'/productos.html';
            exit;
            break;
        case '/login':
            $args = [];
            parse_str($path["query"], $args);
            include HTML.'/login.html';
            if(isset($args) && $args["loginfailed"] === "true"){
                include HTML.'/loginfailed.html';
            }
            exit;
            break;
        case '/admin':
            checkIfLoggedIn();
            if(checkIfAdminPerms()){
                include HTML.'/admin.html';
            }
            break;
        case '/product':
            checkIfLoggedIn();
            include HTML.'/product.html';
            exit;
            break;
        case '/profile':
            checkIfLoggedIn();
            include HTML.'/profile.html';
            exit;
            break;
        case '/signup':
            include HTML.'/signup.html';
            exit;
            break;                
        case '/logout':
            session_unset();
            session_destroy();
            checkIfLoggedIn();
            break;
        //Requests BD    
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
            $user = $daoUser->getUserByEmail($args["email"]);
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
        default:
            http_response_code(404);
    }
}


if($method === "POST"){
    $requestBody = file_get_contents('php://input');
    switch($path["path"]){ //Acá listadas en el switch todas las direcciones que usen POST
        case '/createUser':
            $user = json_decode($requestBody);
            $response = $daoUser->createUser($user->nombre, $user->correo, $user->password, $user->tipo_de_usuario);
            validateJson($response);
            break;
        case '/createPedido':
            $pedido = json_decode($requestBody);
            $response = $daoPedido->createPedido($pedido->id_usuario, $pedido->fecha_pendiente, $pedido->fecha_entregado, $pedido->estado);
            validateJson($response);
            break;
        case '/createProducto':
            $producto = json_decode($requestBody);
            $response = $daoProducto->createProducto($producto->precio, $producto->descripcion, $producto->stock);
            validateJson($response);
            break;
        case '/userLogin':
            $userRequest = $_POST;
            $user = $daoUser->getUserByEmail($userRequest["email"]);  
            $passwordOK = $user ?? password_verify($userRequest["password"], $user["password"]);   

            validateJson('');
            if(!$passwordOK){
                header('Location: /login?loginfailed=true');
                exit;
            }
            else{
                $_SESSION['logged_in'] = true;
                $_SESSION['user'] = $user["nombre"];
                $_SESSION['correo'] = $user["correo"];
                $_SESSION['tipo_sesion'] = $user["tipo_de_usuario"];
                header('Location: /productos');
                exit;
            }
            break;
        case '/userSignup': 
            $request = $_POST;
            $user = $daoUser->createUser($request["nombre"], $request["correo"], $request["password"], 'client');
            validateJson('');
            $_SESSION['logged_in'] = true;
            $_SESSION['user'] = $request["nombre"];
            $_SESSION['correo'] = $request["correo"];
            $_SESSION['tipo_sesion'] = $request["tipo_de_usuario"];
            header('Location: /productos');
            exit;
            break;
        default:
            http_response_code(404);
    }
}

if($method === "PUT"){
    $requestBody = file_get_contents('php://input');
    switch($path["path"]){ //Acá listadas en el switch todas las direcciones que usen PUT
        case '/updateUser':
            $user = json_decode($requestBody);
            $response = $daoUser->updateUser($user->id, $user->nombre, $user->correo, $user->password);
            validateJson($response);
            break;
        case '/updatePedido':
            $pedido = json_decode($requestBody);
            $response = $daoPedido->updatePedido($pedido->id, $pedido->fecha_pendiente, $pedido->fecha_entregado, $pedido->estado);
            validateJson($response);
            break;
        case '/updateProducto':
            $producto = json_decode($requestBody);
            $response = $daoProducto->updateProducto($producto->id, $producto->precio, $producto->descripcion, $producto->stock);
            validateJson($response);
            break;
        default:
            http_response_code(404);
    }
}

if($method === "DELETE"){
    $requestBody = file_get_contents('php://input');
    switch($path["path"]){ //Acá listadas en el switch todas las direcciones que usen DELETE
        case '/deleteUser':
            $args = [];
            parse_str($path["query"], $args);
            $user = $daoUser->deleteUser($args["id"]);
            $response = json_encode($user);
            validateJson($response);
            break;
        case '/deletePedido':
            $args = [];
            parse_str($path["query"], $args);
            $pedido = $daoPedido->deletePedido($args["id"]);
            $response = json_encode($pedido);
            validateJson($response);
            break;
        case '/deleteProducto':
            $args = [];
            parse_str($path["query"], $args);
            $producto = $daoProducto->deleteProducto($args["id"]);
            $response = json_encode($producto);
            validateJson($response);
            break;
        default:
            http_response_code(404);
    }
}

?>