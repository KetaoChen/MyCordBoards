--login

SELECT pin FROM User WHERE email='$enteredEmail';


--homescreen

	--show name

	 SELECT first_name, last_name " . "FROM User " . "WHERE User.email='{$_SESSION['email']}';

	--recent update

	SELECT User.first_name, User.last_name, CorkBoard.title, CorkBoard.private_type, CorkBoard.corkboard_updated_time, CorkBoard.cbID
	FROM User INNER JOIN CorkBoard ON User.email = CorkBoard.email WHERE User.email= '{$_SESSION['email']}'
	UNION
	SELECT U.first_name, U.last_name, C.title, C.private_type, C.corkboard_updated_time, C.cbID FROM CorkBoard AS C
	INNER JOIN Follow AS F ON C.email = F.followee_email
	INNER JOIN User AS U ON F.followee_email = U.email WHERE F.email= '{$_SESSION['email']}'
	UNION
	SELECT U.first_name, U.last_name, CB.title, CB.private_type, CB.corkboard_updated_time, CB.cbID
	FROM CorkBoard AS CB INNER JOIN Watch AS W ON CB.cbID=W.cbID
	INNER JOIN User AS U ON CB.email = U.email
	WHERE W.watcher_email= '{$_SESSION['email']}'
	ORDER BY corkboard_updated_time DESC LIMIT 4;

	--my corkboard

	SELECT table1.cbID, table1.title, table1.private_type, table2.num
	FROM
	(SELECT CorkBoard.cbID, CorkBoard.title, CorkBoard.private_type FROM
	CorkBoard NATURAL JOIN User WHERE User.email= '{$_SESSION['email']}') table1
	LEFT JOIN
	(SELECT CorkBoard.cbID, CorkBoard.title, CorkBoard.private_type, COUNT(PushPin.url) as num
	FROM User NATURAL JOIN CorkBoard NATURAL JOIN PushPin WHERE User.email= '{$_SESSION['email']}'
	GROUP BY CorkBoard.cbID) table2
	ON table1.cbID = table2.cbID ORDER BY table1.title;


--AddCorkBoard

    --insert into CorkBoard

        INSERT INTO CorkBoard VALUES (DEFAULT, '$CBemail','$title','$privatetype',NOW(),'$selectcat');

    ---insert into public/private based on type

        SELECT MAX(cbID) from CorkBoard;
        INSERT INTO PrivateCorkBoard VALUES('$id','$password');
        INSERT INTO PublicCorkBoard VALUES('$id');

    ----retrieve category list

        SELECT * FROM Category ORDER BY category_name ASC;


--PasswordProtection

    SELECT PC.password FROM PrivateCorkBoard AS PC WHERE PC.cbID=$ID;


--ViewCorkBoard

    --view CorkBoard information

    	SELECT * FROM CorkBoard AS C WHERE C.cbID=$currentCbID;

	--view owner's name

	    SELECT * FROM `User` AS U WHERE U.email='$owner';

    --retrieve thumbnails images url

        SELECT PP.url, PP.ppID FROM PushPin AS PP WHERE PP.cbID='$currentCbID'

	--view num of watcher

	    SELECT COUNT(W.watcher_email) AS numwatch FROM Watch As W WHERE W.cbID=$currentCbID;


--Follow

    --check if owner is followed

        SELECT F.email FROM Follow As F WHERE F.followee_email='$CBowner';

    --insert follow list if follow clicked

        INSERT INTO Follow VALUES ('$currentUser', '$CBowner');


--Watch

    --check if CorkBoard is watched

        SELECT W.watcher_email FROM Watch As W WHERE W.cbID='$currentCbID';

    --insert watch list if watch clicked

        INSERT INTO Watch VALUES ('$currentUser','$currentCbID');


--view_pushpin

SELECT url, description, pushpin_updated_time, title, CorkBoard.cbID
FROM PushPin LEFT JOIN CorkBoard ON PushPin.cbID = CorkBoard.cbID
WHERE PushPin.ppID='{$_SESSION['ppID']}'
SELECT first_name, last_name
FROM User
LEFT JOIN CorkBoard ON CorkBoard.email = User.email
LEFT JOIN PushPin ON PushPin.cbID = CorkBoard.cbID
WHERE PushPin.ppID='{$_SESSION['ppID']}'
SELECT tag_content
FROM Tag
WHERE ppID='{$_SESSION['ppID']}'
ORDER BY tag_content ASC;


--add_pushpin

