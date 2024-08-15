<?php

include(__DIR__.'/./connection.php');

    class UserDAO {

        private $pdo;

        public function __construct($pdo){
            $this->pdo = $pdo;
        }


        public function crearUser($nombre, $correo_electronico, $contrasena, $tipoUser){
            $query = "INSERT INTO user (nombre, correo, contraseña, tipo_de_usuario) VALUES (:nombre, :correo, :contraseña, :tipoUser)";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([
                ':nombre' => $nombre,
                ':correo_electronico' => $correo_electronico,
                ':contraseña' => password_hash($contrasena, PASSWORD_BCRYPT)
            ]);
            return $this->pdo->lastInsertId();

        }
    }

?>