<?php // popular tags

include('lib/common.php');
// written by kchen343
if (!isset($_SESSION['email'])) {
	header('Location: login.php');  //redirect to login if user is not set
	exit();
}
?>

<?php include("lib/header.php"); ?>
		<title>Popular Tags</title>
	</head>
	
	<body>
		<div id="main_container">
        <?php include("lib/menu.php"); ?> 
			
		<div class="center_content">
			<div class="center_left">
				<div class="features">   
					<div class="profile_section">						
						<div class="subtitle">Popular Tags</div>
							<table >								
								<tr>
									<td class="heading">Tag</td>
									<td class="heading">PushPins</td>
									<td class="heading">Unique CorkBoards</td>
								</tr> 
								<?php			
									$query ="SELECT Tag.tag_content, COUNT(Tag.tag_content) AS frequency, COUNT(DISTINCT
											PushPin.cbID) AS unique_CorkBoard
											FROM Tag NATURAL JOIN PushPin
											GROUP BY Tag.tag_content
											ORDER BY frequency DESC LIMIT 5";
									
									$result = mysqli_query($db, $query);
									
									include('lib/show_queries.php'); 
									
									if (is_bool($result) && (mysqli_num_rows($result) == 0) ) {
										array_push($error_msg,  "Query ERROR: Failed to get comment history <br>" . __FILE__ ." line:". __LINE__ );
									}
									while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
										$url = $row['url'];
										$site = explode('/',$url);
										print "<tr>";
										print "<font size='3pt'> <td><a href='search_pushpin.php?keyword=$row[tag_content]'> {$row[tag_content]} </a></td> </font> ";
										//print "<td>" . $row[tag_content] . "</td>";
										print "<td>" . $row[frequency] . "</td>";
										print "<td>" . $row[unique_CorkBoard] . "</td>";
										print "</tr>";
									}
								?>
							</table>						
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