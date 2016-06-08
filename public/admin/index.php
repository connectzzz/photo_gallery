<?php
require_once('../../includes/functions.php');
$session = new Session();
if (!$session->isLoggedIn()) { redirect_to("login.php"); }

include_layout_template('admin_header.php');
?>

    <h2>Menu</h2>

<?php include_layout_template('admin_footer.php'); ?>