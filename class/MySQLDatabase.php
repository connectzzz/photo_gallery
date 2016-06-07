<?php
require_once __DIR__ . '/../includes/config.php';

class PDODatabase {
    private $_connection;
    private static $_instance;

    public function __construct() {
        try {
            $this->_connection = new PDO(DB_DSN, DB_USER,  DB_PASS);
        } catch(PDOException $e) {
            echo 'Подключение не удолось: ' . $e->getMessage();
        }

    }

    public static function getInstance() {

        if (!self::$_instance) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }

    public function getConnection() {

        return $this->_connection;
    }

    private function  __clone() {}



}

$f = PDODatabase::getInstance();
$db = $f->getConnection();

