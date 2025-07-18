<?php
    class Database {
        private static $instance = null;
        private $pdo;

        private function __construct() {
            try {
                $host = 'localhost';
                $dbname = 'rede_segura';
                $username = 'root';
                $password = 'redesegura';

                $this->pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Erro na conexão: " . $e->getMessage());
            }
        }

        public static function getInstance() {
            if (!self::$instance) {
                self::$instance = new Database();
            }
            return self::$instance->pdo;
        }
    }
?>
