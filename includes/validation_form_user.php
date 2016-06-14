<?php

$validation = new Validator();
// если пришел $_POST['id'], то это обновление записи
// иначе создание
if ((isset($_POST['id']))) { //Update

    //$_GET['username'] это запись из БД, если она не равна значению введенному
    // пользователем, то выполняем проверку на уникальность. Иначе нет
    if($_GET['username']!=$_POST['username']){
        $rule_username = ['required' => 'Поле %s должно быть заполнено.',
            'unique[users.username]' => 'Указанный вами логин уже существует.'];
    }else{
        $rule_username = ['required' => 'Поле %s должно быть заполнено.'];
    }

} else { //Create
    $rule_username = ['required' => 'Поле %s должно быть заполнено.',
        'unique[users.username]' => 'Токой логин уже существует.'];
}

$validation->set_rules('username', 'Username', $rule_username);
$rule_fist_last_name = ['required' => 'Поле %s должно быть заполнено.',];
$validation->set_rules('first_name', 'First Name', $rule_fist_last_name);
$validation->set_rules('last_name', 'Last Name', $rule_fist_last_name);
$rule_password = ['required' => 'Поле %s должно быть заполнено.',
                    'regexp[|^.{4,10}$|]' => 'Пороль должен быть 4..10 символов!'
                    ];
$validation->set_rules('password', 'Password', $rule_password);
$validation->run();
$errors = $validation->get_array_errors();

// Если ошибок нет, то выполняем сохранение записи
if(empty($errors)) {
    $user =  User::instantiate($validation->array_postdata());
    // Проверяем это был метод update или create
    $user->id = (isset($_POST['id'])) ? $_POST['id'] : null;
    $user->save();
    redirect_to('index.php?manage=on');
}