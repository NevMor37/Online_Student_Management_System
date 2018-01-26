<?php
//session_start();
//
////connect to database
//$db=mysqli_connect("localhost","root","hu1015","authentication");

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
if(isset($_POST["name"]))
{
    if(isset($_POST["name"])) {
        $output = '';
        $name = $_POST["name"];
        $value = $_POST["value"];
        $curr_value = $_SESSION[$name];
        if ($curr_value != $value) {
            $_SESSION[$name] = $value;
            $output = 'success';
        } else {
            $output = 'sameid';
        }


        echo $output;
    }
}
?>
