<?php

require_once('helpers.php'); //Подключение вспомогательных функций
require_once('data.php'); //Данные для валидации форм
require_once('startup.php'); //Подключение к БД и получение из нее категорий

$id = 0;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_GET['id'])) {
        header("HTTP/1.0 404 Not Found");
        exit();
    }
    $id = intval($_GET['id']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user']['id'])) {
        http_response_code(403);
        exit();
    }
    $id = $_SESSION['good_id'] ?? 0;
}

//Получаем лот из БД
$sql = "SELECT lots.id, lot_name, description, img_path, dt_end, initial_price, MAX(bid_price) AS bid_price, bid_step, category_name, user_id_author FROM lots 
LEFT JOIN categories ON lots.category_id = categories.id
LEFT JOIN bids ON lots.id = bids.lot_id
WHERE lots.id = ?
GROUP BY lots.id, lot_name, description, img_path, dt_end, initial_price, bid_step, category_name, user_id_author";
$lot = db_fetch_data($con, $sql, [$id]);
if (!isset($lot[0])) {
    header("HTTP/1.0 404 Not Found");
    exit();
}
$lot = $lot[0];

$_SESSION['good_id'] = intval($lot['id']);

//Создаем массив ошибок
$errors = [];
//В случае получения новой цены
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cost = $_POST['cost'] ?? '';
    if (empty($cost)) {
        $errors['cost'] = $messages['fill_it'];
    } elseif (!is_positive_integer($cost)) {
        $errors['cost'] = $messages['enter_int'];
    } elseif ($cost < ($lot['bid_price'] ? ($lot['bid_price'] + $lot['bid_step']) : ($lot['initial_price'] + $lot['bid_step']))) {
        $errors['cost'] = $messages['fill_correct'];
    }
    
    if (!count($errors)) {
        $sql = "INSERT INTO bids (dt_add, bid_price, user_id, lot_id) 
				VALUES (NOW(), ?, ?, ?)";
        db_insert_data($con, $sql, [$cost, $_SESSION['user']['id'], $id]);
        //Делаем переадресацию на эту же страницу методом GET
        header("Location: lot.php?id=$id");
        exit();
    }
    
    //Если есть ошибки в заполнении формы - отправляем массив с ошибками в шаблон
    $errors['check'] = false;
}

//Получаем историю ставок
$sql = "SELECT dt_add, DATE_FORMAT(dt_add, '%d.%m.%y %H:%i') AS dt_add_format, bid_price, user_name, user_id FROM bids
LEFT JOIN users ON bids.user_id = users.id
WHERE lot_id = ?
ORDER BY bid_price DESC";
$history = db_fetch_data($con, $sql, [$id]);

//Логика показа блока добавления ставок
$is_bidding_show = true;
if (!isset($_SESSION['user']['id']) || strtotime($lot['dt_end']) < time()) {
    $is_bidding_show = false;
} elseif (intval($_SESSION['user']['id']) === intval($lot['user_id_author']) || intval($_SESSION['user']['id']) === intval($history[0]['user_id'] ?? 0)) {
    $is_bidding_show = false;
}

$page_content = include_template(
    'lot.php',
    ['categories' => $categories, 'lot' => $lot, 'errors' => $errors, 'history' => $history, 'is_bidding_show' => $is_bidding_show]
);

$layout_content = include_template(
    'layout.php',
    ['content' => $page_content, 'categories' => $categories, 'title' => $lot['lot_name'], 'main_class' => '']
);

print($layout_content);
