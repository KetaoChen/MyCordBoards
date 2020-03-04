<!DOCTYPE HTML>  
<html>
<head>
<style>
.error {color: #FF0000;}
</style>
</head>
<body>  

<?php
include('lib/common.php');
// written by jzhang3056

if (!isset($_SESSION['email'])) {
	header('Location: login.php');
	exit();
}

//$_SESSION['cbID'] ='1'; //预设一个cbID
//if( $_SERVER['REQUEST_METHOD'] == 'GET') {
//    $_SESSION['cbID'] = $_GET['cbID'];
//}

if (!isset($_SESSION['cbID'])) {
	header('Location: AddCorkBoard.php'); //
	exit();
}

$query = "SELECT title FROM CorkBoard WHERE cbID = '{$_SESSION['cbID']}'";
		 
$result = mysqli_query($db, $query);
    include('lib/show_queries.php');
    
if (!is_bool($result) && (mysqli_num_rows($result) > 0) ) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
} else {
        array_push($error_msg,  "SELECT ERROR: Failed to get User CorkBoard ...<br>" . __FILE__ ." line:". __LINE__ );
}     

// define variables and set to empty values
$urlErr = $descriptionErr = $tagErr = NULL;
$url = $description = $tag = NULL;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if (empty($_POST["url"])) {
		$urlErr = "Image URL is required";
	} else {
		$url = $_POST['url'];
	}
  
	if (empty($_POST["description"])) {
		$descriptionErr = "Description is required";
	} else {
		$description = $_POST["description"];
	}
    
	if (empty($_POST["tag"])) {
		$tagErr = "";
	} else {
		$tag = $_POST["tag"];
	}
}


?>

<?php include("lib/header.php"); ?>
		<title>Add PushPin</title>
	</head>
	
    	
		 <div id="main_container">
            <?php include("lib/menu.php"); ?>
			
			<div class="center_content">
				<div class="center_left">
					<div class="title_name">
					<?php 
						$corkboardID = urlencode($_SESSION['cbID']);
						//print $corkboardID;
						print "Add PushPin to <a href='ViewCorkBoard.php?cbID=$corkboardID'> {$row['title']} </a>"; 
					?>
					</div>          			
					<div class="features">   
						
						<div class="profile_section">						 
							<p><span class="error">* required field</span></p>
							<form name="addPPform" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
								<table>								
									<tr>
										<td class="item_label">URL</td>
										<td><input type="url" name="url"/><span class="error">* <?php print $urlErr;?></span></td>
									</tr>
									<tr>
										<td class="item_label">Description</td>
										<td><input type="text" name="description"><span class="error">* <?php print $descriptionErr;?></span></td>
									</tr>
									<tr>
										<td class="item_label">Tag</td>
										<td><input type="text" name="tag"><span class="error"> <?php print $tagErr;?></span></td>
									</tr>
								</table>
								<br><br>
								<input type="submit" name="submit" value="Add"> 					
							</form>							
						</div>
						
						<div class="profile_section">
						<?php
						
						
						//add PushPin
						if (!empty($url) || !empty($description)){
							$query = "INSERT INTO PushPin (url, description, pushpin_updated_time, cbID) " .
											 "VALUES ('$url', '$description', NOW(), '{$_SESSION['cbID']}')";
					 
							$pushpinID = mysqli_query($db, $query);
						
							include('lib/show_queries.php');
							if (mysqli_affected_rows($db) == -1) {
								array_push($error_msg, "Error: Failed to add PushPin... <br>" . __FILE__ ." line:". __LINE__ );
								print "<div class='subtitle'>PushPin is not successfully created...</div>";
							}else{
								print "<div class='subtitle'>PushPin is successfully created!</div>";
							}
	
							//update Tag table
							$ppid_query = "SELECT ppID FROM PushPin WHERE url='{$_POST['url']}'";
		 
							$result = mysqli_query($db, $ppid_query);
							include('lib/show_queries.php');
    
							if (!is_bool($result) && (mysqli_num_rows($result) > 0) ) {
						        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
							} else {
						        array_push($error_msg,  "SELECT ERROR: Failed to get ppID ...<br>" . __FILE__ ." line:". __LINE__ );
							}     
							$ppID = $row['ppID'];
							$tagArray = explode(',', $tag);
	
							foreach($tagArray as $tag_content){
								$query = "INSERT INTO Tag (ppID, tag_content) " .
											 "VALUES ('$ppID', '$tag_content')";
					 
								$tagID = mysqli_query($db, $query);
								include('lib/show_queries.php');
								if (mysqli_affected_rows($db) == -1) {
									array_push($error_msg, "Error: Failed to add Tag: '" . $tag_content .  "'<br>" . __FILE__ ." line:". __LINE__ );
								}
							}
							
							//update CorkBoard Last_update time
							$query = "UPDATE CorkBoard SET corkboard_updated_time = NOW() WHERE cbID = '{$_SESSION['cbID']}'";
					 
							$pushpinID = mysqli_query($db, $query);
						
							include('lib/show_queries.php');
							if (mysqli_affected_rows($db) == -1) {
							array_push($error_msg, "Error: Failed to add PushPin... <br>" . __FILE__ ." line:". __LINE__ );
							}
						}	
						?>
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
