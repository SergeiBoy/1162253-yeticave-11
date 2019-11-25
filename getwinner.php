<?php

require_once "vendor/autoload.php"; 

//Получаем список лотов из БД, время которых вышло, а победитель не определен
$sql = "SELECT id, lot_name FROM lots WHERE dt_end <= CURRENT_TIMESTAMP AND user_id_winner IS NULL";
$result = mysqli_query($con, $sql);
	if (!$result) {
	$error = mysqli_error($con);
	print("Ошибка MySQL: " . $error); 
	} 
$lots_time_ended = mysqli_fetch_all($result, MYSQLI_ASSOC);

$winners = [];
$winners_id = [];
foreach ($lots_time_ended as $lot) {
	$id = $lot['id'];
	$sql = "SELECT users.id, user_name, email FROM bids LEFT JOIN users ON bids.user_id = users.id
	WHERE bids.lot_id = '$id'
	ORDER BY bid_price DESC";
	$result = mysqli_query($con, $sql);
		if (!$result) {
		$error = mysqli_error($con);
		print("Ошибка MySQL: " . $error); 
		} 
	$winner = mysqli_fetch_assoc($result);
	
		if ($winner !== NULL) {
			
			//Вносим победителя в таблицу
		$sql = "UPDATE lots SET user_id_winner = ".$winner['id']." WHERE id = '$id'";
		$result = mysqli_query($con, $sql);
			if (!$result) {
			$error = mysqli_error($con);
			print("Ошибка MySQL: " . $error); 
			} 		
		//Формируем массив победителей с выигранными ими лотами
		if ( !in_array($winner['id'], $winners_id) ) {
			$winners_id[] = $winner['id'];
			$winner['lot'][] =  [
								'id' => $lot['id'],
								'lot_name' => $lot['lot_name'],
								];
			
			$winners[] = $winner;
		} else {
			foreach ($winners as &$win){
				if ($win['id'] === $winner['id']) {
					$win['lot'][] = [
									'id' => $lot['id'],
									'lot_name' => $lot['lot_name'],
									];
					break;
				}
			}
		}
	}	
}

	//Отправляем письма
foreach ($winners as $winner) {
				
		// Create the Transport
		$transport = (new Swift_SmtpTransport('phpdemo.ru', 25))
		  ->setUsername('keks@phpdemo.ru')
		  ->setPassword('htmlacademy')
		;

		// Create the Mailer using your created Transport
		$mailer = new Swift_Mailer($transport);

		// Create a message
		$winner_email = $winner['email'];
		$winner_name = $winner['user_name'];
				
		$message = new Swift_Message();
		$message->setSubject("Ваша ставка победила");
		$message->setFrom(['keks@phpdemo.ru' => 'Keks']);
		$message->setTo(["$winner_email" => "$winner_name"]);
		
		$msg_content = include_template('email.php', ['winner' => $winner]);
		$message->setBody("$msg_content", 'text/html');
		
		// Send the message
		$result = $mailer->send($message);
		
}



