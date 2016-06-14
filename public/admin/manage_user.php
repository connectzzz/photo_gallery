<?php
require_once('../../includes/functions.php');
$session = new Session();
if (!$session->isLoggedIn()) { redirect_to("login.php"); }

$users = User::findAll();
include_layout_template('admin_header.php');
?>

    <h2>Manage Admins</h2>
    <table style="min-width: 600px">
        <tr style="text-align: left; ">
            <th>Username/Password</th>
            <th>Full Name</th>
            <th>Action</th>
        </tr>
        <?php

       foreach ($users as $user): ?>
            <tr>
                <td><?php echo $user->username .' -> '. $user->password ?></td>
                <td><?php echo $user->fullName()?></td>
                <td><a href="edit_user.php?user=<?= urlencode($user->id)?>">Edit</a>  <a href="delete_user.php?user=<?= urlencode($user->id)?>"  onclick="return confirm('Вы уверены?');">Delete</a></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <br/><br/>
    <a href="new_user.php">Add new admin</a>
    <br/><br/>

<?php include_layout_template('admin_footer.php'); ?>