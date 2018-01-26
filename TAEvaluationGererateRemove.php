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
    $name = $_GET['name'];
    $startdate = $_GET['startdate'];
    $enddate = $_GET['enddate'];
    echo '$id:' .$id .'$name:' .$name.'$startdate:' .$startdate.'$enddate:' .$enddate;
} else {
    echo 'data illegal';
    exit;
}

$sql="DELETE tbl_taevaluationextra
      FROM tbl_taevaluation
      LEFT JOIN tbl_taevaluationextra ON tbl_taevaluationextra.TaEvaluationID=tbl_taevaluation.TaEvaluationID
        where tbl_taevaluation.Term =($id);";

$sql=$sql."DELETE tbl_taevaluation
      FROM tbl_taevaluation
        where tbl_taevaluation.Term =($id);";
echo $sql;
$result=mysqli_multi_query($db,$sql);
if ($result) {
    echo 'delete success!';
} else {
    echo 'delete fail';
    echo("Error description: " . mysqli_error($db));
    while (mysqli_more_results($db))
    {
            if (mysqli_next_result($db) === false)
            {
                echo mysqli_error($db);
                echo "\r\n";
                break;
            }
    }
}
?>
<!DOCTYPE html>
<html>
<!-- Header -->
<head>
    <?php
    include $root.'/links/header.php';
    include $root.'/links/footerLinks.php';
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
<a href="TAEvaluationGereratedashboard.php">View</a><br>
</body>
</html>
