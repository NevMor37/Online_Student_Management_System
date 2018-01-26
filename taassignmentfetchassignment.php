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

if(isset($_POST["termid"]) && isset($_POST["coursestartnumber"]) && isset($_POST["courseendnumber"])) {
    $output = array();
    $termid = $_POST["termid"];
    $coursestartnumber = $_POST["coursestartnumber"];
    $courseendnumber = $_POST["courseendnumber"];

    $instructorarray = array();
    $sql = "select PantherID,email, 
                                    CONCAT(coalesce(FirstName,' ') , IF(MiddleName = '', ' ', IFNULL(MiddleName,' ')),coalesce(LastName,' ')) as Name
                                    from tbl_faculty_info  ";
    //echo $sql . '<br>';
    $result = mysqli_query($db, $sql);

    if ($result->num_rows > 0) {
        $i = 0;
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            $instructorarray[$i] = array();
            $instructorarray[$i]['PantherID'] = $row["PantherID"];
            $instructorarray[$i]['email'] = $row["email"];
            $instructorarray[$i]['Name'] = $row["Name"];
            $i = $i + 1;
        }
    }

    $sql = "select PantherID,email, CONCAT(coalesce(FirstName,' ') , IF(MiddleName = '', ' ', IFNULL(MiddleName,' ')),coalesce(LastName,' ')) as Name
                                    from tbl_student_info
                                    where Position = 'instructor'
                                    order by LastName
                        ";
    $result = mysqli_query($db, $sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            $instructorarray[$i] = array();
            $instructorarray[$i]['PantherID'] = $row["PantherID"];
            $instructorarray[$i]['email'] = $row["email"];
            $instructorarray[$i]['Name'] = $row["Name"];
            $i = $i + 1;
        }
    }

    if (is_numeric($termid))
    {
        $sql = "select TAAssignmentID,co.Subject,co.Course,sc.Facultyid
                from tbl_taassignment_info as tain 
                LEFT JOIN tbl_course as co on co.Courseid = tain.CourseID
                LEFT JOIN tbl_schedule as sc on sc.Courseid = co.Courseid  and sc.Instance ='1'
                where ((tain.TANumber >0) or (tain.GANumber >0) or (tain.LANumber >0) )
                and (co.Course >='$coursestartnumber' and co.Course <='$courseendnumber' ) and sc.Termid = '$termid'
                and sc.Facultyid <>''
                order by co.Subject, co.Course";
        // echo $sql;
        $result = mysqli_query($db, $sql);
        if ($result->num_rows > 0) {
            $i=0;
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                $Facultyid  =$row["Facultyid"];
                $m_instructor = '';
                foreach ($instructorarray as $p_arr) {
                    $email = $p_arr["email"];
                    $Name = $p_arr["Name"];
                    //echo '$email:' .$email;
                    //echo '$Name:' .$Name;
                    if ($Facultyid == $email) {
                        $m_instructor = $Name;
                    }
                }

                $output[$i] = array();
                $output[$i]['TAAssignmentID'] = $row["TAAssignmentID"];
                $output[$i]['Subject'] = $row["Subject"];
                $output[$i]['Course'] = $row["Course"];
                $output[$i]['Instructor'] = $m_instructor;
                $i = $i + 1;
            }


        }

    }
    echo json_encode($output);
    // echo implode(" ",$output);


}
?>