SELECT title FROM CorkBoard WHERE cbID = '{$_SESSION['cbID']}'
INSERT INTO PushPin (url, description, pushpin_updated_time, cbID)
VALUES ('$url', '$description', NOW(), '{$_SESSION['cbID']}')
SELECT ppID FROM PushPin WHERE url='{$_POST['url']}'
INSERT INTO Tag (ppID, tag_content) VALUES ('$ppID', '$tag_content')
UPDATE CorkBoard SET corkboard_updated_time = NOW() WHERE cbID = '{$_SESSION['cbID']}';


--view_like

SELECT email FROM CorkBoard LEFT JOIN PushPin on CorkBoard.cbID = PushPin.cbID WHERE ppID='{$_SESSION['ppID']}'
SELECT * FROM LikeUnlike WHERE ppID='{$_SESSION['ppID']}' AND viewer_email = '{$_SESSION['email']}'
INSERT INTO LikeUnlike (ppID, viewer_email, like_time) VALUES ('{$_SESSION['ppID']}', '{$_SESSION['email']}', NOW())
DELETE FROM LikeUnlike WHERE ppID='{$_SESSION['ppID']}' AND viewer_email = '{$_SESSION['email']}'
SELECT first_name, last_name, like_time
FROM PushPin JOIN LikeUnlike ON PushPin.ppID = LikeUnlike.ppID
LEFT JOIN User ON LikeUnlike.viewer_email = User.email
WHERE PushPin.ppID='{$_SESSION['ppID']}'
ORDER BY like_time DESC;


--view_comment

INSERT INTO CommentOn (commenter_email, comment_content, comment_time, ppID)
VALUES ('{$_SESSION['email']}', '$Comment', NOW(), '{$_SESSION['ppID']}')
SELECT first_name, last_name, comment_content, comment_time
FROM PushPin JOIN CommentOn ON PushPin.ppID = CommentOn.ppID
LEFT JOIN User ON CommentOn.commenter_email = User.email
WHERE PushPin.ppID='{$_SESSION['ppID']}' ORDER BY comment_time DESC;


--Popular_tags

SELECT Tag.tag_content, COUNT(Tag.tag_content) AS frequency, COUNT(DISTINCT
PushPin.cbID) AS unique_CorkBoard
FROM Tag NATURAL JOIN PushPin
GROUP BY Tag.tag_content
ORDER BY frequency DESC LIMIT 5;


--Search_pushpin

SELECT  DISTINCT p.ppID, p.description, c.title, u.first_name, u.last_name
FROM `User` as u
LEFT JOIN CorkBoard as c NATURAL JOIN PublicCorkBoard as pc NATURAL JOIN PushPin as p NATURAL JOIN Tag as t
ON u.email = c.email
WHERE p.description LIKE '%$keyword%' OR t.tag_content LIKE '%$keyword%' OR
c.category LIKE '%$keyword%'
ORDER BY p.description, c.title, u.first_name, u.last_name;


--Popular_sites

SELECT url, COUNT(url) AS frequency
FROM
(SELECT 
left(right(PushPin.url, LENGTH(PushPin.url)-(position('//' in PushPin.url)+1)), position('/' in right(PushPin.url, LENGTH(url)-(position('//' in PushPin.url)+1)))-1) AS url
FROM PushPin) as t1									
GROUP BY url
ORDER BY frequency DESC;


--Statistics

SELECT email, first_name, last_name, numOfPublicCB, numOfPublicPP, numOfPrivateCB, numOfPrivatePP
FROM
`User`
LEFT JOIN
(SELECT `User`.email AS email1, COUNT(DISTINCT PublicCorkBoard.cbID) AS numOfPublicCB
FROM PublicCorkBoard NATURAL JOIN CorkBoard NATURAL JOIN `User`
GROUP BY `User`.email) AS pb
ON `User`.`email`=pb.email1
LEFT JOIN
(SELECT `User`.email AS email2, COUNT(PublicCorkBoard.cbID) AS numOfPublicPP
FROM PushPin NATURAL JOIN PublicCorkBoard NATURAL JOIN CorkBoard NATURAL JOIN `User`
GROUP BY `User`.email) AS pbp
ON `User`.`email`=pbp.email2
LEFT JOIN
(SELECT `User`.email AS email3, COUNT(DISTINCT PrivateCorkBoard.cbID) AS numOfPrivateCB
FROM PrivateCorkBoard NATURAL JOIN CorkBoard NATURAL JOIN `User`
GROUP BY `User`.email) AS pv
ON `User`.`email`=pv.email3
LEFT JOIN
(SELECT `User`.email AS email4, COUNT(PrivateCorkBoard.cbID) AS numOfPrivatePP
FROM PushPin NATURAL JOIN PrivateCorkBoard NATURAL JOIN CorkBoard NATURAL JOIN `User`
GROUP BY `User`.email) AS pvp
ON `User`.`email`=pvp.email4
ORDER BY numOfPublicCB DESC, numOfPrivateCB DESC;


