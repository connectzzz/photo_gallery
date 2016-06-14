<?php
require_once('../../includes/functions.php');
$session = new Session();
// Проверяем авторизовался ли пользовать
if (!$session->isLoggedIn()) { redirect_to("login.php"); }
ob_start();
//Подключаем шаблоны
include_layout_template('admin_header.php');
include_layout_template('admin_menu.php');

// Подключаем manage_user.php
if (isset($_GET['manage'])) {

    // если из GET пришел action подключаем соответствующий шаблон
    if (isset($_GET['action'])) {

        // если пришел POST выполняем валидацию
        if(isset($_POST['submit'])) {

            include __DIR__.'/../../includes/validation_form_user.php';
        }

        switch ($_GET['action']) {
            case 'create':
                include __DIR__.'/../layouts/create_user.php';
                break;
            case 'update':
                //если данные не прошли валидацию ID придет как $_POST['id']
                $user_id = (isset($_GET['id'])) ? $_GET['id'] : $_POST['id'] ;
                $user = User::findByIdPk($user_id);
                include __DIR__.'/../layouts/update_user.php';
                break;
            case 'delete':
                $user = User::findByIdPk($_GET['id']) ;
                if($user->delete() === 1) {
                    $message = 'Запись ' . $user->username . ' удалена.';
                };
                break;
        }
    }

    $users = User::findAll();
    include __DIR__.'/../layouts/manage_user.php';
}
ob_end_flush();
?>
<?php include_layout_template('admin_footer.php'); ?>