<?php
    require __DIR__ . "/../../vendor/autoload.php";

    use Dotenv\Dotenv;

    // Cargar variables de entorno
    $dotenv = Dotenv::createImmutable(__DIR__ . "/../../");
    $dotenv->load();

    // Obtener las variables de entorno para la conexión a la base de datos
    $host = $_ENV["DB_PATH"];
    $dbName = $_ENV["DB_NAME"];
    $user = $_ENV["DB_USER"];
    $password = $_ENV["DB_PASS"];

    // Configuración del DSN (Data Source Name) para PDO
    $dsn = "mysql:host=$host;dbname=$dbName;charset=utf8";

    try {
        // Crear una nueva instancia de PDO
        $pdo = new PDO($dsn, $user, $password);

        // Configurar el manejo de errores de PDO
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        echo "Connected successfully";
    } catch (PDOException $e) {
        // Manejar el error en caso de fallo de conexión
        die("Connection failed: " . $e->getMessage());
    }

?>
