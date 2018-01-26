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
    $termid = '11';
    if (is_numeric($termid)) {
        $instructortermarray = array();
        $instructorsql = " 
                         select distinct(sc.Facultyid) as Facultyid
                        from tbl_schedule  as sc 
                        where  Termid='$termid' and  sc.Facultyid<>'' 
                        and sc.Facultyid not in (select email from tbl_faculty_info )
         ";
echo $instructorsql;
        $instructorresult = mysqli_query($db, $instructorsql);

        if ($instructorresult->num_rows > 0) {
            $i = 0;
            while ($instructorrow = $instructorresult->fetch_assoc())
            {
                $instructortermarray[$i] = array();
                $instructortermarray[$i]['email'] = $instructorrow["Facultyid"];
                //echo '$instructortermarray[$i][\'email\']:'.$instructortermarray[$i]['email'];
                $i=$i+1;
            }
        }

        $sql = "select ga.GAApplicationID as GAApplicationID,ga.TermID as TermID,ga.PantherID as PantherID,
                         CONCAT(coalesce(ga.FirstName,' ') , IF(ga.MiddleName = '', ' ', IFNULL(ga.MiddleName,' ')),coalesce(ga.LastName,' '))  as Name,
                        ga.Email as email,ga.Courses as Courses, ga.Semesters as Semesters,
                        ga.Advisor as Advisor,ga.CurrentInstructor as CurrentInstructor,ga.CurrentGPA as CurrentGPA,
                        ga.Preferencefacultymembers as Preferencefacultymembers,ga.Status as Status,
                        ga.Degree as degree
                        from tbl_gaapplication as ga 
                        where TermID = $termid "." and (ga.Status='Waitinglist' or ga.Status='Approved') " ." order by ga.LastName";
       // echo $sql;
        $result = mysqli_query($db, $sql);
        if ($result->num_rows > 0) {
            $i=0;
            // output data of each row
            while ($row = $result->fetch_assoc()) {

                $m_email=$row["email"];
                $IsInstructor = false;
                echo '$m_email:'.$m_email;
                foreach ($instructortermarray as $arr)
                {
                    $p_email=$arr["email"];
                    echo '$p_email:'.$p_email;
                    if($m_email==$p_email)
                    {
                        $IsInstructor=true;
                    }
                }
                if($IsInstructor==false) {
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
                    $output[$i] = array();
                    $output[$i]['GAApplicationID'] = $row["GAApplicationID"];
                    $output[$i]['PantherID'] = $row["PantherID"];
                    $output[$i]['Name'] = $row["Name"];
                    $output[$i]['email'] = $row["email"];
                    $output[$i]['prority'] = $prority;
                    $output[$i]['CurrentGPA'] = $row["CurrentGPA"];
                    $output[$i]['degree'] = $row["degree"];
                    //echo implode(" ", $output[$i]);
                    //echo '<br>';
                    $i = $i + 1;
                }
            }


        }

    }
    echo json_encode($output);
   // echo implode(" ",$output);


?>
