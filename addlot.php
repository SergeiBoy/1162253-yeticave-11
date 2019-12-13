<?php

require_once('helpers.php'); //Подключение вспомогательных функций
require_once('data.php'); //Данные для валидации форм
require_once('startup.php'); //Подключение к БД и получение из нее категорий

//Закрываем доступ для анонимных пользователей
if (!isset($_SESSION['user']['id'])) {
    http_response_code(403);
    exit();
}

//Создаем массив ошибок
$errors = [];
//Проверяем, что форма отправлена
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
    //Проверяем поля формы на пустоту
    $required_fields = ['lot-name', 'message'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] = $messages['fill_it'];
        }
    }
    //Проверяем, чтобы была указана категория, и определяем id выбранной категории
    $category_id = null;
    if (empty($_POST['category'])) {
        $errors['category'] = $messages['category'];
    } else {
        foreach ($categories as $category) {
            if ($category['category_name'] === $_POST['category']) {
                $category_id = $category['id'];
                break;
            }
        }
        if (!$category_id) {
            $errors['category'] = $messages['category'];
        }
    }
    //Проверяем, что начальная цена - число больше нуля
    $lot_rate = $_POST['lot-rate'] ?? '';
    if (!is_positive_number($lot_rate)) {
        $errors['lot-rate'] = $messages['enter_number'];
    }
    //Проверяем, что шаг ставки - целое число больше нуля
    $lot_step = $_POST['lot-step'] ?? '';
    if (!is_positive_integer($lot_step)) {
        $errors['lot-step'] = $messages['enter_int'];
    }
    //Проверяем формат даты
    $lot_date = $_POST['lot-date'] ?? '';
    if (!is_date_valid($lot_date) || !(strtotime($lot_date) - strtotime('now') > SEC_IN_DAY)) {
        $errors['lot-date'] = $messages['enter_date'];
    }
    //Проверяем загруженный файл - что он есть и что его MIME-тип соответствует «image/png», «image/jpeg»
    if (empty($_FILES['file']['tmp_name'])) {
        $errors['file'] = $messages['add_file'];
    } elseif (!in_array(mime_content_type($_FILES['file']['tmp_name']), $picture_types)) {
        $errors['file'] = $messages['add_file'];
    }
    //Если ошибок нет - перемещаем файл изображения в uploads, добавляем лот в БД, делаем переадресацию на просмотр добавленного лота
    if (!count($errors)) {
        $file_url = 'uploads/'.uniqid().'.'.pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        move_uploaded_file($_FILES['file']['tmp_name'], $file_url);
        //Записываем лот в БД
        $sql = "INSERT INTO lots (dt_add, lot_name, category_id, description, img_path, initial_price, bid_step, dt_end, user_id_author) 
				VALUES (NOW(), ?, ?, ?, ?, ?, ?, ?, ?)";
        $last_id = db_insert_data($con, $sql, [htmlspecialchars($_POST['lot-name']), $category_id, htmlspecialchars($_POST['message']),
                    $file_url, $_POST['lot-rate'], $_POST['lot-step'], $_POST['lot-date'], $_SESSION['user']['id']]);
        if ($last_id) {
            header("Location: lot.php?id=$last_id");
            exit();
        }
    }
    
    //Если есть ошибки в заполнении формы - отправляем массив с ошибками в шаблон
    $errors['check'] = false;
}


$page_content = include_template(
    'add-lot.php',
    ['categories' => $categories, 'errors' => $errors]
);

$layout_content = include_template(
    'layout.php',
    ['content' => $page_content, 'categories' => $categories, 'title' => 'Добавление лота', 'main_class' => '']
);

print($layout_content);
