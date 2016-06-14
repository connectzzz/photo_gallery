<?php


class Validator {

    private $_field_data = array();
    private $_error_messages = array();

    function __construct() {}

    /**
     * Установка полей валидации
     * @param string|array $field
     * @param string $label
     * @param array|string $rules
     */
    public function set_rules($field, $label = '', $rules = '' ) {

        // нет POST данных
        if (count($_POST) == 0) { return; }

        // если парвила валидации переданны в виде массива
        if(is_array($field)) {
            foreach ($field as $row) {
                //если не установленно поле валидации или правила валидации,
                //то пропускаем это поле
                if (! isset($row['field']) or ! isset($row['rules'])) {
                    continue;
                }

                //если название поля не передано используем имя поля
                $label = (isset($row['label'])) ? $row['label'] : $row['field'];

                $this->set_rules($row['field'], $label, $row['rules']);
            }
        }

        //правила валидации должны быть переданы в виде масива,
        //а поле валидации строкой
        if (!is_string($field) or !is_array($rules) or $field == '') { return; }

        //если название поля не передано используем имя поля
        $label = ($label == '') ? $field : $label;

        $this->_field_data[$field] = array(
                                            'field' => $field,
                                            'label' => $label,
                                            'rules' => $rules,
                                            'postdata' => null,
                                            'error'=> '',
                                            );
    }

    /**
     * Установка POST данных
     * @param $field
     * @param $postdata
     */
    private function set_field_postdata($field, $postdata) {

        if(isset($this->_field_data[$field]['postdata'])) {
            $this->_field_data[$field]['postdata'] = $postdata;
        }
    }

    /**
     * Проверка правил валидации
     * @param $field
     * @param $postdata
     */
    public function checrule($field, $postdata) {
        if (is_array($postdata)) {

            foreach($postdata as $key => $val){

                $this->checkrule($field,$val);
            }

            return;
        }

        foreach($field['rules'] as $rule => $message) {

            $param = false;

            if (preg_match("/(.*?)\[(.*?)\]/", $rule, $match))
            {
                $rule	= $match[1]; //Правило валидации
                $param	= $match[2]; //Параметры
            }

            //если это правило не входит в состав библиотеки
            if(!method_exists($this, $rule)) {

                //то будем считать, что это стандартная функция PHP
                //которая принимает только один входной параметр
                if(function_exists($rule)){
                    $result = $rule($postdata);

                    $postdata = (is_bool($result)) ? $postdata : $result;
                    $this->set_field_postdata($field['field'],$postdata);
                    continue;
                }
            } else {

                $result = $this->$rule($postdata, $param);
            }

            //Если функция возвращает булевое значение (TRUR|FALSE),
            //то мы не изменяем переданные POST данные, иначе записываем
            //отформатированные данные
            $postdata = (is_bool($result)) ? $postdata : $result;
            $this->set_field_postdata($field['field'], $postdata);

            // если данные не прошли валидацию
            if($result === false && $message != '') {

                //Формируем сообщение об ошибке
                $error = sprintf($message, $field['label']);

                //Сохраняем сообщение об ошибке
                $this->_field_data[$field['field']]['error'] = $error;

                if ( ! isset($this->_error_messages[$field['field']])){

                    $this->_error_messages[$field['field']] = $error;
                }

            }

            continue;
        }

        return;
    }

    public function run() {

        //Нет POST данных
        if (count($_POST) == 0){ return false; }

        //Если нет заданных полей для валидации
        if(count($this->_field_data) == 0){ return false; }

        foreach ($this->_field_data as $field => $row ) {

            //Получаем POST данные для установленных полей валидации
            $this->_field_data[$field]['postdata'] = (isset($_POST[$field])) ? trim($_POST[$field]) : null;
            $this->checrule($row, $this->_field_data[$field]['postdata']);
        }

        $total_errors = count($this->_error_messages);

        if($total_errors == 0){

            return true;
        }

        return false;

    }







    //*************************************************

    /**
     * Возвращает POST данные для нужного элемента
     * @param string $field
     * @return string
     */
    public function postdata($field) {

        if (isset($this->_field_data[$field]['postdata']))
        return $this->_field_data[$field]['postdata'];
        else return '';
    }
    /**
     * Возвращает POST данные в виде массива
     * @return array
     */
    public function array_postdata() {
        $data = [];
        foreach ($this->_field_data as $field ) {
            $data[$field['field']] = $field['postdata'];
        }
        return $data;
    }


    /**
     * Очищает все POST данные
     */
    public function reset_postdata() {

        $this->_field_data = [];
    }

    /**
     * Возвращает все сообщения об ошибках в виде массива
     */
    function get_array_errors(){

        return $this->_error_messages;
    }

    //***************************************************
    /**
     * Значение не может быть пустым
     * @param string $str
     * @return bool
     */
    public function required($str) {

        if(! is_array($str)){
            return (trim($str) == '') ? false : true;
        } else {
            return (! empty($str));
        }
    }

    /**
     * Проверка поля на целое число
     * @param string $str
     * @return mixed
     */
    public function integer($str) {

        return filter_var($str, FILTER_VALIDATE_INT);
    }

    /**
     * Проверка поля на число с плавающей точкой
     * @param string $str
     * @return mixed
     */
    public function float($str) {

        return filter_var($str, FILTER_VALIDATE_FLOAT);
    }

    /**
     * Проверка url
     * @param string $str
     * @return mixed
     */
    public function valid_url($str) {

        return filter_var($str, FILTER_VALIDATE_URL);
    }

    /**
     * Проверка email
     * @param string $str
     * @return mixed
     */
    public function valid_email($str) {

        return filter_var($str, FILTER_VALIDATE_EMAIL);

    }

    /**
     * Соответствует ли одно поле другому
     * @param $str
     * @param $field
     * @return bool
     */
    public function matches($str, $field) {

        if (!isset($_POST[$field])) {
            return false;
        }
        $field = $_POST[$field];
        return ($str === $field) ? true : false;
    }

    /**
     * Проверка на уникальность
     * @param string $str
     * @param string $fields  [table.field]
     * @return bool
     */
    public function unique($str, $fields) {
       // echo $str,$fields;
        $db = PDODatabase::getInstance();

        list($table, $field) = explode('.', $fields);
        $sql = 'SELECT COUNT(*) AS count FROM `';
        $sql .= $table;
        $sql .= '` WHERE '.$field.' =:'.$field;

        $stmt = $db->prepareQuery($sql,[':'.$field=>$str]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['count'] == 0;
    }

    public function regexp($str, $regexp) {
       // var_dump($str,$regexp);
        $options = ['options' => ['regexp' => $regexp]];

        return filter_var($str, FILTER_VALIDATE_REGEXP, $options);
    }






}

