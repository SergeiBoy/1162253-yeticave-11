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
img_path		VARCHAR(128),
initial_price	DECIMAL,
dt_end			TIMESTAMP,
bid_step		DECIMAL,

user_id_author	INT NOT NULL,
user_id_winner	INT,
category_id		INT
);

CREATE TABLE bids (
id				INT AUTO_INCREMENT PRIMARY KEY,
dt_add			TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
bid_price		DECIMAL,

user_id			INT NOT NULL,
lot_id			INT NOT NULL
);

CREATE TABLE users (
id				INT AUTO_INCREMENT PRIMARY KEY,
dt_reg			TIMESTAMP,
email			VARCHAR(128) NOT NULL UNIQUE,
user_name		VARCHAR(128) NOT NULL UNIQUE,
password		CHAR(64) NOT NULL,
contact_info	TEXT
);

CREATE INDEX dt_add ON lots(dt_add);
CREATE INDEX lot_name ON lots(lot_name);
CREATE INDEX initial_price ON lots(initial_price);
CREATE INDEX dt_end ON lots(dt_end);
CREATE INDEX user_id_author ON lots(user_id_author);
CREATE INDEX user_id_winner ON lots(user_id_winner);
CREATE INDEX category_id ON lots(category_id);

CREATE INDEX dt_add ON bids(dt_add);
CREATE INDEX bid_price ON bids(bid_price);
CREATE INDEX user_id ON bids(user_id);
CREATE INDEX lot_id ON bids(lot_id);

CREATE INDEX dt_reg ON users(dt_reg);

CREATE FULLTEXT INDEX lot_ft_search ON lots(lot_name, description)


