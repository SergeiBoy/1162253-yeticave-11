<?php

session_start();

	//Подключение к БД
$con = mysqli_connect("localhost", "root", "", "yeticave");
	if ($con == false){
	print("Ошибка подключения: " . mysqli_connect_error()); 
	}
mysqli_set_charset($con, "utf8");

//Получаем список категорий из БД
$sql = "SELECT * FROM categories";
$result = mysqli_query($con, $sql);
	if (!$result) {
	$error = mysqli_error($con);
	print("Ошибка MySQL: " . $error); 
	} 
$categories = mysqli_fetch_all($result, MYSQLI_ASSOC);

