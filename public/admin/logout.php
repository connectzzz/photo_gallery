<?php
require_once __DIR__.'/../../includes/functions.php';
$session = new Session();
$session->logout();
redirect_to('login.php');