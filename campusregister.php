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
$db=$mysqli;

if(isset($_POST['register_btn']))
{
    $name=($_POST['name']);
    $location=($_POST['location']);
    $phonenumber=($_POST['phonenumber']);


         if($_SESSION["status"]=="create") {
            // echo 'null';
             $sql = "INSERT INTO tbl_campus ( Name, Location, PhoneNumber) 
                      VALUES ( '$name', '$location', '$phonenumber');
                    ";
         }
         else
         {
             $id = $_SESSION["id"];
             $sql = "update tbl_campus
                    set Name='$name',Location='$location',PhoneNumber='$phonenumber'
                    where Campusid = $id";
         }
         //echo  $sql;
            mysqli_query($db,$sql);
            //$_SESSION['message']="You are now logged in";
            header("location:campusviewdashboard.php");  //redirect home page
}
else
{
        if(empty($_GET['id'])==false) {
            if (is_numeric($_GET['id'])) {

                $id = (int)$_GET['id'];
                //echo $id;
                //$sql = "select id,username from user where id = " . $id;
                $sql = "
                        SELECT Campusid as id,
                            Name as name,
                            Location as location,
                            PhoneNumber as phonenumber
                            FROM tbl_campus
                            where Campusid=
                         " . $id;

                $result = mysqli_query($db, $sql);

                if ($result->num_rows > 0) {
                    // output data of each row
                    while ($row = $result->fetch_assoc()) {
                        //echo "id: " . $row["id"] . "<br>";
                        $id = $row["id"];
                        $name = $row["name"];
                        $location = $row["location"];
                        $phonenumber = $row["phonenumber"];
                        $_SESSION["id"] = $id;
                    }
                } else {
                    //echo "0 results";
                }
                $_SESSION["status"] = "update";
                //echo $_SESSION["status"];
            }
        }
        else
        {
            $_SESSION["status"] = "create";
            //echo $_SESSION["status"];
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
        <form method="post" action="campusregister.php">
          <table>
             <tr>
                  <td>Name : </td>
                  <td><input type="text" name="name" class="textInput" value="<?php if(empty($id)==false){ echo $name;} ?>"  ></td>
              </tr>
              <tr>
                  <td>Location : </td>

                  <td><input type="text" name="location" class="textInput" value="<?php if(empty($id)==false){ echo $location;} ?>" ></td>
              </tr>
             <tr>
                   <td>PhoneNumber : </td>
                   <td><input type="text" name="phonenumber" class="textInput"   value="<?php if(empty($id)==false){ echo $phonenumber;} ?>" ></td>
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
