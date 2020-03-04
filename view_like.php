<!DOCTYPE HTML>  
<html>
<body>  

<?php
include('lib/common.php');
// written by jzhang3056

if (!isset($_SESSION['email'])) {
	header('Location: login.php');
	exit();
}

//$_SESSION['ppID'] = 5;
if (!isset($_SESSION['ppID'])) {
	header('Location: add_pushpin.php');  //redirect to add pushpin if pushpin is not set
	exit();
}

$query = "SELECT email FROM CorkBoard LEFT JOIN PushPin on CorkBoard.cbID = PushPin.cbID WHERE ppID='{$_SESSION['ppID']}'";
$result = mysqli_query($db, $query);
include('lib/show_queries.php');
    
if (!is_bool($result) && (mysqli_num_rows($result) > 0) ) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
} else {
        array_push($error_msg,  "SELECT ERROR: Failed to get PushPin Owner info ...<br>" . __FILE__ ." line:". __LINE__ );
}

$ppOwnerEmail = $row['email'];

$query = "SELECT * FROM LikeUnlike WHERE ppID='{$_SESSION['ppID']}' AND viewer_email = '{$_SESSION['email']}' ";
		 
$result = mysqli_query($db, $query);
include('lib/show_queries.php');
    
if (!is_bool($result) && (mysqli_num_rows($result) > 0) ) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
		if ($ppOwnerEmail == $_SESSION['email']) {$likestatus = NULL;} else {$likestatus = 1;};
} else {
        array_push($error_msg,  "SELECT ERROR: Failed to get like info ...<br>" . __FILE__ ." line:". __LINE__ );
		if ($ppOwnerEmail == $_SESSION['email']) {$likestatus = NULL;} else {$likestatus = 2;};
}

/* if form was submitted, then execute query to update likeulikd status */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	
	if ($_POST['tolike'] == "Like!") {
		$query = "INSERT INTO LikeUnlike (ppID, viewer_email, like_time) VALUES ('{$_SESSION['ppID']}', '{$_SESSION['email']}', NOW())";
		//$status = $_POST['tolike'];
		$likestatus = 1; 		
	}else if ($_POST['tounlike'] == "Unlike") {
		$query = "DELETE FROM LikeUnlike WHERE ppID='{$_SESSION['ppID']}' AND viewer_email = '{$_SESSION['email']}'";
		//$status = $_POST['tounlike'];
		$likestatus = 2;
	}
	$result = mysqli_query($db, $query);
	include('lib/show_queries.php');

	if (mysqli_affected_rows($db) == -1) {
		array_push($error_msg, "Error: Failed to update like status... <br>" . __FILE__ ." line:". __LINE__ );
	}	
}

?>

<?php //include("lib/header.php"); ?>
		<title>LikeUnlike</title>
	</head>
	
    	
		 <div id="main_container">
            <?php //include("lib/menu.php"); ?>
			
			<div class="center_content">
				<div class="center_left">
					<div class="features">

					<div class="profile_section">
						<?php 
							if($likestatus == 2){
								print '<form name="likeform" action="view_pushpin.php" method="POST"><input type = "submit" value = "Like!" name = "tolike"></form>';
							} else if($likestatus == 1){
								print '<form name="likeform" action="view_pushpin.php" method="POST"><input type = "submit" value = "Unlike" name = "tounlike"></form>';
							}else {
								print '';
							}
						?>
						<?php //print "status: " . $status ;?>
					</div>
					
						<div class="profile_section">
						<div class="subtitle"><?php print 'Liked By'; ?></div>						
							<?php			
                                    $query = "SELECT first_name, last_name, like_time " .
											"FROM PushPin JOIN LikeUnlike ON PushPin.ppID = LikeUnlike.ppID " .
											"LEFT JOIN User ON LikeUnlike.viewer_email = User.email " .
											"WHERE PushPin.ppID='{$_SESSION['ppID']}' ORDER BY like_time DESC ";
                                    
                                    $result = mysqli_query($db, $query);
                                    
                                    include('lib/show_queries.php'); 
                                    if (is_bool($result) && (mysqli_num_rows($result) == 0) ) {
                                        array_push($error_msg,  "Query ERROR: Failed to get liker history <br>" . __FILE__ ." line:". __LINE__ );
                                    }
                                    
                                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                                    $count = mysqli_num_rows($result);
                                   
                                   if ($row) {
                                        print '<table>';
                                        print '<tr>';
                                        print '<td class="heading">Name</td>';
										print '<td class="heading">Date</td>';
                                        print '</tr>';
                                    
                                        while ($row){            
                                            print '<tr>';
											print "<td>{$row['first_name']} {$row['last_name']}</td>";
                                            print '<td>' . $row['like_time'] . '</td>';
                                            print '</tr>';
                                            
                                            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                                        }
                                            print '</table>';
                                   }
                             ?>							
						</div>
				</div> 
			</div> 
                
              <?php //include("lib/error.php"); ?>
				
				<div class="clear"></div> 
			</div>    
            
               <?php //include("lib/footer.php"); ?>
		 
		</div>




</body>
</html>
