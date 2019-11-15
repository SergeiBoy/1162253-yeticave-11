<?php

require_once('helpers.php'); //Подключение вспомогательных функций
require_once('startup.php'); //Подключение к БД
require_once('data.php'); //Получаем список категорий (из БД) и другие данные

//Получаем список лотов из БД
$sql = "SELECT lots.id, lot_name, initial_price, img_path, MAX(bid_price) AS bid_price, category_name, lots.dt_add, dt_end 
FROM lots LEFT JOIN categories ON lots.category_id = categories.id
LEFT JOIN bids ON lots.id = bids.lot_id
WHERE dt_end > CURRENT_TIMESTAMP
GROUP BY lots.id, lot_name, initial_price, img_path, category_name, dt_add, dt_end 
ORDER BY lots.dt_add DESC";
$result = mysqli_query($con, $sql);
	if (!$result) {
	$error = mysqli_error($con);
	print("Ошибка MySQL: " . $error); 
	} 
$goods = mysqli_fetch_all($result, MYSQLI_ASSOC);


$page_content = include_template('main.php', 
['categories' => $categories, 'goods' => $goods]);

$layout_content = include_template('layout.php', 
['content' => $page_content, 'is_auth' => $is_auth, 'user_name' => $user_name, 'categories' => $categories, 'title' => 'Главная', 'main_class' => 'container']);

print($layout_content);

