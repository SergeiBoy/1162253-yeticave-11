<?php

//Установка констант
const SEC_IN_DAY = 86400;

//Массив сообщений
$messages = [
	'fill_it' => 'Заполните это поле',
	'category' => 'Выберите категорию',
	'enter_number' => 'Введите число',
	'enter_int' => 'Введите целое число',
	'enter_date' => 'Введите дату завершения торгов, не меньше суток вперед',
	'add_file' => 'Добавьте файл в формате jpg, jpeg, png'
];

//Массив разрешенных форматов для картинок
$picture_types = ['image/png', 'image/jpeg'];

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


