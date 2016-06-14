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

    public function lastInsertId($name=null) {
        return $this->_db->lastInsertId($name);
    }

    public  function queryFetchAll($sql) {

        $stmt = $this->_db->query($sql);
        if (!$stmt) {die('Запрос к БД завершился ошибкой. Последний запрос: '.$sql);}
        return $stmt->fetchAll(PDO::FETCH_CLASS,$this->classname);
    }

    /**
     * @param string $sql
     * @param array $data
     * @return PDOStatement
     */
    public function prepareQuery($sql, array $data) {

        $stmt = $this->_db->prepare($sql);
        //@todo if (!$stmt) {die('Запрос к БД завершился ошибкой. Последний запрос: '.$sql);}
        // @todo не возвращает false
        $res = $stmt->execute($data);
        if (!$res) {die('Запрос к БД завершился ошибкой. Последний запрос: '.$sql);}
        return $stmt;
    }

    public function prepareFetch($sql, array $data) {

        $stmt = $this->prepareQuery($sql, $data);
        $stmt->setFetchMode(PDO::FETCH_CLASS, $this->classname);
        return $stmt->fetch();

    }

}

//$f = PDODatabase::getInstance();
////$db = $f->getConnection();
//$sql ='SHOW  COLUMNS FIELDS FROM users';
//$res=$f->queryFetchAll($sql);
//var_dump($res);
