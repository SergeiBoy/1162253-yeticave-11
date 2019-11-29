<?php

require_once('helpers.php'); //Подключение вспомогательных функций
require_once('startup.php'); //Подключение к БД и получение из нее категорий

$cur_category_id = $_GET['category_id'] ?? 1;
$cur_category_id = intval($cur_category_id);

foreach ($categories as &$category) {
	if ( $cur_category_id === intval($category['id']) ) {
		$category['cur_category'] = true;
		break;
	}
}

//Получаем количество лотов
$sql = "SELECT COUNT(*) as cnt FROM lots LEFT JOIN categories ON lots.category_id = categories.id
WHERE dt_end > CURRENT_TIMESTAMP AND category_id = ?";

$stmt = db_get_prepare_stmt($con, $sql, [$cur_category_id]); 
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
$cur_page_number = $_GET['page'] ?? 1;
$cur_page_number = intval($cur_page_number);
$offset = ($cur_page_number - 1) * $lots_per_page;

//Получаем список лотов из БД для конкретной страницы
$sql = "SELECT lots.id, lot_name, initial_price, img_path, MAX(bid_price) AS bid_price, COUNT(bids.id) AS count_bids, category_name, categories.id AS category_id, lots.dt_add, dt_end 
FROM lots LEFT JOIN categories ON lots.category_id = categories.id
LEFT JOIN bids ON lots.id = bids.lot_id
WHERE dt_end > CURRENT_TIMESTAMP AND category_id = ?
GROUP BY lots.id, lot_name, initial_price, img_path, category_name, category_id, dt_add, dt_end 
ORDER BY lots.dt_add DESC
LIMIT $lots_per_page OFFSET $offset";

$stmt = db_get_prepare_stmt($con, $sql, [$cur_category_id]); 
mysqli_stmt_execute($stmt); 
$result = mysqli_stmt_get_result($stmt);
	if (!$result) {
		$error = mysqli_error($con);
		print("Ошибка MySQL: " . $error); 
		} 
$goods = mysqli_fetch_all($result, MYSQLI_ASSOC);


$page_content = include_template('all-lots.php', 
['categories' => $categories, 'goods' => $goods, 'cur_category_id' => $cur_category_id, 'pages_quantity' => $pages_quantity, 'cur_page_number' => $cur_page_number]);

$layout_content = include_template('layout.php', 
['content' => $page_content, 'categories' => $categories, 'title' => 'Все лоты', 'main_class' => '']);

print($layout_content);

