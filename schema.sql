CREATE DATABASE yeticave
DEFAULT CHARACTER SET utf8
DEFAULT COLLATE utf8_general_ci;
USE yeticave;


CREATE TABLE categories (
id				INT AUTO_INCREMENT PRIMARY KEY,
category_name	VARCHAR(128) NOT NULL UNIQUE,
symbol_code		VARCHAR(128) NOT NULL UNIQUE
);


CREATE TABLE users (
id				INT AUTO_INCREMENT PRIMARY KEY,
dt_reg			TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
email			VARCHAR(128) NOT NULL UNIQUE,
user_name		VARCHAR(128) NOT NULL,
password		CHAR(64) NOT NULL,
contact_info	TEXT
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

ALTER TABLE lots ADD CONSTRAINT FK_Lots_Users_UserIdAuthor FOREIGN KEY (user_id_author) REFERENCES users(id) ON DELETE CASCADE;
ALTER TABLE lots ADD CONSTRAINT FK_Lots_Users_UserIdWinner FOREIGN KEY (user_id_winner) REFERENCES users(id);
ALTER TABLE lots ADD CONSTRAINT FK_Lots_Categories_CategoryId FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE;
CREATE INDEX IX_Lots_UserIdAuthor ON lots(user_id_author);
CREATE INDEX IX_Lots_UserIdWinner ON lots(user_id_winner);
CREATE INDEX IX_Lots_CategoryId ON lots(category_id);
CREATE FULLTEXT INDEX FX_Lots_Name_Description ON lots(lot_name, description);



CREATE TABLE bids (
id				INT AUTO_INCREMENT PRIMARY KEY,
dt_add			TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
bid_price		DECIMAL,

user_id			INT NOT NULL,
lot_id			INT NOT NULL
);

ALTER TABLE bids ADD CONSTRAINT FK_Bids_Users_UserId FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;
ALTER TABLE bids ADD CONSTRAINT FK_Bids_Lots_LotId FOREIGN KEY (lot_id) REFERENCES lots(id) ON DELETE CASCADE;
CREATE INDEX IX_Bids_DtAdd ON bids(dt_add);
CREATE INDEX IX_Bids_BidPrice ON bids(bid_price);
CREATE INDEX IX_Bids_UserId ON bids(user_id);
CREATE INDEX IX_Bids_LotId ON bids(lot_id);


