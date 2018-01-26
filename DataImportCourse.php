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

function UpdateCourseData($db,$year,$course,$p_same,$campusarray,$termarray,$facultyarray,$schoolcoursearray)
{
    $p_crn = $course['crn'];
    $instance = 1;

    $crn = ($course['crn']);
    $subj = ($course['subj']);
    $crse = ($course['crse']);
    $sec = ($course['sec']);
    $campus = ($course['campus']);
    $cred = ($course['cred']);
    $title = ($course['title']);
    $cap = ($course['cap']);
    $act = ($course['act']);
    $rem = ($course['rem']);
    $xlcap = ($course['xlcap']);
    $xlact = ($course['xlact']);
    $xlrem = ($course['xlrem']);
    $comments = ($course['comments']);
    $instructor = ($course['Instructor']);
    $startdate = ($course['startday']) . '/' . $year;
    $enddate = ($course['endday']) . '/' . $year;;
    $location = ($course['Location']);
    $days = ($course['days']);
    $starttime = ($course['starttime']);
    $endtime = ($course['endtime']);

    //echo '$schoolcoursearray:'.implode(",", $schoolcoursearray);
    //check course is on school course list
    foreach ($schoolcoursearray as $arr)
    {

        //echotoscreennobr($arr['Subject'] . $arr['Course'] . ':');
        //echotoscreennobr( $subj . $crse);
        if ($arr['Subject'] == $subj && $arr['Course'] == $crse)
        {
            $m_SchoolCourseID = $arr['SchoolCourseID'];
            echotoscreen('SchoolCourseID:' . $m_SchoolCourseID );
        }
    }
    if(empty($m_SchoolCourseID))
    {
        echo 'Course:'.string_to_ascii($subj).string_to_ascii($crse). '<br>';
        echo $subj.$crse.':is not in the school course list!'. '<br>';
    }


    $instructor = str_replace('(P)', "", $instructor);
    $instructor = str_replace('Irene   Weber', "", $instructor);
    $instructor = str_replace(',', "", $instructor);
    echotoscreen( 'string_to_ascii $instructor:' .string_to_ascii($instructor));
    if(empty($instructor)==false)
    {
        $firstletter = substr($instructor,0,1);
        echotoscreen(  '$firstletter:'.$firstletter) ;
        while (!preg_match('/^[A-Za-z]/',$firstletter))
        {
            $instructor = substr($instructor,1,strlen($instructor)-1);
            $firstletter = substr($instructor,0,1);
            echotoscreen(  '$firstletter:'.$firstletter) ;
            echotoscreen(  'later $instructor:'.$instructor) ;
        }
        $lastletter = substr($instructor,strlen($instructor)-1,1);
        //echo '$lastletter:'.$lastletter ;
        while (!preg_match('/^[A-Za-z]/',$lastletter))
        {
            $instructor = substr($instructor,0,strlen($instructor)-1);
            $lastletter = substr($instructor,strlen($instructor)-1,1);
            //echo '$lastletter:'.$lastletter ;
            //echo 'later $instructor:'.$instructor ;
        }
    }
    if ($p_same == false) {
        $instance = 1;
    } else {
        $instance = 2;
    }
    $instructor = trim($instructor);
    //echo '$instructor:' .$instructor;
    //echo string_to_ascii($instructor);
    $firstname = null;
    $lastname = null;
    if (strlen($instructor) > 0) {
        if (strpos($instructor, ' ') === false) {
            $firstname = $instructor;
            $lastname = $instructor;
        } else {
            $names = explode(' ', $instructor);
            $lastname = $names[count($names) - 1];
            $firstname = $names[0];
        }
        //$lastname = (strpos($instructor, ' ') === false) ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $instructor);

    }
   // echo '$firstname:';
   // echo $firstname;
    //echo '$lastname:';
    //echo $lastname;

    $firstname = str_replace(chr(160),'',$firstname);
    $firstname = str_replace(chr(194),' ',$firstname);
    $asciistr = string_to_ascii(@$firstname);
    //echo '$asciistr $firstname:' .$asciistr;

    $lastname = str_replace(chr(160),'',$lastname);
    $lastname = str_replace(chr(194),'',$lastname);
    $asciistr = string_to_ascii(@$lastname);
    //echo '$asciistr $lastname:' .$asciistr;

    //echo '$starttime:' . $starttime;
    if(empty($starttime)==false)
    {
        $starttime = date("G:i", strtotime($starttime));
    }
    else
    {
        $starttime = null;
    }
    //echo '$starttime end:' . $starttime;
    $starttime = !empty($starttime) ? "'$starttime'" : "NULL";

    //echo '$endtime:' . $endtime;
    $asciistr = string_to_ascii(@$endtime);
    //echo '$asciistr $endtime:' .$asciistr;
    if (empty($endtime)==false) {
        $endtime = date("G:i", strtotime($endtime));
    }
    else {
        $endtime = null;
    }
    //echo '$endtime:' . $endtime;

    $endtime = !empty($endtime) ? "'$endtime'" : "NULL";

    $campusid = null;
    foreach ($campusarray as $arr) {
        //echo $arr['Name'];
        //echo $arr['Endday'];
        //echo $campus;
        //echo $enddate;
        if ($arr['Name'] == $campus) {
            $campusid = $arr['Campusid'];
           // echo '$campusid:' . $campusid . '<br>';
        }
    }
    if(empty($campusid))
    {
        echo 'Campus: '.$campus.' is not exist!<br>';
    }
    $campusid = !empty($campusid) ? "'$campusid'" : "NULL";

    $termid = null;
    foreach ($termarray as $arr) {
        //echo $arr['Startday'];
        //echo $arr['Endday'];
        //echo $startdate;
        //echo $enddate;
        if (strtotime($arr['Startday']) == strtotime($startdate) && strtotime($arr['Endday']) == strtotime($enddate)) {
            $termid = $arr['Termid'];
            //echo '$termid:' . $termid . '<br>';
        }
    }
    if(empty($termid))
    {
        echo 'Term from Startday '.$startdate.'to $enddate '.$enddate.' is not exist!<br>';
    }
    $termid = !empty($termid) ? "'$termid'" : "NULL";

    $email = null;
    if((empty($firstname)==true && empty($lastname)==true)== false ) {
        foreach ($facultyarray as $arr) {
            //echo $arr['Name'] . ':';
            //echo $arr['Endday'];
            //echo $firstname;
            //echo $lastname;
            //echo $enddate;
            if (strpos($arr['Name'], $firstname) !== false && strpos($arr['Name'], $lastname) !== false) {
                $email = $arr['email'];
               // echo '$email:' . $email . '<br>';
            }
        }
        if (empty($email)) {
            echo 'Person firstname:'.$firstname.' lastname: '.$lastname.' is not exist!<br>';
            //echo ' this person is not in list!!!';
        }
    }
    else
    {
        echo 'CRN:'.$crn.'Subject:'.$subj.'Course:'.$crse;
        echo 'Person firstname or lastname is empty!<br>';
    }
    //echo '<br>';
    $email = !empty($email) ? "'$email'" : "''";

    $sql = "select  c.Courseid as Courseid , c.CRN as crn,sc.Scheduleid as scheduleid,sc2.Scheduleid as scheduleid2
                from tbl_course as c
                LEFT JOIN tbl_schedule as sc on sc.Courseid = c.Courseid and sc.Instance='1'
                LEFT JOIN tbl_schedule as sc2 on sc2.Courseid = c.Courseid and sc2.Instance='2'
                where  c.CRN = " . $p_crn ." and sc.Termid = ".$termid. " LIMIT 1";
    //echo $sql . '<br>';
    $result = mysqli_query($db, $sql) ;

    //echo '$p_same' . $p_same;
    //echo '$instance' . $instance;

    if ($result->num_rows > 0)
    {
        // output data of each row
        while (($row = mysqli_fetch_assoc($result))) {
            $Courseid = $row["Courseid"];
            $scheduleid = $row["scheduleid"];//$p_same=false
            $scheduleid2 = $row["scheduleid2"];//$p_same=true

            $sql = "UPDATE tbl_course
                set CRN='$crn', Subject='$subj', Course='$crse', Sec='$sec', Credit='$cred',
		        Title='$title', Capacity='$cap',Crosslistcapacity='$xlcap'
                where Courseid = $Courseid
                ";
            //echo $sql . '<br>';
            $resultcourse = mysqli_query($db, $sql);
            //echo "Course ID : " . $Courseid . " update";
            //echo '$instance' . $instance;
            if ($instance == 1) {
                if (!empty($scheduleid))
                {
                    if(empty($instructor)==false)
                    {
                        $sql = "update tbl_schedule
                      set Facultyid = $email,
                      Termid = $termid,
                      Campusid = $campusid,
                      Actual = '$act', Remaining='$rem',CrosslistActual='$xlact',Crosslistremaining='$xlrem',
                      Comments='$comments',Location='$location',Instance='$instance',Days='$days',
                      Starttime=$starttime,Endtime=$endtime
                      where Scheduleid = $scheduleid
                    ";
                    }
                    else
                    {
                        $sql = "update tbl_schedule
                                  set 
                                  Termid = $termid,
                                  Campusid = $campusid,
                                  Actual = '$act', Remaining='$rem',CrosslistActual='$xlact',Crosslistremaining='$xlrem',
                                  Comments='$comments',Location='$location',Instance='$instance',Days='$days',
                                  Starttime=$starttime,Endtime=$endtime
                                  where Scheduleid = $scheduleid
                                  ";
                    }
                   // echo $sql . '<br>';
                    $resultschedule = mysqli_query($db, $sql);
                    //echo "Schedule ID : " . $Courseid . "instance 1 Schedule ID:" . $scheduleid . " update";
                } else {
                    $sql = "INSERT INTO tbl_schedule(Courseid, Facultyid, Termid, Campusid, Actual, Remaining,
                  CrosslistActual, Crosslistremaining, Comments, Location, Instance, Days, Starttime, Endtime)
                    values('$Courseid',$email, $termid,$campusid,'$act','$rem','$xlact',
                    '$xlrem', '$comments','$location','$instance',
                    '$days', $starttime, $endtime)";
                    //echo $sql . '<br>';
                    $resultschedule = mysqli_query($db, $sql);
                    $scheduleid = mysqli_insert_id($db);
                    //echo "Schedule ID " . $instance . " of last inserted record is: " . $scheduleid;
                }
            }
            else
            {
                if (!empty($scheduleid2))
                {
                    if(empty($instructor)==false) {
                        $sql = "update tbl_schedule
                              set Facultyid = $email,
                              Termid = $termid,
                              Campusid = $campusid,
                              Actual = '$act', Remaining='$rem',CrosslistActual='$xlact',Crosslistremaining='$xlrem',
                              Comments='$comments',Location='$location',Instance='$instance',Days='$days',
                              Starttime=$starttime,Endtime=$endtime
                              where Scheduleid = $scheduleid2
                    ";
                    }
                    else
                    {
                        $sql = "update tbl_schedule
                              set 
                              Termid = $termid,
                              Campusid = $campusid,
                              Actual = '$act', Remaining='$rem',CrosslistActual='$xlact',Crosslistremaining='$xlrem',
                              Comments='$comments',Location='$location',Instance='$instance',Days='$days',
                              Starttime=$starttime,Endtime=$endtime
                              where Scheduleid = $scheduleid2
                    ";
                    }
                    //echo $sql . '<br>';
                    $resultschedule = mysqli_query($db, $sql);
                    //echo "Schedule ID : " . $Courseid . "instance 2 Schedule ID:" . $scheduleid . " update";
                }
                else
                {
                    $sql = "INSERT INTO tbl_schedule(Courseid, Facultyid, Termid, Campusid, Actual, Remaining,
                  CrosslistActual, Crosslistremaining, Comments, Location, Instance, Days, Starttime, Endtime)
                    values('$Courseid',$email, $termid,$campusid,'$act','$rem','$xlact',
                    '$xlrem', '$comments','$location','$instance',
                    '$days', $starttime, $endtime)";
                    //echo $sql . '<br>';
                    $resultschedule = mysqli_query($db, $sql);
                    $scheduleid2 = mysqli_insert_id($db);
                    //echo "Schedule ID " . $instance . " of last inserted record is: " . $scheduleid2;
                }
            }

        }

        mysqli_free_result($result);
    }
    else
        {
        //insert
        //echo "0 results";
        $sql = "INSERT INTO tbl_course(CRN, Subject, Course, Sec, Credit, Title, Capacity, Crosslistcapacity)
                values( '$crn', '$subj', '$crse', '$sec', '$cred','$title', '$cap', '$xlcap')";
        //echo $sql . '<br>';
        $resultcourse = mysqli_query($db, $sql);
        $Courseid = mysqli_insert_id($db);
        //echo "Course ID of last inserted record is: " . $Courseid;

        $sql = "INSERT INTO tbl_schedule(Courseid, Facultyid, Termid, Campusid, Actual, Remaining,
                  CrosslistActual, Crosslistremaining, Comments, Location, Instance, Days, Starttime, Endtime)
                    values('$Courseid',$email, $termid,$campusid,'$act','$rem','$xlact',
                    '$xlrem', '$comments','$location','$instance',
                    '$days', $starttime, $endtime)";
       // echo $sql . '<br>';
        $resultschedule = mysqli_query($db, $sql);
        $scheduleid = mysqli_insert_id($db);
        //echo "Schedule ID " . $instance . " of last inserted record is: " . $scheduleid;


    }

    if($p_same==false) {
        // add to ta assignment
        $sql = "SELECT ta.TAAssignmentID as taassignmentid,ta.CourseID as courseid,ta.TANumber as tanumber,ta.GANumber as ganumber,ta.LANumber as labnumber
                    FROM tbl_taassignment_info as ta
                    where ta.CourseID = " . $Courseid . " LIMIT 1";
        //echo $sql . '<br>';
        $result = mysqli_query($db, $sql);

        if ($result->num_rows > 0) {
            //echo 'exist this ta assignment:' . $Courseid;
            // output data of each row
            //        while (($row = mysqli_fetch_assoc($result)))
            //        {
            //            $taassignmentid = $row["taassignmentid"];
            //        }
        } else {
            $sql = "INSERT INTO tbl_taassignment_info(CourseID, TANumber, GANumber, LANumber)
                    values( '$Courseid', '0', '0', '0')";
            // echo $sql . '<br>';
            $resultassignment = mysqli_query($db, $sql);
            $taassignmentid = mysqli_insert_id($db);
            // echo "taassignmentid of last inserted record is: " . $taassignmentid;
        }

    }
}
function curl_downloadcourse($year,$month,$subject,$db,$campusarray,$termarray,$facultyarray,$schoolcoursearray,$m_startdate,$m_enddate){
    //$year = '2017';
    //$month = '05';
    //$subject = 'CSC';
    $url = "https://www.gosolar.gsu.edu/bprod/bwckschd.p_get_crse_unsec";
    $refurl = "http://cs.gsu.edu/?q=courses";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_REFERER, $refurl);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2 GTB5');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0); // allow redirects
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 0);
    curl_setopt($ch, CURLOPT_POST, 1); // set POST method
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "term_in=" . $year . $month . "&sel_subj=dummy&sel_subj=" . $subject .
        "&sel_day=dummy&sel_day=&sel_schd=dummy&sel_insm=dummy&sel_camp=dummy&sel_camp=%25&sel_camp=dummy&sel_camp=%25&sel_levl=dummy&sel_levl=US&sel_levl=GS&sel_sess=dummy&sel_instr=dummy&sel_instr=%25&sel_ptrm=dummy&sel_ptrm=%25&sel_attr=dummy&sel_attr=%25&sel_crse=&sel_title=&sel_from_cred=&sel_to_cred=&begin_hh=0&begin_mi=0&begin_ap=a&end_hh=0&end_mi=0&end_ap=a");
    curl_setopt($ch, CURLOPT_SSLVERSION, 3);
    $output = curl_exec($ch);
    echo $output;
    curl_close($ch);
    //echo strlen($output);
    // Create a DOM object
    //echo 'simple_html_dom start';
    $dom =new DOMDocument();
    //echo 'HTMLFile new';
    $dom ->loadHTML($output);
    //echo 'HTMLFile load';
    # Iterate over all the <a> tags
