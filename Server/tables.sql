


CREATE TABLE IF NOT EXISTS `users`
(
	`user_id`			INT(11)				NOT NULL		AUTO_INCREMENT
,	`username`			VARCHAR(255)		NOT NULL
,	`token`				VARCHAR(255)		NOT NULL
,	`wins`				INT(11)				NOT NULL		DEFAULT 0
,	`attempts`			INT(11)				NOT NULL		DEFAULT 0
,	`first_seen`		TIMESTAMP			NOT NULL		DEFAULT CURRENT_TIMESTAMP

,	PRIMARY KEY (`user_id`)

) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `attempts`
(
	`attempt_id`		INT(11)				NOT NULL		AUTO_INCREMENT
,	`user_id`			INT(11)				NOT NULL
,	`user_attempt`		ENUM
	('scissors', 'rock', 'paper')			NOT NULL
,	`computer_attempt`	ENUM
	('scissors', 'rock', 'paper')			NOT NULL
,	`created_on`		TIMESTAMP			NOT NULL		DEFAULT CURRENT_TIMESTAMP

,	PRIMARY KEY (`attempt_id`)
,	FOREIGN KEY (`user_id`)
		REFERENCES `users` (`user_id`)

) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

