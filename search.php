<?php

require_once('helpers.php'); //Подключение вспомогательных функций
require_once('startup.php'); //Подключение к БД и получение из нее категорий

$goods_search = [];
$pages_quantity = 0;
$current_page_number = 1;
$search = '';


if (!empty($_GET['search'])) {
    $search = trim($_GET['search']);
    
    if ($search !== '') {
        $sql = "SELECT COUNT(*) as count FROM lots
		WHERE MATCH(lot_name,description) AGAINST(?) AND dt_end > CURRENT_TIMESTAMP";
        $lots_quantity = db_fetch_data($con, $sql, [$search]);
        $lots_quantity = $lots_quantity[0]['count'] ?? 0;
        
        //Рассчитываем пагинацию
        $lots_per_page = 9;
        $pages_quantity = ceil($lots_quantity/$lots_per_page);
        $current_page_number = intval($_GET['page'] ?? 1);
        $offset = ($current_page_number - 1) * $lots_per_page;
        
        //Получаем список лотов из БД
        $sql = "SELECT lots.id, lot_name, initial_price, img_path, MAX(bid_price) AS bid_price, category_name, lots.dt_add, dt_end 
		FROM lots LEFT JOIN categories ON lots.category_id = categories.id
		LEFT JOIN bids ON lots.id = bids.lot_id
		WHERE MATCH(lot_name, description) AGAINST(?) AND dt_end > CURRENT_TIMESTAMP
		GROUP BY lots.id, lot_name, initial_price, img_path, category_name, dt_add, dt_end 
		ORDER BY lots.dt_add DESC
		LIMIT $lots_per_page OFFSET $offset";
        $goods_search = db_fetch_data($con, $sql, [$search]);
    }
}

$page_content = include_template(
    'search.php',
    ['categories' => $categories, 'goods' => $goods_search, 'pages_quantity' => $pages_quantity, 'current_page_number' => $current_page_number, 'search' => $search]
);

$layout_content = include_template(
    'layout.php',
    ['content' => $page_content, 'categories' => $categories, 'title' => 'Результаты поиска', 'main_class' => '']
);

print($layout_content);
