<?php
require_once __DIR__ . '/../../includes/functions.php';
$session = new Session();
if(!$session->isLoggedIn()) { redirect_to('login.php');}

if(empty($_GET['id'])) {
    $session->setMessage('No photograph ID was Provided.');
    redirect_to('list_photos.php');
}
$photo =  Photograph::findByIdPk($_GET['id']);

if ($photo && $photo->destroy()) {
    $session->setMessage("The photo $photo->filename was deleted");
} else {
    $session->setMessage('The photo could not be deleted');
}

redirect_to('list_photos.php');