<?php
/**
 * Created by PhpStorm.
 * User: Moskini
 * Date: 14.06.2016
 * Time: 9:33
 */

class Photograph
                extends AbstractModel{

    protected static $_table = 'photographs';
    public $id;
    public $filename;
    public $type;
    public $size;
    public $caption;

    private $temp_path; //временный путь
    protected $upload_dir='images';//дериктория загрузки
    public $errors = [];

    protected $upload_errors = [
        UPLOAD_ERR_OK         => "No errors.",
        UPLOAD_ERR_INI_SIZE   => "Larger than upload_max_filesize.",
        UPLOAD_ERR_FORM_SIZE  => "Larger than form MAX_FILE_SIZE.",
        UPLOAD_ERR_PARTIAL    => "Partial upload.",
        UPLOAD_ERR_NO_FILE    => "No file.",
        UPLOAD_ERR_NO_TMP_DIR => "No temporary directory.",
        UPLOAD_ERR_CANT_WRITE => "Can't write to disk.",
        UPLOAD_ERR_EXTENSION  => "File upload stopped by extension.",
    ];

    /**
     * @param array $file === $_FILE['uploaded_file']
     * @return bool
     */
    public function attach_file($file) {

        if(!$file || empty($file) || !is_array($file)) {
            $this->errors[] = 'No file was uploaded.';
            return false;

        } elseif($file['error'] != 0) {
            $this->errors[] = $this->upload_errors[$file['error']];
            return false;

        } else {
            $this->temp_path = $file['tmp_name'];
            $this->filename  = basename($file['name']);
            $this->type      = $file['type'];
            $this->size      = $file['size'];
            return true;
        }
    }

    public function save() {
        if(isset($this->id)) {
            $this->update();

        } else {

            if(!empty($this->errors)) {
                return false;
            }

            if(strlen($this->caption) > 255) {
                $this->errors[] = 'The caption can only be 255 characters long.';
                return false;
            }

            if(empty($this->filename) || empty($this->temp_path)) {
                $this->errors[] = 'The file location was not available';
                return false;
            }

            $target_path = __DIR__ . '/../public/' . $this->upload_dir . '/' . $this->filename;

            if(file_exists($target_path)) {
                $this->errors[] = 'THE file ' . $this->filename . ' already exists.';
                return false;
            }

            if(move_uploaded_file($this->temp_path, $target_path)) {

                if($this->create()) {
                    unset($this->temp_path);
                    return true;
                }

            } else {
                $this->errors[] = 'The file upload failed, possibly due to incorrect permissions on the upload folder.';
                return false;
            }
        }
    }

    public function destroy() {

        if(isset($this->id)) {

            if(file_exists(__DIR__.'/../public/'.$this->image_path())) {

                if($this->delete() === 1) {
                    unlink(__DIR__.'/../public/'.$this->image_path());
                    return true;
                }
                return false;
            }
            return false;
        }
        return false;
    }

    public function image_path() {

        return $this->upload_dir . '/' . $this->filename;
    }

    public function size_format() {
        switch (true) {
            case $this->size >= 1024 :
                echo round($this->size/1024, 1) . ' KB';
                break;
            case $this->size >= 1024*1024 :
                echo round($this->size/1024/1024, 1) . ' MB';
                break;
            default :
                echo $this->size . ' B';
        }
    }

} 