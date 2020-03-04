<!DOCTYPE HTML>
<html>
<head>
</head>
<body>
<?php

//Password Protection.php
//Written by Qianwen Shi

include('lib/common.php');
if(!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}

    if (!empty($_GET['cbID'])) {
        $_SESSION['cbID']=$_GET['cbID'];
    }

    $ID=$_SESSION['cbID'];

    //echo $ID;

    $querypass = "SELECT PC.password FROM PrivateCorkBoard AS PC WHERE PC.cbID=$ID";
    $resultpass = mysqli_query($db, $querypass);
    $row = mysqli_fetch_assoc($resultpass);
    echo $row['password'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $passcheck = mysqli_real_escape_string($db, $_POST['passcheck']);

        if ($passcheck == $row['password']) {
            session_start();
            $_SESSION['cbID']=$ID;
            header("Location:ViewCorkBoard.php");
        } else {
            echo "<script>alert('Wrong password, please try again.')</script>";
        }
    }

?>

<?php include("lib/header.php");?>
<title>Password Protection</title>


    <div id="main_container">
        <?php include("lib/menu.php");?>


        <div class="center_content">
            <div class="center_left">
                <div class="title_name"><?php print 'Password Protection for Private CorkBoard' ?></div>
                <div class="features">

                    <div class="profile_section">
                        <form name="PasswordProtection" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
                            <table>
                                <tr>
                                    <div class="subtitle">Please enter the password to access this private CorkBoard</div>
                                    <td><input type="text" name="passcheck"></td>
                            </table>
                            <br><br><br>
                            <input type="submit" value="Enter">
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






