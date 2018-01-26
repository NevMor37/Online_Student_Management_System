<?php
if(isset($_POST['StudentId'])){$StudentId=$_POST['StudentId'];}
if(isset($_POST['StudentName'])){$StudentName=$_POST['StudentName'];}

include("db_connection.php");

$date = date("Y-m-d");
$query = "DELETE from tbl_checkin WHERE PantherId = '$StudentId'";

if (!($result = $mysqli->query($query))) {
	exit(mysql_error());
}
echo "Student ".$StudentName." Deleted Successfully";

//sendEmail($facultyId, $StudentId,$mysqli2);

?>