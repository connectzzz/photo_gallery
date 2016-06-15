<?php
require_once __DIR__ . '/../../includes/functions.php';

$session = new Session();
if (!$session->isLoggedIn()) {redirect_to('login.php');}

$photos = Photograph::findAll();

include_layout_template('admin_header.php');
?>

<p><a href="index.php"><< Back</a></p>

<h2>Photographs</h2>

<?= output_message($session->getMessage()) ?>
<table class="photos">
    <tr>
        <th>Image</th>
        <th>Filename</th>
        <th>Caption</th>
        <th>Size</th>
        <th>Type</th>
        <th></th>
    </tr>
    <?php foreach ($photos as $photo): ?>
    <tr>
        <td>
            <a href="#">
                <img class="photos" src="../<?=$photo->image_path() ?>"
                     alt="<?=$photo->filename?>"/>
            </a>
        </td>
        <td><?=$photo->filename?></td>
        <td><?=$photo->caption?></td>
        <td><?=$photo->size_format()?></td>
        <td><?=$photo->type?></td>
        <td><a href="delete_photo.php?id=<?=$photo->id?>">Delete</a></td>
    </tr>
    <?php endforeach;?>
</table>

<p><a href="photo_upload.php">Upload a new photograph</a></p>
<?php include_layout_template('admin_footer.php');
