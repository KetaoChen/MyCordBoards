-- CREATE USER 'newuser'@'localhost' IDENTIFIED BY 'password';
CREATE USER IF NOT EXISTS gatechUser@localhost IDENTIFIED BY 'gatech123';

DROP DATABASE IF EXISTS `cs6400_fa18_team053`;
SET default_storage_engine=InnoDB;
SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE DATABASE IF NOT EXISTS cs6400_fa18_team053
DEFAULT CHARACTER SET utf8mb4
DEFAULT COLLATE utf8mb4_unicode_ci;
USE cs6400_fa18_team053;

GRANT SELECT, INSERT, UPDATE, DELETE, FILE ON *.* TO 'gatechUser'@'localhost';
GRANT ALL PRIVILEGES ON `gatechuser`.* TO 'gatechUser'@'localhost';
GRANT ALL PRIVILEGES ON `cs6400_fa18_team053`.* TO 'gatechUser'@'localhost';
FLUSH PRIVILEGES;

-- Tables

CREATE TABLE `User` (
email varchar(250) NOT NULL,
pin varchar(4) NOT NULL,
first_name varchar(100) NOT NULL,
last_name varchar(100) NOT NULL,
PRIMARY KEY (email)
);

CREATE TABLE Follow (
email varchar(250) NOT NULL,
followee_email varchar(250) NOT NULL,
PRIMARY KEY (email, followee_email)
);

CREATE TABLE CorkBoard(
cbID SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
email VARCHAR(250) NOT NULL,
title VARCHAR(200) NOT NULL, -- set length of a title to 200 char
private_type VARCHAR(50) NOT NULL,
corkboard_updated_time DATETIME NOT NULL,
category VARCHAR(100) NOT NULL,
PRIMARY KEY (cbID, email)
);

CREATE TABLE PrivateCorkBoard (
cbID SMALLINT UNSIGNED NOT NULL,
password varchar(128) NOT NULL,
PRIMARY KEY (cbID)
);

CREATE TABLE PublicCorkBoard (
cbID SMALLINT UNSIGNED NOT NULL,
PRIMARY KEY (cbID)
);

CREATE TABLE Watch (
watcher_email varchar(250) NOT NULL,
cbID SMALLINT UNSIGNED NOT NULL,
PRIMARY KEY (cbID, watcher_email)
);

CREATE TABLE Category (
category_name varchar(60) NOT NULL,
PRIMARY KEY (category_name)
);

CREATE TABLE PushPin(
ppID SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
url VARCHAR(767) NOT NULL,
description VARCHAR(200) NOT NULL,
pushpin_updated_time DATETIME NOT NULL,
cbID SMALLINT UNSIGNED NOT NULL,
PRIMARY KEY (ppID),
UNIQUE KEY (url)
);

CREATE TABLE Tag(
ppID SMALLINT UNSIGNED NOT NULL,
tag_content VARCHAR(200) NOT NULL,	-- length limit is 200 char
PRIMARY KEY (ppID, tag_content),
UNIQUE(ppID,tag_content)

);

CREATE TABLE Commenton(
ppID SMALLINT UNSIGNED NOT NULL,
commenter_email VARCHAR(250) NOT NULL,
comment_content VARCHAR(250) NOT NULL,
comment_time DATETIME NOT NULL,
PRIMARY KEY (comment_time),
  
UNIQUE(ppID, commenter_email, comment_content, comment_time)
);

CREATE TABLE LikeUnlike(
ppID SMALLINT UNSIGNED NOT NULL,
viewer_email VARCHAR(250) NOT NULL,
like_time DATETIME NOT NULL,
PRIMARY KEY (ppID, viewer_email),
UNIQUE(ppID, viewer_email, like_time)
);

-- Constraints   Foreign Keys: FK_ChildTable_childColumn_ParentTable_parentColumn

ALTER TABLE Follow
ADD CONSTRAINT fk_Follow_email_User_email FOREIGN KEY (email) REFERENCES User (email),
ADD CONSTRAINT fk_Follow_followee_email_User_email FOREIGN KEY (followee_email) REFERENCES User (email);

