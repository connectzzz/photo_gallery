<!-- $users = User::findAll(); -->
<?= (isset($message)) ? output_message($message) : '';?>
<h2><a href="index.php?manage=on">Manage Admins</a></h2>
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
            <td><a href="index.php?manage=on&action=update&id=<?= urlencode($user->id)?>">Edit</a>  <a href="index.php?manage=on&action=delete&id=<?= urlencode($user->id)?>"  onclick="return confirm('Вы уверены?');">Delete</a></td>
        </tr>
    <?php endforeach; ?>
</table>

<br/><br/>
<a href="index.php?action=create&manage=on">Add new admin</a>
<br/><br/>