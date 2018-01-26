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
    $courseid = '1766';
    $termid = '3';
    if (is_numeric($courseid) && is_numeric($termid)) {
        //echo $courseid;

        $sql = "select    c.Courseid as id,
                            c.CRN as crn,
                            c.Subject as subj,
                            c.Course as crse
                            from tbl_course as c
                            where c.Courseid = " . $courseid .' limit 1';
        //echo $sql . '<br>';
        $result = mysqli_query($db, $sql);

        if ($result->num_rows > 0) {
            $i = 0;
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                $crn = $row['crn'];
                $subj = $row['subj'];
                $crse = $row['crse'];
            }
        }
        //echo $crn;
        //echo $subj;
        //echo $crse;
        if(empty($crn)==false && empty($subj)==false && empty($crse)==false)
        {
            $sql = "select ga.GAApplicationID as GAApplicationID,ga.TermID as TermID,ga.PantherID as PantherID,
                            CONCAT(coalesce(ga.FirstName,' '),coalesce(ga.MiddleName,' '),coalesce(ga.LastName,' ')) as Name,
                            ga.Email as email,ga.Courses as Courses, ga.Semesters as Semesters,
                            ga.Advisor as Advisor,ga.CurrentInstructor as CurrentInstructor,ga.CurrentGPA as CurrentGPA,
                            ga.Preferencefacultymembers as Preferencefacultymembers,ga.Status as Status
                            from tbl_gaapplication as ga 
                            where TermID = $termid and Courses like '%".$subj.$crse."%'";
            echo $sql;
            $result = mysqli_query($db, $sql);
            if ($result->num_rows > 0) {
                $i=0;
                // output data of each row
                while ($row = $result->fetch_assoc()) {
                    $Courses =  $row["Courses"];
                    $Course = explode(",", $Courses);
                    $prority=-1;

                    $j=0;
                    foreach ($Course as $arr)
                    {
                        echo 'Course:'.$subj.$crse .'arr:'.$arr;
                        echo 'j:'.$j;
                        if(($subj . $crse)==$arr)
                        {
                            if($prority==-1) {
                                echo  'equal:'.$j;
                                $prority = $j;
                            }
                        }
                        $j=$j+1;
                    }
                    echo '$prority:'. $prority;
                    echo '<br>';
                    $output[$i] = array();
                    $output[$i]['GAApplicationID'] = $row["GAApplicationID"];
                    $output[$i]['PantherID'] = $row["PantherID"];
                    $output[$i]['Name'] = $row["Name"];
                    $output[$i]['email'] = $row["email"];
                    $output[$i]['prority'] = $prority;
                    $output[$i]['CurrentGPA'] = $row["CurrentGPA"];

                    echo implode(" ", $output[$i]);
                    echo '<br>';
                    $i = $i + 1;
                }
            }

        }
        //echo json_encode($output);
        //echo '$output:'. count($output,COUNT_RECURSIVE);
        //echo implode(" ",$output);
        echo json_encode($output);
    }



?>
