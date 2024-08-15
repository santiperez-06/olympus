<?php

include(__DIR__.'/./connection.php');

    class ProductDAO {

        private $pdo;

        public function __construct($pdo){
            $this->pdo = $pdo;
        }

        public function createProducto($precio, $descripcion, $stock) {
            $sql = "INSERT INTO producto (precio, descripcion, stock) 
                    VALUES (:precio, :descripcion, :stock)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':precio' => $precio,
                ':descripcion' => $descripcion,
                ':stock' => $stock
            ]);
            return $this->pdo->lastInsertId();
        }
    
        // Obtener un producto por ID
        public function getProductoById($id_producto) {
            $sql = "SELECT * FROM producto WHERE id_producto = :id_producto";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id_producto' => $id_producto]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
    
        // Obtener todos los productos
        public function getAllProductos() {
            $sql = "SELECT * FROM producto";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    
        // Actualizar un producto
        public function updateProducto($id_producto, $precio, $descripcion, $stock) {
            $sql = "UPDATE producto SET 
                    precio = :precio, 
                    descripcion = :descripcion, 
                    stock = :stock 
                    WHERE id_producto = :id_producto";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':id_producto' => $id_producto,
                ':precio' => $precio,
                ':descripcion' => $descripcion,
                ':stock' => $stock
            ]);
            return $stmt->rowCount();
        }
    
        // Eliminar un producto
        public function deleteProducto($id_producto) {
            $sql = "DELETE FROM producto WHERE id_producto = :id_producto";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id_producto' => $id_producto]);
            return $stmt->rowCount();
        }
    }

?>