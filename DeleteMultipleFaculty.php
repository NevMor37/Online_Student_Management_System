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
$Batch  = false;

if(isset($_POST['StudentId'])){$StudentId=$_POST['StudentId'];}


include($root.'/osms.dbconfig.inc');

$error_message = "";
$counter = 0;

$mysqli = new mysqli($hostname,$username, $password,$dbname);

/* check connection */
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}




$StuID = (explode(",",$StudentId));
$StCount = 0;
$test = "";
$arrlength = count($StuID);
for($x = 0; $x < $arrlength-1; $x++) {
	 $query = "DELETE FROM tbl_faculty_info WHERE PantherID = '$StuID[$x]';";
	 if (!($result = $mysqli->query($query))) {
			exit(mysql_error());
	}
	}
