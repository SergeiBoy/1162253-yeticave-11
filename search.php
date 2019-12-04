<?php

require_once('helpers.php'); //Подключение вспомогательных функций
require_once('startup.php'); //Подключение к БД и получение из нее категорий

$goods_search = [];	
$pages_quantity = 0;
$cur_page_number = 1;
$search = '';


if (!empty($_GET['search'])) {
	
	$search = trim($_GET['search']);
	
	if ($search !== '') {
	
		$sql = "SELECT COUNT(*) as cnt FROM lots
		WHERE MATCH(lot_name,description) AGAINST(?) AND dt_end > CURRENT_TIMESTAMP";
		
		$stmt = db_get_prepare_stmt($con, $sql, [$search]); 
		mysqli_stmt_execute($stmt); 
		$result = mysqli_stmt_get_result($stmt);
			if (!$result) {
				$error = mysqli_error($con);
				print("Ошибка MySQL: " . $error); 
				} 
		$lots_quantity = mysqli_fetch_assoc($result)['cnt'];
		
		//Рассчитываем пагинацию
		$lots_per_page = 9;
		$pages_quantity = ceil($lots_quantity/$lots_per_page);
		$cur_page_number = intval($_GET['page'] ?? 1);
		$offset = ($cur_page_number - 1) * $lots_per_page;
		
		//Получаем список лотов из БД
		$sql = "SELECT lots.id, lot_name, initial_price, img_path, MAX(bid_price) AS bid_price, category_name, lots.dt_add, dt_end 
		FROM lots LEFT JOIN categories ON lots.category_id = categories.id
		LEFT JOIN bids ON lots.id = bids.lot_id
		WHERE MATCH(lot_name,description) AGAINST(?) AND dt_end > CURRENT_TIMESTAMP
		GROUP BY lots.id, lot_name, initial_price, img_path, category_name, dt_add, dt_end 
		ORDER BY lots.dt_add DESC
		LIMIT $lots_per_page OFFSET $offset";
		
		$stmt = db_get_prepare_stmt($con, $sql, [$search]); 
		mysqli_stmt_execute($stmt); 
		$result = mysqli_stmt_get_result($stmt);
			if (!$result) {
				$error = mysqli_error($con);
				print("Ошибка MySQL: " . $error); 
				} 
		$goods_search = mysqli_fetch_all($result, MYSQLI_ASSOC);
	
	}
} 

$page_content = include_template('search.php', 
['categories' => $categories, 'goods' => $goods_search, 'pages_quantity' => $pages_quantity, 'cur_page_number' => $cur_page_number, 'search' => $search]);

$layout_content = include_template('layout.php', 
['content' => $page_content, 'categories' => $categories, 'title' => 'Результаты поиска', 'main_class' => '']);

print($layout_content);

