<?php

include('lib/common.php');
// written by kchen343

if (!isset($_SESSION['email'])) {
	header('Location: login.php');  //redirect to login if user is not set
	exit();
}

if( $_SERVER['REQUEST_METHOD'] == 'GET') {
	$keyword = mysqli_real_escape_string($db, $_GET['keyword']);
	$query ="SELECT  DISTINCT p.ppID, p.description, c.title, u.first_name, u.last_name
		FROM `User` as u
		LEFT JOIN CorkBoard as c NATURAL JOIN PublicCorkBoard as pc NATURAL JOIN PushPin as p NATURAL JOIN Tag as t
		ON u.email = c.email
		WHERE p.description LIKE '%$keyword%' OR t.tag_content LIKE '%$keyword%' OR
		c.category LIKE '%$keyword%'

		ORDER BY p.description, c.title, u.first_name, u.last_name";

	$result = mysqli_query($db, $query);
	include('lib/show_queries.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	
	$keyword = mysqli_real_escape_string($db, $_POST['keyword']);
	
	$query ="SELECT  DISTINCT p.ppID, p.description, c.title, u.first_name, u.last_name
			FROM `User` as u
			LEFT JOIN CorkBoard as c NATURAL JOIN PublicCorkBoard as pc NATURAL JOIN PushPin as p NATURAL JOIN Tag as t
			ON u.email = c.email
			WHERE p.description LIKE '%$keyword%' OR t.tag_content LIKE '%$keyword%' OR
			c.category LIKE '%$keyword%'

			ORDER BY p.description, c.title, u.first_name, u.last_name";

	$result = mysqli_query($db, $query);
	include('lib/show_queries.php');
}




?>

<?php include("lib/header.php"); ?>
		<title>Search PushPin</title>
	</head>
	
	<body>
    	<div id="main_container">
            <?php include("lib/menu.php"); ?>
			
			<div class="center_content">
				<div class="center_left">       			
					<div class="features">   
						<div class="profile_section">						
							<div class="subtitle">Search PushPin</div> 
							
							<form name="searchform" action="search_pushpin.php" method="POST">
								<table>								
									<tr>
										<td class="item_label">Keyword</td>
										<td><input type="text" name="keyword" /></td>
									</tr>

									
								</table>
									<a href="javascript:searchform.submit();" class="fancy_button">Search</a> 					
							</form>							
						</div>
					 

					
							<div class='profile_section'>
								<div class='subtitle'>Search Results</div>
								<table>
									<tr>
										<td class='heading'>PushPin Description</td>
										<td class='heading'>CorkBoard</td>
										<td class='heading'>Owner</td>
									</tr>
									<?php
									if (isset($result)) {
										while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
											$owner = $row[first_name] .' '. $row[last_name];
											print "<tr>";
											print "<font size='3pt'> <td><a href='view_pushpin.php?ppID=$row[ppID]'> {$row[description]} </a></td> </font> ";
											//print "<td>$row[description]</td>";
											print "<td>$row[title]</td>";	
											print "<td>$owner</td>";	
											print "</tr>";
										}
									}	?>
										
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