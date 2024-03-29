<?php

require_once "vendor/autoload.php";

//Получаем список лотов из БД, время которых вышло, а победитель не определен
$sql = "SELECT id, lot_name FROM lots WHERE dt_end <= CURRENT_TIMESTAMP AND user_id_winner IS NULL";
$lots_time_ended = db_fetch_data($con, $sql);

$winners = [];
$winners_id = [];
foreach ($lots_time_ended as $lot) {
    
    //Находим победителя
    $sql = "SELECT users.id, user_name, email FROM bids LEFT JOIN users ON bids.user_id = users.id
	WHERE bids.lot_id = '".$lot['id']."'
	ORDER BY bid_price DESC";
    $winner = db_fetch_data($con, $sql);
    $winner = $winner[0] ?? null;
    
    if ($winner !== null) {
            
            //Вносим победителя в таблицу
        $sql = "UPDATE lots SET user_id_winner = '".$winner['id']."' WHERE id = '".$lot['id']."'";
        db_insert_data($con, $sql);
                
        //Формируем массив победителей с выигранными ими лотами
        if (!in_array($winner['id'], $winners_id)) {
            $winners_id[] = $winner['id'];
            $winner['lot'][] =  [
                                'id' => $lot['id'],
                                'lot_name' => $lot['lot_name'],
                                ];
            
            $winners[] = $winner;
        } else {
            foreach ($winners as &$win) {
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
    try {
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
            
        $message_content = include_template('email.php', ['winner' => $winner]);
        $message->setBody("$message_content", 'text/html');
            
        // Send the message
        $result = $mailer->send($message);
    } catch (Exception $e) {
        echo "Ошибка отправки электронной почты: ";
        echo $e->getMessage();
    }
}
