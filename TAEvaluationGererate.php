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


function echotoscreen($textstr)
{
    $IsDebug=false;
    if(empty($textstr)==false && $IsDebug==true)
    {
        echo $textstr.'<br>';
    }
}
function echotoscreennobr($textstr)
{
    $IsDebug=false;
    if(empty($textstr)==false && $IsDebug==true)
    {
        echo $textstr;
    }
}

function string_to_ascii($string)
{
    $ascii = NULL;
    $asciiString=null;

    for($i = 0; $i != strlen($string); $i++)
    {

        $asciiString .= "&#".ord($string[$i]).";";

    }

    $ascii = str_replace("&#", "", $asciiString);

    return($ascii);
}


function mb_str_split($string, $split_length = 1)
{
    if ($split_length == 1) {
        return preg_split("//u", $string, -1, PREG_SPLIT_NO_EMPTY);
    } elseif ($split_length > 1) {
        $return_value = [];
        $string_length = mb_strlen($string, "UTF-8");
        for ($i = 0; $i < $string_length; $i += $split_length) {
            $return_value[] = mb_substr($string, $i, $split_length, "UTF-8");
        }
        return $return_value;
    } else {
        return false;
    }
}

function taevaluationextraData($TaEvaluationID,$Field,$Instance,$Value)
{
    $sqlInsert ="
               insert into  tbl_taevaluationextra(TaEvaluationID,Field,Instance,Value)
               values('$TaEvaluationID','$Field','$Instance','$Value');
                    ";
    return $sqlInsert;
}

function UpdatetaevaluationextraData($db,$taevaluationid)
{

    $Count =1;
    $Defaultvalue = ''; //set default value =''
    $sql='';

    $sql=$sql. taevaluationextraData($taevaluationid,'ReliabilityA1',$Count,$Defaultvalue);
    //$Count =$Count+1;
    $sql=$sql. taevaluationextraData($taevaluationid,'ReliabilityA2',$Count,$Defaultvalue);
    //$Count =$Count+1;
    $sql=$sql. taevaluationextraData($taevaluationid,'ReliabilityA3',$Count,$Defaultvalue);
    //$Count =$Count+1;
    $sql=$sql. taevaluationextraData($taevaluationid,'ReliabilityA4',$Count,$Defaultvalue);
    //$Count =$Count+1;
    $sql=$sql. taevaluationextraData($taevaluationid,'EngagementA1',$Count,$Defaultvalue);
    //$Count =$Count+1;
    $sql=$sql. taevaluationextraData($taevaluationid,'ProficiencyA1',$Count,$Defaultvalue);
    //$Count =$Count+1;
    $sql=$sql. taevaluationextraData($taevaluationid,'CommunicationA1',$Count,$Defaultvalue);
    //$Count =$Count+1;
    $sql=$sql. taevaluationextraData($taevaluationid,'JudgementA1',$Count,$Defaultvalue);
    //$Count =$Count+1;
    $sql=$sql. taevaluationextraData($taevaluationid,'TutorialslabsA1',$Count,$Defaultvalue);
    //$Count =$Count+1;
    $sql=$sql. taevaluationextraData($taevaluationid,'TutorialslabsA2',$Count,$Defaultvalue);
    //$Count =$Count+1;
    $sql=$sql. taevaluationextraData($taevaluationid,'ConstructingA1',$Count,$Defaultvalue);
    //$Count =$Count+1;
    $sql=$sql. taevaluationextraData($taevaluationid,'ConstructingA2',$Count,$Defaultvalue);
    //$Count =$Count+1;
    $sql=$sql. taevaluationextraData($taevaluationid,'GradingA1',$Count,$Defaultvalue);
    //$Count =$Count+1;
    $sql=$sql. taevaluationextraData($taevaluationid,'GradingA2',$Count,$Defaultvalue);
    //$Count =$Count+1;
    $sql=$sql. taevaluationextraData($taevaluationid,'GradingA3',$Count,$Defaultvalue);
    //$Count =$Count+1;
    $sql=$sql. taevaluationextraData($taevaluationid,'TestExamA1',$Count,$Defaultvalue);
    //$Count =$Count+1;
    //$sql=$sql. taevaluationextraData($taevaluationid,'OverallRating',$Count,$Defaultvalue);
    //$Count =$Count+1;
    //echo $sql . '<br>';
    $result = mysqli_multi_query($db, $sql) ;
    if($result === false) {
        echo mysqli_error($db);
    }
    while (mysqli_more_results($db)) {
        if (mysqli_next_result($db) === false) {
            echo mysqli_error($db);
            echo "\r\n";
            break;
        }
    }
}

