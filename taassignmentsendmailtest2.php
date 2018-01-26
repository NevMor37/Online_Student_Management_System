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

$mailto='hhu4@student.gsu.edu';
$mailsubject='TA Assignment';
$mailmessage='Dear Dr. Dileep Gunda,

You have been assigned the following TA(s):
1010: HUAFU HU - Lab Assistant
1010: HUAFU HU - Lab Assistant
';

$mailmessage=$mailmessage  ."\r\n\r\n"  ."Dear TA(s):";
$mailmessage=$mailmessage  ."\r\n\r\n"  ."1. Please log into OGMS (https://www.google.com/) to ACCEPT/DECLINE this assignment WITHIN 2 DAYs. After login, click 'Funding' on the left panel, click 'Applicant Information', then click the 'Accept/Decline' button. You cannot change your decision once the button is clicked. ";
$mailmessage=$mailmessage  ."\r\n\r\n"  ."2. If you accept this assignment, please contact the course instructor at least one week ahead of the next semester.  " ;
$mailmessage=$mailmessage  ."\r\n\r\n"  ."Thank you," ;
$mailmessage=$mailmessage  ."\r\n\r\n"  ."Director of Graduate Studies" ;

$mailheader='From:  Hu Huafu <hhu4@student.gsu.edu>' . "\r\n" .
            'Reply-To: hhu4@student.gsu.edu' . "\r\n" .
            'Return-Path:hhu4@student.gsu.edu' . "\r\n" .
            'X-Mailer: PHP/' . phpversion(). "\r\n" .
            'CC: hhu4@student.gsu.edu';

    $sendreturn = mail($mailto, $mailsubject, $mailmessage, $mailheader);
if ($sendreturn) {
    echo 'success';
} else {
    echo 'fail';
}
?>