<?php

include('lib/common.php');
// written by jzhang3056

if (!isset($_SESSION['email'])) {
	header('Location: login.php');  //redirect to login if user is not set
	exit();
}

if (!isset($_SESSION['ppID'])) {
	header('Location: add_pushpin.php');  //redirect to add pushpin if pushpin is not set
	exit();
}
	
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	$Comment = mysqli_real_escape_string($db, $_POST['Comment']);

	if (!empty($Comment)) {
		/*array_push($error_msg,  "Error: You must provide a Comment ");
	}
	else{*/
		$query = "INSERT INTO CommentOn (commenter_email, comment_content, comment_time, ppID) " .
					 "VALUES ('{$_SESSION['email']}', '$Comment', NOW(), '{$_SESSION['ppID']}')";
					 
		$commentID = mysqli_query($db, $query);

        include('lib/show_queries.php');

        if (mysqli_affected_rows($db) == -1) {
             array_push($error_msg, "Error: Failed to add Comment: '" . $Comment .  "'<br>" . __FILE__ ." line:". __LINE__ );
        } 
            
	}
}

?>

<?php //include("lib/header.php"); ?>
	<title>PushPin Comments</title>
	</head>
	
	<body>
		<div id="main_container">
		<?php //include("lib/menu.php"); ?>	
        <div class="center_content">
            <div class="center_left">
                <div class="features">   
                      <div class="title_name"> Comment </div>
                            <div class="profile_section">						
           
                                <form name="commentform" action="view_pushpin.php" method="POST">
                                    <table >								
                                        <tr>
                                            <td class="item_label">Say something</td>
                                            <td><input type="textbox" name="Comment" /></td>
                                        </tr>
                                    </table>
                                <input type="submit" name="submit" value="Add Comment"> 					
                                    </form>							
                            </div>
                        
            
                        <div class="profile_section">
                                
                                <?php			
                                    $query = "SELECT first_name, last_name, comment_content, comment_time " .
											"FROM PushPin JOIN CommentOn ON PushPin.ppID = CommentOn.ppID " .
											"LEFT JOIN User ON CommentOn.commenter_email = User.email " .
											"WHERE PushPin.ppID='{$_SESSION['ppID']}' ORDER BY comment_time DESC ";
                                    
                                    $result = mysqli_query($db, $query);
                                    
                                    include('lib/show_queries.php'); 
                                    if (is_bool($result) && (mysqli_num_rows($result) == 0) ) {
                                        array_push($error_msg,  "Query ERROR: Failed to get comment history <br>" . __FILE__ ." line:". __LINE__ );
                                    }
                                    
                                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                                    $count = mysqli_num_rows($result);
                                   
                                   if ($row) {
                                        print '<table>';
                                        print '<tr>';
                                        print '<td class="heading">Name</td>';
                                        print '<td class="heading">Comment</td>';
										print '<td class="heading">Date</td>';
                                        print '</tr>';
                                    
                                        while ($row){            
                                            print '<tr>';
											print "<td>{$row['first_name']} {$row['last_name']}</td>";
                                            print '<td>' . $row['comment_content'] . '</td>';
                                            print '<td>' . $row['comment_time'] . '</td>';
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
		</div>
	</body>
</html>