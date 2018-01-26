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
    $Subject=($_POST['Subject']);
    $Course=($_POST['Course']);
    $CourseName= ($_POST['CourseName']);
    $Credit=($_POST['Credit']);
    $Prerequisites=($_POST['Prerequisites']);
    $Description=($_POST['Description']);

         if($_SESSION["status"]=="create") {
             echo 'null';
             $sql = "INSERT INTO tbl_schoolcourse ( Subject, Course,CourseName, Credit,Prerequisites,Description) 
                      VALUES ( '$Subject', '$Course','$CourseName', '$Credit', '$Prerequisites', '$Description');
                    ";
         }
         else
         {
             $id = $_SESSION["id"];
             $sql = "update tbl_schoolcourse
                    set Subject='$Subject',Course='$Course',CourseName='$CourseName',Credit='$Credit',Prerequisites='$Prerequisites',Description='$Description'
                    where SchoolCourseID = $id";
         }
         echo  $sql;
            mysqli_query($db,$sql);
            //$_SESSION['message']="You are now logged in";
            header("location:schoolcourseviewdashboard.php");  //redirect home page
}
else
{
        if(empty($_GET['id'])==false) {
            if (is_numeric($_GET['id'])) {

                $id = (int)$_GET['id'];
                //echo $id;
                //$sql = "select id,username from user where id = " . $id;
                $sql = "
                        SELECT SchoolCourseID as SchoolCourseID,
                            Subject as Subject,
                            Course as Course,
                            CourseName as CourseName, 
                            Credit as Credit,
                            Prerequisites as Prerequisites,
                            Description as Description
                            FROM tbl_schoolcourse
                            where SchoolCourseID=
                         " . $id;

                $result = mysqli_query($db, $sql);

                if ($result->num_rows > 0) {
                    // output data of each row
                    while ($row = $result->fetch_assoc()) {
                       // echo "id: " . $row["SchoolCourseID"] . "<br>";
                        $id = $row["SchoolCourseID"];
                        $Subject = $row["Subject"];
                        $Course = $row["Course"];
                        $CourseName = $row["CourseName"];
                        $Credit = $row["Credit"];
                        $Prerequisites = $row["Prerequisites"];
                        $Description = $row["Description"];
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
            //echo $_SESSION["status"];
        }
}
?>
<!DOCTYPE html>
<html>
<head>
    <?php
    include $root.'/links/header.php';
    include $root.'/links/footerLinks.php';
    ?>
</head>
<body>
<div class="header">
    <h1></h1>
</div>
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
    <div class="row" id="printableArea">
        <form method="post" style="table-layout：fixed" action="schoolcourseregister.php">

          <table>
             <tr>
                  <td>Subject : </td>
                  <td><input type="text" name="Subject" class="textInput" value="<?php if(empty($id)==false){ echo $Subject;} ?>"  ></td>
              </tr>
              <tr>
                  <td>Course : </td>
                  <td><input type="text" name="Course" class="textInput" value="<?php if(empty($id)==false){ echo $Course;} ?>"  ></td>
              </tr>
              <tr>
                  <td>CourseName : </td>
                  <td><input type="text" name="CourseName" class="textInput" value="<?php if(empty($id)==false){ echo $CourseName;} ?>"  ></td>
              </tr>
              <tr>
                  <td>Credit : </td>

                  <td><input type="text" name="Credit" class="textInput" value="<?php if(empty($id)==false){ echo $Credit;} ?>" ></td>
              </tr>
             <tr>
                   <td>Prerequisites : </td>
                   <td><input type="text" name="Prerequisites" class="textInput"   value="<?php if(empty($id)==false){ echo $Prerequisites;} ?>" ></td>
             </tr>
              <tr>
                  <td>Description : </td>
                  <td><textarea  name="Description" class="textInput" cols="40" rows="10"  ><?php if(empty($id)==false){ echo $Description;} ?></textarea></td>
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
