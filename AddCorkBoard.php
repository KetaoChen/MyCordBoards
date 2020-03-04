<!DOCTYPE HTML>
<html>
<title>Add CorkBoard</title>
<head>
    <style>
.error{coclor:#FF0000;}
    </style>
</head>

<?php
//AddCorkBoard.php
//Written by Qianwen Shi


include('lib/common.php');
if(!isset($_SESSION['email'])){
    header('Location: login.php');
    exit();
}

//Use $_SESSION to track user and corkboard ID
//session_start();
$CBemail=$_SESSION['email'];

//$CBemail='michael@bluthco.com';
//$_SESSION['email']=$CBemail;

if( $_SERVER['REQUEST_METHOD'] == 'POST') {

    if (empty($_POST['title'])) {
        //echo "<script>alert('Please enter a title')</script>";
        $titleErr='Please enter a title';
    }
    elseif (strlen($_POST['title'])>40){
        $lenErr='The Maximum length of title is 40';

    } elseif ($_POST['privatetype'] == "private" AND empty($_POST['CBpassword'])) {
        //echo "<script>alert('Please enter the password for your private CorkBoard')</script>";
        $nopass='Please enter the password for your private CorkBoard';

    } elseif($_POST['privatetype'] == "private" AND strlen($_POST['CBpassword'])>18){
        //echo "<script>alert('Max length of password is 18')</script>";
        $passErr='The Maximum length of password is 18';
    }
        elseif ($_POST['privatetype'] == "public" AND !empty($_POST['CBpassword'])){
        //echo "<script>alert('Please choose private if you want to set a password for your CorkBoard')</script>";
            $privErr='Please choose private if you want to set a password for your CorkBoard';
    } else {
        $title = mysqli_real_escape_string($db, $_POST['title']);
        $privatetype = mysqli_real_escape_string($db, $_POST['privatetype']);
        $selectcat = mysqli_real_escape_string($db, $_POST['selectcat']);
        $password = mysqli_real_escape_string($db, $_POST['CBpassword']);


        $query = "INSERT INTO CorkBoard VALUES (DEFAULT, '$CBemail','$title','$privatetype',NOW(),'$selectcat')";
        $result = mysqli_query($db, $query);
        include('lib/show_queries.php');
        if (!$result) die("Adding CorkBoard failed, please try again");


        $idquery = "SELECT MAX(cbID) from CorkBoard";
        $idresult = mysqli_query($db, $idquery);
        $idrow = mysqli_fetch_array($idresult);
        $id = $idrow[0];

        if ($privatetype == "private") {
            $priquery = "INSERT INTO PrivateCorkBoard VALUES('$id','$password')";
            $priresult = mysqli_query($db, $priquery);
            session_start();
            $_SESSION['cbID'] = $idrow[0];
            header("Location: PasswordProtection.php");
        } else {
            $pubquery = "INSERT INTO PublicCorkBoard VALUES('$id')";
            $pubresult = mysqli_query($db, $pubquery);
            session_start();
            $_SESSION['cbID'] = $idrow[0];
            header("Location: ViewCorkBoard.php");
        }
    }
}
?>

<?php include("lib/header.php");?>

    <body>
        <div id="main_container">
            <?php include("lib/menu.php");?>


            <div class="center_content">
                <div class="center_left">
                    <div class="title_name"><?php print 'Add CorkBoard' ?></div>
                    <div class="features">

                        <div class="profile_section">
                            <p><span class="error">* required field</span></p>
                            <form name="AddCorkBoard" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
                                <table>
                                    <tr>
                                        <td class="item_label">Title*
                                        <input type="title" name="title"/><span class="error"><?php
                                                print $titleErr;
                                                print '<br>';
                                                print $lenErr;?></span></td>
                                    </tr>
                                    <tr><br></tr>
                                    <tr>
                                        <td class="item_label">Public
                                        <input type="radio" name="privatetype" value="public" checked="checked"></td>
                                    </tr>
                                    <tr>
                                        <td class="item_label">Private
                                        <input type="radio" name="privatetype" value="private" ><span class="error"><?php print $privErr;?></span>

                                        <class="item_label">Set Password
                                        <input type="text" name="CBpassword"><span class="error"><?php
                                                print $nopass;
                                                echo '<br>';
                                                print $passErr;?></span></td>
                                    </tr>

                                    <tr>
                                        <td class="item_label">Category*

                                            <select type="text" name="selectcat" size="1">
                                                <?php
                                                $query1="SELECT * FROM Category ORDER BY category_name ASC";
                                                $result1=mysqli_query($db, $query1);
                                                if(!$result1) die("Query failed");

                                                While($row=mysqli_fetch_array($result1)){?>
                                                    <option value="<?php echo $row["category_name"];?>"><?php echo $row["category_name"]; ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </td>
                                    </tr>

                                </table>
                                <br><br>
                                <input type="submit" name="submit" value="Add CorkBoard">
                            </form>
                        </div>

                        <div class="profile_section">

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








