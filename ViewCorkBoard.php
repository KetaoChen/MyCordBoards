
<?php
//View CorkBoard.php
//Written by Qianwen

include('lib/common.php');

if(!isset($_SESSION['email'])){
    header('Location: login.php');
    exit();
}

//1.Check if current user is the owner of the CorkBoard
$currentuser = $_SESSION['email'];

    if(!empty($_GET['cbID'])){
        //$currentCbID=$_GET['cbID'];
        $_SESSION['cbID']=$_GET['cbID'];
    }

//echo $currentuser;
$currentCbID=$_SESSION['cbID'];
//echo $currentCbID;
//echo $currentuser;
//echo $currentCbID;


//$query = "SELECT CB.email FROM `corkboard` AS CB WHERE CB.cbID=$currentCbID";//
//$result = mysqli_query($db, $query);
//$row_num = mysqli_num_rows($result);
//$row=mysqli_fetch_assoc($result);
//if ($row_num != 1) die("More than one CorkBoard ID found, error!");

//If current user is the owner, display Add Pushpin on the CorkBoard
//echo $owner;
//echo $currentuser;
//echo $_SESSION['owner'];
//$owner="michael@bluthco.com";


//------To select corkboard information----/////
$cbquery = "SELECT * FROM CorkBoard AS C WHERE C.cbID=$currentCbID";
$cbresult = mysqli_query($db, $cbquery);
$cbrow=mysqli_fetch_array($cbresult);

$owner=$cbrow['email'];
$_SESSION['owner']=$owner;

$title=$cbrow['title'];
$category=$cbrow['category'];
$cbtime=$cbrow['corkboard_updated_time'];
$visibility=$cbrow['private_type'];

//-----------To select owner name------------///
$query1 = "SELECT * FROM `User` AS U WHERE U.email='$owner'";
$result1= mysqli_query($db, $query1);
$row1=mysqli_fetch_assoc($result1);

$first=$row1['first_name'];
$last=$row1['last_name'];


//-----To select whatch list----------///
$watchquery = "SELECT COUNT(W.watcher_email) AS numwatch FROM Watch As W WHERE W.cbID=$currentCbID"; //COUNT numbers
$watchresult = mysqli_query($db, $watchquery);

$watchrow=mysqli_fetch_array($watchresult);
$numwatcher=$watchrow['numwatch'];

?>


<!DOCTYPE HTML>
<html>

    <?php include("lib/header.php");?>
    <title>View CorkBoard</title>
    <head></head>
    <body>
        <div id="main_container">
            <?php include("lib/menu.php");?>

                <div class="center_content">
                    <table>
                        <tr>
                            <td>
                                <div class="qs_left">
                                    <div class="title_name">
                                        <?php echo $first."  ".$last; ?>
                                        <?php if($currentuser!=$owner){
                                            include('Follow.php');} ?>
                                    </div>
                                </div>
                            </td>


                            <td>
                                <div class="qs_right">
                                    <div class="qs_cate">
                                        <?php echo $category;?>
                                    </div>
                                </div>
                            </td>
                        </tr>


                    </table>


                    <table>
                        <tr>
                            <td>
                                <div class="qs_cl">
                                    <div class="qs_title">
                                        <?php echo $title;?>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>

                    <table>
                        <tr>
                            <td>
                                <div class="qs_cent1">
                                    <div class="qs_body">
                                        <?php echo "Last updated time: ".$cbtime;?>
                                    </div>
                                </div>
                            </td>

                            <td align="right"><div class="qs_right"><?php if($currentuser==$owner) {
                                            echo <<<_END
                                                <form action=add_pushpin.php?cbID=$currentCbID>
                                                <input type="submit" value="Add PushPin" />
                                                </form>
_END;
                                        }?></div>
                            </td>
                        </tr>
                    </table>

                    <table><div class="center_content">
                        <?php
                        /**
                         * Created by PhpStorm.
                         * User: hibar
                         * Date: 2018/11/24
                         * Time: 0:00
                         */

                        include('lib/common.php');

                        $urlquery="SELECT PP.url, PP.ppID FROM PushPin AS PP WHERE PP.cbID='$currentCbID'"; //'1'
                        $urlresult=mysqli_query($db,$urlquery);

                        if(empty($urlresult)){
                            echo "Currently, there are no PushPins on this CorkBoard :(";
                        }

                        $j=0;
                        echo "<tr>";
                        while ($urlrow=mysqli_fetch_array($urlresult, MYSQLI_ASSOC)){

                            $ppID=$urlrow['ppID'];
                            $image=base64_encode(file_get_contents($urlrow['url']));

                            $j=$j+1;

                            echo "<td>";
                            echo '<a href="view_pushpin.php?ppID='.$ppID.'">';
                            echo '<img src="data:image/jpg;base64,'.$image.'" width="200" height=auto>';
                            echo '</a>';
                            echo "</td>";

                            if($j%2==0) {
                                echo "</tr>";
                                echo "<tr>";
                            }
                        }
                        echo "</tr>";
                        ?>
                    </table>

                    <table><div class="center_content">
                        <tr>
                            <td>
                                <div class="qs_cent1">
                                    <div class="qs_body">
                                        <?php if($visibility=="public"){
                                            echo "This CorkBoard has ".$numwatcher." watchers";
                                        }else{
                                            echo "This is a private CorkBoard.";
                                        }?>
                                    </div>
                                </div>
                            </td>

                            <td align="right">
                                <div class="qs_right">
                                    <?php if($currentuser!=$owner AND $visibility=="public"){include ('Watch.php');}?>
                                </div>
                            </td>
                        </tr>
                        </div>
                    <table>
                </div>

        <?php include("lib/error.php"); ?>

        <div class="clear"></div>
        </div>

        <?php include("lib/footer.php"); ?>

        </div>

        </body>
</html>
