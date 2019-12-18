<?php

/**
 * Проверяет email при регистрации
 *
 * @param array $messages Массив сообщений об ошибках заполнения форм
 * @param $con mysqli Ресурс соединения
 * @param string $email email пользователя
 *
 * @return string сообщение об ошибке заполнения формы, иначе bool false
 */
function is_not_valid_email($messages, $con, $email)
{
    if (empty($email)) {
        return $messages['fill_it'];
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return $messages['fill_correct'];
    }
    
    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = db_get_prepare_stmt($con, $sql, [$email]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($result === false) {
        $error = mysqli_error($con);
        print("Ошибка MySQL: " . $error);
        return $messages['fill_it'];
    }
    if (mysqli_num_rows($result) > 0) {
        return $messages['fill_another_email'];
    }
    
    return false;
}


/**
 * Добавляет пользователя в базу данных
 *
 * @param $con mysqli Ресурс соединения
 * @param string $pasword Пароль пользователя
 * @param string $email email пользователя
 * @param string $name Имя пользователя
 * @param string $message Контактная информация пользователя
 *
 * @return bool true Если добавление в БД успешно, иначе false
 */
function add_user($con, $password, $email, $name, $message)
{
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (dt_reg, email, user_name, password, contact_info) 
			VALUES (NOW(), ?, ?, '$passwordHash', ?)";
    $stmt = db_get_prepare_stmt($con, $sql, [$email, $name, $message]);
    $result = mysqli_stmt_execute($stmt);
    if (!$result) {
        $error = mysqli_error($con);
        print("Ошибка MySQL: " . $error);
        return false;
    }
    return true;
}
