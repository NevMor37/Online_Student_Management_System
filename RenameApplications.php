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
include($root.'/osms.dbconfig.inc');

$error_message = "";
$counter = 0;

$mysqli = new mysqli($hostname,$username, $password,$dbname);

/* check connection */
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}

function filesize_formatted($path)
{
	$size = filesize($path);
	$units = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
	$power = $size > 0 ? floor(log($size, 1024)) : 0;
	return number_format($size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];
}

function camelCase($str, array $noStrip = [])
{
	// non-alpha and non-numeric characters become spaces
	$str = preg_replace('/[^a-z0-9' . implode("", $noStrip) . ']+/i', ' ', $str);
	$str = trim($str);
	// uppercase the first character of each word
	$str = ucwords($str);
	$str = str_replace(" ", "", $str);
	// 	$str = lcfirst($str);

	return $str;
}

$sqlF = "SELECT * FROM tbl_excel_info  where ApplicantID is not null";

// $sql2 = "SELECT StudentId FROM tbl_student_evaluation WHERE Status='Pending'";
// $result1 = $mysqli->query($sql1);
// $result2 = $mysqli->query($sql2);
//$sql = "SELECT * FROM tbl_faculty_info LEFT OUTER JOIN tbl_student_evaluation ON tbl_student_evaluation.FacultyId = tbl_faculty_info.email WHERE tbl_faculty_info.email='".$user_email."'";
$resultF = $mysqli->query($sqlF);
$dir = $root."/Applications/";
echo "Following Files are Renamed: <br/>";
if($resultF-> num_rows > 0)
{
	while($rowF = $resultF->fetch_assoc())
	{
		$PantherId = $rowF["PantherId"];
		if($PantherId==0)
			continue;
			$FirstName=$rowF["FirstName"];
			$LastName=$rowF["LastName"];
			$ApplicationID=$rowF["ApplicantID"];
			$FileName = camelCase($LastName)."_".camelCase($FirstName).'.pdf';
			$FullFileName = $dir.$FileName;
			$files = null;
			$files = glob($root."/Applications/*".$ApplicationID.".pdf");
			if($files){
				try{
// 					copy(files[0], $FullFileName);
				if(!rename(files[0], $FullFileName)) {
   				 throw new Exception('Unable to write to '. $FullFileName.'<br/>');
  				}
  				else
  				echo $files[0]." To  ".$FullFileName."<br/>";
				} catch (Exception $e) {
					echo $e;
				}
			
		}
			
	}
}
?>

<?php
// echo "Hello  this is testing..!!";
?>