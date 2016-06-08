<?php
require_once '../includes/functions.php';
class User   {
    public $id;
    public $username;
    public $password;
    public $first_name;
    public $last_name;

    protected static $_table = 'users';


    public static function getTable() {
        return self::$_table;
    }

    public static function findAll() {
        $db = PDODatabase::getInstance();

        $db->setClassname(get_called_class());
        $sql = "SELECT * FROM " . self::$_table;
        return $db->queryFetchAll($sql);
    }

    public static function findOne(array $data) {
        if(empty($data)) {
            return false;
        }
        $db = PDODatabase::getInstance();
        $db->setClassname(get_called_class());
        $param = [];
        $data_param = [];
        foreach ($data as $key => $val) {
            $param[] = $key. '=:'.$key;
            $data_param[':'.$key] = $val;
        }

        $sql = 'SELECT * FROM ' . self::$_table . ' WHERE ' . $param[0] ;
        if (isset($param[1])) {
            $sql .= ' AND ' . $param[1];
        }
        $sql .= ' LIMIT 1';
        return $db->prepareFetch($sql, $data_param);
    }

    public static function findByIdPk($id=0) {
        $data = ['id'=>$id];
        return self::findOne($data);
    }

    public function fullName() {
        if (isset($this->first_name,$this->last_name)) {
            return $this->first_name . ' ' . $this->last_name;
        } else {
            return '';
        }
    }



}
try{
    $user = new User();
    var_dump($record=User::findAll());
    var_dump($d = User::findOne([]));
    var_dump($d = User::findByIdPk(1));
    //echo $d->id;
} catch (Exception $e) {
    echo $e->getMessage();
}

print_r( User::findByIdPk(1));