ALTER TABLE CorkBoard
ADD CONSTRAINT fk_CorkBoard_email_User_email FOREIGN KEY (email) REFERENCES User (email),
ADD CONSTRAINT fk_CorkBoard_category_Category_category_name FOREIGN KEY (category) REFERENCES Category (category_name);

ALTER TABLE PushPin
ADD CONSTRAINT fk_PushPin_cbID_CorkBoard_cbID FOREIGN KEY (cbID) REFERENCES CorkBoard (cbID);

ALTER TABLE Tag
ADD CONSTRAINT fk_Tag_ppID_PushPin_ppID FOREIGN KEY (ppID) REFERENCES PushPin (ppID);

ALTER TABLE Commenton
ADD CONSTRAINT fk_Commenton_ppID_PushPin_ppID FOREIGN KEY (ppID) REFERENCES PushPin (ppID),
ADD CONSTRAINT fk_Commenton_commenter_email_User_email FOREIGN KEY(commenter_email) REFERENCES `User`(email);

ALTER TABLE LikeUnlike
ADD CONSTRAINT fk_LikeUnlike_ppID_PushPin_ppID FOREIGN KEY (ppID) REFERENCES PushPin (ppID),
ADD CONSTRAINT fk_LikeUnlike_viewer_email_User_email FOREIGN KEY (viewer_email) REFERENCES `User` (email);

ALTER TABLE PrivateCorkBoard
ADD CONSTRAINT fk_PrivateCorkBoard_cbID_CorkBoard_cbID FOREIGN KEY (cbID) REFERENCES CorkBoard (cbID);

ALTER TABLE PublicCorkBoard
ADD CONSTRAINT fk_PublicCorkBoard_cbID_CorkBoard_cbID FOREIGN KEY (cbID) REFERENCES CorkBoard (cbID);

ALTER TABLE Watch
ADD CONSTRAINT fk_Watch_watcher_email_User_email FOREIGN KEY (watcher_email) REFERENCES User (email),
ADD CONSTRAINT fk_Watch_cbID_CorkBoard_cbID FOREIGN KEY (cbID) REFERENCES CorkBoard (cbID);








USE cs6400_fa18_team053 ;

-- Inserting 7 Users --
INSERT INTO User (email,pin,first_name,last_name) VALUES ('michael@bluthco.com','m123','Michael','Bluth');
INSERT INTO User (email,pin,first_name,last_name) VALUES ('rtaylor@gatech.edu','r333','Robert','Taylor');
INSERT INTO User (email,pin,first_name,last_name) VALUES ('dwilliamson@gatech.edu','d123','Daniel','Williamson');
INSERT INTO User (email,pin,first_name,last_name) VALUES ('llopez@gatech.edu','l456','Luis','Lopez');
INSERT INTO User (email,pin,first_name,last_name) VALUES ('pestrada@gatech.edu','p789','Patrick','Estrada');
INSERT INTO User (email,pin,first_name,last_name) VALUES ('rwalker@gatech.edu','r111','Rodney','Walker');
INSERT INTO User (email,pin,first_name,last_name) VALUES ('shenry@gatech.edu','9098','Sean','Henry');

-- Inserting 14 Following --
INSERT INTO Follow (email,followee_email) VALUES ('michael@bluthco.com','rtaylor@gatech.edu');
INSERT INTO Follow (email,followee_email) VALUES ('michael@bluthco.com','dwilliamson@gatech.edu');
INSERT INTO Follow (email,followee_email) VALUES ('rtaylor@gatech.edu','michael@bluthco.com');
INSERT INTO Follow (email,followee_email) VALUES ('rtaylor@gatech.edu','pestrada@gatech.edu');
INSERT INTO Follow (email,followee_email) VALUES ('dwilliamson@gatech.edu','michael@bluthco.com');
INSERT INTO Follow (email,followee_email) VALUES ('dwilliamson@gatech.edu','pestrada@gatech.edu');
INSERT INTO Follow (email,followee_email) VALUES ('llopez@gatech.edu','shenry@gatech.edu');
INSERT INTO Follow (email,followee_email) VALUES ('llopez@gatech.edu','rtaylor@gatech.edu');
INSERT INTO Follow (email,followee_email) VALUES ('pestrada@gatech.edu','michael@bluthco.com');
INSERT INTO Follow (email,followee_email) VALUES ('pestrada@gatech.edu','shenry@gatech.edu');
INSERT INTO Follow (email,followee_email) VALUES ('rwalker@gatech.edu','dwilliamson@gatech.edu');
INSERT INTO Follow (email,followee_email) VALUES ('rwalker@gatech.edu','pestrada@gatech.edu');
INSERT INTO Follow (email,followee_email) VALUES ('shenry@gatech.edu','michael@bluthco.com');
INSERT INTO Follow (email,followee_email) VALUES ('shenry@gatech.edu','dwilliamson@gatech.edu');

