<?php
session_start();
include(__DIR__."/../DAOs/daoPedido.php");
include(__DIR__."/../DAOs/daoProducto.php");
include(__DIR__."/../DAOs/daoUser.php");

define("HTML", __DIR__."/../../src/views");
//define("CSS", __DIR__."/../../public/styles");
$path = parse_url($_SERVER["REQUEST_URI"]);
$method = $_SERVER["REQUEST_METHOD"];
$pathParts = explode('/', $path["path"]);
if(isset($path["query"])){
    parse_str($path["query"], $args);
}

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
function validateQuery(){
    if(!isset($args)){
        http_response_code(400);
        exit;
    }
    else{
        return true;
    }
}
function validateJson($response){
    if (json_last_error() !== JSON_ERROR_NONE) {
        internalServerError("Failed to encode JSON");
    } else {
        http_response_code(200);
        echo $response;
    }
}
function internalServerError($msg){
    header('Content-Type: application/json');
    http_response_code(500);
    $error = ["error" => $msg];
    echo json_encode($error);
}

if (file_exists(__DIR__.'/../../'.$path["path"]) && is_file(__DIR__.'/../../'.$path["path"])) {
    return false; 
}

if($method === "GET"){ 
    switch('/'.$pathParts[1]){ //Acá listadas en el switch todas las direcciones que usen GET
        case '/':
            include HTML.'/productos.html';
            exit;
            break;
        case '/pagar':
            internalServerError("Sistema de pagos inactivo. Intente nuevamente más tarde");
            break;
        case '/productos':
            include HTML.'/productos.html';
            exit;
            break;
        case '/login':
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
        case '/bd':
            checkIfLoggedIn();
            checkIfAdminPerms();
            switch('/'.$pathParts[2]){
                case '/getUserById':
                    try {
                        validateQuery();
                        $user = $daoUser->getUserById($args["id"]);
                        header('Content-Type: application/json');
                        $response = json_encode($user);
                        validateJson($response);
                    } catch (Exception $e) {
                        internalServerError($e->getMessage());
                    }
                    break;
                case '/getUserByEmail':
                    try {
                        validateQuery();
                        $user = $daoUser->getUserByEmail($args["email"]);
                        header('Content-Type: application/json');
                        $response = json_encode($user);
                        validateJson($response);
                    } catch (Exception $e) {
                        internalServerError($e->getMessage());
                    }
                    break;
                case '/getProductoById':
                    try {
                        validateQuery();
                        $producto = $daoProducto->getProductoById($args["id"]);
                        header('Content-Type: application/json');
                        $response = json_encode($producto);
                        validateJson($response);
                    } catch (Exception $e) {
                        internalServerError($e->getMessage());
                    }
                    break;
                case '/getPedidoById':
                    try {
                        validateQuery();
                        $pedido = $daoPedido->getPedidoById($args["id"]);
                        header('Content-Type: application/json');
                        $response = json_encode($pedido);
                        validateJson($response);
                    } catch (Exception $e) {
                        internalServerError($e->getMessage());
                    }
                    break;
                case '/getAllUsers':
                    try {
                        $users = $daoUser->getAllUsers();
                        header('Content-Type: application/json');
                        $response = json_encode($users);
                        validateJson($response);
                    } catch (Exception $e) {
                        internalServerError($e->getMessage());
                    }
                    break;
                case '/getAllPedidos':
                    try {
                        $pedidos = $daoPedido->getAllPedidos();
                        header('Content-Type: application/json');
                        $response = json_encode($pedidos);
                        validateJson($response);
                    } catch (Exception $e) {
                        internalServerError($e->getMessage());
                    }
                    break;
                case '/getAllProductos':
                    try {
                        $productos = $daoProducto->getAllProductos();
                        header('Content-Type: application/json');
                        $response = json_encode($productos);
                        validateJson($response);
                    } catch (Exception $e) {
                        internalServerError($e->getMessage());
                    }
                    break;
                default:
                    http_response_code(404);
            }
            break;
        default:
            http_response_code(404);
    }
}
else if($method === "POST"){
    $requestBody = file_get_contents('php://input');
    switch('/'.$pathParts[1]){ //Acá listadas en el switch todas las direcciones que usen POST
        case '/userLogin':
            try {
                $userRequest = $_POST;
                $user = $daoUser->getUserByEmail($userRequest["email"]);

                if ($user) {
                    $passwordOK = password_verify($userRequest["password"], $user["password"]);
                } else {
                    $passwordOK = false;
                }
                validateJson('');
                if (!$passwordOK) {
                    header('Location: /login?loginfailed=true');
                    exit;
                } else {
                    $_SESSION['logged_in'] = true;
                    $_SESSION['user'] = $user["nombre"];
                    $_SESSION['correo'] = $user["correo"];
                    $_SESSION['tipo_sesion'] = $user["tipo_de_usuario"];
                    header('Location: /productos');
                    exit;
                }
            } catch (Exception $e) {
                internalServerError($e->getMessage());
            }
            break;
        case '/userSignup':
            try {
                $request = $_POST;
                $user = $daoUser->createUser($request["nombre"], $request["correo"], $request["password"], 'client');
                validateJson('');
                $_SESSION['logged_in'] = true;
                $_SESSION['user'] = $request["nombre"];
                $_SESSION['correo'] = $request["correo"];
                $_SESSION['tipo_sesion'] = 'client'; // Ensure the session type is set to 'client'
                header('Location: /productos');
                exit;
            } catch (Exception $e) {
                internalServerError($e->getMessage());
            }
            break;
            case '/bd':
                checkIfLoggedIn();
                checkIfAdminPerms();
                switch('/'.$pathParts[2]){
                    case '/createUser':
                        try {
                            $user = json_decode($requestBody);
                            $response = $daoUser->createUser($user->nombre, $user->correo, $user->password, $user->tipo_de_usuario);
                            validateJson($response);
                        } catch (Exception $e) {
                            internalServerError($e->getMessage());
                        }
                        break;
                    case '/createPedido':
                        try {
                            $pedido = json_decode($requestBody);
                            $response = $daoPedido->createPedido($pedido->id_usuario, $pedido->fecha_pendiente, $pedido->fecha_entregado, $pedido->estado);
                            validateJson($response);
                        } catch (Exception $e) {
                            internalServerError($e->getMessage());
                        }
                        break;
                    case '/createProducto':
                        try {
                            $producto = json_decode($requestBody);
                            $response = $daoProducto->createProducto($producto->precio, $producto->descripcion, $producto->stock);
                            validateJson($response);
                        } catch (Exception $e) {
                            internalServerError($e->getMessage());
                        }
                        break;
                    default:
                        http_response_code(404);
                }
                break;
            default:
                http_response_code(404);
        }
    }
    else if($method === "PUT"){
    $requestBody = file_get_contents('php://input');
    switch($path["path"]){ //Acá listadas en el switch todas las direcciones que usen PUT
        case '/bd':
            checkIfLoggedIn();
            checkIfAdminPerms();
            switch('/'.$pathParts[2]){
                case '/updateUser':
                    try {
                        $user = json_decode($requestBody);
                        $response = $daoUser->updateUser($user->id, $user->nombre, $user->correo, $user->password);
                        validateJson($response);
                    } catch (Exception $e) {
                        internalServerError($e->getMessage());
                    }
                    break;
                case '/updatePedido':
                    try {
                        $pedido = json_decode($requestBody);
                        $response = $daoPedido->updatePedido($pedido->id, $pedido->fecha_pendiente, $pedido->fecha_entregado, $pedido->estado);
                        validateJson($response);
                    } catch (Exception $e) {
                        internalServerError($e->getMessage());
                    }
                    break;
                case '/updateProducto':
                    try {
                        $producto = json_decode($requestBody);
                        $response = $daoProducto->updateProducto($producto->id, $producto->precio, $producto->descripcion, $producto->stock);
                        validateJson($response);
                    } catch (Exception $e) {
                        internalServerError($e->getMessage());
                    }
                    break;
                default:
                    http_response_code(404);
            }
            break;
        default:
            http_response_code(404);
    }
}
else if($method === "DELETE"){
    $requestBody = file_get_contents('php://input');
    switch($path["path"]){ //Acá listadas en el switch todas las direcciones que usen DELETE
        case '/bd':
            checkIfLoggedIn();
            checkIfAdminPerms();
            switch('/'.$pathParts[2]){
                case '/deleteUser':
                    try {
                        validateQuery();
                        $user = $daoUser->deleteUser($args["id"]);
                        $response = json_encode($user);
                        validateJson($response);
                    } catch (Exception $e) {
                        internalServerError($e->getMessage());
                    }
                    break;
                case '/deletePedido':
                    try {
                        validateQuery();
                        $pedido = $daoPedido->deletePedido($args["id"]);
                        $response = json_encode($pedido);
                        validateJson($response);
                    } catch (Exception $e) {
                        internalServerError($e->getMessage());
                    }
                    break;
                case '/deleteProducto':
                    try {
                        validateQuery();
                        $producto = $daoProducto->deleteProducto($args["id"]);
                        $response = json_encode($producto);
                        validateJson($response);
                    } catch (Exception $e) {
                        internalServerError($e->getMessage());
                    }
                    break;
                default:
                    http_response_code(404);
            }
            break;
        default:
            http_response_code(404);
    }
}
else{
    http_response_code(405);
}
?>