<?php

require_once('helpers.php'); //Подключение вспомогательных функций
require_once('data.php'); //Данные для валидации форм
require_once('startup.php'); //Подключение к БД и получение из нее категорий

//Создаем массив ошибок
$errors = [];

//Проверяем, что форма отправлена
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
    //Проверяем поля формы на пустоту
    $required_fields = ['email', 'password'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] = $messages['fill_it'];
        }
    }
	
    //Аутентификация
    if (!count($errors)) {
        $user = can_user_login($con, $_POST['email'], $_POST['password']);
        if (!$user) {
            $errors['email_password'] = $messages['wrong_password_email'];
        } else {
            $_SESSION['user'] = $user;
        }
    }
	
    //Если ошибок нет
    if (!count($errors)) {
        //Делаем переадресацию на главную страницу
        header("Location: index.php");
        exit();
    }        
    
	//Если есть ошибки в заполнении формы - отправляем массив с ошибками в шаблон
    $errors['check'] = false;
}


$page_content = include_template(
    'login.php',
    ['categories' => $categories, 'errors' => $errors]
);

$layout_content = include_template(
    'layout.php',
    ['content' => $page_content, 'categories' => $categories, 'title' => 'Вход', 'main_class' => '']
);

print($layout_content);
