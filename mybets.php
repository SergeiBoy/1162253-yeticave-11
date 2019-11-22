<?php

require_once('helpers.php'); //Подключение вспомогательных функций
require_once('startup.php'); //Подключение к БД и получение из нее категорий

	if (!isset($_SESSION['user'])){
	http_response_code(403);
	exit();
	}

//Получаем список ставок
$cur_user_id = $_SESSION['user']['id'];
$sql = "SELECT lots.id, lot_name, img_path, category_name, MAX(bid_price) AS bid_price, 
MAX(bids.dt_add) AS dt_add, MAX(DATE_FORMAT(bids.dt_add, '%d.%m.%y %H:%i')) AS dt_add_format, dt_end, email, contact_info
FROM bids LEFT JOIN lots ON bids.lot_id = lots.id
LEFT JOIN categories ON lots.category_id = categories.id
LEFT JOIN users ON lots.user_id_author = users.id
WHERE bids.user_id = $cur_user_id
GROUP BY lots.id, lot_name, img_path, category_name, dt_end, email, contact_info
ORDER BY dt_end ASC";
$result = mysqli_query($con, $sql);
	if (!$result) {
	$error = mysqli_error($con);
	print("Ошибка MySQL: " . $error); 
	} 
$goods = mysqli_fetch_all($result, MYSQLI_ASSOC);


$page_content = include_template('my-bets.php', 
['categories' => $categories, 'goods' => $goods]);

$layout_content = include_template('layout.php', 
['content' => $page_content, 'categories' => $categories, 'title' => 'Мои ставки', 'main_class' => '']);

print($layout_content);

