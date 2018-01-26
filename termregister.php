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
    $startdate=($_POST['startdate']);
    $enddate=($_POST['enddate']);
     if($_SESSION["status"]=="create") {
         //echo 'null';
         $sql = "INSERT INTO tbl_term(Term,StartDay,EndDay) 
                VALUES('$name','$startdate','$enddate')";
     }
     else
     {
         $id = $_SESSION["id"];
         $sql = "UPDATE tbl_term 
                set Term='$name',StartDay = '$startdate',EndDay='$enddate'
                where Termid = $id";
     }
    // echo  $sql;
        mysqli_query($db,$sql);
        header("location:termviewdashboard.php");  //redirect home page
}
else
{
        if(empty($_GET['id'])==false) {
            if (is_numeric($_GET['id'])) {

                $id = (int)$_GET['id'];

                $sql = "select Termid as id,Term as name,StartDay as startdate, EndDay as enddate
                        from tbl_term 
                        where Termid=
                        " . $id;
                //echo $sql;
                $result = mysqli_query($db, $sql);

                if ($result->num_rows > 0) {
                    // output data of each row
                    while ($row = $result->fetch_assoc()) {
                        //echo "id: " . $row["id"] .  "<br>";
                        $id = $row["id"];
                        $name = $row["name"];
                        $startdate = $row["startdate"];
                        $enddate = $row["enddate"];
                        $_SESSION["id"] = $id;
                    }
                } else {
                    //echo "0 results";
                }

                $_SESSION["status"] = "update";
               // echo $_SESSION["status"];
            }
        }
        else
        {
            $_SESSION["status"] = "create";
           // echo $_SESSION["status"];
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
        <form method="post" action="termregister.php">
          <table>
             <tr>
                  <td>Term Name : </td>
                  <td><input type="text" name="name" class="textInput" value="<?php if(empty($id)==false){ echo $name;} ?>"  ></td>
              </tr>
              <tr>
                  <td>Startdate : </td>
                  <td><input type="date" name="startdate" class="textInput" value="<?php if(empty($id)==false){ echo $startdate;} ?>" ></td>
              </tr>
              <tr>
                  <td>Enddate : </td>
                  <td><input type="date" name="enddate" class="textInput" value="<?php if(empty($id)==false){ echo $enddate;} ?>" ></td>
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
