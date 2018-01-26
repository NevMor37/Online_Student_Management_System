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

if(isset($_POST['register_btn']))
{
    //$username=($_POST['firstName']);
    $crn=($_POST['crn']);
    $subj=($_POST['subj']);
    $crse=($_POST['crse']);
    $sec=($_POST['sec']);
    $campus=($_POST['campus']);
    $cred=($_POST['cred']);
    $title=($_POST['title']);
    $cap=($_POST['cap']);
    $act=($_POST['act']);
    $rem=($_POST['rem']);
    $xlcap=($_POST['xlcap']);
    $xlact=($_POST['xlact']);
    $xlrem=($_POST['xlrem']);
    $comments=($_POST['comments']);
    $instructor=($_POST['instructor']);
    $instructor2=($_POST['instructor2']);
    $termid=($_POST['term']);
    $startdate=($_POST['startdate']);
    $enddate=($_POST['enddate']);
    $location=($_POST['location']);
    $location2=($_POST['location2']);
    //echo $_POST['days1'];

    if(!empty($_POST['days1'])) {
        $days1='';
        foreach($_POST['days1'] as $_days1) {
            //echo '$days1before:';
            //echo $_days1;
            $days1=$days1 .$_days1 .',';
            //echo '$days1:';
            //echo $days1;
        }
        //echo '$days1before';
        //echo $days1;
        $days1=substr($days1, 0, -1);
        //echo '$days1:';
        //echo $days1;
    }
    //$days1=($_POST['days1']);
    $starttime1=($_POST['starttime1']);
    $endtime1=($_POST['endtime1']);
    //echo $_POST['days2'];
    //$days2=($_POST['days2']);
    if(!empty($_POST['days2'])) {
        $days2='';
        foreach($_POST['days2'] as $_days2) {
            //echo '$days1before:';
            //echo $_days1;
            $days2=$days2 .$_days2 .',';
            //echo '$days1:';
            //echo $days1;
        }
        //echo '$days1before';
        //echo $days1;
        $days2=substr($days2, 0, -1);
        // echo '$days2:';
        //echo $days2;
    }
    $starttime2=($_POST['starttime2']);
    $endtime2=($_POST['endtime2']);

    if($_SESSION["status"]=="create") {
        //echo ' id is null. ';
        $sql = "INSERT INTO tbl_course(CRN, Subject, Course, Sec, Credit, Title, Capacity, Crosslistcapacity)
                  VALUES('$crn', '$subj', '$crse', '$sec', '$cred','$title', '$cap', '$xlcap')";
        echo $sql .'<br>';
        $result = mysqli_query($db,$sql);
        $courseid = mysqli_insert_id($db);
        // echo "Course ID of last inserted record is: " . $courseid;

        if(empty($days1))
        {
            $days1=null;
        }
        if(empty($starttime1))
        {
            $starttime1=null;
        }
        $starttime1 = !empty($starttime1) ? "'$starttime1'" : "NULL";
        if(empty($endtime1))
        {
            $endtime1=null;
        }
        $endtime1 = !empty($endtime1) ? "'$endtime1'" : "NULL";
        $sql = "INSERT INTO tbl_schedule(Courseid, Facultyid, Termid, Campusid, Actual, Remaining,
                  CrosslistActual, Crosslistremaining, Comments, Location, Instance, Days, Starttime, Endtime)
                    select '$courseid',fa.email, te.Termid,ca.Campusid,'$act','$rem','$xlact', '$xlrem', '$comments','$location','1','$days1', $starttime1, $endtime1
                    from tbl_faculty_info as fa, tbl_term as te,tbl_campus as ca
                    
                    where fa.email ='$instructor'
                    and te.Termid = '$termid'
                    and ca.Campusid = '$campus'";
        echo $sql .'<br>';
        $result = mysqli_query($db,$sql);
        $scheduleid = mysqli_insert_id($db);
        //echo "Schedule ID 1 of last inserted record is: " . $scheduleid;

        if(!empty($instructor2) or!empty($location2) or !empty($days2) or !empty($starttime2) or !empty($endtime2) ) {
            if(empty($instructor2))
            {
                $instructor2=null;
            }
            if(empty($location2))
            {
                $location2=null;
            }
            if(empty($days2))
            {
                $days2=null;
            }
            if(empty($starttime2))
            {
                $starttime2=null;
            }
            $starttime2 = !empty($starttime2) ? "'$starttime2'" : "NULL";
            if(empty($endtime2))
            {
                $endtime2=null;
            }
            $endtime2 = !empty($endtime2) ? "'$endtime2'" : "NULL";
            $sql = "INSERT INTO tbl_schedule(Courseid, Facultyid, Termid, Campusid, Actual, Remaining,
                  CrosslistActual, Crosslistremaining, Comments, Location, Instance, Days, Starttime, Endtime)
                    select '$courseid','$instructor2', te.Termid,ca.Campusid,'$act','$rem','$xlact', '$xlrem', '$comments','$location2','2','$days2', $starttime2, $endtime2
                    from  tbl_term as te,tbl_campus as ca
                    
                    where  te.Termid = '$termid'
                    and ca.Campusid = '$campus'";
            echo $sql . '<br>';
            $result = mysqli_query($db, $sql);
            $scheduleid = mysqli_insert_id($db);
            echo "Schedule ID 2 of last inserted record is: " . $scheduleid;
        }
    }
    else
    {
        $id = $_SESSION["id"];
//         $sql = "UPDATE ta_course_info
//                set crn='$crn', subj='$subj', crse='$crse', sec='$sec', campus='$campus',
//                cred='$cred', title='$title', cap='$cap', act='$act', rem='$rem',
//                xlcap='$xlcap', xlact='$xlact', xlrem='$xlrem', comments='$comments',
//                instructor='$instructor', startdate='$startdate', enddate='$enddate', location='$location',
//                days1='$days1', starttime1='$starttime1', endtime1='$endtime1',
//                days2='$days2', starttime2='$starttime2', endtime2='$endtime2'
//                where id = $id";
        $sql = "UPDATE tbl_course
                set CRN='$crn', Subject='$subj', Course='$crse', Sec='$sec', Credit='$cred', 
		        Title='$title', Capacity='$cap',Crosslistcapacity='$xlcap'
                where Courseid = $id
                ";
        echo $sql .'<br>';
        $result = mysqli_query($db,$sql);
        //echo "Course ID : " . $id ." update";

        $scheduleid=$_SESSION["scheduleid"];
        if(!empty($scheduleid))
        {
            if( !empty($days1) or !empty($starttime1) or !empty($endtime1) ) {
                if (empty($days1)) {
                    $days1 = null;
                }
                if (empty($starttime1)) {
                    $starttime1 = null;
                }
                $starttime1 = !empty($starttime1) ? "'$starttime1'" : "NULL";
                if (empty($endtime1)) {
                    $endtime1 = null;
                }
                $endtime1 = !empty($endtime1) ? "'$endtime1'" : "NULL";
                $sql = "update tbl_schedule
                      set Facultyid = '$instructor',
                      Termid = (select Termid from tbl_term as te where te.Termid = '$termid'),
                      Campusid = (select Campusid from tbl_campus as ca where ca.Campusid = '$campus'),
                      Actual = '$act', Remaining='$rem',CrosslistActual='$xlact',Crosslistremaining='$xlrem',
                      Comments='$comments',Location='$location',Instance='1',Days='$days1',
                      Starttime=$starttime1,Endtime=$endtime1
                      where Scheduleid = $scheduleid
                    ";
                echo $sql . '<br>';
                $result = mysqli_query($db, $sql);
                //echo "Course ID : " . $id . "instance 1 Schedule ID:" . $scheduleid . " update";
            }
            else
            {
                $sql = "delete from tbl_schedule
                        where Scheduleid = $scheduleid
                    ";
                echo $sql . '<br>';
                $result = mysqli_query($db, $sql);
                //echo "Course ID : " . $id . "instance 1 Schedule ID:" . $scheduleid . " delete";
            }
        }
        else
        {
            if(!empty($days1) or !empty($starttime1) or !empty($endtime1) ) {
                if (empty($days1)) {
                    $days1 = null;
                }
                if (empty($starttime1)) {
                    $starttime1 = null;
                }
                $starttime1 = !empty($starttime1) ? "'$starttime1'" : "NULL";
                if (empty($endtime1)) {
                    $endtime1 = null;
                }
                $endtime1 = !empty($endtime1) ? "'$endtime1'" : "NULL";
                $sql = "INSERT INTO tbl_schedule(Courseid, Facultyid, Termid, Campusid, Actual, Remaining,
                  CrosslistActual, Crosslistremaining, Comments, Location, Instance, Days, Starttime, Endtime)
                    select '$id',fa.email, te.Termid,ca.Campusid,'$act','$rem','$xlact', '$xlrem', '$comments','$location','1','$days1', $starttime1, $endtime1
                    from tbl_faculty_info as fa, tbl_term as te,tbl_campus as ca
                    
                    where fa.email ='$instructor'
                    and te.Termid = '$termid'
                    and ca.Campusid = '$campus'";
                echo $sql .'<br>';
                $result = mysqli_query($db,$sql);
                $scheduleid = mysqli_insert_id($db);
                //echo "Schedule ID 1 of last inserted record is: " . $scheduleid;
            }
        }


        $scheduleid2=$_SESSION["scheduleid2"];
        if(!empty($scheduleid2))
        {
            if(!empty($instructor2) or!empty($location2) or !empty($days2) or !empty($starttime2) or !empty($endtime2) ) {
                if (empty($instructor2)) {
                    $instructor2 = null;
                }
                if (empty($location2)) {
                    $location2 = null;
                }
                if (empty($days2)) {
                    $days2 = null;
                }
                if (empty($starttime2)) {
                    $starttime2 = null;
                }
                $starttime2 = !empty($starttime2) ? "'$starttime2'" : "NULL";
                if (empty($endtime2)) {
                    $endtime2 = null;
                }
                $endtime2 = !empty($endtime2) ? "'$endtime2'" : "NULL";
                $sql = "update tbl_schedule
                      set Facultyid = (select email from tbl_faculty_info as fa where fa.email ='$instructor2'),
                      Termid = (select Termid from tbl_term as te where te.Termid = '$termid'),
                      Campusid = (select Campusid from tbl_campus as ca where ca.Campusid = '$campus'),
                      Actual = '$act', Remaining='$rem',CrosslistActual='$xlact',Crosslistremaining='$xlrem',
                      Comments='$comments',Location='$location2',Instance='2',Days='$days2',
                      Starttime=$starttime2,Endtime=$endtime2
                      where Scheduleid = $scheduleid2
                    ";
                echo $sql . '<br>';
                $result = mysqli_query($db, $sql);
                //echo "Course ID : " . $id . "instance 2 Schedule ID:" . $scheduleid2 . " update";
            }
            else
            {
                $sql = "delete from tbl_schedule
                        where Scheduleid = $scheduleid2
                    ";
                echo $sql . '<br>';
                $result = mysqli_query($db, $sql);
                //echo "Course ID : " . $id . "instance 2 Schedule ID:" . $scheduleid2 . " delete";
            }
        }
        else
        {
            if(!empty($instructor2) or!empty($location2) or !empty($days2) or !empty($starttime2) or !empty($endtime2) ) {
                if (empty($instructor2)) {
                    $instructor2 = null;
                }
                if (empty($location2)) {
                    $location2 = null;
                }
                if (empty($days2)) {
                    $days2 = null;
                }
                if (empty($starttime2)) {
                    $starttime2 = null;
                }
                $starttime2 = !empty($starttime2) ? "'$starttime2'" : "NULL";
                if (empty($endtime2)) {
                    $endtime2 = null;
                }
                $endtime2 = !empty($endtime2) ? "'$endtime2'" : "NULL";
                $sql = "INSERT INTO tbl_schedule(Courseid, Facultyid, Termid, Campusid, Actual, Remaining,
                  CrosslistActual, Crosslistremaining, Comments, Location, Instance, Days, Starttime, Endtime)
                    select '$id',fa.email, te.Termid,ca.Campusid,'$act','$rem','$xlact', '$xlrem', '$comments','$location2','2','$days2', $starttime2, $endtime2
                    from tbl_faculty_info as fa, tbl_term as te,tbl_campus as ca
                    
                    where fa.email ='$instructor2'
                    and te.Termid = '$termid'
                    and ca.Campusid = '$campus'";
                echo $sql .'<br>';
                $result = mysqli_query($db,$sql);
                $scheduleid2 = mysqli_insert_id($db);
                //echo "Schedule ID 2 of last inserted record is: " . $scheduleid2;
            }
        }

    }
    //echo  $sql;
    //mysqli_query($db,$sql);
    //$_SESSION['message']="You are now logged in";
    header("location:tacourseviewdashboard.php");  //redirect home page
}
else
{
    if(empty($_GET['id'])==false) {
        if (is_numeric($_GET['id'])) {

            $id = (int)$_GET['id'];
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
                                        sc.Facultyid  as instructor,
                                        sc2.Facultyid  as instructor2,
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
                                        left JOIN tbl_term as te on te.Termid = sc.Termid
                                        left JOIN tbl_schedule as sc2 on sc2.Courseid= c.Courseid and sc2.Instance=2
                        where  c.Courseid = " . $id;
            //echo $sql;
            $result = mysqli_query($db, $sql);

            if ($result->num_rows > 0) {
                // output data of each row
                while ($row = $result->fetch_assoc()) {
                    //echo "id: " . $row["id"] . " - Name: " . $row["crn"] . " " . $row["subj"] . $row["crse"]. $row["instructor"] . "<br>";
                    $id = $row["id"];
                    $crn = $row["crn"];
                    $subj = $row["subj"];
                    $crse = $row["crse"];
                    $sec = $row["sec"];
                    $campus = $row["campus"];
                    $cred = $row["cred"];
                    $title = $row["title"];
                    $cap = $row["cap"];
                    $act = $row["act"];
                    $rem = $row["rem"];
                    $xlcap = $row["xlcap"];
                    $xlact = $row["xlact"];
                    $xlrem = $row["xlrem"];
                    $comments = $row["comments"];
                    $instructor = $row["instructor"];
                    $instructor2 = $row["instructor2"];
                    $termid = $row["termid"];
                    $startdate = $row["startdate"];
                    $enddate = $row["enddate"];
                    $location = $row["location"];
                    $location2 = $row["location2"];
                    $days1 = $row["days1"];
                    $starttime1 = $row["starttime1"];
                    $endtime1 = $row["endtime1"];
                    $days2 = $row["days2"];
                    $starttime2 = $row["starttime2"];
                    $endtime2 = $row["endtime2"];
                    $scheduleid = $row["scheduleid"];
                    $scheduleid2 = $row["scheduleid2"];
                    $_SESSION["id"] = $id;
                    $_SESSION["scheduleid"]=$scheduleid;
                    $_SESSION["scheduleid2"]=$scheduleid2;
                }
            } else {
                //echo "0 results";
            }

            $_SESSION["status"] = "update";
            //echo $_SESSION["status"];
        }
    }
    else
    {
        $_SESSION["status"] = "create";
        //echo $_SESSION["status"];
    }
}

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
?>
<!DOCTYPE html>
<html>
<!-- Header -->
<head>
    <?php
    include $root.'/links/header.php';
    include $root.'/links/footerLinks.php';
    ?>

