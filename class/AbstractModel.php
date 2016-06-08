<?php


abstract class AbstractModel {

    protected static $_table;


    public static function getTable() {
        return static::$_table;
    }

    public static function findAll() {
        $db = PDODatabase::getInstance();

        $db->setClassname(get_called_class());
        $sql = "SELECT * FROM " . static::$_table;
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

        $sql = 'SELECT * FROM ' . static::$_table . ' WHERE ' . $param[0] ;
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

}