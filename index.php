<?php

require_once('helpers.php');

$is_auth = rand(0, 1);

$user_name = 'Сергей'; // укажите здесь ваше имя

$categories = ["Доски и лыжи", "Крепления", "Ботинки", "Одежда", "Инструменты", "Разное"];

$goods = [
	[
		'name' => '2014 Rossignol District Snowboard',
		'category' => 'Доски и лыжи',
		'price' => '10999',
		'img_path' => 'img/lot-1.jpg',
		'deadline_date' => '2019-11-06'
	],
	[
		'name' => 'DC Ply Mens 2016/2017 Snowboard',
		'category' => 'Доски и лыжи',
		'price' => '159999',
		'img_path' => 'img/lot-2.jpg',
		'deadline_date' => '2019-11-03'
	],
	[
		'name' => 'Крепления Union Contact Pro 2015 года размер L/XL',
		'category' => 'Крепления',
		'price' => '8000',
		'img_path' => 'img/lot-3.jpg',
		'deadline_date' => '2019-11-05'
	],
	[
		'name' => 'Ботинки для сноуборда DC Mutiny Charocal',
		'category' => 'Ботинки',
		'price' => '10999',
		'img_path' => 'img/lot-4.jpg',
		'deadline_date' => '2019-10-31'
	],
	[
		'name' => 'Куртка для сноуборда DC Mutiny Charocal',
		'category' => 'Одежда',
		'price' => '7500',
		'img_path' => 'img/lot-5.jpg',
		'deadline_date' => '2019-11-18'
	],
	[
		'name' => 'Маска Oakley Canopy',
		'category' => 'Разное',
		'price' => '5400',
		'img_path' => 'img/lot-6.jpg',
		'deadline_date' => '2019-11-02'
	]
];



$page_content = include_template('main.php', 
['categories' => $categories, 'goods' => $goods]);

$layout_content = include_template('layout.php', 
['content' => $page_content, 'is_auth' => $is_auth, 'user_name' => $user_name, 'categories' => $categories, 'title' => 'Главная']);

print($layout_content);



