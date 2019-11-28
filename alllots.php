<?php

require_once('helpers.php'); //Подключение вспомогательных функций
require_once('startup.php'); //Подключение к БД и получение из нее категорий

if ( isset($_GET['id']) ) {

	//Получаем список лотов из БД
	$sql = "SELECT lots.id, lot_name, initial_price, img_path, MAX(bid_price) AS bid_price, COUNT(bids.id) AS count_bids, category_name, categories.id AS category_id, lots.dt_add, dt_end 
	FROM lots LEFT JOIN categories ON lots.category_id = categories.id
	LEFT JOIN bids ON lots.id = bids.lot_id
	WHERE dt_end > CURRENT_TIMESTAMP AND category_id = '".$_GET['id'].
	"' GROUP BY lots.id, lot_name, initial_price, img_path, category_name, category_id, dt_add, dt_end 
	ORDER BY lots.dt_add DESC";
	$result = mysqli_query($con, $sql);
		if (!$result) {
		$error = mysqli_error($con);
		print("Ошибка MySQL: " . $error); 
		} 
	$goods = mysqli_fetch_all($result, MYSQLI_ASSOC);

	foreach ($categories as &$category) {
		if ( intval($_GET['id']) === intval($category['id']) ) {
			$category['cur_category'] = true;
			break;
		}
	}
}

$page_content = include_template('all-lots.php', 
['categories' => $categories, 'goods' => $goods]);

$layout_content = include_template('layout.php', 
['content' => $page_content, 'categories' => $categories, 'title' => 'Все лоты', 'main_class' => '']);

print($layout_content);

