/* Добавляем существующий список категорий */
INSERT INTO categories
(category_name, symbol_code) VALUES ('Доски и лыжи','boards');
INSERT INTO categories
(category_name, symbol_code) VALUES ('Крепления','attachment');
INSERT INTO categories
(category_name, symbol_code) VALUES ('Ботинки','boots');
INSERT INTO categories
(category_name, symbol_code) VALUES ('Одежда','clothing');
INSERT INTO categories
(category_name, symbol_code) VALUES ('Инструменты','tools');
INSERT INTO categories
(category_name, symbol_code) VALUES ('Разное','other');

/* Добавляем пару пользователей */
INSERT INTO users (dt_reg, email, user_name, password, contact_info) 
VALUES ('2018-05-12', 'kostya@mail.ru', 'Костя', '1234', 'Пишите на email');
INSERT INTO users (dt_reg, email, user_name, password, contact_info) 
VALUES ('2018-10-15', 'ivan@gmail.com', 'Иван', '5678', 'Тел. 256-17-34');

/* Добавляем существующий список объявлений */
INSERT INTO lots (dt_add, lot_name, img_path, initial_price, dt_end, bid_step, user_id_author, category_id) 
VALUES ('2019-10-25', '2014 Rossignol District Snowboard', 'img/lot-1.jpg', 10999, '2019-12-25', 100, 1, 1);
INSERT INTO lots (dt_add, lot_name, img_path, initial_price, dt_end, bid_step, user_id_author, category_id) 
VALUES ('2019-10-20', 'DC Ply Mens 2016/2017 Snowboard', 'img/lot-2.jpg', 159999, '2019-12-28', 200, 2, 1);
INSERT INTO lots (dt_add, lot_name, img_path, initial_price, dt_end, bid_step, user_id_author, category_id) 
VALUES ('2019-10-30', 'Крепления Union Contact Pro 2015 года размер L/XL', 'img/lot-3.jpg', 8000, '2019-12-30', 100, 2, 2);
INSERT INTO lots (dt_add, lot_name, img_path, initial_price, dt_end, bid_step, user_id_author, category_id) 
VALUES ('2019-11-01', 'Ботинки для сноуборда DC Mutiny Charocal', 'img/lot-4.jpg', 10999, '2019-12-29', 100, 1, 3);
INSERT INTO lots (dt_add, lot_name, img_path, initial_price, dt_end, bid_step, user_id_author, category_id) 
VALUES ('2019-10-01', 'Куртка для сноуборда DC Mutiny Charocal', 'img/lot-5.jpg', 7500, '2019-12-30', 50, 2, 4);
INSERT INTO lots (dt_add, lot_name, img_path, initial_price, dt_end, bid_step, user_id_author, category_id) 
VALUES ('2019-10-15', 'Маска Oakley Canopy', 'img/lot-6.jpg', 5400, '2019-12-30', 10, 1, 6);

/* Добавляем пару ставок для любого объявления */
INSERT INTO bids (bid_price, user_id, lot_id) 
VALUES (8100, 2, 3);
INSERT INTO bids (bid_price, user_id, lot_id) 
VALUES (11099, 1, 1);

/* Получаем все категории */
SELECT * FROM categories;

/* Получаем самые новые, открытые лоты. Каждый лот должен включать название, стартовую цену, ссылку на изображение, цену, название категории  */
SELECT lot_name, initial_price, img_path, bid_price, category_name 
FROM lots LEFT JOIN bids ON lots.id = bids.lot_id
INNER JOIN categories ON lots.category_id = categories.id
WHERE dt_end > CURRENT_TIMESTAMP;

/* Показываем лот по его id. Получаем также название категории, к которой принадлежит лот */
SELECT lots.id, dt_add, lot_name, description, img_path, initial_price, dt_end, bid_step, user_id_author, user_id_winner, category_name  
FROM lots INNER JOIN categories ON lots.category_id = categories.id WHERE lots.id = 3;

/* Обновляем название лота по его идентификатору  */
UPDATE lots SET lot_name = 'Горнолыжное оборудование'
WHERE id = 2;
UPDATE lots SET lot_name = 'DC Ply Mens 2016/2017 Snowboard'
WHERE id = 2;

/* Получаем список ставок для лота по его идентификатору с сортировкой по дате  */
SELECT * FROM bids WHERE lot_id = 3 ORDER BY dt_add DESC;


