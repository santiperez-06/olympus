<?php

    class UserDAO {

        public function createUser($nombre, $correo, $contrasena, $tipo_de_usuario){
            include(__DIR__."/../database/connection.php");
            $sql = "INSERT INTO user (nombre, correo, password, tipo_de_usuario) VALUES (:nombre, :correo, :password, :tipo_de_usuario)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nombre' => $nombre,
                ':correo' => $correo,
                ':password' => password_hash($contrasena, PASSWORD_BCRYPT),
                ':tipo_de_usuario' => $tipo_de_usuario
            ]);
            $response = $pdo->lastInsertId();
            $pdo = null;
            return $response;
        }

        public function getAllUsers() {
            include(__DIR__."/../database/connection.php");
            $sql = "SELECT * FROM user";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $pdo = null;
            return $response;
        }

        public function getUserById($id_usuario) {
            include(__DIR__."/../database/connection.php");
            $sql = "SELECT * FROM user WHERE id_usuario = :id_user";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id_user' => $id_usuario]);
            $response = $stmt->fetch(PDO::FETCH_ASSOC);
            $pdo = null;
            return $response;
        }
    
        public function getUserByEmail($correo) {
            include(__DIR__."/../database/connection.php");
            $sql = "SELECT * FROM user WHERE correo = :correo";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':correo' => $correo]);
            $response = $stmt->fetch(PDO::FETCH_ASSOC);
            $pdo = null;
            return $response;
        }

        public function updateUser($id_usuario, $nombre, $correo, $password) {
            include(__DIR__."/../database/connection.php");
            $sql = "UPDATE user SET nombre = :nombre, correo = :correo, password = :password WHERE id_usuario = :id_usuario";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':id_usuario' => $id_usuario,
                ':nombre' => $nombre,
                ':correo' => $correo,
                ':password' => password_hash($password, PASSWORD_BCRYPT)
            ]);
            $response = $stmt->rowCount();
            $pdo = null;
            return $response;
        }

        public function deleteUser($id_usuario) {
            include(__DIR__."/../database/connection.php");
            $sql = "DELETE FROM user WHERE id_usuario = :id_usuario";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id_usuario' => $id_usuario]);
            $response = $stmt->rowCount();
            $pdo = null;
            return $response;
        }

    }

?>
