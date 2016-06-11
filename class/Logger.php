<?php


class Logger {

    const LOGFILE = __DIR__ . '/../logs/log.txt';
    public $filename;

    public $time;
    public $action;
    public $message;

    public function __construct() {
        $this->filename = self::LOGFILE;
    }

    public function action($action, $message='') {
        if ( $handle = fopen($this->filename, 'a' )) {
            $content = strftime('%d/%m/%Y %H:%M:%S');
            $content .= ' | ';
            $content .= $action . ": " . $message;
            $content .= "\n";
            fwrite($handle, $content);

            fclose($handle);
        }else {
            echo 'Could not open log file for writing';
        }
    }

    public function readLogInArray() {
        if(is_file($this->filename)){
            if ($arr_log = file($this->filename)) {
                return $arr_log;
            }
        }
        return array();
    }

    public function clear() {
        if(is_file($this->filename)){
            if(false !== file_put_contents($this->filename, '')) {
                $this->action('Logs Cleared', "by User ID ".Session::userId());
                redirect_to('logfile.php');
            }
        }
        return false;
    }

}



