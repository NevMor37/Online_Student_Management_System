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

if (is_numeric($_GET['id'])) {

    $id = (int) $_GET['id'];

} else {
    echo 'data illegal';
    exit;
}

$sql="delete tbl_transfercredit_extra,tbl_transfercredit 
      from tbl_transfercredit 
      LEFT JOIN tbl_transfercredit_extra ON tbl_transfercredit_extra.TransferCreditID=tbl_transfercredit.TransferCreditID
      where TransferCreditID =($id)";
$result=mysqli_query($db,$sql);
if ($result) {
    echo 'delete success!';
} else {
    echo 'delete fail';
}
?>
<!DOCTYPE html>
<html>
<!-- Header -->
<head>
    <?php
    //include $root.'/links/header.php';
    //include $root.'/links/footerLinks.php';
    ?>

</head>
<!-- /#Header -->
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
<h1>Delete</h1>
<div>
    <h4></h4></div>
</div>
<a href="transfercreditdashboard.php">View</a><br>
</body>
</html>
