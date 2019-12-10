<?php

require_once('helpers.php'); //Подключение вспомогательных функций
require_once('startup.php'); //Подключение к БД и получение из нее категорий

$current_category_id = intval($_GET['category_id'] ?? 1);

foreach ($categories as &$category) {
    if ($current_category_id === intval($category['id'])) {
        $category['current_category'] = true;
        break;
    }
}

//Получаем количество лотов
$sql = "SELECT COUNT(*) as count FROM lots LEFT JOIN categories ON lots.category_id = categories.id
WHERE dt_end > CURRENT_TIMESTAMP AND category_id = ?";
$lots_quantity = db_fetch_data($con, $sql, [$current_category_id]);
$lots_quantity = $lots_quantity[0]['count'] ?? 0;

//Рассчитываем пагинацию
$lots_per_page = 9;
$pages_quantity = ceil($lots_quantity/$lots_per_page);
$current_page_number = intval($_GET['page'] ?? 1);
$offset = ($current_page_number - 1) * $lots_per_page;

//Получаем список лотов из БД для конкретной страницы
$sql = "SELECT lots.id, lot_name, initial_price, img_path, MAX(bid_price) AS bid_price, COUNT(bids.id) AS count_bids, category_name, categories.id AS category_id, lots.dt_add, dt_end 
FROM lots LEFT JOIN categories ON lots.category_id = categories.id
LEFT JOIN bids ON lots.id = bids.lot_id
WHERE dt_end > CURRENT_TIMESTAMP AND category_id = ?
GROUP BY lots.id, lot_name, initial_price, img_path, category_name, category_id, dt_add, dt_end 
ORDER BY lots.dt_add DESC
LIMIT $lots_per_page OFFSET $offset";
$goods = db_fetch_data($con, $sql, [$current_category_id]);


$page_content = include_template(
    'all-lots.php',
    ['categories' => $categories, 'goods' => $goods, 'current_category_id' => $current_category_id, 'pages_quantity' => $pages_quantity, 'current_page_number' => $current_page_number]
);

$layout_content = include_template(
    'layout.php',
    ['content' => $page_content, 'categories' => $categories, 'title' => 'Все лоты', 'main_class' => '']
);

print($layout_content);