function UpdatetaevaluationData($db,$termid,$instructorarray,$PantherID,$instructor,$CourseID)
{

    $sql = "select taev.TaEvaluationID
            from tbl_taevaluation as taev
            where taev.Term = '$termid' and taev.StudentID='$PantherID' and taev.Instructor='$instructor' and taev.CourseID='$CourseID'
            LIMIT 1 
            ";
    //echo $sql . '<br>';
    $result = mysqli_query($db, $sql) ;

    if ($result->num_rows > 0) {
        // output data of each row
        while (($row = mysqli_fetch_assoc($result))) {
            echo '$termid:'.$termid.'$PantherID:'.$PantherID.'$instructor:'.$instructor.'$CourseID:'.$CourseID.' has exists!!!'.'<br>';
        }
    }
    else
    {
        $sqlInsert ="
               insert into  tbl_taevaluation(StudentID,Instructor,Term,CourseID,UpdateTime)
               values('$PantherID','$instructor','$termid','$CourseID',NOW())
                    ";
        //echo $sqlInsert . '<br>';
        $resulttaevaluation = mysqli_query($db, $sqlInsert);
        $taevaluationid = mysqli_insert_id($db);
        echo '$termid:'.$termid.'$PantherID:'.$PantherID.'$instructor:'.$instructor.'$CourseID:'.$CourseID.' has create.'.'taevaluationid:'.$taevaluationid.'<br>';
        //create extra data
        UpdatetaevaluationextraData($db,$taevaluationid);


    }

}

function getcourse($db,$termid,$instructorarray){
    $output='';
    $sql = "
            select ga.PantherID,sc.Facultyid,tain.CourseID
            from tbl_taassignment_info as tain
            INNER JOIN tbl_taassignment_extra as taex on taex.TAAssignmentID=tain.TAAssignmentID
            INNER JOIN tbl_course as co on co.Courseid=tain.CourseID 
            INNER JOIN tbl_schedule as sc on sc.Courseid=co.Courseid and sc.Instance='1' 
            INNER JOIN tbl_gaapplication as ga on ga.PantherID = taex.PantherID and ga.TermID= sc.Termid
            where sc.Termid=
             ".$termid;
    //echo $sql . '<br>';
    $result = mysqli_query($db, $sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            $PantherID = $row["PantherID"];
            $Facultyid = $row["Facultyid"];
            $CourseID = $row["CourseID"];
            UpdatetaevaluationData($db,$termid,$instructorarray,$PantherID,$Facultyid,$CourseID);
        }
    }
    return $output;
}

if(empty($_GET['id'])==false) {
    if (is_numeric($_GET['id'])) {

        //echo $_GET['id'];
        $id = (int)$_GET['id'];
        $name = $_GET['name'];
        $startdate = $_GET['startdate'];
        $enddate = $_GET['enddate'];

        $m_startdate=date( "Y-m-d", strtotime( $startdate ) );
        $year = date( 'Y',strtotime($startdate));
        $month = date( 'm',strtotime($startdate));
        $date = date( 'd',strtotime($startdate));

        $termid='0';

        $termarray = array();
        $sql = "select Termid,Startday,Endday
            from tbl_term";
        $result = mysqli_query($db, $sql);

        if ($result->num_rows > 0) {
            $i = 0;
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                $termarray[$i] = array();
                $termarray[$i]['Termid'] = $row["Termid"];
                $termarray[$i]['Startday'] = $row["Startday"];
                $termarray[$i]['Endday'] = $row["Endday"];
                if($termarray[$i]['Startday']==$m_startdate)
                {
                    $termid= $termarray[$i]['Termid'];
                }
                $i = $i + 1;
            }
        }

        $instructorarray = array();
        $sql = "select PantherID,email, CONCAT(coalesce(FirstName,' ') , coalesce(MiddleName,' '),coalesce(LastName,' ')) as Name
              from tbl_faculty_info ";
        echotoscreen( $sql );
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

        $sql = "select PantherID,email, CONCAT(coalesce(FirstName,' ') , coalesce(MiddleName,' '),coalesce(LastName,' ')) as Name
                    from tbl_student_info
                    where Position = 'instructor'";
        echotoscreen($sql);
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


        getcourse($db,$termid,$instructorarray);
    }
}



?>