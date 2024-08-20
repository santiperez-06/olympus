<?php
    class PedidoDAO {

        public function createPedido($id_usuario, $fecha_pendiente, $fecha_entregado, $estado) {
            include(__DIR__."/../database/connection.php");
            $sql = "INSERT INTO pedido (id_usuario, fecha_pendiente, fecha_entregado, estado) 
                    VALUES (:id_usuario, :fecha_pendiente, :fecha_entregado, :estado)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':id_usuario' => $id_usuario,
                ':fecha_pendiente' => $fecha_pendiente,
                ':fecha_entregado' => $fecha_entregado,
                ':estado' => $estado
            ]);
            $response = $pdo->lastInsertId();
            $pdo = null; 
            return $response;
        }
    
        // Obtener un pedido por ID
        public function getPedidoById($id_pedido) {
            include(__DIR__."/../database/connection.php");
            $sql = "SELECT * FROM pedido WHERE id_pedido = :id_pedido";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id_pedido' => $id_pedido]);
            $response = $stmt->fetch(PDO::FETCH_ASSOC);
            $pdo = null; 
            return $response;
        }
    
        // Obtener todos los pedidos
        public function getAllPedidos() {
            include(__DIR__."/../database/connection.php");
            $sql = "SELECT * FROM pedido";
            $stmt = $pdo->query($sql);
            $response = $stmt->fetchAll(PDO::FETCH_ASSOC); 
            $pdo = null;
            return $response;
        }
    
        // Actualizar un pedido
        public function updatePedido($id_pedido, $fecha_pendiente, $fecha_entregado, $estado) {
            include(__DIR__."/../database/connection.php");
            $sql = "UPDATE pedido SET 
                    fecha_pendiente = :fecha_pendiente, 
                    fecha_entregado = :fecha_entregado, 
                    estado = :estado 
                    WHERE id_pedido = :id_pedido";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':id_pedido' => $id_pedido,
                ':fecha_pendiente' => $fecha_pendiente,
                ':fecha_entregado' => $fecha_entregado,
                ':estado' => $estado
            ]);
            $response = $stmt->rowCount(); 
            $pdo = null;
            return $response;
        }
    
        // Eliminar un pedido
        public function deletePedido($id_pedido) {
            include(__DIR__."/../database/connection.php");
            $deletePedidoProducto = "DELETE FROM pedido_producto WHERE id_pedido = :id_pedido";
            $stmt = $pdo->prepare($deletePedidoProducto);
            $stmt->execute([':id_pedido' => $id_pedido]);
            
            $deletePedido = "DELETE FROM pedido WHERE id_pedido = :id_pedido";
            $stmt2 = $pdo->prepare($deletePedido);
            $stmt2->execute([':id_pedido' => $id_pedido]);
            $response = $stmt2->rowCount() + $stmt->rowCount();
            $pdo = null;
            return $response;
        }
        
        public function sumarProducto($id_pedido, $id_producto, $cantidad){
            include(__DIR__."/../database/connection.php");
            $sql = "INSERT INTO pedido_producto (id_pedido, id_producto, cantidad) VALUES (:id_pedido, :id_producto, :cantidad)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':id_pedido'=>$id_pedido,
                ':id_producto'=>$id_producto,
                ':cantidad'=>$cantidad
            ]);
            $response = $stmt->rowCount();
            $pdo = null;
            return $response;
        }

        public function quitarProducto($id_pedido, $id_producto){
            include(__DIR__."/../database/connection.php");
            $sql = "DELETE FROM pedido_producto WHERE id_pedido = :id_pedido AND id_producto = :id_producto";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id_pedido' => $id_pedido, ':id_producto' => $id_producto]);
            $response = $stmt->rowCount();
            $pdo = null;
            return $response;
        }
    }

?>