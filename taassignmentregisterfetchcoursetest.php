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
    $output = '';
    $id= '1766';
    if(is_numeric($id) )
    {
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

        $sql="select    c.Courseid as id,
                                        c.CRN as crn,
                                        c.Subject as subj,
                                        c.Course as crse,
                                        c.Sec as sec,
                                        ca.Name as campus,
                                        c.Credit as cred,
                                        c.Title as title,
                                        c.Capacity as cap,
                                        sc.Actual as act,
                                        sc.Remaining as rem,
                                        c.Crosslistcapacity as xlcap,
                                        sc.CrosslistActual as xlact,
                                        sc.Crosslistremaining as xlrem,
                                        sc.Comments as comments,
                                        fa.email  as instructor,
                                        fa2.email  as instructor2,
                                        te.Startday as startdate,
                                        te.Endday as enddate,
                                        sc.Location as location,
                                        sc2.Location as location2,
                                        sc.Days as days1,
                                        sc.Termid as termid,
                                        sc.Starttime as starttime1,
                                        sc.Endtime as endtime1,
                                        sc2.Days as days2,
                                        sc2.Starttime as starttime2,
                                        sc2.Endtime as endtime2,
                                        sc.Scheduleid as scheduleid,
                                        sc2.Scheduleid as scheduleid2
                                        from tbl_course as c
                                        left JOIN tbl_schedule as sc on sc.Courseid= c.Courseid and sc.Instance=1
                                        left JOIN tbl_campus as ca on ca.Campusid=sc.Campusid
                                        left JOIN tbl_faculty_info as fa on fa.email=sc.Facultyid
                                        left JOIN tbl_term as te on te.Termid = sc.Termid
                                        left JOIN tbl_schedule as sc2 on sc2.Courseid= c.Courseid and sc2.Instance=2
                                        left JOIN tbl_faculty_info as fa2 on fa2.email=sc2.Facultyid and sc2.Instance=2
                        where  c.Courseid = " . $id;
        echo $sql;
        $result = mysqli_query($db, $sql);
        if ($result->num_rows > 0)
        {
            while ($row = mysqli_fetch_array($result))
            {
                $m_currentinstructor='';
                foreach ($instructorarray as $p_arr)
                {
                    $email = $p_arr["email"];
                    $Name = $p_arr["Name"];
                    //echo '$email:' .$email;
                    //echo '$Name:' .$Name;
                    if($row['instructor']==$email)
                    {
                        $m_currentinstructor =$Name;
                    }
                }

                $output =  '
              
              <p><label>ID : </label>' . $row['id'] . '</p>
              <p><label>CRN : </label>' . $row['crn'] . '</p>
              <p><label>Subj : </label>' . $row['subj'] . '</p>
              <p><label>Course : </label>' . $row['crse'] . '</p>
              <p><label>Title : </label>' . $row['title'] . '</p>
              <p><label>Actual : </label>' . $row['act'] . '</p>
              <p><label>CrosslistActual : </label>' . $row['xlact'] . '</p>
              <p><label>instructor : </label>' . $m_currentinstructor . '</p>
            ';

            }
        }
    }
    echo $output;
?>
