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

//echo 'id:'. $_GET['id'];
if (is_numeric($_POST['id'])) {

    $id = (int) $_POST['id'];

} else {
    echo 'data illegal';
    exit;
}

$sql = "select Name, value
            from tbl_settings
            where Name = 'taassignmentemailbody' or Name = 'taassignmentemailbodyvariable' or Name ='taassignmentemailhead';
            " ;
//echo $sql;
$result = mysqli_query($db, $sql);

if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        //echo "<br>"."name: " . $row["Name"] . " value: " . nl2br ($row["value"]) ;
        $name="";
        $value="";
        $name = $row["Name"];
        $value= $row["value"];
        if($name == 'taassignmentemailbody')
        {
            //$TAApplicationstartdatetime=substr($value,0,10);
            $taassignmentemailbody=$value;
        }
        if($name == 'taassignmentemailbodyvariable')
        {
            //$TAApplicationstartdatetime=substr($value,0,10);
            $taassignmentemailbodyvariable=$value;
        }
        if($name == 'taassignmentemailhead')
        {
            //$TAApplicationstartdatetime=substr($value,0,10);
            $taassignmentemailhead=$value;
        }
        //echo $taassignmentemailbody;
    }
}

//echo $_GET['id'];
$instructorarray = array();
$sql = "select PantherID,email, CONCAT(coalesce(FirstName,' ') , IF(MiddleName = '', ' ', IFNULL(MiddleName,' ')),coalesce(LastName,' ')) as Name
              from tbl_faculty_info ";
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

$sql="select 	tai.TANumber as tanumber,
                tai.GANumber as ganumber,
                tai.LANumber as lanumber,
				tae.TAAssignmentExtraID as extraid,
				tae.Assignment as assignment,
				tae.Instance as instance,
				CONCAT(coalesce(si.FirstName,' '),IF(MiddleName = '', ' ', IFNULL(MiddleName,' ')),coalesce(si.LastName,' ')) as stuname,
				si.email as stuemail,
				ts.Facultyid as faemail,
				tc.Course as course
                from tbl_taassignment_info as tai
                LEFT JOIN tbl_taassignment_extra as tae on tae.TAAssignmentID = tai.TAAssignmentID
                LEFT JOIN tbl_course as tc on tc.Courseid = tai.CourseID 
                LEFT JOIN tbl_schedule as ts on ts.Courseid = tai.CourseID and ts.Instance =1
                LEFT JOIN tbl_gaapplication as si on si.PantherID = tae.PantherID and si.TermID=ts.Termid
                where tai.TAAssignmentID=($id)"
    ."ORDER BY tae.Assignment,tae.Instance";



