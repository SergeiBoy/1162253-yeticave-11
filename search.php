<?php

require_once('helpers.php'); //Подключение вспомогательных функций
require_once('startup.php'); //Подключение к БД и получение из нее категорий

if (!empty($_GET['search'])) {
	//Получаем список лотов из БД
	$sql = "SELECT lots.id, lot_name, initial_price, img_path, MAX(bid_price) AS bid_price, category_name, lots.dt_add, dt_end 
	FROM lots LEFT JOIN categories ON lots.category_id = categories.id
	LEFT JOIN bids ON lots.id = bids.lot_id
	WHERE MATCH(lot_name,description) AGAINST(?)
	GROUP BY lots.id, lot_name, initial_price, img_path, category_name, dt_add, dt_end 
	ORDER BY lots.dt_add DESC";
	
	$stmt = db_get_prepare_stmt($con, $sql, [$_GET['search']]); 
	mysqli_stmt_execute($stmt); 
	$result = mysqli_stmt_get_result($stmt);
		if (!$result) {
			$error = mysqli_error($con);
			print("Ошибка MySQL: " . $error); 
			} 
	$goods_search = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
	$goods_search = [];
}

$page_content = include_template('search.php', 
['categories' => $categories, 'goods' => $goods_search]);

$layout_content = include_template('layout.php', 
['content' => $page_content, 'categories' => $categories, 'title' => 'Результаты поиска', 'main_class' => '']);

print($layout_content);

