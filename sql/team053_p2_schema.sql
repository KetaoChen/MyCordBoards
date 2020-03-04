
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
  email varchar(150) NOT NULL,
  pin varchar(4) NOT NULL,
  first_name varchar(100) NOT NULL,
  last_name varchar(100) NOT NULL,
  PRIMARY KEY (email)
);

CREATE TABLE Follow (
  email varchar(150) NOT NULL,
  followee_email varchar(150) NOT NULL,
  PRIMARY KEY (email, followee_email)
);

CREATE TABLE CorkBoard( 
  cbID SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
  email VARCHAR(150) NOT NULL, 
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
  watcher_email varchar(150) NOT NULL,
  cbID SMALLINT UNSIGNED NOT NULL,
  PRIMARY KEY (cbID, watcher_email)
 );

CREATE TABLE Category (
  category_name varchar(60) NOT NULL,	  
  PRIMARY KEY (category_name)
);

CREATE TABLE PushPin(
  ppID SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,	
  url VARCHAR(150) NOT NULL,	
  description VARCHAR(200) NOT NULL,
  pushpin_updated_time DATETIME NOT NULL,
  cbID SMALLINT UNSIGNED NOT NULL,
  PRIMARY KEY (ppID),
  UNIQUE KEY (url)
);
	
CREATE TABLE Tag(
  ppID SMALLINT UNSIGNED NOT NULL,
  tag_content VARCHAR(20) NOT NULL,	-- length limit is 20 char
  PRIMARY KEY (ppID, tag_content),
  UNIQUE(ppID,tag_content)

);

CREATE TABLE Commenton(
  ppID SMALLINT UNSIGNED NOT NULL,
  commenter_email VARCHAR(150) NOT NULL,
  comment_content VARCHAR(150) NOT NULL,
  comment_time DATETIME NOT NULL,
  PRIMARY KEY (comment_time),
  UNIQUE(ppID, commenter_email, comment_content, comment_time)
);  

CREATE TABLE LikeUnlike(
  ppID SMALLINT UNSIGNED NOT NULL,
  viewer_email VARCHAR(150) NOT NULL,
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
