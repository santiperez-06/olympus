<?php

    class ProductDAO {

        public function createProducto($precio, $descripcion, $stock) {
            include(__DIR__."/../database/connection.php");
            $sql = "INSERT INTO producto (precio, descripcion, stock) 
                    VALUES (:precio, :descripcion, :stock)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':precio' => $precio,
                ':descripcion' => $descripcion,
                ':stock' => $stock
            ]);
            $response = $pdo->lastInsertId();
            $pdo = null;
            return $response;
        }
    
        // Obtener un producto por ID
        public function getProductoById($id_producto) {
            include(__DIR__."/../database/connection.php");
            $sql = "SELECT * FROM producto WHERE id_producto = :id_producto";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id_producto' => $id_producto]);
            $response = $stmt->fetch(PDO::FETCH_ASSOC);
            $pdo = null;
            return $response;
        }
    
        // Obtener todos los productos
        public function getAllProductos() {
            include(__DIR__."/../database/connection.php");
            $sql = "SELECT * FROM producto";
            $stmt = $pdo->query($sql);
            $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $pdo = null;
            return $response;
        }
    
        // Actualizar un producto
        public function updateProducto($id_producto, $precio, $descripcion, $stock) {
            include(__DIR__."/../database/connection.php");
            $sql = "UPDATE producto SET 
                    precio = :precio, 
                    descripcion = :descripcion, 
                    stock = :stock 
                    WHERE id_producto = :id_producto";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':id_producto' => $id_producto,
                ':precio' => $precio,
                ':descripcion' => $descripcion,
                ':stock' => $stock
            ]);
            $response = $stmt->rowCount();
            $pdo = null;
            return $response;
        }
    
        // Eliminar un producto
        public function deleteProducto($id_producto) {
            include(__DIR__."/../database/connection.php");
            $sql = "DELETE FROM producto WHERE id_producto = :id_producto";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id_producto' => $id_producto]);
            $response = $stmt->rowCount();
            $pdo = null;
            return $response;
        }
    }

?>