//    foreach($dom ->getElementsByTagName('a') as $link) {
//        # Show the <a href>
//        echo $link->getAttribute('href');
//        echo "<br />";
//    }
    $isbegin=false;
    $count = 0;
    $courses= array();
    $course = null;
    $p_crn=null;
    $p_subj = null;
    $p_crse=null;
    $p_sec=null;
    $p_campus=null;
    $p_cred=null;
    $p_title=null;
    $p_days=null;
    $p_time=null;
    $p_cap=null;
    $p_act=null;
    $p_rem=null;
    $p_xlcap=null;
    $p_xlact=null;
    $p_xlrem=null;
    $p_comments=null;
    $p_instructor=null;
    $p_date=null;
    $p_location=null;
    $p_same =false;
    $offcampus = false;
    foreach ($dom->getElementsByTagName('td') as $td)
    {
        if($td->getAttribute('class')=='dddefault' && $isbegin==false)
        {
            $isbegin=true;
        }
        else if ($td->getAttribute('class')=='ntdefault' && $isbegin==true)
        {
            $isbegin=false;
        }
        if($isbegin==true)
        {
            $count= $count+1;
            $value=$td->nodeValue;
            echotoscreennobr('nodeValue'.(string)$count.':'.$value);
            //echo "<br />";
            $value=$td->nodeValue;
            if(strlen($value) <= 2)
            {
                echotoscreennobr('value lenth less than or equal 2');
                $asciistr = string_to_ascii(@$value);
                //echo $asciistr;
                if (strpos($asciistr, '194') !== false && strpos($asciistr, '160') !== false)
                {
                    $value='';
                }
            }
            $asciistr = string_to_ascii(@$value);
            echotoscreennobr( '$asciistr:' .$asciistr);
            switch ($count)
            {
                case 1:
                    break;
                case 2:
                    break;
                case 3:
                    $course= array();
                    $offcampus=false;
                    echotoscreen('new array');

//                    echo 'length:';
//                    echo strlen(@$value);
//                    echo '@$value:';
//                    echo @$value;
//                    echo 'change:';
//                    echo string_to_ascii(@$value);
                    $value=str_replace(" ","",$value);
                    $value=str_replace("\n","",$value);
                    $value=str_replace("\r","",$value);

                    if($value=='')
                    {
                        $p_same=true;
                    }
                    if($p_crn == null)
                    {
                        $p_crn = $value;
                    }
                    else if ($value != '' && $value != $p_crn)
                    {
                        $p_crn=$value;
                        $p_same=false;
                    }
                    echotoscreennobr('$p_same:'.(string)$p_same);
                    echotoscreennobr('add crn:');
                    $course['crn']= $p_crn;
                    echotoscreennobr($course['crn']);
                    break;
                case 4:
                    if($p_subj == null)
                    {
                        $p_subj = $value;
                    }
                    else if ($value != '' && $value != $p_subj)
                    {
                        $p_subj=$value;
                    }
                    echotoscreennobr('add subj:');
                    $course['subj']= $p_subj;
                    echotoscreennobr( $course['subj']);
                    break;
                case 5:
                    if($p_crse == null)
                    {
                        $p_crse = $value;
                    }
                    else if ($value != '' && $value != $p_crse)
                    {
                        $p_crse=$value;
                    }
                    echotoscreennobr('add crse:');
                    $course['crse']= $p_crse;
                    if (strpos($p_crse, '4870') !== false or strpos($p_crse, '4880') !== false
                        or strpos($p_crse, '4940') !== false or strpos($p_crse, '4999') !== false
                        or strpos($p_crse, '6999') !== false or strpos($p_crse, '8999') !== false
                        or strpos($p_crse, '9999') !== false or strpos($p_crse, '8940') !== false
                        or strpos($p_crse, '8950') !== false or strpos($p_crse, '8982') !== false)
                    {
                        $offcampus=true;
                    }
                    //echo  $course['crse'];
                    break;
                case 6:
                    if($p_sec == null)
                    {
                        $p_sec = $value;
                    }
                    else if ($value != '' && $value != $p_sec)
                    {
                        $p_sec=$value;
                    }
                    echotoscreennobr( 'add sec:');
                    $course['sec']= $p_sec;
                    echotoscreennobr($course['sec']);
                    break;
                case 7:
                    if($p_campus == null)
                    {
                        $p_campus = $value;
                    }
                    else if ($value != '' && $value != $p_campus)
                    {
                        $p_campus=$value;
                    }
                    echotoscreennobr('add campus:');
                    $course['campus']= $p_campus;
                    if (strpos($p_campus, 'Off') !== false)
                    {
                        $offcampus=true;
                    }
                    echotoscreennobr( $course['campus']);
                    break;
                case 8:
                    if($p_cred == null)
                    {
                        $p_cred = $value;
                    }
                    else if ($value != '' && $value != $p_cred)
                    {
                        $p_cred=$value;
                    }
                    echotoscreennobr( 'add cred:');
                    if(strpos($value, '-') !== false)
                    {
                        $_cred =  explode("-", $value);
                        $p_cred=$_cred[1];
                    }

                    $course['cred']= $p_cred;
                    echotoscreennobr( $course['cred']);
                    break;
                case 9:
                    if($p_title == null)
                    {
                        $p_title = $value;
                    }
                    else if ($value != '' && $value != $p_title)
                    {
                        $p_title=$value;
                    }
                    echotoscreennobr( 'add title:');
                    $course['title']= $p_title;
                    echotoscreennobr( $course['title']);
                    break;
                case 10:
                    echotoscreennobr( 'add days:');
                    if(strpos($value, 'TBA') !== false)
                    {
                        echotoscreennobr( 'contains TBA:');
                        $value='';
                    }
                    $curvalue=implode(",",mb_str_split($value));
                    //echo implode("','",$curvalue);
                    //echo $curvalue;
                    $course['days']= $curvalue;
                    echotoscreennobr( $course['days']);
                    break;
                case 11:
                    echotoscreennobr( 'add time:');
                    if(strpos($value, 'TBA') !== false)
                    {
                        echotoscreennobr( 'contains TBA:');
                        $value='';
                    }
                    $value = str_replace(chr(160),'',$value);
                    $value = str_replace(chr(194),' ',$value);
                    //$time = str_replace(char(194),'',$starttime);
                    $asciistr = string_to_ascii(@$value);
                    echotoscreennobr( '$asciistr $time:' .$asciistr);
                    $time =  explode("-", $value);
                    $course['starttime']= $time[0];
                    $course['endtime']= $time[1];
                    echotoscreennobr(  $course['starttime']);
                    echotoscreennobr( ' end:');
                    echotoscreennobr(  $course['endtime']);
                    break;
                case 12:
                    if($p_cap == null)
                    {
                        $p_cap = $value;
                    }
                    else if ($value != '' && $value != $p_cap)
                    {
                        $p_cap=$value;
                    }
                    echotoscreennobr('add cap:');
                    $course['cap']= $p_cap;
                    echotoscreennobr( $course['cap']);
                    break;
                case 13:
                    if($p_act == null)
                    {
                        $p_act = $value;
                    }
                    else if ($value != '' && $value != $p_act)
                    {
                        $p_act=$value;
                    }
                    echotoscreennobr( 'add act:');
                    $course['act']= $p_act;
                    echotoscreennobr(  $course['act']);
                    break;
                case 14:
                    if($p_rem == null)
                    {
                        $p_rem = $value;
                    }
                    else if ($value != '' && $value != $p_rem)
                    {
                        $p_rem=$value;
                    }
                    echotoscreennobr( 'add rem:');
                    $course['rem']= $p_rem;
                    echotoscreennobr(  $course['rem']);
                    break;
                case 15:
                    if($p_xlcap == null)
                    {
                        $p_xlcap = $value;
                    }
                    else if ($value != '' && $value != $p_xlcap)
                    {
                        $p_xlcap=$value;
                    }
                    echotoscreennobr( 'add xlcap:');
                    $course['xlcap']= $p_xlcap;
                    echotoscreennobr(  $course['xlcap']);
                    break;
                case 16:
                    if($p_xlact == null)
                    {
                        $p_xlact = $value;
                    }
                    else if ($value != '' && $value != $p_xlact)
                    {
                        $p_xlact=$value;
                    }
                    echotoscreennobr( 'add xlact:');
                    $course['xlact']= $p_xlact;
                    echotoscreennobr(  $course['xlact']);
                    break;
                case 17:
                    if($p_xlrem == null)
                    {
                        $p_xlrem = $value;
                    }
                    else if ($value != '' && $value != $p_xlrem)
                    {
                        $p_xlrem=$value;
                    }
                    echotoscreennobr( 'add xlrem:');
                    $course['xlrem']= $p_xlrem;
                    echotoscreennobr(  $course['xlrem']);
                    break;
                case 18:
                    if($p_comments == null)
                    {
                        $p_comments = $value;
                    }
                    else if ($value != '' && $value != $p_comments)
                    {
                        $p_comments=$value;
                    }
                    echotoscreennobr( 'add comments:');
                    $course['comments']= $p_comments;
                    echotoscreennobr(  $course['comments']);
                    break;
                case 19:
                    echotoscreennobr( 'add Instructor:');
                    //$value = str_replace(chr(160),'',$value);
                    //$value = str_replace(chr(194),' ',$value);
                    //$time = str_replace(char(194),'',$starttime);
                    //$asciistr = string_to_ascii(@$value);
                    //echo '$asciistr Instructor:' .$asciistr;
                    if($value=='Staff')
                    {
                        $value='';
                    }
                    $course['Instructor']= $value;
                    echotoscreennobr(  $course['Instructor']);
                    break;
                case 20:
                    echotoscreennobr( 'add Date:');
                    $_date =  explode("-", $value);
                    if($m_startdate !=$_date[0])
                    {
                        $_date[0]=substr(str_replace('-','/',$m_startdate),5);
                    }
                    if($m_enddate!=$_date[1])
                    {
                        $_date[1]=substr(str_replace('-','/',$m_enddate),5);
                    }

                    $course['startday']= $_date[0];
                    $course['endday']= $_date[1];
                    echotoscreennobr(  $course['startday']);
                    echotoscreennobr(  ' endday:');
                    echotoscreennobr(  $course['endday']);
                    break;
                case 21:
                    $course['Location']= $value;
                    array_merge($courses,$course);
                    echotoscreennobr( '$p_same:');
                    echotoscreennobr( (string)$p_same);
                    echotoscreennobr( '$courses:');
                    echotoscreennobr( implode(",",$course));
                    if($offcampus == false)
                    {
                        UpdateCourseData($db, $year, $course, $p_same, $campusarray, $termarray, $facultyarray,$schoolcoursearray);
                    }
                    //echo '$endday:';
                    //echo $course['endday'];
                    //echo implode('|', array_map('implode', $courses, array_fill(0, count($courses), ',')));
                    break;
                default:
                    break;
            }

            if($count>=21)
            {
                $count=0;
            }
            echotoscreen( " ");
        }
        //echo $td->getAttribute('class');
        //echo $td->nodeValue;
    }

    return $output;
}
//$year ='2017';
//$month = '05';
//$subject = 'CSC';

