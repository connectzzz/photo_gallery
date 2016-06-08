<?php

function strip_zeros_from_date($marked_string='') {
    $no_zeros = str_replace('*0', '', $marked_string);
    $cleaned_string = str_replace('*', '', $no_zeros);
    return $cleaned_string;
}

function redirect_to($location = null) {
   if ($location != null) {
       header('Location: ' . $location);
       exit;
   }
}

function output_message($message='') {
    if (!empty($message)) {
        return "<p class=\"message\">{$message}</p>";
    }else {
        return '';
    }
}

function __autoload($classname) {
    $path = __DIR__.'/../class/'.$classname.'.php';
    if (file_exists($path)) {
        require_once $path;
    } else {
        die ("Файл {$classname}.php не найден.");
    }
}

function include_layout_template($template) {
    include __DIR__ . '/../public/layouts/'.$template;
}