<?php
//Watch.php
//Written by Qianwen Shi

include('lib/common.php');

if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}

$currentUser=$_SESSION['email'];
$currentCbID=$_SESSION['cbID'];
//$CBowner=$_SESSION['owner'];

//-------------------check if current user is following the owner-----//
$wquery="SELECT W.watcher_email FROM Watch As W WHERE W.cbID='$currentCbID'";
$wresult=mysqli_query($db,$wquery);

//echo mysqli_num_rows($fresult);

if (!is_bool($wresult) && mysqli_num_rows($wresult)>0){
    $watchstatus =1;
    While ($wrow = mysqli_fetch_array($wresult, MYSQLI_ASSOC)) {
        if ($wrow['watcher_email'] == $currentuser) {
            $watchstatus = 0;
            break;
        }
    }
}else{
    $watchtatus=1;
}

/* if form was submitted, then execute query to insert data to follow*/
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if ($_POST['watch'] == "Watch"){
        $query = "INSERT INTO Watch VALUES ('$currentUser','$currentCbID')";
        $result=mysqli_query($db,$query);
        $watchstatus = 0;
    }

}

?>

<!DOCTYPE HTML>
<html>
<body>
<?php
if($watchstatus == 1){
    echo '<form name="watchform" action="ViewCorkBoard.php" method="POST"><input type = "submit" value = "Watch" name = "watch"></form>';
}else {
    echo '<button type="button" disabled>Watch</button>';
}
?>


</body>
</html>
