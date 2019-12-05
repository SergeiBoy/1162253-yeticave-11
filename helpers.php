<?php
/**
 * Проверяет переданную дату на соответствие формату 'ГГГГ-ММ-ДД'
 *
 * Примеры использования:
 * is_date_valid('2019-01-01'); // true
 * is_date_valid('2016-02-29'); // true
 * is_date_valid('2019-04-31'); // false
 * is_date_valid('10.10.2010'); // false
 * is_date_valid('10/10/2010'); // false
 *
 * @param string $date Дата в виде строки
 *
 * @return bool true при совпадении с форматом 'ГГГГ-ММ-ДД', иначе false
 */
function is_date_valid(string $date) : bool
{
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = [])
{
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
        die($errorMsg);
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 's';

            if (is_int($value)) {
                $type = 'i';
            } elseif (is_string($value)) {
                $type = 's';
            } elseif (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

        if (mysqli_errno($link) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
            die($errorMsg);
        }
    }

    return $stmt;
}

/**
 * Возвращает корректную форму множественного числа
 * Ограничения: только для целых чисел
 *
 * Пример использования:
 * $remaining_minutes = 5;
 * echo "Я поставил таймер на {$remaining_minutes} " .
 *     get_noun_plural_form(
 *         $remaining_minutes,
 *         'минута',
 *         'минуты',
 *         'минут'
 *     );
 * Результат: "Я поставил таймер на 5 минут"
 *
 * @param int $number Число, по которому вычисляем форму множественного числа
 * @param string $one Форма единственного числа: яблоко, час, минута
 * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 * @param string $many Форма множественного числа для остальных чисел
 *
 * @return string Рассчитанная форма множественнго числа
 */
function get_noun_plural_form(int $number, string $one, string $two, string $many): string
{
    $number = (int) $number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    switch (true) {
        case ($mod100 >= 11 && $mod100 <= 20):
            return $many;

        case ($mod10 > 5):
            return $many;

        case ($mod10 === 1):
            return $one;

        case ($mod10 >= 2 && $mod10 <= 4):
            return $two;

        default:
            return $many;
    }
}

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */
function include_template($name, array $data = [])
{
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}

/**
 * Форматирует цену (на главной странице)
 *
 * @param float $price Цена
 *
 * @return string Цена, округленная до ближайшего большего целого, с добавлением символа рубля
 */
function set_price($price)
{
    return number_format(ceil($price), 0, ',', ' ').' ₽';
}

/**
 * Определяет часы и минуты до окончания аукциона
 *
 * @param string $deadline_date Дата в формате YYYY-MM-DD
 *
 * @return array Остающиеся до $deadline_date часы и минуты
 */
function get_time_remaining($deadline_date)
{
    $time_remaining = strtotime($deadline_date) - time();
    $hours_remaining = str_pad(floor($time_remaining/3600), 2, "0", STR_PAD_LEFT);
    $minutes_remaining = str_pad(floor(($time_remaining%3600)/60), 2, "0", STR_PAD_LEFT);
    return [$hours_remaining, $minutes_remaining];
}

/**
 * Определяет сколько времени назад была сделана ставка
 *
 * @param string $dt_add Дата в формате YYYY-MM-DD HH:ii:ss
 *
 * @return string Если прошло меньше 24 часов, то строка, например "5 часов 18 минут назад",
 *                иначе возвращает NULL
 */
function get_time_since_adding($dt_add)
{
    $time_since = time() - strtotime($dt_add);
    $H = floor($time_since/3600);
    $M = floor(($time_since%3600)/60);
    if ($H < 24) {
        if ($H%20 >= 2 && $H%20 <= 4) {
            $HF = 'часа';
        } elseif ($H%20 == 1) {
            $HF = 'час';
        } else {
            $HF = 'часов';
        }
            
        if ($M%100 >= 11 && $M%100 <= 20) {
            $MF = 'минут';
        } elseif ($M%10 > 5) {
            $MF = 'минут';
        } elseif ($M%10 === 1) {
            $MF = 'минута';
        } elseif ($M%10 >= 2 && $M%10 <= 4) {
            $MF = 'минуты';
        } else {
            $MF = 'минут';
        }
        
        return ("$H $HF $M $MF назад");
    }
    
    return null;
}

/**
 * Определяет является ли переданное значение положительным числом
 *
 * @param float/int/string $num Число
 *
 * @return bool true, если является, иначе false
 */
function is_positive_number($num)
{
    return (filter_var($num, FILTER_VALIDATE_FLOAT) && filter_var($num, FILTER_VALIDATE_FLOAT)>0);
}

/**
 * Определяет является ли переданное значение положительным целым числом
 *
 * @param float/int/string $num Число
 *
 * @return bool true, если является, иначе false
 */
function is_positive_integer($num)
{
    return (filter_var($num, FILTER_VALIDATE_INT) && filter_var($num, FILTER_VALIDATE_INT)>0);
}

/**
 * Выполняет аутентификацию
 *
 * @param $con mysqli Ресурс соединения
 * @param string $email email пользователя
 * @param string $pasword Пароль пользователя
 *
 * @return array с данными пользователя, если email и пароль правильные, иначе bool false
 */
function is_pass($con, $email, $password)
{
    $sql = "SELECT id, user_name, password FROM users WHERE email = ?";
    $stmt = db_get_prepare_stmt($con, $sql, [$email]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (!$result) {
        $error = mysqli_error($con);
        print("Ошибка MySQL: " . $error);
    }
    $user = mysqli_fetch_assoc($result);
    if (!$user) {
        $user = false;
    } elseif (!password_verify($password, $user['password'])) {
        $user = false;
    }

    return $user;
}
