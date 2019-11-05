CREATE DATABASE yeticave
DEFAULT CHARACTER SET utf8
DEFAULT COLLATE utf8_general_ci;
USE yeticave;

CREATE TABLE categories (
id				INT AUTO_INCREMENT PRIMARY KEY,
category_name	VARCHAR(128) NOT NULL UNIQUE,
symbol_code		VARCHAR(128) NOT NULL UNIQUE
);

CREATE TABLE lots (
id				INT AUTO_INCREMENT PRIMARY KEY,
dt_add			TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
lot_name		VARCHAR(128),
description		TEXT,
img				VARCHAR(128),
initial_price	DECIMAL,
dt_end			TIMESTAMP,
bid_step		DECIMAL,

users_id_author	INT NOT NULL,
users_id_winner	INT,
categories_id	INT
);

CREATE TABLE bids (
id				INT AUTO_INCREMENT PRIMARY KEY,
dt_add			TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
bid_price		DECIMAL,

users_id		INT NOT NULL,
lots_id			INT NOT NULL
);

CREATE TABLE users (
id				INT AUTO_INCREMENT PRIMARY KEY,
dt_reg			TIMESTAMP,
email			VARCHAR(128) NOT NULL UNIQUE,
user_name		VARCHAR(128) NOT NULL UNIQUE,
password		CHAR(64) NOT NULL,
contact_info	TEXT
);

CREATE INDEX lot_name ON lots(lot_name);