//echo $sql;
$result = mysqli_query($db, $sql);
$mailheader=$user_email;
$mailheader='From: ' .$mailheader;
$mailheader =$mailheader."\r\n". 'Reply-To: '.$mailheader;
$mailheader =$mailheader."\r\n". 'Return-Path:' .$mailheader;
$mailheader =$mailheader."\r\n". 'X-Mailer: PHP/' . phpversion();
//$mailheaderccdefault="\r\n" ."CC: tdudley@gsu.edu,yili@gsu.edu";
//$mailheaderccdefault="\r\n" ."CC: tdudley@gsu.edu,";
$mailheaderccdefault="\r\n" ."CC: hhu4@student.gsu.edu";
$mailheader=$mailheader .$mailheaderccdefault;
if(empty($taassignmentemailhead))
{
    $mailsubject="OGMS Testing Please ignore incase if you receive this";
}
else
{
    $mailsubject=$taassignmentemailhead;
}
$mailsubject=$taassignmentemailhead;
$mailto="";
$mailmessage="";
$issingle = false;
$sameassign=false;
$assignment = "";
$tanumber="";
$ganumber="";
$lanumber="";
while($row=mysqli_fetch_assoc($result)) {
    if (!$issingle)
    {
        $m_instructor ='';
        foreach ($instructorarray as $arr)
        {
            $p_email = $arr["email"];
            $instructorname = $arr["Name"];
            if($row["faemail"]==$p_email)
            {
                $m_instructor=$instructorname;
            }
        }

        $p_course = $row["course"];
        $p_mailto = $row["faemail"];
        $mailto = $p_mailto;
        $p_mailname = $m_instructor;
        //$mailmessage=$mailmessage ."Dear Dr. ".$p_mailname .",";
        $mailmessage=$mailmessage ."Dear Dr. ".$p_mailname .",";
        $mailmessage=$mailmessage."\r\n\r\nYou have been assigned the following TA(s):";
        $tanumber = $row["tanumber"];
        $ganumber = $row["ganumber"];
        $lanumber = $row["lanumber"];
        $issingle=true;
    }
    $p_maistu = $row["stuemail"];
    //  echo '$p_maistu:'.$p_maistu;
    $mailheaderccstu = "\r\n" ."CC: ".$p_maistu;
    $mailheader=$mailheader .$mailheaderccstu;

    $p_stuname = $row["stuname"];
    $p_assignment = $row["assignment"];
    $p_instance = $row["instance"];
    $mailmessage=$mailmessage ."\r\n" .$p_course .": ";
    $mailmessage=$mailmessage  .$p_stuname ." - ";
    if($p_assignment=="TA")
    {
        $assignment="Teaching Assistant";
    }
    else if($p_assignment=="GA")
    {
        $assignment="Grader Assistant";
    }
    else if ($p_assignment=="LA")
    {
        $assignment="Lab Assistant";
    }
    $mailmessage=$mailmessage  .$assignment ;
}

if(empty($taassignmentemailbody))
{
    $mailmessage=$mailmessage  ."\r\n\r\n"  ."Dear TA(s):";
    $mailmessage=$mailmessage  ."\r\n\r\n"  ."1. Please log into OGMS (http://grid.cs.gsu.edu/~ogms/) to ACCEPT/DECLINE this assignment WITHIN 2 DAYs. After login, click 'Funding' on the left panel, click 'Applicant Information', then click the 'Accept/Decline' button. You cannot change your decision once the button is clicked. ";
    $mailmessage=$mailmessage  ."\r\n\r\n"  ."2. If you accept this assignment, please contact the course instructor at least one week ahead of the next semester.  " ;
    $mailmessage=$mailmessage  ."\r\n\r\n"  ."Thank you," ;
    $mailmessage=$mailmessage  ."\r\n\r\n"  ."Director of Graduate Studies" ;
}
else
{
    $mailmessage=$mailmessage."\r\n\r\n"  .$taassignmentemailbody;
}

//$mailmessage= "
//Dear All,
//please igore the previous TA assignment email if you receive. Because just now OGMS occured an error.
//It's an accident. Sorry about that.
//Best Regards!";

//echo "<br>mailto:<br>";
//echo $mailto;
//echo "<br>mailsubject:<br>";
//echo $mailsubject;
//echo "<br>mailmessage:<br>";
//echo $mailmessage;
//echo "<br>mailheader:<br>";
//echo $mailheader;
//Dear XXXX,
//
//You have been assigned the following TAs:
//XXXX: XXXX - Grader
//
//TAs: Please contact the instructor as soon as possible.
//
//Thank you,
//
//Director of Graduate Studies
//echo '$mailmessage:'. $mailmessage;
//echo $tanumber;
//echo $ganumber;
//echo $lanumber;
//echo '$mailto:'.$mailto;
//echo '$mailheader:'.$mailheader;
if(empty($tanumber)&& empty($ganumber)&& empty($lanumber))
{
    $sendreturn=0;
}
else
{
    $sendreturn = mail($mailto, $mailsubject, $mailmessage, $mailheader);
    //$sendreturn=1;
}
if ($sendreturn) {
    echo 'success';
} else {
    echo 'fail';
}
?>