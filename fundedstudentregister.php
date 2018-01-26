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
    $id=($_POST['id']);
    $firstname=($_POST['firstname']);
    $middlename=($_POST['middlename']);
    $lastname=($_POST['lastname']);
    $email=($_POST['email']);
    $mobilenumber=($_POST['mobilenumber']);
    $college = ($_POST['college']);
    $department=($_POST['department']);
    $location=($_POST['location']);
    $degree = ($_POST['degree']);
    $major = ($_POST['major']);
    $concentration = ($_POST['concentration']);
    $starttermid = ($_POST['starttermid']);
    $position = ($_POST['position']);
    $status = ($_POST['status']);

     if($_SESSION["status"]=="create") {
         echo 'null';
         $sql = "insert into tbl_student_info(PantherID,FirstName,MiddleName,LastName,
                                              Email,MobileNumber,College,Department,Location,Degree,Major,
                                              Concentration,Position,Status,matricTerm)
                  values('$id','$firstname','$middlename','$lastname',
                          '$email','$mobilenumber','$college','$department','$location','$degree','$major',
                                            '$concentration','$position','$status','$starttermid')
                ";
     }
     else
     {
         $id = $_SESSION["id"];
         $sql = "   update tbl_student_info
                    set FirstName='$firstname',MiddleName='$middlename',LastName='$lastname',
                        Email='$email',MobileNumber='$mobilenumber',College='$college',
                        Department='$department',Location='$location',Degree='$degree',
                        Major='$major',Concentration='$concentration',Position='$position',
                        Status='$status',matricTerm='$starttermid'
                    where PantherID = $id";
     }
     echo  $sql;
        mysqli_query($db,$sql);
        header("location:studentviewdashboard.php");  //redirect home page

}
else
{
        if(empty($_GET['id'])==false) {
            if (is_numeric($_GET['id'])) {

                $id = (int)$_GET['id'];
                //echo $id;
                //$sql = "select id,username from user where id = " . $id;
                $sql = "select  PantherID as id,
                                FirstName as firstname,
                                MiddleName as middlename,
                                LastName as lastname,
                                Email as email,
                                MobileNumber as mobilenumber,
                                College as college,
                                Department as department,                                
                                Location as location,
                                Degree as degree,
                                Major as major,
                                Concentration as concentration,
                                matricTerm as matricTerm,
                                Position as position,
                                Status as status
                        from tbl_student_info
                        where PantherID = " . $id;

                $result = mysqli_query($db, $sql);

                if ($result->num_rows > 0) {
                    // output data of each row
                    while ($row = $result->fetch_assoc()) {
                        //echo "id: " . $row["id"] .  "<br>";
                        $id = $row["id"];
                        $firstname = $row["firstname"];
                        $middlename = $row["middlename"];
                        $lastname = $row["lastname"];
                        $email = $row["email"];
                        $mobilenumber = $row["mobilenumber"];
                        $college = $row["college"];
                        $department = $row["department"];
                        $location = $row["location"];
                        $degree = $row["degree"];
                        $major = $row["major"];
                        $concentration = $row["concentration"];
                        $starttermid = $row['matricTerm'];
                        $position = $row["position"];                      
                        $status = $row["status"];
                        $_SESSION["id"] = $id;
                        //echo $position;
                        //echo $status;
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

$termarray = array();

$sql = "select Termid ,Term ,StartDay , EndDay 
                from tbl_term 
         ";

$result = mysqli_query($db, $sql);

if ($result->num_rows > 0) {
    $i = 0;
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        $termarray[$i] = array();
        $termarray[$i]['Termid'] = $row["Termid"];
        $termarray[$i]['Term'] = $row["Term"];
        $termarray[$i]['Startday'] = $row["Startday"];
        $termarray[$i]['Endday'] = $row["Endday"];
        $i = $i + 1;
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
<div id="wrapper">
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
<div id="page-wrapper">
<form method="post" action="fundedstudentregister.php">
  <table>
      <tr>
          <td>Term:</td>
          <td>
              <select name="termid" id="termid" >
                  <?php
                  foreach ($termarray as $arr)
                  {
                      $p_Termid = $arr["Termid"];
                      $p_Term = $arr["Term"];
                      echo '$p_Termid'.$p_Termid;
                      echo "<option value='$p_Termid'";

                      if($starttermid==$p_Termid)
                      {
                          echo " selected ";
                      }
                      echo  "    >
	                      $p_Term
	                      </option>";
                  }
                  ?>
              </select>
          </td>
      </tr>
      <tr>
          <td>PantherID : </td>
          <td><input type="text" name="id" class="textInput" value="<?php if(empty($id)==false){ echo $id;} ?>"  ></td>
      </tr>
      <tr>
          <td>Position: </td>
          <td ALIGN="left" style="left: inherit">
              <input type="radio" name="position" value="PHD" <?php if(empty($id)==false){ if($position =='instructor'){echo 'checked';} } ?> >PHD
              <input type="radio" name="position" value="MS" <?php if(empty($id)==false){ if($position =='notinstructor'){echo 'checked';} }else {echo 'checked';} ?> >MS
          </td>
      </tr>
      <tr>
          <td>Department: </td>
          <td><input type="text" name="department" class="textInput"  value="<?php if(empty($id)==false){ echo $department;} ?>" ></td>
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
