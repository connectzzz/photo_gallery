<?php
require_once __DIR__ . '/../includes/functions.php';
$session = new Session();

include_layout_template('header.php');

$photos = Photograph::findAll();
?>
<?= output_message($session->getMessage()) ?>
<?php foreach ($photos as $photo): ?>
<div style="float: left; margin: 20px ">
    <a href="photo.php?id=<?=$photo->id ?>">
        <img src="<?=$photo->image_path() ?>" alt="<?=$photo->caption ?>" width="250"/>
    </a>
    <p align="center"><?=$photo->caption ?></p>
</div>


<?php
    endforeach;
    include_layout_template('footer.php');