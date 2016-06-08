<?php
require_once __DIR__ . '/../includes/config.php';

class PDODatabase {
    private $_db;
    private static $_instance;
    protected $classname='stdClass';

    public function __construct() {
        try {
            $this->_db = new PDO(DB_DSN, DB_USER,  DB_PASS, [ [PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION]]);
        } catch(PDOException $e) {
            echo 'Подключение не удолось: ' . $e->getMessage();
        }

    }

    /**
     * @return PDODatabase
     */
    public static function getInstance() {

        if (!self::$_instance) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }

    /**
     * @return PDO
     */
    public function getConnection() {

        return $this->_db;
    }

    private function  __clone() {}

    public function setClassname($classname) {
        $this->classname = $classname;
    }

    public  function queryFetchAll($sql) {

        $stmt = $this->_db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_CLASS,$this->classname);
    }

    public function prepareFetch($sql, array $data) {

        $stmt = $this->_db->prepare($sql);
        $stmt->execute($data);
        $stmt->setFetchMode(PDO::FETCH_CLASS, $this->classname);
        return $stmt->fetch();

    }

}

$f = PDODatabase::getInstance();
$db = $f->getConnection();

