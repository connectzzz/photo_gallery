<?php


class Session {

    private $loggedIn=false;  // Авторизован ли пользователь
    public  $userId;    // ID пользователя


    function __construct() {
        session_start();
        $this->checkLogin();
        if ($this->loggedIn) {
            ///////
        }else {
            //////
        }
    }

    public function isLoggedIn() { // Проверяет авторизован ли пользователь
        return $this->loggedIn;
    }
    private function checkLogin() {
        if(isset($_SESSION['user_id'])) {
            $this->userId = $_SESSION['user_id'];
            $this->loggedIn = true;
        } else {
            unset($this->userId);
            $this->loggedIn = false;
        }
    }

    /**
     * @param object $user
     */
    public function login($user) {
        // БД найдет user  исходя из логина и пароля
        if($user) {
            $this->userId = $_SESSION['user_id'] = $user->id;
            $this->loggedIn = true;
        }
    }

    public function logout() {
        unset($_SESSION['user_id']);
        unset($this->userId);
        $this->loggedIn = false;
    }

    public static function userId() {
        return $_SESSION['user_id'];
    }

    public function setMessage($message='') {

        $_SESSION['message'] = $message;
    }

    public function getMessage() {

        if(isset($_SESSION['message'])) {
            $message = $_SESSION['message'];
            unset ($_SESSION['message']);
            return $message;
        } else {
            return '';
        }

    }
}

