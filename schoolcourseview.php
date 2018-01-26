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

?>

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
<div>
    <a href="schoolcourseregister.php">Add New School Course</a><br>
    <h4></h4></div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body"  style="overflow:auto">
                <table width="100%" class="table table-striped table-bordered table-hover" id="schoolcourse-view">
                    <thead>
                        <!-- Head -->
                        <tr>
                            <td>SchoolCourseID</td><td>Subject</td><td>Course</td><td>CourseName</td><td>Credit</td><td>Prerequisites</td><td>Description</td><td>Update</td><td>Remove</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        $sql = "SELECT SchoolCourseID as SchoolCourseID,
                                Subject as Subject,
                                Course as Course,
                                CourseName as CourseName,
                                Credit as Credit,
                                Prerequisites as Prerequisites,
                                Description as Description
                                FROM tbl_schoolcourse
                                ORDER  by Course;
                                 ";

                        $result = mysqli_query($db, $sql);

                        while($row=mysqli_fetch_assoc($result))
                        {
                            $everyrow = 'insert into tbl_schoolcourse(SUBJECT ,Course,CourseName,Credit,Prerequisites,Description) VALUES('.
                                '@'.$row["Subject"] .'@'.','.
                                '@'.$row["Course"] .'@'.','.
                                '@'.$row["CourseName"] .'@'.','.
                                '@'.$row["Credit"] .'@'.','.
                                '@'.$row["Prerequisites"] .'@'.','.
                                '@'.$row["Description"] .'@'.');';
                            //echo '<tr><td>'.$everyrow.'</td></tr>';

                            echo '<tr><td>' . $row["SchoolCourseID"] .
                                '</td><td>' . $row["Subject"] .
                                '</td><td>' . $row["Course"] .
                                '</td><td>' . $row["CourseName"] .
                                '</td><td>' . $row["Credit"] .
                                '</td><td>' . $row["Prerequisites"] .
                                '</td><td>' . $row["Description"] .
                                //'</td><td>' .$everyrow.
                                '</td><td><a href="schoolcourseregister.php?id= ' . $row["SchoolCourseID"] . '" >Update</a></td><td><a href="schoolcourseremove.php?id=' . $row["SchoolCourseID"] . ' "\">Remove</a></td></tr>';


                        }

                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
