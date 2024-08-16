<?php

    include(__DIR__."/../database/connection.php");

    class PedidoDAO {

        private $pdo;

        public function __construct($pdo){
            $this->pdo = $pdo;
        }

        public function createPedido($id_usuario, $fecha_pendiente, $fecha_entregado, $estado) {
            $sql = "INSERT INTO pedido (id_usuario, fecha_pendiente, fecha_entregado, estado) 
                    VALUES (:id_usuario, :fecha_pendiente, :fecha_entregado, :estado)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':id_usuario' => $id_usuario,
                ':fecha_pendiente' => $fecha_pendiente,
                ':fecha_entregado' => $fecha_entregado,
                ':estado' => $estado
            ]);
            return $this->pdo->lastInsertId();
        }
    
        // Obtener un pedido por ID
        public function getPedidoById($id_pedido) {
            $sql = "SELECT * FROM pedido WHERE id_pedido = :id_pedido";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id_pedido' => $id_pedido]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
    
        // Obtener todos los pedidos
        public function getAllPedidos() {
            $sql = "SELECT * FROM pedido";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    
        // Actualizar un pedido
        public function updatePedido($id_pedido, $fecha_pendiente, $fecha_entregado, $estado) {
            $sql = "UPDATE pedido SET 
                    fecha_pendiente = :fecha_pendiente, 
                    fecha_entregado = :fecha_entregado, 
                    estado = :estado 
                    WHERE id_pedido = :id_pedido";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':id_pedido' => $id_pedido,
                ':fecha_pendiente' => $fecha_pendiente,
                ':fecha_entregado' => $fecha_entregado,
                ':estado' => $estado
            ]);
            return $stmt->rowCount();
        }
    
        // Eliminar un pedido
        public function deletePedido($id_pedido) {
            $deletePedidoProducto = "DELETE FROM pedido_producto WHERE id_pedido = :id_pedido";
            $stmt = $this->pdo->prepare($deletePedidoProducto);
            $stmt->execute([':id_pedido' => $id_pedido]);
            
            $deletePedido = "DELETE FROM pedido WHERE id_pedido = :id_pedido";
            $stmt2 = $this->pdo->prepare($deletePedido);
            $stmt2->execute([':id_pedido' => $id_pedido]);
            return $stmt2->rowCount() + $stmt->rowCount();
        }
        
        public function sumarProducto($id_pedido, $id_producto, $cantidad){
            $sql = "INSERT INTO pedido_producto (id_pedido, id_producto, cantidad) VALUES (:id_pedido, :id_producto, :cantidad)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':id_pedido'=>$id_pedido,
                ':id_producto'=>$id_producto,
                ':cantidad'=>$cantidad
            ]);
            return $stmt->rowCount();
        }

        public function quitarProducto($id_pedido, $id_producto){
            $sql = "DELETE FROM pedido_producto WHERE id_pedido = :id_pedido AND id_producto = :id_producto";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id_pedido' => $id_pedido, ':id_producto' => $id_producto]);
            return $stmt->rowCount();
        }
    }

?>