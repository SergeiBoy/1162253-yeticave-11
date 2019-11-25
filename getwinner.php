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
	
	//Если ставка найдена - отправляем письмо победителю и вносим его в таблицу
	if ($winner !== NULL) {
		
		//Вносим победителя в таблицу
		$winner_id = $winner['id'];
		$sql = "UPDATE lots SET user_id_winner = '$winner_id' WHERE id = '$id'";
		$result = mysqli_query($con, $sql);
			if (!$result) {
			$error = mysqli_error($con);
			print("Ошибка MySQL: " . $error); 
			} 		
				
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
		
		$msg_content = include_template('email.php', 
		['lot' => $lot, 'winner' => $winner]);
		$message->setBody("$msg_content", 'text/html');
		
		// Send the message
		$result = $mailer->send($message);
		
	}
}



