<?php 

if(isset($_POST['StudentId'])){$StudentId=$_POST['StudentId'];} 
if(isset($_POST['StudentName'])){$StudentName=$_POST['StudentName'];}

include("db_connection.php");

$date = date("Y-m-d");
$query = "INSERT INTO tbl_checkin(PantherId, InDate) VALUES('$StudentId','$date')";

if (!($result = $mysqli->query($query))) {
        exit(mysql_error());
    }
echo "Student ".$StudentName." Checked-In Successfully";
 
?>


         
          
          
          