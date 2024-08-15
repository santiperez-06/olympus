<?php

include(__DIR__ . '/../database/connection.php');

    class UserDAO {

        private $pdo;

        public function __construct($pdo){
            $this->pdo = $pdo;
        }


        public function createUser($nombre, $correo_electronico, $contrasena, $tipoUser){
            $sql = "INSERT INTO user (nombre, correo, contraseña, tipo_de_usuario) VALUES (:nombre, :correo, :contraseña, :tipoUser)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':nombre' => $nombre,
                ':correo_electronico' => $correo_electronico,
                ':contraseña' => password_hash($contrasena, PASSWORD_BCRYPT)
            ]);
            return $this->pdo->lastInsertId();
        }

        public function getUserById($id_usuario) {
            $sql = "SELECT * FROM user WHERE id_usuario = :id_user";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id_user' => $id_usuario]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
    
        public function getUserByEmail($correo) {
            $sql = "SELECT * FROM user WHERE correo = :correo";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':correo' => $correo]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function updateUser($id_usuario, $nombre, $correo, $contraseña) {
            $sql = "UPDATE user SET nombre = :nombre, correo = :correo, contraseña = :contraseña WHERE id_usuario = :id_usuario";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':id_user' => $id_usuario,
                ':nombre' => $nombre,
                ':correo' => $correo,
                ':contraseña' => password_hash($contraseña, PASSWORD_BCRYPT)
            ]);
            return $stmt->rowCount();
        }

        public function deleteUser($id_usuario) {
            $sql = "DELETE FROM user WHERE id_usuario = :id_usuario";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id_user' => $id_usuario]);
            return $stmt->rowCount();
        }

    }

?>