if(empty($_GET['id'])==false) {
    if (is_numeric($_GET['id'])) {

        $id = (int)$_GET['id'];
        $name = $_GET['name'];
        $startdate = $_GET['startdate'];
        $enddate = $_GET['enddate'];
        $subject = 'CSC';

        $m_startdate=date( "Y-m-d", strtotime( $startdate ) );
        $year = date( 'Y',strtotime($startdate));
        $month = date( 'm',strtotime($startdate));
        switch ($month) {
            case 1:
            case 2:
            case 3:
            case 4:
            $month = '01';
                break;
            case 5:
            case 6:
            case 7:
            $month = '05';
                break;
            case 8:
            case 9:
            case 10:
            case 11:
            case 12:
            $month = '08';
                break;
            default:
                break;
        }

        //echo date('Y', strtotime($startdate));
        //echo '$id:' .$id .'$name:' .$name.'$startdate:' .$startdate.'$enddate:' .$enddate.'$subject:' .$subject.'$m_startdate:' .$m_startdate.'$year:' .$year.'$month:' .$month;
        $campusarray = array();
        $sql = "select Campusid,Name
                from tbl_campus";
        echotoscreen( $sql );
        $result = mysqli_query($db, $sql);

        if ($result->num_rows > 0) {
            $i = 0;
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                $campusarray[$i] = array();
                $campusarray[$i]['Campusid'] = $row["Campusid"];
                $campusarray[$i]['Name'] = $row["Name"];
                $i = $i + 1;
            }
        }

        $termarray = array();
        $sql = "select Termid,Startday,Endday
            from tbl_term";
        echotoscreen($sql );
        $result = mysqli_query($db, $sql);

        if ($result->num_rows > 0) {
            $i = 0;
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                $termarray[$i] = array();
                $termarray[$i]['Termid'] = $row["Termid"];
                $termarray[$i]['Startday'] = $row["Startday"];
                $termarray[$i]['Endday'] = $row["Endday"];
                $i = $i + 1;
            }
        }

        $facultyarray = array();
        $sql = "select PantherID,email, CONCAT(coalesce(FirstName,' ') , coalesce(MiddleName,' '),coalesce(LastName,' ')) as Name
              from tbl_faculty_info ";
        echotoscreen( $sql );
        $result = mysqli_query($db, $sql);

        if ($result->num_rows > 0) {
            $i = 0;
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                $facultyarray[$i] = array();
                $facultyarray[$i]['PantherID'] = $row["PantherID"];
                $facultyarray[$i]['email'] = $row["email"];
                $facultyarray[$i]['Name'] = $row["Name"];
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
                $facultyarray[$i] = array();
                $facultyarray[$i]['PantherID'] = $row["PantherID"];
                $facultyarray[$i]['email'] = $row["email"];
                $facultyarray[$i]['Name'] = $row["Name"];
                $i = $i + 1;
            }
        }

        $schoolcoursearray = array();
        $sql = "SELECT SchoolCourseID as SchoolCourseID,
                                Subject as Subject,
                                Course as Course,
                                CourseName as CourseName,
                                Credit as Credit,
                                Prerequisites as Prerequisites,
                                Description as Description
                                FROM tbl_schoolcourse
                                ORDER  by Course
                  ";
        echotoscreen($sql );
        $result = mysqli_query($db, $sql);

        if ($result->num_rows > 0) {
            $i = 0;
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                $schoolcoursearray[$i] = array();
                $schoolcoursearray[$i]['SchoolCourseID'] = $row["SchoolCourseID"];
                $schoolcoursearray[$i]['Subject'] = $row["Subject"];
                $schoolcoursearray[$i]['Course'] = $row["Course"];
                $schoolcoursearray[$i]['CourseName'] = $row["CourseName"];
                $schoolcoursearray[$i]['Credit'] = $row["Credit"];
                $schoolcoursearray[$i]['Prerequisites'] = $row["Prerequisites"];
                $schoolcoursearray[$i]['Description'] = $row["Description"];
                $i = $i + 1;
            }
        }


        curl_downloadcourse($year,$month,$subject,$db,$campusarray,$termarray,$facultyarray,$schoolcoursearray,$startdate,$enddate);
    }
}



?>