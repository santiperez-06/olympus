<?php
    require 'vendor/autoload.php';
    use Dotenv\Dotenv;
    $dotenv = Dotenv::createImmutable(__DIR__."/../../");
    $dotenv->load();

    $path = $_ENV["DB_PATH"];
    $password = $_ENV["DB_PASS"];
    $user = $_ENV["DB_USER"];
    $db = $_ENV["DB_NAME"];
    $connection = new mysqli($path, $user, $password, $db);

    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }
    echo "Connected successfully";

?>