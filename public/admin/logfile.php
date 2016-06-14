<?php
require_once('../../includes/functions.php');
$session = new Session();
if (!$session->isLoggedIn()) {redirect_to('login.php');}


$log = new Logger();
if (isset($_GET['clear'])) {
   $log->clear();
}
$arr_log = $log->readLogInArray();
include_layout_template('admin_header.php');
?>
<p><a href="index.php"><< Admin Menu</a></p>
<h2>LOG FILE</h2>
<p style="text-align: right; padding-right: 5em ">
    <a href="logfile.php?clear=true">Clear log file</a>
</p>
<table>
    <?php foreach ($arr_log as $key=>$vel): ?>
        <tr>
            <td><?= $vel  ?></td>
        </tr>
    <?php endforeach;?>
</table>

<?php include_layout_template('admin_footer.php');?>