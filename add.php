<?php

require_once('helpers.php'); //Подключение вспомогательных функций
require_once('startup.php'); //Подключение к БД

//Установка использующихся в коде переменных
$is_auth = rand(0, 1);
$user_name = 'Сергей'; // укажите здесь ваше имя

//Получаем список категорий из БД
$sql = "SELECT * FROM categories";
$result = mysqli_query($con, $sql);
	if (!$result) {
	$error = mysqli_error($con);
	print("Ошибка MySQL: " . $error); 
	} 
$categories = mysqli_fetch_all($result, MYSQLI_ASSOC);

//Создаем массив ошибок
$errors = [];
//Проверяем, что форма отправлена
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		
	//Проверяем поля формы на пустоту
	$required_fields = ['lot-name', 'category', 'message', 'lot-rate', 'lot-step', 'lot-date']; 
	foreach ($required_fields as $field) {
		if (empty($_POST[$field])) {
		$errors[$field] = 'Заполните это поле';
		} 
	}
	//Проверяем поле формы для выбора категории, чтобы была указана категория
	if ($_POST['category'] == 'Выберите категорию') {
		$errors['category'] = 'Выберите категорию';
	}
	//Проверяем, что начальная цена - число больше нуля
	if ( !(filter_var($_POST['lot-rate'], FILTER_VALIDATE_FLOAT)) || !(filter_var($_POST['lot-rate'], FILTER_VALIDATE_FLOAT) > 0) ) {
		$errors['lot-rate'] = 'Введите число';
	}
	//Проверяем, что шаг ставки - целое число больше нуля
	if ( !(filter_var($_POST['lot-step'], FILTER_VALIDATE_INT)) || !(filter_var($_POST['lot-step'], FILTER_VALIDATE_INT) > 0) ) {
		$errors['lot-step'] = 'Введите целое число';
	}
	//Проверяем формат даты
	if ( !is_date_valid($_POST['lot-date']) || !(strtotime($_POST['lot-date']) - strtotime('now') > 86400) ) {
		$errors['lot-date'] = 'Введите дату завершения торгов, не меньше суток вперед';
	}
	//Проверяем загруженный файл - что он есть и что его MIME-тип соответствует «image/png», «image/jpeg»
	if (empty($_FILES['file']['tmp_name'])) {
		$errors['file'] = 'Добавьте файл в формате jpg, jpeg, png';
	} else if (mime_content_type($_FILES['file']['tmp_name']) !== 'image/png' && mime_content_type($_FILES['file']['tmp_name']) !== 'image/jpeg') {
		$errors['file'] = 'Добавьте файл в формате jpg, jpeg, png';
	}
	//Если ошибок нет - перемещаем файл изображения в uploads, добавляем лот в БД, делаем переадресацию на просмотр добавленного лота
	if(!count($errors)){
		$file_url = 'uploads/'.$_FILES['file']['name'];
		move_uploaded_file($_FILES['file']['tmp_name'], $file_url);
		//Ищем номер категории
		$category_id = 6;
		foreach ($categories as $category) {
			if ($category['category_name'] == $_POST['category']) {
				$category_id = $category['id'];
				break;
			}
		}
		//Записываем лот в БД
		$sql = "INSERT INTO lots (lot_name, category_id, description, img_path, initial_price, bid_step, dt_end, user_id_author) 
				VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
		$stmt = db_get_prepare_stmt($con, $sql, [$_POST['lot-name'], $category_id, $_POST['message'], $file_url, $_POST['lot-rate'], $_POST['lot-step'], $_POST['lot-date'], 1]); 
		mysqli_stmt_execute($stmt); 
		$result = mysqli_stmt_get_result($stmt);
		$last_id = mysqli_insert_id($con); 
			if (!$last_id) {
			$error = mysqli_error($con);
			print("Ошибка MySQL: " . $error); 
			} 
						
		//Делаем переадресацию на просмотр добавленного лота
		header("Location: lot.php?id=$last_id");
		exit;
		
	//Если есть ошибки в заполнении формы - отправляем массив с ошибками в шаблон	
	} else {
		$errors['check'] = false;
	}
}


$page_content = include_template('add-lot.php', 
['categories' => $categories, 'errors' => $errors]);

$layout_content = include_template('layout.php', 
['content' => $page_content, 'is_auth' => $is_auth, 'user_name' => $user_name, 'categories' => $categories, 'title' => 'Добавление лота', 'main_class' => '']);

print($layout_content);



