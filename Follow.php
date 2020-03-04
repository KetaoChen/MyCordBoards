<?php
//Follow.php
//Written by Qianwen

include('lib/common.php');

if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}

$currentUser=$_SESSION['email'];
$currentCbID=$_SESSION['cbID'];
$CBowner=$_SESSION['owner'];

//-------------------check if current user is following the owner-----//
$fquery="SELECT F.email FROM Follow As F WHERE F.followee_email='$CBowner'";
$fresult=mysqli_query($db,$fquery);

//echo mysqli_num_rows($fresult);


if (mysqli_num_rows($fresult)>0){
    $followstatus =1;
    While ($frow = mysqli_fetch_array($fresult, MYSQLI_ASSOC)) {
        if ($frow['email'] == $currentuser) {
            $followstatus = 0;
            break;
        }
    }
}else{
    $followstatus=1;
}

/* if form was submitted, then execute query to insert data to follow*/
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if ($_POST['follow'] == "Follow") {
        $query = "INSERT INTO Follow VALUES ('$currentUser', '$CBowner')";
        $result=mysqli_query($db,$query);
        $followstatus = 0;
    }

}

    ?>

<!DOCTYPE HTML>
<html>
<body>
    <?php
    if($followstatus == 1){
    echo '<form name="followform" action="ViewCorkBoard.php" method="POST"><input type = "submit" value = "Follow" name = "follow"></form>';
}else {
    echo '<button type="button" disabled>Follow</button>';
}
    ?>

</body>
</html>
