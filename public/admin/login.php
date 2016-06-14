<?php
require_once('../../includes/functions.php');
$session = new Session();
if ($session->isLoggedIn()) { redirect_to("index.php"); }

$message = '';
if(isset($_POST['submit'])){

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // check database to see if username/password exist
    $found_user = User::authenticate($username, $password);

    if ($found_user) {
        //var_dump($found_user);// die;
        $session->login($found_user);
        $log = new Logger();
        $log_message = $found_user->username ." logged in.";
        $log->action('Login', $log_message);
        redirect_to('index.php');
    }else {
        $message = 'Username/password combination incorrect.';
    }
}else {
    $username = '';
    $password = '';
}



?>


<html>
<head>
    <title>Photo Gallery</title>
    <link href="../css/main.css" media="all" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="header">
    <h1>Photo Gallery</h1>
</div>
<div id="main">
    <h2>Staff Login</h2>
    <?php echo output_message($message); ?>

    <form action="login.php" method="post">
        <table>
            <tr>
                <td>Username:</td>
                <td>
                    <input type="text" name="username" maxlength="30" value="<?php echo htmlentities($username); ?>" />
                </td>
            </tr>
            <tr>
                <td>Password:</td>
                <td>
                    <input type="password" name="password" maxlength="30" value="<?php echo htmlentities($password); ?>" />
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <input type="submit" name="submit" value="Login" />
                </td>
            </tr>
        </table>
    </form>
</div>
<div id="footer">Copyright <?php echo date("Y", time()); ?>, Kevin</div>
</body>
</html>

