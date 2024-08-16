<?php

include(__DIR__ . '/../database/connection.php');

    class UserDAO {

        private $pdo;

        public function __construct($pdo){
            $this->pdo = $pdo;
        }


        public function createUser($nombre, $correo, $contrasena, $tipo_de_usuario){
            $sql = "INSERT INTO user (nombre, correo, password, tipo_de_usuario) VALUES (:nombre, :correo, :password, :tipo_de_usuario)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':nombre' => $nombre,
                ':correo' => $correo,
                ':password' => password_hash($contrasena, PASSWORD_BCRYPT),
                ':tipo_de_usuario' => $tipo_de_usuario
            ]);
            return $this->pdo->lastInsertId();
        }

        public function getAllUsers() {
            $sql = "SELECT * FROM user";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
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

        public function updateUser($id_usuario, $nombre, $correo, $password) {
            $sql = "UPDATE user SET nombre = :nombre, correo = :correo, password = :password WHERE id_usuario = :id_usuario";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':id_usuario' => $id_usuario,
                ':nombre' => $nombre,
                ':correo' => $correo,
                ':password' => password_hash($password, PASSWORD_BCRYPT)
            ]);
            return $stmt->rowCount();
        }

        public function deleteUser($id_usuario) {
            $sql = "DELETE FROM user WHERE id_usuario = :id_usuario";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id_usuario' => $id_usuario]);
            return $stmt->rowCount();
        }

    }

?>