-- Inserting 8 Category--
INSERT INTO Category (category_name) VALUES ('Education');
INSERT INTO Category (category_name) VALUES ('People');
INSERT INTO Category (category_name) VALUES ('Architecture');
INSERT INTO Category (category_name) VALUES ('Sports');
INSERT INTO Category (category_name) VALUES ('Technology');
INSERT INTO Category (category_name) VALUES ('Food & Drink');
INSERT INTO Category (category_name) VALUES ('Travel');
INSERT INTO Category (category_name) VALUES ('Pets');

-- Inserting 8 CorkBoards --
INSERT INTO CorkBoard (cbID,email,title,private_type,corkboard_updated_time,category) VALUES ('1','michael@bluthco.com','OMSCS at Georgia Tech ','public','2017-11-19 12:17:27','Education');
INSERT INTO CorkBoard (cbID,email,title,private_type,corkboard_updated_time,category) VALUES ('2','shenry@gatech.edu','Tech Tower','public','2017-11-19 11:37:27','Architecture');
INSERT INTO CorkBoard (cbID,email,title,private_type,corkboard_updated_time,category) VALUES ('3','rwalker@gatech.edu','Sports Events','public','2017-11-19 12:27:27','Sports');
INSERT INTO CorkBoard (cbID,email,title,private_type,corkboard_updated_time,category) VALUES ('4','pestrada@gatech.edu','Computer Lab Resources','public','2017-10-19 12:28:27','Technology');
INSERT INTO CorkBoard (cbID,email,title,private_type,corkboard_updated_time,category) VALUES ('5','pestrada@gatech.edu','Delicious Food!','public','2018-09-19 12:27:27','Food & Drink');
INSERT INTO CorkBoard (cbID,email,title,private_type,corkboard_updated_time,category) VALUES ('6','llopez@gatech.edu','Travel','public','2018-11-19 12:27:27','Travel');

INSERT INTO CorkBoard (cbID,email,title,private_type,corkboard_updated_time,category) VALUES ('7','michael@bluthco.com','People of OMSCS','private','2018-11-19 12:20:27','People');
INSERT INTO CorkBoard (cbID,email,title,private_type,corkboard_updated_time,category) VALUES ('8','dwilliamson@gatech.edu','Funny Moments of Pets','private','2018-11-19 19:27:27','Pets');


-- Inserting 2 PrivateCorkBoard --
INSERT INTO PrivateCorkBoard (cbID,password) VALUES ('7','abc123@kl');
INSERT INTO PrivateCorkBoard (cbID,password) VALUES ('8','lolXDlol');

-- Inserting 6 PublicCorkBoard --
INSERT INTO PublicCorkBoard (cbID) VALUES ('1');
INSERT INTO PublicCorkBoard (cbID) VALUES ('2');
INSERT INTO PublicCorkBoard (cbID) VALUES ('3');
INSERT INTO PublicCorkBoard (cbID) VALUES ('4');
INSERT INTO PublicCorkBoard (cbID) VALUES ('5');
INSERT INTO PublicCorkBoard (cbID) VALUES ('6');

