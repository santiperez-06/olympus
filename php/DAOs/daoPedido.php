<?php

include(__DIR__.'/./connection.php');

    class PedidoDAO {

        private $pdo;

        public function __construct($pdo){
            $this->pdo = $pdo;
        }
    }

?>