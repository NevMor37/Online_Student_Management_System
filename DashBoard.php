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
include $root.'/authenticate.php';
if(isset($_GET['PantherId'])) {
	$patherid = $_GET['PantherId'];
}
else
{
	$patherid = 110;
}
?>
<?php 
include('osms.dbconfig.inc');
$error_message = "";
$counter = 0;

$mysqli = new mysqli($hostname,$username, $password,$dbname);

/* check connection */
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}


$sql = "SELECT * FROM ogms.tbl_student_evaluation where FacultyId='".$user_email."';";
$result = $mysqli->query($sql);
$row_cnt = $result->num_rows;


if ($result->num_rows > 0) {
	// output data of each row
	$count = 0;
	while($row = $result->fetch_assoc()) {
		$Id 				=$row["Id"];
		$LOR1               =$row["LOR1"];
		$LOR2               =$row["LOR2"];
		$LOR3               =$row["LOR3"];
		$SOP                =$row["SOP"];
		$Recommendation     =$row["Recommendation"];
		$FinAid             =$row["FinAid"];
		$Comments           =$row["Comments"];
		$FacultyName        =$row["FacultyName"];
		$Date               =$row["Date"];
		$StudentId          =$row["StudentId"];
		$FacultyId          =$row["FacultyId"];
	}
}
?>