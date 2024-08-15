<?php

include(__DIR__.'/./connection.php');

    class ProductDAO {

        private $pdo;

        public function __construct($pdo){
            $this->pdo = $pdo;
        }
    }

?>