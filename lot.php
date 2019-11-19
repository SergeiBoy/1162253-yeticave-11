<?php

require_once('helpers.php'); //Подключение вспомогательных функций
require_once('startup.php'); //Подключение к БД
require_once('data.php'); //Получаем список категорий (из БД) и другие данные

//Получаем лот из БД
if (!isset($_GET['id'])){
	header("HTTP/1.0 404 Not Found");
	exit();
} else {
	$sql = "SELECT lots.id, lot_name, description, img_path, dt_end, initial_price, MAX(bid_price) AS bid_price, bid_step, category_name FROM lots 
	LEFT JOIN categories ON lots.category_id = categories.id
	LEFT JOIN bids ON lots.id = bids.lot_id
	WHERE lots.id = ?
	GROUP BY lots.id, lot_name, description, img_path, dt_end, initial_price, bid_step, category_name";

	$stmt = db_get_prepare_stmt($con, $sql, [$_GET['id']]); 
	mysqli_stmt_execute($stmt); 
	$result = mysqli_stmt_get_result($stmt);
		if (!$result) {
			$error = mysqli_error($con);
			print("Ошибка MySQL: " . $error); 
			} 
	$lot = mysqli_fetch_assoc($result);
	
		if (!$lot['id']){
			header("HTTP/1.0 404 Not Found");
			exit();
		} 
	
	$page_content = include_template('lot.php', 
	['categories' => $categories, 'lot' => $lot]);

	$layout_content = include_template('layout.php', 
	['content' => $page_content, 'is_auth' => $is_auth, 'user_name' => $user_name, 'categories' => $categories, 'title' => $lot['lot_name'], 'main_class' => '']);

	print($layout_content);
}



