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


?>


<?php
    if(isset($_SESSION['message']))
    {
         echo "<div id='error_msg'>".$_SESSION['message']."</div>";
         unset($_SESSION['message']);
    }
?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body"  style="overflow:auto">
                <table width="100%" class="table table-striped table-bordered table-hover" id="term-view">
                    <thead>
                        <!-- Head -->
                        <tr>
                            <th>TermID</th><th>Name</th><th>StartDate</th><th>EndDate</th><th>Import</th><th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php

                    $sql = "select Termid as id,Term as name,StartDay as startdate, EndDay as enddate
                            from tbl_term 
                             ";

                    $result = mysqli_query($db, $sql);

                    while($row=mysqli_fetch_assoc($result))
                    {
                        echo '<tr><td>' . $row["id"] .
                            '</td><td>' . $row["name"] .
                            '</td><td>' . $row["startdate"] .
                            '</td><td>' . $row["enddate"] .
                            '</td><td><a href="DataImportCourse.php?id=' . $row["id"] .'&name='. $row["name"] .'&startdate='. $row["startdate"].'&enddate='. $row["enddate"]. '" >Import</a></td><td><a href="DataCourseRemove.php?id=' . $row["id"] .'&name='. $row["name"] .'&startdate='. $row["startdate"].'&enddate='. $row["enddate"]. ' "\">Delete</a></td></tr>';
                    }

                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
