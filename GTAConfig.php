<?php
if (isset($_SERVER['HTTP_HOST']))
{
    if($_SERVER['HTTP_HOST'] == "localhost")
    {
        $root =  realpath($_SERVER["CONTEXT_DOCUMENT_ROOT"]);
        define("ROOT",$root."/student/ogms/public_html");
        $root = ROOT;
    }
    else
    {
        $root =  realpath($_SERVER["CONTEXT_DOCUMENT_ROOT"]);
    }
}
else
{
    $root =  realpath($_SERVER["CONTEXT_DOCUMENT_ROOT"]);
}
session_start();
$user_name = $_SESSION['user']['name'] ;
$user_email = $_SESSION['user']['mail'] ;
$user_pantherid = $_SESSION['user']['pid'] ;
//include $root.'/authenticate.php';
include($root.'/osms.dbconfig.inc');
$error_message = "";
$counter = 0;

$mysqli = new mysqli($hostname,$username, $password,$dbname);

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
?>
<?php
//connect to database
$db=$mysqli;

if(isset($_POST['register_btn']))
{
    //$username=($_POST['firstName']);
    $name=($_POST['name']);
    $TAApplicationstartdatetime=($_POST['TAApplicationstartdatetime'])." 00:00:01";
    $TAApplicationEnddatetime=($_POST['TAApplicationEnddatetime'])." 23:59:59";
    //echo '$TAApplicationstartdatetime:'.$TAApplicationstartdatetime;
    //echo '$TAApplicationEnddatetime:'.$TAApplicationEnddatetime;
//     if($_SESSION["status"]=="create") {
//         //echo 'null';
//         $sql = "INSERT INTO tbl_term(Term,StartDay,EndDay)
//                VALUES('$name','$startdate','$enddate')";
//     }
//     else
//     {
         $sql = "UPDATE tbl_settings 
                set value = '$TAApplicationstartdatetime'
                where Name = 'TAApplicationStartdatetime'";
    //}
    // echo  $sql;
        $result=mysqli_query($db,$sql);

        $sql = "UPDATE tbl_settings 
                    set value = '$TAApplicationEnddatetime'
                    where Name = 'TAApplicationEnddatetime'";
        $result=mysqli_query($db,$sql);
        //header("location:termviewdashboard.php");  //redirect home page
    echo 'update successful';

}
else
{
    //echo 'getdata';
    $sql = "select Name, value
            from tbl_settings
            " ;
    //echo $sql;
    $result = mysqli_query($db, $sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            //echo "<br>"."name: " . $row["Name"] . " id: " . $row["value"] ;
            $name="";
            $value="";
            $name = $row["Name"];
            $value= $row["value"];
            if($name == 'TAApplicationstartdatetime')
            {
                //$TAApplicationstartdatetime=substr($value,0,10);
                $TAApplicationstartdatetime=$value;
                //echo 'TAApplicationstartdatetime:'. $TAApplicationstartdatetime;
            }
            if($name == 'TAApplicationEnddatetime')
            {
                $TAApplicationEnddatetime=$value;
                //echo 'TAApplicationEnddatetime:'. $TAApplicationEnddatetime;
            }
        }
    } else
    {
        //echo "0 results";
    }


}
?>
<!DOCTYPE html>
<html>
<!-- Header -->
<head>
    <?php
    include $root.'/links/header.php';
    include $root.'/links/footerLinks.php';
    ?>

</head>
<!-- /#Header -->
<body>
<div id="wrapper">
<?php
    if(isset($_SESSION['message']))
    {
         echo "<div id='error_msg'>".$_SESSION['message']."</div>";
         unset($_SESSION['message']);
    }
?>
    <!-- Navigation -->
    <?php
    include $root.'/UI/staff/staffmenu.php';
    ?>
    <!-- /#Navigation -->

    <!-- page-wrapper -->
    <div id="page-wrapper">
        <form method="post" action="GTAConfig.php">
          <table>
              <tr>
                  <td>TA Application Start datetime  : </td>
                  <td><input type="date" name="TAApplicationstartdatetime" class="textInput" value="<?php if(empty($TAApplicationstartdatetime)==false){ echo substr($TAApplicationstartdatetime,0,10);} ?>" ></td>
              </tr>
              <tr>
                  <td>TA Application End datetime : </td>
                  <td><input type="date" name="TAApplicationEnddatetime" class="textInput" value="<?php if(empty($TAApplicationEnddatetime)==false){ echo substr($TAApplicationEnddatetime,0,10);} ?>" ></td>
              </tr>
              <tr>
                   <td></td>
                   <td><input type="submit" name="register_btn" class="Register" value="Submit"></td>
             </tr>

        </table>
        </form>
    </div>
</div>
</body>
</html>