-- Inserting 14 Watches --
INSERT INTO Watch (cbID,watcher_email) VALUES ('2','michael@bluthco.com');
INSERT INTO Watch (cbID,watcher_email) VALUES ('3','michael@bluthco.com');
INSERT INTO Watch (cbID,watcher_email) VALUES ('4','rtaylor@gatech.edu');
INSERT INTO Watch (cbID,watcher_email) VALUES ('6','rtaylor@gatech.edu');
INSERT INTO Watch (cbID,watcher_email) VALUES ('1','dwilliamson@gatech.edu');
INSERT INTO Watch (cbID,watcher_email) VALUES ('3','dwilliamson@gatech.edu');
INSERT INTO Watch (cbID,watcher_email) VALUES ('2','llopez@gatech.edu');
INSERT INTO Watch (cbID,watcher_email) VALUES ('5','llopez@gatech.edu');
INSERT INTO Watch (cbID,watcher_email) VALUES ('3','pestrada@gatech.edu');
INSERT INTO Watch (cbID,watcher_email) VALUES ('6','pestrada@gatech.edu');
INSERT INTO Watch (cbID,watcher_email) VALUES ('2','rwalker@gatech.edu');
INSERT INTO Watch (cbID,watcher_email) VALUES ('4','rwalker@gatech.edu');
INSERT INTO Watch (cbID,watcher_email) VALUES ('1','shenry@gatech.edu');
INSERT INTO Watch (cbID,watcher_email) VALUES ('5','shenry@gatech.edu');

-- Inserting 3 PushPins --
INSERT INTO PushPin (cbID,ppID,url,description,pushpin_updated_time) VALUES ('1','1','https://www.cc.gatech.edu/sites/default/files/images/mercury/oms-cs-web-rotator_2_0_3.jpeg','OMSCS program logo','2018-10-19 12:26:27');
INSERT INTO PushPin (cbID,ppID,url,description,pushpin_updated_time) VALUES ('1','2','http://www.buzzcard.gatech.edu/sites/default/files/uploads/images/superblock_images/img_2171.jpg','student ID for Georgia Tech','2018-9-19 12:27:27');
INSERT INTO PushPin (cbID,ppID,url,description,pushpin_updated_time) VALUES ('1','3','https://www.news.gatech.edu/sites/default/files/uploads/mercury_images/piazza-icon.png','logo for Piazza','2018-11-19 12:39:27');
INSERT INTO PushPin (cbID,ppID,url,description,pushpin_updated_time) VALUES ('1','4','http://www.comm.gatech.edu/sites/default/files/images/brand-graphics/gt-seal.png','official seal of Georgia Tech','2018-11-19 12:26:29');

INSERT INTO PushPin (cbID,ppID,url,description,pushpin_updated_time) VALUES ('2','5','http://daily.gatech.edu/sites/default/files/styles/1170_x_x/public/hgt-tower-crop.jpg','Tech Tower interior photo','2018-11-18 12:26:27');
INSERT INTO PushPin (cbID,ppID,url,description,pushpin_updated_time) VALUES ('2','6','http://www.livinghistory.gatech.edu/s/1481/images/content_images/techtower1_636215523842964533.jpg','Tech Tower exterior photo','2018-11-19 12:28:27');
INSERT INTO PushPin (cbID,ppID,url,description,pushpin_updated_time) VALUES ('2','7','https://www.ece.gatech.edu/sites/default/files/styles/1500_x_scale/public/images/mercury/kessler2.0442077-p16-49.jpg','Kessler Campanile at Georgia Tech','2018-11-19 18:37:27');
INSERT INTO PushPin (cbID,ppID,url,description,pushpin_updated_time) VALUES ('2','8','https://www.scs.gatech.edu/sites/scs.gatech.edu/files/files/klaus-building.jpg','student facilities, computing, gtcomputing','2018-11-19 12:26:27');
INSERT INTO PushPin (cbID,ppID,url,description,pushpin_updated_time) VALUES ('2','9','https://www.news.gatech.edu/sites/default/files/styles/740_x_scale/public/uploads/mercury_images/Tech_Tower_WebFeature_1.jpg','Tech tower sign','2018-11-19 12:27:28');

