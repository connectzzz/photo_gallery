<?php
require_once __DIR__ . '/../../includes/functions.php';
$session = new Session();
if(!$session->isLoggedIn()) { redirect_to('login.php');}

ob_start();
include_layout_template('admin_header.php');

$message = '';
if(isset($_POST['submit'])) {
    $photo = new Photograph();
    $photo->caption = $_POST['caption'];
    $photo->attach_file($_FILES['file_upload']);
    if($photo->save()) {
        $message = 'Photograph uploaded successfully.';
        $session->setMessage($message);
        redirect_to('list_photos.php');
    } else {
        $message = implode('<br/>', $photo->errors);
    }
}
ob_end_flush();
?>

<h2>Photo Upload</h2>

<?= output_message($message) ?>
<form action="photo_upload.php" enctype="multipart/form-data" method="post">
    <input type="hidden" name="MAX_FILE_SEZE" value="1000000">
    <p><input type="file" name="file_upload" style="border: 0"></p>
    <p>Caption: <input type="text" name="caption" value=""></p>
    <input type="submit" name="submit" value="Upload">
</form>
    <br/>
<p><a href="list_photos.php"><< Photographs</a></p>

<?php include_layout_template('admin_footer.php');


