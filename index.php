<?php

require_once('helpers.php'); //Подключение вспомогательных функций
require_once('startup.php'); //Подключение к БД

//Установка использующихся в коде переменных
$is_auth = rand(0, 1);
$user_name = 'Сергей'; // укажите здесь ваше имя

//Получаем список лотов из БД
$sql = "SELECT lot_name, initial_price, img_path, MAX(bid_price) AS bid_price, category_name, lots.dt_add, dt_end 
FROM lots LEFT JOIN categories ON lots.category_id = categories.id
LEFT JOIN bids ON lots.id = bids.lot_id
WHERE dt_end > CURRENT_TIMESTAMP
GROUP BY lot_name, initial_price, img_path, category_name, dt_add, dt_end 
ORDER BY lots.dt_add DESC";
$result = mysqli_query($con, $sql);
	if (!$result) {
	$error = mysqli_error($con);
	print("Ошибка MySQL: " . $error); 
	} 
$goods = mysqli_fetch_all($result, MYSQLI_ASSOC);

//Получаем список категорий из БД
$sql = "SELECT * FROM categories";
$result = mysqli_query($con, $sql);
	if (!$result) {
	$error = mysqli_error($con);
	print("Ошибка MySQL: " . $error); 
	} 
$categories = mysqli_fetch_all($result, MYSQLI_ASSOC);

$page_content = include_template('main.php', 
['categories' => $categories, 'goods' => $goods]);

$layout_content = include_template('layout.php', 
['content' => $page_content, 'is_auth' => $is_auth, 'user_name' => $user_name, 'categories' => $categories, 'title' => 'Главная']);

print($layout_content);



