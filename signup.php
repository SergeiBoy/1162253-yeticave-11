<?php

require_once('helpers.php'); //Подключение вспомогательных функций
require_once('functions.php'); //Подключение специфических для данного сценария функций
require_once('data.php'); //Данные для валидации форм
require_once('startup.php'); //Подключение к БД и получение из нее категорий

//Закрываем доступ для залогиненных пользователей
if (isset($_SESSION['user'])) {
    http_response_code(403);
    exit();
}

//Создаем массив ошибок
$errors = [];
//Проверяем, что форма отправлена
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
    //Проверяем поля формы на пустоту
    $required_fields = ['password', 'name', 'message'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] = $messages['fill_it'];
        }
    }
    //Проверяем email
    $email = $_POST['email'] ?? '';
    if ($email_error_message = is_not_valid_email($messages, $con, $email)) {
        $errors['email'] = $email_error_message;
    }
    //Если ошибок нет - добавляем пользователя в БД
    if (!count($errors)) {
        add_user($con, $_POST['password'], $_POST['email'], $_POST['name'], $_POST['message']);
        //Делаем переадресацию на форму входа
        header("Location: login.php");
        exit();
            
    //Если есть ошибки в заполнении формы - отправляем массив с ошибками в шаблон
    } else {
        $errors['check'] = false;
    }
}


$page_content = include_template(
    'sign-up.php',
    ['categories' => $categories, 'errors' => $errors]
);

$layout_content = include_template(
    'layout.php',
    ['content' => $page_content, 'categories' => $categories, 'title' => 'Регистрация', 'main_class' => '']
);

print($layout_content);
