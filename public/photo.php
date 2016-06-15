<?php
require_once __DIR__ . '/../includes/functions.php';
$session = new Session();

if(empty($_GET['id'])) {
    $session->setMessage('No photograph ID was Provided.');
    redirect_to('index.php');
}
$photo = Photograph::findByIdPk($_GET['id']);
include_layout_template('header.php');
?>
    <p><a href="index.php"><< Back</a></p>
    <img src="<?=$photo->image_path() ?>" alt="<?=$photo->caption ?>" width="90%"/>

<?php include_layout_template('footer.php');