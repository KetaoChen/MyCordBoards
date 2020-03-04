USE cs6400_fa18_team053 ;

-- Inserting 21 Users --
INSERT INTO User (email,pin,first_name,last_name) VALUES ('michael@bluthco.com','michael123','Michael','Bluth');
INSERT INTO User (email,pin,first_name,last_name) VALUES ('rtaylor@gatech.edu','pin123','Robert','Taylor');
INSERT INTO User (email,pin,first_name,last_name) VALUES ('dwilliamson@gatech.edu','pin123','Daniel','Williamson');
INSERT INTO User (email,pin,first_name,last_name) VALUES ('llopez@gatech.edu','pin123','Luis','Lopez');
INSERT INTO User (email,pin,first_name,last_name) VALUES ('pestrada@gatech.edu','pin123','Patrick','Estrada');
INSERT INTO User (email,pin,first_name,last_name) VALUES ('rwalker@gatech.edu','pin123','Rodney','Walker');
INSERT INTO User (email,pin,first_name,last_name) VALUES ('shenry@gatech.edu','pin123','Sean','Henry');
INSERT INTO User (email,pin,first_name,last_name) VALUES ('tfox@gatech.edu','pin123','Thomas','Fox');
INSERT INTO User (email,pin,first_name,last_name) VALUES ('jflowers@gatech.edu','pin123','Jacob','Flowers');
INSERT INTO User (email,pin,first_name,last_name) VALUES ('braymond@gatech.edu','pin123','Bianca','Raymond');
INSERT INTO User (email,pin,first_name,last_name) VALUES ('jcarroll@gatech.edu','pin123','Julie','Carroll');
INSERT INTO User (email,pin,first_name,last_name) VALUES ('jhernandez@gatech.edu','pin123','Jane','Hernandez');
INSERT INTO User (email,pin,first_name,last_name) VALUES ('crogers@gatech.edu','pin123','Colleen','Rogers');
INSERT INTO User (email,pin,first_name,last_name) VALUES ('efrank@gatech.edu','pin123','Elizabeth','Frank');
INSERT INTO User (email,pin,first_name,last_name) VALUES ('ahess@gatech.edu','pin123','Alicia','Hess');
INSERT INTO User (email,pin,first_name,last_name) VALUES ('tjohnson@gatech.edu','pin123','Tara','Johnson');
INSERT INTO User (email,pin,first_name,last_name) VALUES ('cchavez@gatech.edu','pin123','Christine','Chavez');
INSERT INTO User (email,pin,first_name,last_name) VALUES ('admin01@gtonline.com','admin123','Sharon','Lowery');
INSERT INTO User (email,pin,first_name,last_name) VALUES ('admin02@gtonline.com','admin123','Gary','Turner');
INSERT INTO User (email,pin,first_name,last_name) VALUES ('admin03@gtonline.com','admin123','Kristina','Scott');
INSERT INTO User (email,pin,first_name,last_name) VALUES ('admin04@gtonline.com','admin123','Charles','James');



-- Inserting 2 Following --
INSERT INTO Follow (email,followee_email) VALUES ('michael@bluthco.com','rtaylor@gatech.edu');
INSERT INTO Follow (email,followee_email) VALUES ('michael@bluthco.com','dwilliamson@gatech.edu');

-- Inserting 2 Category--
INSERT INTO Category (category_name) VALUES ('sea');
INSERT INTO Category (category_name) VALUES ('cloud');

-- Inserting 2 CorkBoards --
INSERT INTO CorkBoard (cbID,email,title,private_type,corkboard_updated_time,category) VALUES ('1','michael@bluthco.com','bluesea','private','2018-11-19 12:27:27','sea');
INSERT INTO CorkBoard (cbID,email,title,private_type,corkboard_updated_time,category) VALUES ('2','michael@bluthco.com','bluesky','public','2018-11-19 12:37:27','cloud');

-- Inserting 1 PrivateCorkBoard --
INSERT INTO PrivateCorkBoard (cbID,password) VALUES ('1','111');

-- Inserting 1 PublicCorkBoard --
INSERT INTO PublicCorkBoard (cbID) VALUES ('2');

-- Inserting 2 Watches --
INSERT INTO Watch (cbID,watcher_email) VALUES ('1','dwilliamson@gatech.edu');
INSERT INTO Watch (cbID,watcher_email) VALUES ('2','dwilliamson@gatech.edu');


-- Inserting 3 PushPins --
INSERT INTO PushPin (cbID,ppID,url,description,pushpin_updated_time) VALUES ('1','1','https://kids.nationalgeographic.com/explore/nature/habitats/ocean/#coral-reef-fish.jpg','nice sea','2018-11-19 12:26:27');
INSERT INTO PushPin (cbID,ppID,url,description,pushpin_updated_time) VALUES ('1','2','https://oceanservice.noaa.gov/facts/sevenseas.html','blue sea','2018-11-19 12:27:27');
INSERT INTO PushPin (cbID,ppID,url,description,pushpin_updated_time) VALUES ('2','3','https://www.recode.net/2015/4/30/11562024/too-embarrassed-to-ask-what-is-the-cloud-and-how-does-it-work','sunny','2018-11-19 12:37:27');

-- Inserting 3 Tags --
INSERT INTO Tag (ppID,tag_content) VALUES ('1','ocean');
INSERT INTO Tag (ppID,tag_content) VALUES ('1','beautiful');
INSERT INTO Tag (ppID,tag_content) VALUES ('1','blue');
INSERT INTO Tag (ppID,tag_content) VALUES ('2','ocean');
INSERT INTO Tag (ppID,tag_content) VALUES ('3','blue');
INSERT INTO Tag (ppID,tag_content) VALUES ('3','sky');

-- Inserting 2 Commenton --
INSERT INTO Commenton (ppID,commenter_email,comment_content,comment_time) VALUES ('1','rtaylor@gatech.edu','great!','2018-11-19 13:26:27');
INSERT INTO Commenton (ppID,commenter_email,comment_content,comment_time) VALUES ('1','dwilliamson@gatech.edu','awesome!','2018-11-19 14:26:27');

-- Inserting 2 Likes --
INSERT INTO LikeUnlike (ppID,viewer_email,like_time) VALUES ('1','rtaylor@gatech.edu','2018-11-19 13:26:27');
INSERT INTO LikeUnlike (ppID,viewer_email,like_time) VALUES ('3','dwilliamson@gatech.edu','2018-11-19 13:26:27');


