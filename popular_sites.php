
<?php // popular site

include('lib/common.php');
// written by kchen343
if (!isset($_SESSION['email'])) {
	header('Location: login.php');  //redirect to login if user is not set
	exit();
}
?>

<?php include("lib/header.php"); ?>
		<title>Popular Sites</title>
	</head>
	
	<body>
		<div id="main_container">
        <?php include("lib/menu.php"); ?> 
			
		<div class="center_content">
			<div class="center_left">
				<div class="features">   
					<div class="profile_section">						
						<div class="subtitle">Popular Sites</div>
							<table >								
								<tr>
									<td class="heading">Site</td>
									<td class="heading">PushPins</td>
								</tr> 
								<?php			
									$query ="SELECT url, COUNT(url) AS frequency
											FROM
											(SELECT 
											left(right(PushPin.url, LENGTH(PushPin.url)-(position('//' in PushPin.url)+1)), position('/' in right(PushPin.url, LENGTH(url)-(position('//' in PushPin.url)+1)))-1) AS url
											FROM PushPin) as t1									
											GROUP BY url
											ORDER BY frequency DESC";
									
									$result = mysqli_query($db, $query);
									
									include('lib/show_queries.php'); 
									
									if (is_bool($result) && (mysqli_num_rows($result) == 0) ) {
										array_push($error_msg,  "Query ERROR: Failed to get comment history <br>" . __FILE__ ." line:". __LINE__ );
									}
									while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {

										print "<tr>";
										print "<td>" . $row[url] . "</td>";
										print "<td>" . $row[frequency] . "</td>";
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