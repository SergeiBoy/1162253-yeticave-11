<?php

require_once('helpers.php'); //Подключение вспомогательных функций
require_once('functions.php'); //Подключение специфических для данного сценария функций
require_once('startup.php'); //Подключение к БД
require_once('data.php'); //Получаем список категорий (из БД) и другие данные

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
	if (is_not_valid_email($messages, $con)) {
		$errors['email'] = is_not_valid_email($messages, $con);
	}
	
	//Если ошибок нет - добавляем пользователя в БД
	if(!count($errors)){
		add_user($con);
		//Делаем переадресацию на форму входа
		header("Location: pages/login.html");
		exit();
			
	//Если есть ошибки в заполнении формы - отправляем массив с ошибками в шаблон	
	} else {
		$errors['check'] = false;
	}
}


$page_content = include_template('sign-up.php', 
['categories' => $categories, 'errors' => $errors]);

$layout_content = include_template('layout.php', 
['content' => $page_content, 'is_auth' => $is_auth, 'user_name' => $user_name, 'categories' => $categories, 'title' => 'Регистрация', 'main_class' => '']);

print($layout_content);


