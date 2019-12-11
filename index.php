<?php

require_once('helpers.php'); //Подключение вспомогательных функций
require_once('startup.php'); //Подключение к БД и получение из нее категорий
require_once('getwinner.php'); //Подключение сценария определения победителя

//Получаем список лотов из БД
$sql = "SELECT lots.id, lot_name, initial_price, img_path, MAX(bid_price) AS bid_price, category_name, lots.dt_add, dt_end 
FROM lots LEFT JOIN categories ON lots.category_id = categories.id
LEFT JOIN bids ON lots.id = bids.lot_id
WHERE dt_end > CURRENT_TIMESTAMP
GROUP BY lots.id, lot_name, initial_price, img_path, category_name, dt_add, dt_end 
ORDER BY lots.dt_add DESC";
$goods = db_fetch_data($con, $sql);


$page_content = include_template(
    'main.php',
    ['categories' => $categories, 'goods' => $goods]
);

$layout_content = include_template(
    'layout.php',
    ['content' => $page_content, 'categories' => $categories, 'title' => 'Главная', 'main_class' => 'container']
);

print($layout_content);
