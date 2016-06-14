<?php


abstract class AbstractModel {

    protected static $_table;
    public $id;

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

    public static function findByFieldsTable() {
        $db = PDODatabase::getInstance();

        $sql = "SHOW COLUMNS FROM " . static::$_table;
        $stmt = $db->prepareQuery($sql, []);
        $stmt->setFetchMode(PDO::FETCH_COLUMN, 0 );
        return $stmt->fetchAll();


    }

    public function save() {
        if ($this->id) {
            return $this->update();
        } else {
            return $this->create();
        }
    }

    protected function create() {
        $db = PDODatabase::getInstance();
        $db->setClassname(get_called_class());

        $fields = static::findByFieldsTable();
        $sql_fields = [];
        $data = [];
        foreach ($fields as $field ) {
            if (property_exists(get_called_class(), $field)) {
                $data[':'.$field] = $this->$field;
               // $sql_fields[] = $field . ' = :' . $field;
            } else { return 'false';} //@TODO FALSE
        }
        $sql = 'INSERT INTO ' . static::$_table ;
        $sql .= " (" . implode(", ", $fields) . ") ";
        $sql .= " VALUES (" . implode(", ", array_keys($data)) . ")";
        //var_dump($sql);var_dump($data);die;
        $db->prepareQuery($sql, $data);
        return $db->lastInsertId();
    }

    protected  function update() {
        $db = PDODatabase::getInstance();
        $db->setClassname(get_called_class());

        $fields = static::findByFieldsTable();
        $sql_fields = [];
        $data = [];
        foreach ($fields as $field ) {
            if (property_exists(get_called_class(), $field)) {
                $data[':'.$field] = $this->$field;
                $sql_fields[] = $field . ' = :' . $field;
            } else { return 'false';} //@TODO FALSE
        }
        $sql = 'UPDATE ' . static::$_table ;
        $sql .= " SET " . implode(", ", $sql_fields);
        $sql .= " WHERE  id = :id";
        //var_dump($sql);var_dump($data);die;
        $stmt = $db->prepareQuery($sql, $data);

        return $stmt->rowCount();
    }

    public function delete() {
        $db = PDODatabase::getInstance();
        $db->setClassname(get_called_class());
        $data = [':id'=>$this->id];
        $sql = 'DELETE FROM  ' . static::$_table ;
        $sql .= " WHERE  id = :id";
        //var_dump($sql);var_dump($data);die;
        $stmt = $db->prepareQuery($sql, $data);
        return $stmt->rowCount();
    }

    public static function instantiate(array $record) {

        $object = new static;
        //var_dump($object);
        foreach($record as $attribute=>$value){
            if(property_exists($object, $attribute)) {
                $object->$attribute = $value;
            }
        }
        return $object;
    }

}