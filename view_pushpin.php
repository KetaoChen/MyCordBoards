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

if( $_SERVER['REQUEST_METHOD'] == 'GET') {
    $_SESSION['ppID'] = $_GET['ppID'];
}

//$_SESSION['ppID'] ='3'; //预设一个ppID

if (!isset($_SESSION['ppID'])) {
	header('Location: add_pushpin.php');  //redirect to add pushpin if pushpin is not set
	exit();
}

$query = "SELECT url, description, pushpin_updated_time, title, CorkBoard.cbID " .
		 "FROM PushPin LEFT JOIN CorkBoard ON PushPin.cbID = CorkBoard.cbID " .
		 "WHERE PushPin.ppID='{$_SESSION['ppID']}'";
		 
$result = mysqli_query($db, $query);
    include('lib/show_queries.php');
    
if (!is_bool($result) && (mysqli_num_rows($result) > 0) ) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
} else {
        array_push($error_msg,  "SELECT ERROR: Failed to get User PushPin ...<br>" . __FILE__ ." line:". __LINE__ );
}     

?>

<?php include("lib/header.php"); ?>
<title>CorkBoardlt View PushPin</title>
</head>

<body>
	<div id="main_container"> 
    <?php include("lib/menu.php");?>

    <div class="center_content">
        <div class="center_left">
            <div class="title_name">
                <?php
					$query = "SELECT first_name, last_name " .
							"FROM User " . 
							"LEFT JOIN CorkBoard ON CorkBoard.email = User.email " .
							"LEFT JOIN PushPin ON PushPin.cbID = CorkBoard.cbID " . 
							"WHERE PushPin.ppID='{$_SESSION['ppID']}'"; 
                    $result = mysqli_query($db, $query);
                                            
                    include('lib/show_queries.php');
                                             
                    if (!is_bool($result) && (mysqli_num_rows($result) > 0) ) {
						$owner = mysqli_fetch_array($result, MYSQLI_ASSOC);
					} else {
						array_push($error_msg,  "SELECT ERROR: Failed to get PushPin Owner Name...<br>" . __FILE__ ." line:". __LINE__ );
					}
					print $owner['first_name'] . ' ' . $owner['last_name'] ; 
					//print 'Hello' . ' ' . 'World';
				?>
				</div>
				<br>
				<table>
					<tr>
						<td class='item_label'>Date</td>
						<td>
                            <?php print $row['pushpin_updated_time'];?>
                        </td>
					</tr>
					<tr>
						<td class='item_label'>CorkBoard</td>
						<?php
							$corkboardID = urlencode($row['cbID']);
							print "<td><a href='ViewCorkBoard.php?cbID=$corkboardID'> {$row['title']} </a></td>"; //需要知道view corkboard 的php叫什么名字
						?>
					</tr>	
				</table>
            
            <div class="features">   
                <div class="profile_section">  <!--image and description-->
                    <div class="heading">
						<?php print 'Description: ' . $row['description'];?>
						</div>
						<br>
					<img src="<?php print $row['url']; ?>" alt="Pic from web" style="width:700px;">
					<div>
						<?php print 'from ' . $row['url'];?>
						</div>
                    <table>
                        <tr>
                            <td class="heading">Tags</td>
                            <td>
                                <ol>
                                    <?php
                                            $query = "SELECT tag_content FROM Tag WHERE ppID='{$_SESSION['ppID']}' ORDER BY tag_content ASC";
                                            $result = mysqli_query($db, $query);
                                            
                                            include('lib/show_queries.php');
                                             
                                             if (is_bool($result) && (mysqli_num_rows($result) == 0) ) {
                                                    array_push($error_msg,  "Query ERROR: Failed to get User interests...<br>" . __FILE__ ." line:". __LINE__ );
                                             }
                                                 
                                            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                                                print "<li>{$row['tag_content']}</li>";
                                            }
										?>
                                </ol>
                            </td>
                        </tr>
                    </table>						
                </div>

			<?php include('view_like.php'); ?> 
			<?php include('view_comment.php'); ?>
			
			</div>
                <?php include("lib/error.php"); ?>
            </div>       
				<div class="clear"></div> 		
			</div>    
			<?php include("lib/footer.php"); ?>	 
		</div>

</body>
</html>
