CREATE TABLE beers (
	id int IDENTITY(1,1) PRIMARY KEY,
	amount int,
	total int, 
	dateTime int
);

CREATE TABLE profiles (
	uname varchar(255),
	upass varchar(255),
	fullname varchar(255),
	token varchar(255),
	id int IDENTITY(1,1) PRIMARY KEY,
	role int
);

CREATE TABLE settings (
	id int IDENTITY(1,1) PRIMARY KEY,
	minFridge int,
	maxFridge int,
	minNotify int
);