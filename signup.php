<?php

require_once('helpers.php'); //Подключение вспомогательных функций
require_once('startup.php'); //Подключение к БД
require_once('data.php'); //Получаем список категорий (из БД) и другие данные

//Создаем массив ошибок
$errors = [];
//Проверяем, что форма отправлена
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		
	//Проверяем поля формы на пустоту
	$required_fields = ['password', 'name', 'message']; 
	foreach ($required_fields as $field) {
		if (empty($_POST[$field])) {
		$errors[$field] = $messages['fill_it'];
		} 
	}
	//Проверяем email
	if (empty($_POST['email'])) {
		$errors['email'] = $messages['fill_it'];
	} else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
		$errors['email'] = $messages['fill_correct'];
	} else {
		$sql = "SELECT id FROM users WHERE email = ?";
		$stmt = db_get_prepare_stmt($con, $sql, [$_POST['email']]); 
		mysqli_stmt_execute($stmt); 
		$res = mysqli_stmt_get_result($stmt); 
		if (!$res) {
				$error = mysqli_error($con);
				print("Ошибка MySQL: " . $error); 
				$errors['check'] = false;
		} else if (mysqli_num_rows($res) > 0) {
			$errors['email'] = $messages['fill_another_email'];
		}
	}
	//Если ошибок нет - добавляем пользователя в БД
	if(!count($errors)){
		$passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
		$sql = "INSERT INTO users (dt_reg, email, user_name, password, contact_info) 
				VALUES (NOW(), ?, ?, '$passwordHash', ?)";
		$stmt = db_get_prepare_stmt($con, $sql, [$_POST['email'], $_POST['name'], $_POST['message']]); 
		$res = mysqli_stmt_execute($stmt); 
			if (!$res) {
				$error = mysqli_error($con);
				print("Ошибка MySQL: " . $error); 
			} else {
				//Делаем переадресацию на форму входа
				header("Location: pages/login.html");
				exit();
			}
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


