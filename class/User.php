<?php
require_once __DIR__.'/../includes/functions.php';
//require_once('../../includes/functions.php');
class User
            extends  AbstractModel{
    public $id;
    public $username;
    public $password;
    public $first_name;
    public $last_name;

    protected static $_table = 'users';


    /**
     * @param string $username
     * @param string $password
     * @return User
     */
    public static function authenticate($username='', $password='') {

        $found_user = self::findOne(['username'=>$username, 'password'=>$password ]);
        return $found_user;
    }

    public function fullName() {
        if (isset($this->first_name,$this->last_name)) {
            return $this->first_name . ' ' . $this->last_name;
        } else {
            return '';
        }
    }



}