</head>
<!-- /#Header -->
<body  onload="ChangTerm(this)">
<div id="wrapper">
    <?php
    if(isset($_SESSION['message']))
    {
        echo "<div id='error_msg'>".$_SESSION['message']."</div>";
        unset($_SESSION['message']);
    }
    ?>

<script LANGUAGE="JavaScript">
    function ChangTerm(selectObject){
        //var value = selectObject.label;
        //window.alert(value);
        //var res = value.split(":");
        //document.tacourse_newregister.startdate.value = res[0];
        //document.tacourse_newregister.enddate.value = res[1];
        //var Index = document.tacourse_newregister.term.options[document.tacourse_newregister.term.selectedIndex].value
        var pvalue=document.tacourse_newregister.term.options[document.tacourse_newregister.term.selectedIndex].text;
        //window.alert(pvalue);
        var res = pvalue.split(":");
        //window.alert(res[0]);
        //window.alert(res[1]);
        document.tacourse_newregister.startdate.value = res[0];
        document.tacourse_newregister.enddate.value = res[1];
        //document.getElementById("demo").innerHTML
//        if (chosen == 'oth') {
//            document.myform.other.style.visibility = 'visible';
//        } else {
//            document.myform.other.style.visibility = 'hidden';
//            document.myform.other.value = '';
//        }

    }