INSERT INTO PushPin (cbID,ppID,url,description,pushpin_updated_time) VALUES ('3','10','http://traditions.gatech.edu/images/mantle-reck3.jpg','Ramblin’ wreck today','2018-11-19 12:26:27');
INSERT INTO PushPin (cbID,ppID,url,description,pushpin_updated_time) VALUES ('3','11','http://www.swag.gatech.edu/sites/default/files/buzz-android-tablet.jpg','Driving the mini wreck','2018-10-19 12:27:27');
INSERT INTO PushPin (cbID,ppID,url,description,pushpin_updated_time) VALUES ('3','12','http://www.livinghistory.gatech.edu/s/1481/images/content_images/ramblinwreck1_636215542678295357.jpg','Ramblin’ Wreck of the past','2018-11-19 15:37:27');
INSERT INTO PushPin (cbID,ppID,url,description,pushpin_updated_time) VALUES ('3','13','https://www.news.gatech.edu/sites/default/files/uploads/mercury_images/screen_shot_2016-08-11_at_12.45.48_pm_10.png','Bobby Dodd stadium','2018-11-15 12:26:27');

INSERT INTO PushPin (cbID,ppID,url,description,pushpin_updated_time) VALUES ('4','14','http://www.livinghistory.gatech.edu/s/1481/images/content_images/thevarsity1_636215546286483906.jpg','The Varsity','2018-11-19 12:26:25');
INSERT INTO PushPin (cbID,ppID,url,description,pushpin_updated_time) VALUES ('4','15','http://blogs.iac.gatech.edu/food14/files/2014/09/wafflefries2.jpg','Chick-fil-a Waffle Fries','2018-11-14 12:27:27');



-- Inserting 3 Tags --
INSERT INTO Tag (ppID,tag_content) VALUES ('1','OMSCS');
INSERT INTO Tag (ppID,tag_content) VALUES ('2','buzzcard');
INSERT INTO Tag (ppID,tag_content) VALUES ('3','Piazza');
INSERT INTO Tag (ppID,tag_content) VALUES ('4','Georgia tech seal');
INSERT INTO Tag (ppID,tag_content) VALUES ('4','great seal');
INSERT INTO Tag (ppID,tag_content) VALUES ('4','official');


INSERT INTO Tag (ppID,tag_content) VALUES ('5','administration');
INSERT INTO Tag (ppID,tag_content) VALUES ('5','facilities');
INSERT INTO Tag (ppID,tag_content) VALUES ('6','administration');
INSERT INTO Tag (ppID,tag_content) VALUES ('6','facilities');
INSERT INTO Tag (ppID,tag_content) VALUES ('8','student facilities');
INSERT INTO Tag (ppID,tag_content) VALUES ('8','computing');
INSERT INTO Tag (ppID,tag_content) VALUES ('8','gtcomputing');

INSERT INTO Tag (ppID,tag_content) VALUES ('10','tohellwithgeorgia');
INSERT INTO Tag (ppID,tag_content) VALUES ('10','decked out');
INSERT INTO Tag (ppID,tag_content) VALUES ('10','parade');
INSERT INTO Tag (ppID,tag_content) VALUES ('11','ramblin wreck');
INSERT INTO Tag (ppID,tag_content) VALUES ('11','buzz');
INSERT INTO Tag (ppID,tag_content) VALUES ('11','mascot');
INSERT INTO Tag (ppID,tag_content) VALUES ('11','parade');
INSERT INTO Tag (ppID,tag_content) VALUES ('12','ootball game');
INSERT INTO Tag (ppID,tag_content) VALUES ('12','parade');
INSERT INTO Tag (ppID,tag_content) VALUES ('13','football, game day');
INSERT INTO Tag (ppID,tag_content) VALUES ('13','tohellwithgeorgia');


-- Inserting 2 Commenton --
INSERT INTO Commenton (ppID,commenter_email,comment_content,comment_time) VALUES ('1','rtaylor@gatech.edu','great!','2018-11-19 13:26:27');
INSERT INTO Commenton (ppID,commenter_email,comment_content,comment_time) VALUES ('1','dwilliamson@gatech.edu','awesome!','2018-11-19 14:26:27');

-- Inserting 2 Likes --
INSERT INTO LikeUnlike (ppID,viewer_email,like_time) VALUES ('1','rtaylor@gatech.edu','2018-11-19 13:26:27');
INSERT INTO LikeUnlike (ppID,viewer_email,like_time) VALUES ('3','dwilliamson@gatech.edu','2018-11-19 13:26:27');
