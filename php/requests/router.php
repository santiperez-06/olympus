<?php

include(__DIR__."/../database/connection.php"); 

$path = $_SERVER["REQUEST_URI"];
$method = $_SERVER["REQUEST_METHOD"];

if($method = "GET"){ 
    switch($path){ //Acá listadas en el switch todas las direcciones que usen GET
        case '/':
            echo "SERVER ANDANDO";
            break;
    }
}

?>