</script>

    <!-- Navigation -->
    <?php
    include $root.'/UI/staff/staffmenu.php';
    ?>
    <!-- /#Navigation -->
    <!-- page-wrapper -->
    <div id="page-wrapper">
        <form method="post" action="tacourse_newregister.php" name ="tacourse_newregister" >
            <table>
                <tr>
                    <td>CRN : </td>
                    <td><input type="text" name="crn" class="textInput" required value="<?php if(empty($id)==false){ echo $crn;} ?>"  ></td>
                </tr>
                <tr>
                    <td>Subj : </td>
                    <td><input type="text" name="subj" class="textInput" value="<?php if(empty($id)==false){ echo $subj;} ?>" ></td>
                </tr>
                <tr>
                    <td>Crse : </td>
                    <td><input type="text" name="crse" class="textInput" value="<?php if(empty($id)==false){ echo $crse;} ?>" ></td>
                </tr>
                <tr>
                    <td>Sec : </td>
                    <td><input type="text" name="sec" class="textInput" value="<?php if(empty($id)==false){ echo $sec;} ?>" ></td>
                </tr>
                <tr>
                    <td class="select">Campus:</td>
                    <td ALIGN="left">
                        <select name="campus">
                            <?php
                            //$db=mysqli_connect("localhost","root","hu1015","authentication");
                            $sql = "select Name,Campusid
                          from tbl_campus
                        ";
                            $result = mysqli_query($db,$sql);
                            while ($facultyrow=mysqli_fetch_array($result)) {
                                $campusname = $facultyrow["Name"];
                                $campusid = $facultyrow["Campusid"];
                                echo "<option value='$campusid'";
                                if(empty($id)==false)
                                {
                                    if ($campusid==$campus)
                                    {
                                        echo "selected";
                                    }
                                }
                                echo  "    >
                            $campusname
                            </option>";
                            }
                            ?>
                        </select>
                    </td>

                </tr>
                <tr>
                    <td>Cred : </td>
                    <td><input type="number" name="cred"  step="0.5" class="textInput" value="<?php if(empty($id)==false){ echo $cred;} else{echo '0';} ?>"  ></td>
                </tr>
                <tr>
                    <td>Title : </td>
                    <td><input type="text" name="title" class="textInput" value="<?php if(empty($id)==false){ echo $title;} ?>" ></td>
                </tr>
                <tr>
                    <td>Cap : </td>
                    <td><input type="number" name="cap" class="textInput" value="<?php if(empty($id)==false){ echo $cap;} else {echo '0';} ?>" ></td>
                </tr>
                <tr>
                    <td>Act : </td>
                    <td><input type="number" name="act" class="textInput" value="<?php if(empty($id)==false){ echo $act;} else {echo '0';} ?>" ></td>
                </tr>
                <tr>
                    <td>Rem : </td>
                    <td><input type="number" name="rem" class="textInput" value="<?php if(empty($id)==false){ echo $rem;} else {echo '0';} ?>" ></td>
                </tr>
                <tr>
                    <td>Xlcap : </td>
                    <td><input type="number" name="xlcap" class="textInput" value="<?php if(empty($id)==false){ echo $xlcap;} else {echo '0';} ?>" ></td>
                </tr>
                <tr>
                    <td>Xlact : </td>
                    <td><input type="number" name="xlact" class="textInput" value="<?php if(empty($id)==false){ echo $xlact;} else {echo '0';} ?>" ></td>
                </tr>
                <tr>
                    <td>Xlrem : </td>
                    <td><input type="number" name="xlrem" class="textInput" value="<?php if(empty($id)==false){ echo $xlrem;} else {echo '0';}  ?>" ></td>
                </tr>
                <tr>
                    <td>Comments : </td>
                    <td><textarea  name="comments" rows="4" cols="30" ><?php if(empty($id)==false){ echo $comments;} ?></textarea></td>
                </tr>
                <tr>
                    <td class="select">Term:</td>
                    <td ALIGN="left">
                        <select name="term" id="term" onChange="ChangTerm(this)">
                            <?php
                            //$db=mysqli_connect("localhost","root","hu1015","authentication");
                            $sql = "select Termid,Term,Startday,Endday
                          from tbl_term
                        ";
                            $result = mysqli_query($db,$sql);
                            while ($facultyrow=mysqli_fetch_array($result)) {
                                $termname = $facultyrow["Term"];
                                $ptermid = $facultyrow["Termid"];
                                $Days = $facultyrow["Startday"].':'.$facultyrow["Endday"];
                                echo "<option label='$termname' value='$ptermid' ";
                                if(empty($id)==false)
                                {
                                    if ($ptermid==$termid)
                                    {
                                        echo "selected";
                                    }
                                }
                                echo  "    >
                            $Days
                            </option>";
                            }
                            ?>
                        </select>
                    </td>

                </tr>
                <tr>
                    <td>Startdate : </td>
                    <td><input type="date" name="startdate" class="textInput"  <?php if(empty($id)==false){ echo "value= $startdate";}else {} ?>   readonly="true" ></td>
                </tr>
                <tr>
                    <td>Enddate : </td>
                    <td><input type="date" name="enddate" class="textInput" <?php if(empty($id)==false){ echo "value= $enddate";}else {} ?>  readonly="true"></td>
                </tr>
                <tr>
                    <td class="select">Instructor:</td>
                    <td ALIGN="left">
                        <select name="instructor">
                            <?php
                            echo "<option value=''>NULL</option>";
                            foreach ($instructorarray as $arr) {
                                $email = $arr["email"];
                                $instructorname = $arr["Name"];
                                echo "<option value='$email'";
                                if(empty($id)==false)
                                {
                                    if ($email==$instructor)
                                    {
                                        echo "selected";
                                    }
                                }
                                echo  "    >
                            $instructorname
                            </option>";
                            }
                            ?>
                        </select>
                    </td>

                </tr>
                <tr>
                    <td>Location : </td>
                    <td><input type="text" name="location" class="textInput" value="<?php if(empty($id)==false){ echo $location;} ?>" ></td>
                </tr>
                <tr>
                    <td >Days : </td>
                    <td>
                        <div class="container">
                            <input type="checkbox" name="days1[]" value="M" <?php if(empty($id)==false){if(preg_match('/M/',$days1)){ echo 'checked';}} ?> />Monday
                            <input type="checkbox" name="days1[]" value="T" <?php if(empty($id)==false){if(preg_match('/T/',$days1)){ echo 'checked';}} ?> />Tuesday
                            <input type="checkbox" name="days1[]" value="W" <?php if(empty($id)==false){if(preg_match('/W/',$days1)){ echo 'checked';}} ?> />Wednesday<br />
                            <input type="checkbox" name="days1[]" value="R" <?php if(empty($id)==false){if(preg_match('/R/',$days1)){ echo 'checked';}} ?> />Thursday
                            <input type="checkbox" name="days1[]" value="F" <?php if(empty($id)==false){if(preg_match('/F/',$days1)){ echo 'checked';}} ?> />Friday
                            <input type="checkbox" name="days1[]" value="S" <?php if(empty($id)==false){if(preg_match('/S/',$days1)){ echo 'checked';}} ?> />Saturday<br />
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Starttime : </td>
                    <td><input type="time" name="starttime1" class="textInput" value="<?php if(empty($id)==false){ echo $starttime1;} ?>" ></td>
                </tr>
                <tr>
                    <td>Endtime : </td>
                    <td><input type="time" name="endtime1" class="textInput" value="<?php if(empty($id)==false){ echo $endtime1;} ?>" ></td>
                </tr>
                <tr>
                    <td class="select">Instructor2:</td>
                    <td ALIGN="left">
                        <select name="instructor2">
                            <?php
                            echo "<option value=''>NULL</option>";
                            foreach ($instructorarray as $arr) {
                                $email = $arr["email"];
                                $instructorname = $arr["Name"];
                                echo "<option value='$email'";
                                if(empty($id)==false)
                                {
                                    if ($email==$instructor2)
                                    {
                                        echo "selected";
                                    }
                                }
                                echo  "    >
                            $instructorname
                            </option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Location2 : </td>
                    <td><input type="text" name="location2" class="textInput" value="<?php if(empty($id)==false){ echo $location2;} ?>" ></td>
                </tr>
                <tr>
                    <td>Days : </td>
                    <td>
                        <div class="container">
                            <input type="checkbox" name="days2[]" value="M" <?php if(empty($id)==false){if(preg_match('/M/',$days2)){ echo 'checked';}} ?> />Monday
                            <input type="checkbox" name="days2[]" value="T" <?php if(empty($id)==false){if(preg_match('/T/',$days2)){ echo 'checked';}} ?> />Tuesday
                            <input type="checkbox" name="days2[]" value="W" <?php if(empty($id)==false){if(preg_match('/W/',$days2)){ echo 'checked';}} ?> />Wednesday<br />
                            <input type="checkbox" name="days2[]" value="R" <?php if(empty($id)==false){if(preg_match('/R/',$days2)){ echo 'checked';}} ?> />Thursday
                            <input type="checkbox" name="days2[]" value="F" <?php if(empty($id)==false){if(preg_match('/F/',$days2)){ echo 'checked';}} ?> />Friday
                            <input type="checkbox" name="days2[]" value="S" <?php if(empty($id)==false){if(preg_match('/S/',$days2)){ echo 'checked';}} ?> />Saturday<br />
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Starttime : </td>
                    <td><input type="time" name="starttime2" class="textInput" value="<?php if(empty($id)==false){ echo $starttime2;} ?>" ></td>
                </tr>
                <tr>
                    <td>Endtime : </td>
                    <td><input type="time" name="endtime2" class="textInput" value="<?php if(empty($id)==false){ echo $endtime2;} ?>" ></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="submit" name="register_btn" class="Register" value="Submit"></td>
                </tr>
            </table>
</form>
    </div>
</div>
</body>
</html>
