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
<div>
    <a href="transfercreditview.php">Add New Transfer Course</a><br>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body"  style="overflow:auto">
                <table width="100%" class="table table-striped table-bordered table-hover" id="term-view">
                    <thead>
                        <!-- Head -->
                        <tr>
                            <th>TermID</th><th>Name</th><th>TermID</th><th>CourseNumber</th><th>Status</th><th>Update</th><th>Remove</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php

                    $sql = "select TransferCreditID as id,StudentID as name,TermID as TermID, CourseNumber as CourseNumber,
                                  Status as Status
                            from tbl_transfercredit 
                             ";

                    $result = mysqli_query($db, $sql);

                    while($row=mysqli_fetch_assoc($result))
                    {
                        echo '<tr><td>' . $row["id"] .
                            '</td><td>' . $row["name"] .
                            '</td><td>' . $row["TermID"] .
                            '</td><td>' . $row["CourseNumber"] .
                            '</td><td>' . $row["Status"] .
                            '</td><td><a href="transfercreditregister.php?id= ' . $row["id"] . '" >Update</a></td><td><a href="transfercreditremove.php?id=' . $row["id"] . ' "\">Remove</a></td></tr>';
                    }

                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
