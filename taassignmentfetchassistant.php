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


if(isset($_POST["courseid"]) && isset($_POST["termid"]) ) {
    $output = array();
    $courseid = $_POST["courseid"];
    $termid = $_POST["termid"];
    if (is_numeric($courseid) && is_numeric($termid)) {
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
        if(empty($crn)==false && empty($subj)==false && empty($crse)==false)
        {
            $instructortermarray = array();
            $instructorsql = " 
                         select distinct(sc.Facultyid) as Facultyid
                        from tbl_schedule  as sc 
                        where  Termid='$termid' and  sc.Facultyid<>'' 
                        and sc.Facultyid not in (select email from tbl_faculty_info )
         ";

            $instructorresult = mysqli_query($db, $instructorsql);

            if ($instructorresult->num_rows > 0) {
                $i = 0;
                while ($instructorrow = $instructorresult->fetch_assoc())
                {
                    $instructortermarray[$i] = array();
                    $instructortermarray[$i]['email'] = $instructorrow["Facultyid"];
                    $i=$i+1;
                }
            }

            //select assistant by course and term
            $sql = "select ga.GAApplicationID as GAApplicationID,ga.TermID as TermID,ga.PantherID as PantherID,
                             CONCAT(coalesce(ga.FirstName,' ') , IF(ga.MiddleName = '', ' ', IFNULL(ga.MiddleName,' ')),coalesce(ga.LastName,' '))  as Name,
                            ga.Email as email,ga.Courses as Courses, ga.Semesters as Semesters,
                            ga.Advisor as Advisor,ga.CurrentInstructor as CurrentInstructor,ga.CurrentGPA as CurrentGPA,
                            ga.Preferencefacultymembers as Preferencefacultymembers,ga.Status as Status,
                            ga.Degree as degree
                            from tbl_gaapplication as ga 
                            where TermID = $termid and Courses like '%".$subj.$crse."%'" ." and ga.Status='Waitinglist'";
           // echo $sql;
            $result = mysqli_query($db, $sql);
            if ($result->num_rows > 0) {
                $i=0;
                // output data of each row
                while ($row = $result->fetch_assoc()) {

                    $m_email=$row["email"];
                    $IsInstructor = false;
                    foreach ($instructortermarray as $arr)
                    {
                        $p_email=$arr["email"];
                        if($m_email==$p_email)
                        {
                            $IsInstructor=true;
                        }
                    }
                    if($IsInstructor==false) {
                        $output[$i] = array();
                        $output[$i]['GAApplicationID'] = $row["GAApplicationID"];
                        $output[$i]['PantherID'] = $row["PantherID"];
                        $output[$i]['Name'] = $row["Name"];
                        $output[$i]['email'] = $row["email"];
                        $output[$i]['CurrentGPA'] = $row["CurrentGPA"];
                        $output[$i]['degree'] = $row["degree"];
                        $Courses = $row["Courses"];
                        $Course = explode(",", $Courses);
                        $prority = -1;

                        $j = 0;
                        foreach ($Course as $arr) {
                            // echo 'Course:'.$subj.$crse .'arr:'.$arr;
                            //echo 'j:'.$j;
                            if (($subj . $crse) == $arr) {
                                if ($prority == -1) {
                                    //echo  'equal:'.$j;
                                    $prority = $j;
                                }
                            }
                            $j = $j + 1;
                        }
                        //echo '$prority:'. $prority;
                        //echo '<br>';

                        $output[$i]['prority'] = $prority;
                        //echo implode(" ", $output[$i]);
                        //echo '<br>';
                        $i = $i + 1;
                    }
                }


                //order the assistant by stander.(ex. GPA)

                # get a list of sort columns and their data to pass to array_multisort
                $sort = array();
                foreach($output as $k=>$v) {
                    $sort['prority'][$k] = $v['prority'];
                    $sort['CurrentGPA'][$k] = $v['CurrentGPA'];
                }
                # sort by event_type desc and then title asc
                array_multisort($sort['prority'], SORT_ASC, $sort['CurrentGPA'], SORT_DESC,$output);
            }

        }
        echo json_encode($output);
       // echo implode(" ",$output);
    }


}
?>
