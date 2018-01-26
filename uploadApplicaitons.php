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

$target_dir =  $root.'/Applications/';
$html= "";

if ( 0 < $_FILES['fileToUpload']['error'] ) {
	$html.='Error: ' . $_FILES['fileToUpload']['error'] . '<br>';
	
}
else {
// 	echo "has been uploaded ";
// 	for($i=0; $i<count($_FILES['fileToUpload']['name']); $i++){
// 		echo "has been uploaded ".$i;
// 		$html.="has been uploaded";
// 		$target_path = $target_dir;
// 		$ext = explode('.', basename( $_FILES['fileToUpload']['name'][$i]));
// 		$target_path = $target_path . md5(uniqid()) . "." . $ext[count($ext)-1];
	
// 		if(move_uploaded_file($_FILES['fileToUpload']['tmp_name'][$i], $target_path)) {
// 			$html.="The file has been uploaded successfully <br />";
// 		} else{
// 			$html.="There was an error uploading the file, please try again! <br />";
// 		}

	
	if (isset($_FILES['files']) && !empty($_FILES['files'])) {
		
		$no_files = count($_FILES["files"]['name']);
		for ($i = 0; $i < $no_files; $i++) {
			if ($_FILES["files"]["error"][$i] > 0) {
				echo "Error: " . $_FILES["files"]["error"][$i] . "<br>";
			} else {
				if (file_exists($target_dir . $_FILES["files"]["name"][$i])) {
					echo 'File already exists : '. $target_dir. $_FILES["files"]["name"][$i];
				} else {
					move_uploaded_file($_FILES["files"]["tmp_name"][$i], $target_dir. $_FILES["files"]["name"][$i]);
					echo 'File successfully uploaded : '.$target_dir . $_FILES["files"]["name"][$i] . ' ';
// 					else 
// 						echo 'Error while uploading : '.$target_dir . $_FILES["files"]["name"][$i] . ' ';
				}
			}
		}
	} else {
		echo 'Please choose at least one file';
	}
	
// 	}
// 	echo $html;
// $total = count($_FILES['fileToUpload']['name']);
// echo "has been uploaded ".$total;
// echo $_FILES['upload']['name'];

// $target_file = $target_dir . basename($_FILES["file"]["name"]);
// $filename = basename($_FILES["file"]["name"]);

// Loop through each file
// for($i=0; $i<$total; $i++) {
	//Get the temp file path
// 	$tmpFilePath = $_FILES['fileToUpload']['tmp_name'][$i];

	//Make sure we have a filepath
// 	if ($tmpFilePath != ""){
		//Setup our new file path
// 		 $newFilePath = $target_dir . $_FILES['fileToUpload']['name'][$i];
// 		 echo $newFilePath;
		//Upload the file into the temp dir
// 		if(move_uploaded_file($tmpFilePath, $newFilePath)) {
// 			echo "has been uploaded ";
			//Handle other code here

// 		}
// 		else {
// 			echo "error occured while uploading";
// 		}
// 	}
// }
	}
	?>