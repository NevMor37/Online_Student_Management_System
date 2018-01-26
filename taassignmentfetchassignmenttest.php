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

    $output = array();
    $termid = '3';
    $coursestartnumber = '1';
    $courseendnumber = '9999';
    if (is_numeric($termid)) {
        $sql = "select TAAssignmentID,co.Subject,co.Course
                from tbl_taassignment_info as tain 
                LEFT JOIN tbl_course as co on co.Courseid = tain.CourseID
                LEFT JOIN tbl_schedule as sc on sc.Courseid = co.Courseid  and sc.Instance ='1'
                where ((tain.TANumber >0) or (tain.GANumber >0) or (tain.LANumber >0) )
                and (co.Course >='$coursestartnumber' and co.Course <='$courseendnumber' ) and sc.Termid = '$termid'
                and sc.Facultyid <>''
                order by co.Subject, co.Course";
        //echo $sql;
        $result = mysqli_query($db, $sql);
        if ($result->num_rows > 0) {
            $i=0;
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                $output[$i] = array();
                $output[$i]['TAAssignmentID'] = $row["TAAssignmentID"];
                $output[$i]['Subject'] = $row["Subject"];
                $output[$i]['Course'] = $row["Course"];
                $i = $i + 1;
            }


        }

    }
    echo json_encode($output);
    // echo implode(" ",$output);


?>
