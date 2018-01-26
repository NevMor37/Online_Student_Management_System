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
    $name=($_POST['name']);

    $taassignmentemailhead=($_POST['taassignmentemailhead']);
    $taassignmentemailhead= str_replace("'","",$taassignmentemailhead);
    $sql = "UPDATE tbl_settings 
                set value = '$taassignmentemailhead'
                where Name = 'taassignmentemailhead'";
    //  echo  $sql;
    $result=mysqli_query($db,$sql);

    $taassignmentemailbody=($_POST['taassignmentemailbody']);
    $taassignmentemailbody= str_replace("'","",$taassignmentemailbody);
    $sql = "UPDATE tbl_settings 
                set value = '$taassignmentemailbody'
                where Name = 'taassignmentemailbody'";
  //  echo  $sql;
    $result=mysqli_query($db,$sql);

//    $taassignmentemailbodyvariable=($_POST['taassignmentemailbodyvariable']);
//    $taassignmentemailbodyvariable= str_replace("'","",$taassignmentemailbodyvariable);
//    $sql = "UPDATE tbl_settings
//                set value = '$taassignmentemailbodyvariable'
//                where Name = 'taassignmentemailbodyvariable'";
//    //  echo  $sql;
//    $result=mysqli_query($db,$sql);

    //header("location:termviewdashboard.php");  //redirect home page
    echo 'update successful';
    header("location:taassignmentEmailConfig.php");

}
else
{
    //echo 'getdata';
    $sql = "select Name, value
            from tbl_settings
            where Name = 'taassignmentemailbody' or Name = 'taassignmentemailbodyvariable' or Name ='taassignmentemailhead';
            " ;
    //echo $sql;
    $result = mysqli_query($db, $sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            //echo "<br>"."name: " . $row["Name"] . " value: " . nl2br ($row["value"]) ;
            $name="";
            $value="";
            $name = $row["Name"];
            $value= $row["value"];
            if($name == 'taassignmentemailbody')
            {
                //$TAApplicationstartdatetime=substr($value,0,10);
                $taassignmentemailbody=$value;
            }
            if($name == 'taassignmentemailbodyvariable')
            {
                //$TAApplicationstartdatetime=substr($value,0,10);
                $taassignmentemailbodyvariable=$value;
            }
            if($name == 'taassignmentemailhead')
            {
                //$TAApplicationstartdatetime=substr($value,0,10);
                $taassignmentemailhead=$value;
            }
            //echo $taassignmentemailbody;
        }
    }


    if(empty($taassignmentemailbody))
    {
        $Insertsql = "insert into tbl_settings(Name,value)
                values('taassignmentemailbody','')
                ";
        $insertresult = mysqli_query($db, $Insertsql);
    }

    if(empty($taassignmentemailbodyvariable))
    {
        $Insertsql = "insert into tbl_settings(Name,value)
                values('taassignmentemailbodyvariable','')
                ";
        $insertresult = mysqli_query($db, $Insertsql);
    }

    if(empty($taassignmentemailhead))
    {
        $Insertsql = "insert into tbl_settings(Name,value)
                values('taassignmentemailhead','')
                ";
        $insertresult = mysqli_query($db, $Insertsql);
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
        <form method="post" action="taassignmentEmailConfig.php">
          <table>
              <tr>
                  <td>TA Assignment Email : </td>
              </tr>
              <tr>
                  <td><textarea  name="taassignmentemailhead" value="<?php if(empty($taassignmentemailhead)==false){ echo $taassignmentemailhead;} ?>" cols="100" rows="1" style="text-align: center"><?php if(empty($taassignmentemailhead)==false){ echo $taassignmentemailhead;} ?></textarea></td>
              </tr>
              <tr>
                  <td><textarea  name="taassignmentemailbodyvariable" value="<?php if(empty($taassignmentemailbodyvariable)==false){ echo $taassignmentemailbodyvariable;} ?>" cols="100" rows="7" readonly style="color: grey"><?php if(empty($taassignmentemailbody)==false){ echo $taassignmentemailbodyvariable;} ?></textarea></td>
              </tr>
              <tr>
                  <td><textarea  name="taassignmentemailbody" value="<?php if(empty($taassignmentemailbody)==false){ echo $taassignmentemailbody;} ?>" cols="100" rows="10"><?php if(empty($taassignmentemailbody)==false){ echo $taassignmentemailbody;} ?></textarea></td>
              </tr>
              <tr>
                   <td><input type="submit" name="register_btn" class="Register" value="Submit"></td>
             </tr>

        </table>
        </form>
    </div>
</div>
</body>
</html>
