<?php
require_once __DIR__ . '/../includes/functions.php';
include_layout_template('header.php');

    $user = User::findByIdPk(1);
    echo $user->fullName();

    echo "<hr />";

    $users = User::findAll();
    foreach($users as $user) {
        echo "User: " . $user->username . "<br />";
        echo "Name: " . $user->fullName() . "<br /><br />";
    }
?>







<?php include_layout_template('footer.php');