<?php

//Проверка email
function is_not_valid_email($messages, $con, $email) {
	$msg = false;
	if (empty($email)) {
		$msg = $messages['fill_it'];
	} else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$msg = $messages['fill_correct'];
	} else {
		$sql = "SELECT id FROM users WHERE email = ?";
		$stmt = db_get_prepare_stmt($con, $sql, [$email]); 
		mysqli_stmt_execute($stmt); 
		$res = mysqli_stmt_get_result($stmt); 
			if (!$res) {
				$error = mysqli_error($con);
				print("Ошибка MySQL: " . $error); 
				$msg = $messages['fill_it'];
			} else if (mysqli_num_rows($res) > 0) {
				$msg = $messages['fill_another_email'];
			}
	}
	return $msg;
}

//Добавление пользователя
function add_user($con, $password, $email, $name, $message) {
	$passwordHash = password_hash($password, PASSWORD_DEFAULT);
	$sql = "INSERT INTO users (dt_reg, email, user_name, password, contact_info) 
			VALUES (NOW(), ?, ?, '$passwordHash', ?)";
	$stmt = db_get_prepare_stmt($con, $sql, [$email, $name, $message]); 
	$res = mysqli_stmt_execute($stmt); 
		if (!$res) {
			$error = mysqli_error($con);
			print("Ошибка MySQL: " . $error); 
		} 
}


