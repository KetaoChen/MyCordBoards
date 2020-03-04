<?php

include('lib/common.php');
// written by Cong Zhang

if (!isset($_SESSION['email'])) {
	header('Location: login.php');
	exit();
}

    // query user's name
    $query = "SELECT first_name, last_name " . "FROM User " . "WHERE User.email='{$_SESSION['email']}'";

    $result = mysqli_query($db, $query);
    include('lib/show_queries.php');
 
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    } else {
        array_push($error_msg,  "Query ERROR: Failed to get User name...<br>" . __FILE__ ." line:". __LINE__ );
    }
?>

<?php include("lib/header.php"); ?>
<title>CorkBoardIt</title>
</head>

<body>
	<div id="main_container">
    <?php include("lib/menu_for_homescreen.php"); ?>

    <div class="nav_bar">
                <ul>    
                    <li><a href="popular_tags.php" <?php if($current_filename=='popular_tags.php') echo "class='active'"; ?>>Popular Tags</a></li>                       
                    <li><a href="popular_sites.php" <?php if(strpos($current_filename, 'popular_sites.php') !== false) echo "class='active'"; ?>>Popular Sites</a></li>  
                    <li><a href="statistics.php" <?php if($current_filename=='statistics.php') echo "class='active'"; ?>>Corkboard Statistics</a></li>  
                    <li><a href="logout.php" <span class='glyphicon glyphicon-log-out'></span> Log Out</a></li>              
                </ul>
    </div>

    <div class="center_content">
        <div class="center_left">
            <div class="title_name">
                <?php // Part 1: display user's name
                print 'Homepage for ' . $row['first_name'] . ' ' . $row['last_name'] ?>     
            </div>
                      
            <div class="features">   
            
                <div class="profile_section">
                    <div class="subtitle">Recent CorkBoard Updates</div>  
                        <?php // Part 2: recent updates
                        $query = "SELECT User.first_name, User.last_name, CorkBoard.title, CorkBoard.private_type, CorkBoard.corkboard_updated_time, CorkBoard.cbID FROM User INNER JOIN CorkBoard ON User.email = CorkBoard.email WHERE User.email= '{$_SESSION['email']}'
                        UNION 
                        SELECT U.first_name, U.last_name, C.title, C.private_type, C.corkboard_updated_time, C.cbID FROM CorkBoard AS C
                        INNER JOIN Follow AS F ON C.email = F.followee_email
                        INNER JOIN User AS U ON F.followee_email = U.email WHERE F.email= '{$_SESSION['email']}'
                        UNION 
                        SELECT U.first_name, U.last_name, CB.title, CB.private_type, CB.corkboard_updated_time, CB.cbID FROM CorkBoard AS CB INNER JOIN Watch AS W ON CB.cbID=W.cbID INNER JOIN User AS U ON CB.email = U.email WHERE W.watcher_email= '{$_SESSION['email']}' ORDER BY corkboard_updated_time DESC LIMIT 4";

                        $result = mysqli_query($db, $query);

                        include('lib/show_queries.php'); 
                        if (mysqli_num_rows($result) == 0) {
                                print "No updates";
                        } else {
                                while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                        print "<tr>";
                                        $corkboardID = urlencode($row['cbID']);
                                        if ($row['private_type'] == 'private') {
                                            print "<font size='3pt'> <td><a href='PasswordProtection.php?cbID=$corkboardID'> {$row['title']} </a></td> </font> ";
                                        } else {
                                            print "<font size='3pt'> <td><a href='ViewCorkBoard.php?cbID=$corkboardID'> {$row['title']} </a></td> </font> ";
                                        }
                                        if ($row['private_type'] == 'private') {
                                            print "<td>" . '('.$row['private_type'] . ')'."</td>";
                                        }
                                        print "</br>";
                                        print "<td>" .'updated by '. $row['first_name'] .' '. $row['last_name'] . "</td>";
                                        print "<td>" .' on '. $row['corkboard_updated_time'] . "</td>";
                                        print "</tr>";
                                        print "</br>";
                                        print "</br>";
                                }
                        }
                        ?>                       
                </div>  

                <div class="profile_section">
                    <div class="subtitle">My CorkBoards &nbsp;&nbsp; 
                        <a href="AddCorkBoard.php" <?php if($current_filename=='AddCorkBoard.php') echo "class='active'"; ?>>Add Corkboard</a>         
                    </div>
                        <?php // Part 3: my corkboard

                        $query = "SELECT table1.cbID, table1.title, table1.private_type, table2.num
                        FROM 
                        (SELECT CorkBoard.cbID, CorkBoard.title, CorkBoard.private_type FROM 
                        CorkBoard NATURAL JOIN User WHERE User.email= '{$_SESSION['email']}') table1
                        LEFT JOIN
                        (SELECT CorkBoard.cbID, CorkBoard.title, CorkBoard.private_type, COUNT(PushPin.url) as num FROM
                                User NATURAL JOIN CorkBoard NATURAL JOIN PushPin WHERE User.email= '{$_SESSION['email']}' GROUP BY CorkBoard.cbID) table2
                        ON table1.cbID = table2.cbID ORDER BY table1.title";

                        //$query = "SELECT CorkBoard.title, CorkBoard.private_type FROM
                        //       User INNER JOIN CorkBoard ON User.email = CorkBoard.email
                        //       WHERE User.email= '{$_SESSION['email']}' ORDER BY CorkBoard.title";

						$result = mysqli_query($db, $query); 

                        include('lib/show_queries.php');

                        if (mysqli_num_rows($result) == 0) {
                                    print "You have no CorkBoards";   
                        } else {
								while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
									print "<tr>";
                                    $corkboardID = urlencode($row['cbID']);
                                    if ($row['private_type'] == 'private') {
                                            print "<font size='3pt'> <td><a href='PasswordProtection.php?cbID=$corkboardID'> {$row['title']} </a></td> </font> ";
                                        } else {
                                            print "<font size='3pt'> <td><a href='ViewCorkBoard.php?cbID=$corkboardID'> {$row['title']} </a></td> </font> ";
                                        }
                                    if ($row['private_type'] == 'private') {
                                            print "<td>" . '('.$row['private_type'] . ')'."</td>";
                                        }
                                    if (is_null($row['num'])) {
                                            print "<td>" .' with 0 PushPin'."</td>";
                                        } else {
                                            print "<td>" .' with '. $row['num'] .' PushPins'."</td>";
                                        }
                                    print "</tr>";
									print "</br>";
                                    print "</br>";
								}
                        }


						?>    						
                </div>	

                <div class="profile_section">
                    <div class="subtitle">
                        <a href="search_pushpin.php" <?php if($current_filename=='search_pushpin.php') echo "class='active'"; ?>>Search Pushpin</a>         
                    </div>
                </div>

            </div> 			
        </div> 

                <?php include("lib/error.php"); ?>
                    
				<div class="clear"></div> 		
			</div>    

               <?php include("lib/footer.php"); ?>
				 
		</div>
	</body>
</html>