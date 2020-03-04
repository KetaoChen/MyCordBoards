<?php // popular site

include('lib/common.php');
// written by kchen343
if (!isset($_SESSION['email'])) {
	header('Location: login.php');  //redirect to login if user is not set
	exit();
}

session_start();
?>

<?php include("lib/header.php"); ?>
		<title>Statistics</title>
	</head>
	
	<body>
		<div id="main_container">
        <?php include("lib/menu.php"); ?> 
			
		<div class="center_content">
			<div class="center_left">
				<div class="features">   
					<div class="profile_section">						
						<div class="subtitle">Statistics</div>
							<table >								
								<tr>
									<td class="heading">User</td>
									<td class="heading">Public CorkBoards</td>
									<td class="heading">Public PushPins</td>
									<td class="heading">Private CorkBoards</td>
									<td class="heading">Private PushPins</td>
								</tr> 
								<?php			
									$query ="SELECT email, first_name, last_name, numOfPublicCB, numOfPublicPP, numOfPrivateCB, numOfPrivatePP
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
											ORDER BY numOfPublicCB DESC, numOfPrivateCB DESC, numOfPublicPP DESC, numOfPrivatePP DESC";
									
									$result = mysqli_query($db, $query);
									
									include('lib/show_queries.php'); 
									
									if (is_bool($result) && (mysqli_num_rows($result) == 0) ) {
										array_push($error_msg,  "Query ERROR: Failed to get comment history <br>" . __FILE__ ." line:". __LINE__ );
									}
									while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
										print "<tr>";
										if ($row[0] == $_SESSION['email']) {
											echo "<td>" . "<div style=\"color: red;\">$row[1] $row[2]</div>" . "</td>";
											for ($k = 3; $k < 7; ++$k){
												if ($row[$k]) {
													print "<td>" . "<div style=\"color: red;\">$row[$k]</div>" . "</td>";
												} else {
													print "<td>" . "<div style=\"color: red;\">0</div>" . "</td>";
												}
											}
										}
										else{
											print "<td>" . $row[1] . ' ' . $row[2] . "</td>";
											for ($k = 3; $k < 7; ++$k){
												if ($row[$k]) {
													print "<td>$row[$k]</td>";
												} else {
													print "<td>0</td>";
												}
											}
										}
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