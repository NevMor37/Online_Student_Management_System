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
$user_role=$_SESSION['user']['role'] ;
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

function taevaluationextraInsertData($TaEvaluationID,$Field,$Instance,$Value)
{
    $sql ="
               insert into  tbl_taevaluationextra(TaEvaluationID,Field,Instance,Value)
               values('$TaEvaluationID','$Field','$Instance','$Value');
                    ";
    return $sql;
}

function taevaluationextraupdateData($TaEvaluationID,$Field,$Instance,$Value)
{
    $sql="
                update tbl_taevaluationextra
                set Value='$Value'
                where TaEvaluationID='$TaEvaluationID' and Field='$Field';
                    ";
    return $sql;
}

if (isset($_POST['options']) && count($_POST['options']) <= 3) {
    // OK
} else {
    // Not OK
}

if(isset($_POST['register_btn']))
{
    echo 'begin';
    $pTATermID=$_SESSION["TATermID"];
    $pTAStudentID=$_SESSION["TAStudentID"];
    $pTAInstructor=$_SESSION["TAInstructor"];
    $pTACourseID=$_SESSION["TACourseID"];
    $TermID=($_POST['termid']);
    if(empty($TermID))
    {
        $TermID=$pTATermID;
    }
    echo '$TermID:'. $TermID;
    $StudentID=($_POST['TAInfo']);
    if(empty($StudentID))
    {
        $StudentID=$pTAStudentID;
    }
    $Instructor=($_POST['InstructorInfo']);
    if(empty($Instructor))
    {
        $Instructor=$pTAInstructor;
    }
    $CourseID=($_POST['CourseInfo']);
    if(empty($CourseID))
    {
        $CourseID=$pTACourseID;
    }
    $ReliabilityA1=($_POST['ReliabilityA1']);
    $ReliabilityA2=($_POST['ReliabilityA2']);
    $ReliabilityA3=($_POST['ReliabilityA3']);
    $ReliabilityA4=($_POST['ReliabilityA4']);
    $EngagementA1=($_POST['EngagementA1']);
    $ProficiencyA1=($_POST['ProficiencyA1']);
    $CommunicationA1=($_POST['CommunicationA1']);
    $JudgementA1=($_POST['JudgementA1']);
    $TutorialslabsA1=($_POST['TutorialslabsA1']);
    $TutorialslabsA2=($_POST['TutorialslabsA2']);
    $ConstructingA1=($_POST['ConstructingA1']);
    $ConstructingA2=($_POST['ConstructingA2']);
    $GradingA1=($_POST['GradingA1']);
    $GradingA2=($_POST['GradingA2']);
    $GradingA3=($_POST['GradingA3']);
    $TestExamA1=($_POST['TestExamA1']);
    $OverallScore=($_POST['OverallScore']);
    $IsAcceptNextTerm=($_POST['IsAcceptNextTerm']);
    $Comment=($_POST['Comment']);

    //$IsSetEvaluationDatetime='0';//0 not set time, 1 set time(Insert or update)
//    if($user_role=='Staff' or $user_role=='Admin')
//    {
//        $IsSetEvaluationDatetime=0;
//    }
//    else if($user_role=='Faculty')
//    {
//        $IsSetEvaluationDatetime=1;
//    }

    $sql = "
            select TaEvaluationID
            from tbl_taevaluation as taev 
            where StudentID='$StudentID' and Instructor= '$Instructor'and Term='$TermID' and CourseID='$CourseID'
             " ;
    echo $sql;
    $result = mysqli_query($db, $sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            //echo "id: " . $row["id"] . "<br>";
            $id = $row["TaEvaluationID"];
        }
        if( $_SESSION["status"]=='update') {
            if($user_role=='Staff' or $user_role=='Admin')
            {
                $updatesql = " 
                      update tbl_taevaluation
                      set OverallScore='$OverallScore',Comment='$Comment',IsAcceptNextTerm='$IsAcceptNextTerm',
                      UpdateTime=NOW()
                      where TaEvaluationID='$id'
                     ";
            }
            else if($user_role=='Faculty' or $user_role=='Student')
            {
                $updatesql = " 
                      update tbl_taevaluation
                      set OverallScore='$OverallScore',Comment='$Comment',IsAcceptNextTerm='$IsAcceptNextTerm',
                      EvaluationDatetime=NOW(),UpdateTime=NOW()
                      where TaEvaluationID='$id'
                     ";
            }
            else
            {
                $errormsg = 'You have been logout!';
                //echo $errormsg;
                $_SESSION['message']= $errormsg;
                die($errormsg);
            }

            echo $updatesql;
            $updateresult = mysqli_query($db, $updatesql);
            $Count =1;
            $sql='';
            $sql=$sql. taevaluationextraupdateData($id,'ReliabilityA1',$Count,$ReliabilityA1);
            $sql=$sql. taevaluationextraupdateData($id,'ReliabilityA2',$Count,$ReliabilityA2);
            $sql=$sql. taevaluationextraupdateData($id,'ReliabilityA3',$Count,$ReliabilityA3);
            $sql=$sql. taevaluationextraupdateData($id,'ReliabilityA4',$Count,$ReliabilityA4);
            $sql=$sql. taevaluationextraupdateData($id,'EngagementA1',$Count,$EngagementA1);
            $sql=$sql. taevaluationextraupdateData($id,'ProficiencyA1',$Count,$ProficiencyA1);
            $sql=$sql. taevaluationextraupdateData($id,'CommunicationA1',$Count,$CommunicationA1);
            $sql=$sql. taevaluationextraupdateData($id,'JudgementA1',$Count,$JudgementA1);
            $sql=$sql. taevaluationextraupdateData($id,'TutorialslabsA1',$Count,$TutorialslabsA1);
            $sql=$sql. taevaluationextraupdateData($id,'TutorialslabsA2',$Count,$TutorialslabsA2);
            $sql=$sql. taevaluationextraupdateData($id,'ConstructingA1',$Count,$ConstructingA1);
            $sql=$sql. taevaluationextraupdateData($id,'ConstructingA2',$Count,$ConstructingA2);
            $sql=$sql. taevaluationextraupdateData($id,'GradingA1',$Count,$GradingA1);
            $sql=$sql. taevaluationextraupdateData($id,'GradingA2',$Count,$GradingA2);
            $sql=$sql. taevaluationextraupdateData($id,'GradingA3',$Count,$GradingA3);
            $sql=$sql. taevaluationextraupdateData($id,'TestExamA1',$Count,$TestExamA1);

            echo $sql . '<br>';
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
        else
        {
            $errormsg = 'This Application has existed!';
            //echo $errormsg;
            $_SESSION['message']= $errormsg;
            die($errormsg);
        }
    }
    else{
        //insert
        if($user_role=='Staff' or $user_role=='Admin')
        {
            $insertsql = " 
                      insert into  tbl_taevaluation(StudentID,Instructor,Term,CourseID,
                      OverallScore,Comment,IsAcceptNextTerm,EvaluationDatetime,UpdateTime)
                       values('$PantherID','$instructor','$termid','$CourseID',
                       '$OverallScore','$Comment','$IsAcceptNextTerm','$EvaluationDatetime',NOW())
                     ";
        }
        else if($user_role=='Faculty' or $user_role=='Student')
        {
            $insertsql = " 
                      insert into  tbl_taevaluation(StudentID,Instructor,Term,CourseID,
                      OverallScore,Comment,IsAcceptNextTerm,EvaluationDatetime,UpdateTime)
                       values('$PantherID','$instructor','$termid','$CourseID',
                       '$OverallScore','$Comment','$IsAcceptNextTerm','$EvaluationDatetime',NOW())
                     ";
        }
        else
        {
            $errormsg = 'You have been logout!';
            //echo $errormsg;
            $_SESSION['message']= $errormsg;
            die($errormsg);
        }

        echo $insertsql;
        $insertresult = mysqli_query($db, $insertsql);
        $taevaluationid = mysqli_insert_id($db);
        $Count =1;
        $sql='';
        $sql=$sql. taevaluationextraInsertData($taevaluationid,'ReliabilityA1',$Count,$ReliabilityA1);
        $sql=$sql. taevaluationextraInsertData($taevaluationid,'ReliabilityA2',$Count,$ReliabilityA1);
        $sql=$sql. taevaluationextraInsertData($taevaluationid,'ReliabilityA3',$Count,$ReliabilityA3);
        $sql=$sql. taevaluationextraInsertData($taevaluationid,'ReliabilityA4',$Count,$ReliabilityA4);
        $sql=$sql. taevaluationextraInsertData($taevaluationid,'EngagementA1',$Count,$EngagementA1);
        $sql=$sql. taevaluationextraInsertData($taevaluationid,'ProficiencyA1',$Count,$ProficiencyA1);
        $sql=$sql. taevaluationextraInsertData($taevaluationid,'CommunicationA1',$Count,$CommunicationA1);
        $sql=$sql. taevaluationextraInsertData($taevaluationid,'JudgementA1',$Count,$JudgementA1);
        $sql=$sql. taevaluationextraInsertData($taevaluationid,'TutorialslabsA1',$Count,$TutorialslabsA1);
        $sql=$sql. taevaluationextraInsertData($taevaluationid,'TutorialslabsA2',$Count,$TutorialslabsA2);
        $sql=$sql. taevaluationextraInsertData($taevaluationid,'ConstructingA1',$Count,$ConstructingA1);
        $sql=$sql. taevaluationextraInsertData($taevaluationid,'ConstructingA2',$Count,$ConstructingA2);
        $sql=$sql. taevaluationextraInsertData($taevaluationid,'GradingA1',$Count,$GradingA1);
        $sql=$sql. taevaluationextraInsertData($taevaluationid,'GradingA2',$Count,$GradingA2);
        $sql=$sql. taevaluationextraInsertData($taevaluationid,'GradingA3',$Count,$GradingA3);
        $sql=$sql. taevaluationextraInsertData($taevaluationid,'TestExamA1',$Count,$TestExamA1);

        echo $sql . '<br>';
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

    header("location:TAEvaluationdashboard.php");  //redirect home page
}
else
{
    //echo 'other';
    if(empty($_GET['id'])==false) {
        if (is_numeric($_GET['id'])) {
            //echo 'get id';
            $id = (int)$_GET['id'];
            //echo $id;
            //$sql = "select id,username from user where id = " . $id;
            $sql = "
                    select taev.TaEvaluationID, taev.StudentID,taev.Instructor,taev.Term,taev.CourseID,
                    taev.OverallScore,taev.Comment,taev.IsAcceptNextTerm,
                    taex.Field,taex.Instance,taex.Value
                    from tbl_taevaluation as taev 
                    LEFT JOIN tbl_taevaluationextra as taex on taex.TaEvaluationID=taev.TaEvaluationID
                    where taev.TaEvaluationID ='$id'
                   ";
            //echo $sql;
            $result = mysqli_query($db, $sql);

            if ($result->num_rows > 0) {
                // output data of each row
                while ($row = $result->fetch_assoc()) {
                    //echo "id: " . $row["id"] . "<br>";
                    $id = $row["TaEvaluationID"];

                    $TermID = $row["Term"];
                    // select taev.TaEvaluationID, taev.StudentID,taev.Instructor,taev.Term,taev.CourseID,
                    // taev.OverallScore,taev.Comment,taev.IsAcceptNextTerm,taev.Comment,
                    //taex.Field,taex.Instance,taex.Value
                    $StudentID = $row["StudentID"];
                    $Instructor = $row["Instructor"];
                    $CourseID = $row["CourseID"];
                    $OverallScore = $row["OverallScore"];
                    $Comment = $row["Comment"];
                    $IsAcceptNextTerm = $row["IsAcceptNextTerm"];
                    $Field = $row["Field"];
                    $Instance = $row["Instance"];
                    $Value = $row["Value"];

                    $_SESSION["TATermID"] = $TermID;
                    //echo 'TermID:'.$TermID;
                    $_SESSION["TAStudentID"] = $StudentID;
                    $_SESSION["TAInstructor"] = $Instructor;
                    $_SESSION["TACourseID"] = $CourseID;

                    switch ($Field)
                    {
                        case "ReliabilityA1":
                            $ReliabilityA1=$Value;
                            break;
                        case "ReliabilityA2":
                            $ReliabilityA2=$Value;
                            break;
                        case "ReliabilityA3":
                            $ReliabilityA3=$Value;
                            break;
                        case "ReliabilityA4":
                            $ReliabilityA4=$Value;
                            break;
                        case "EngagementA1":
                            $EngagementA1=$Value;
                            break;
                        case "ProficiencyA1":
                            $ProficiencyA1=$Value;
                            break;
                        case "CommunicationA1":
                            $CommunicationA1=$Value;
                            break;
                        case "JudgementA1":
                            $JudgementA1=$Value;
                            break;
                        case "TutorialslabsA1":
                            $TutorialslabsA1=$Value;
                            break;
                        case "TutorialslabsA2":
                            $TutorialslabsA2=$Value;
                            break;
                        case "ConstructingA1":
                            $ConstructingA1=$Value;
                            break;
                        case "ConstructingA2":
                            $ConstructingA2=$Value;
                            break;
                        case "GradingA1":
                            $GradingA1=$Value;
                            break;
                        case "GradingA2":
                            $GradingA2=$Value;
                            break;
                        case "GradingA3":
                            $GradingA3=$Value;
                            break;
                        case "TestExamA1":
                            $TestExamA1=$Value;
                            break;
                        default:
                            break;
                    }
                    $_SESSION["id"] = $id;

                }
            } else {
                //echo "0 results";
                $PantherID=(int)$user_pantherid;
            }
            $_SESSION["status"] = "update";
            //echo $_SESSION["status"];
        }
    }
    else
    {
        //echo 'create';
        $_SESSION["status"] = "create";
        //echo $_SESSION["status"];
    }

    //echo $_SESSION["status"];
    $termarray = array();
    $sql = "
                select Termid,Term,Startday,Endday
                from tbl_term
                where Termid in(
								select termid 
								from 
								(
									select Termid,Term,Startday,Endday,ABS(DATEDIFF(DATE_ADD(Endday,INTERVAL -30 DAY),Now()) )
									from tbl_term
									order by ABS(DATEDIFF(DATE_ADD(Endday,INTERVAL -30 DAY),Now()))  ASC 
									LIMIT 1
								) as te
							)
                union all 

                select Termid,Term,Startday,Endday
                from tbl_term
                where Termid not in(
										select termid 
										from 
										(
											select Termid,Term,Startday,Endday,ABS(DATEDIFF(DATE_ADD(Endday,INTERVAL -30 DAY),Now()) )
											from tbl_term
											order by ABS(DATEDIFF(DATE_ADD(Endday,INTERVAL -30 DAY),Now()))  ASC 
											LIMIT 1
										) as te
							)


            ";
    //echo $sql . '<br>';
    $result = mysqli_query($db, $sql);

    if ($result->num_rows > 0) {
        $i = 0;
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            $termarray[$i] = array();
            $termarray[$i]['Termid'] = $row["Termid"];
            $termarray[$i]['Term'] = $row["Term"];
            $termarray[$i]['Startday'] = $row["Startday"];
            $termarray[$i]['Endday'] = $row["Endday"];
            $i = $i + 1;
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

    $studentarray = array();
    $sql = "select PantherID,email, 
            CONCAT(coalesce(FirstName,' ') , IF(MiddleName = '', ' ', IFNULL(MiddleName,' ')),coalesce(LastName,' ')) as Name,
            TermID
            from tbl_gaapplication as ga
            order by TermID, FirstName
        ";
//echo $sql . '<br>';
    $result = mysqli_query($db, $sql);

    if ($result->num_rows > 0) {
        $i = 0;
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            $studentarray[$i] = array();
            $studentarray[$i]['PantherID'] = $row["PantherID"];
            $studentarray[$i]['email'] = $row["email"];
            $studentarray[$i]['Name'] = $row["Name"];
            $studentarray[$i]['TermID'] = $row["TermID"];
            $i = $i + 1;
        }
    }

    $coursearray = array();
    $sql = "select co.Courseid as id, 
                te.Term as term,
                te.Termid as termid,
                co.CRN as crn,
                co.Subject as subject,
                co.Course as course,
                co.Title as title
                from tbl_course  as co
                LEFT JOIN tbl_schedule as sc on sc.Courseid = co.Courseid and sc.instance=1
                LEFT JOIN tbl_term as te on te.Termid = sc.Termid
                order by te.Startday desc,co.Course,co.CRN
                                 ";

    $result = mysqli_query($db, $sql);

    if ($result->num_rows > 0) {
        $i = 0;
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            $coursearray[$i] = array();
            $coursearray[$i]['id'] = $row["id"];
            $coursearray[$i]['term'] = $row["term"];
            $coursearray[$i]['termid'] = $row["termid"];
            $coursearray[$i]['crn'] = $row["crn"];
            $coursearray[$i]['subject'] = $row["subject"];
            $coursearray[$i]['course'] = $row["course"];
            $coursearray[$i]['title'] = $row["title"];
            $i = $i + 1;
        }
    }

    $Scorearray = array(
        array("number"=>"","value"=>""),
        array("number"=>"4","value"=>"4"),
        array("number"=>"3","value"=>"3"),
        array("number"=>"2","value"=>"2"),
        array("number"=>"1","value"=>"1")
    );

    if($user_role =="Admin" or $user_role =="Staff" ) {
        $isadmin=True;
        //$isadmin=false;
    }
    else
    {
        $isadmin=false;
    }
    $readonly =false;
    //echo $isadmin;
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

    <style>
        input:-moz-read-only { /* For Firefox */
            background-color:  gray;
        }

        input:read-only {
            background-color: gray;
        }
        tr.padding_left td {
            padding-left:10px;
        }
    </style>
</head>
<!-- /#Header -->
<body onload="init();">
<div id="wrapper">
    <?php
    if(isset($_SESSION['message']))
    {
        echo "<div id='error_msg'>".$_SESSION['message']."</div>";
        unset($_SESSION['message']);
    }
    ?>
    <!-- Navigation -->
    <?php
    if($user_role=='Staff' or $user_role=='Admin')
    {
        include $root.'/UI/staff/staffmenu.php';
    }
    else if($user_role=='Faculty')
    {
        include $root.'/UI/faculty/facultymenu.php';
    }
    else if($user_role=='Student')
    {
        include $root.'/UI/student/StudentMenu.php';
    }

    ?>
    <!-- /#Navigation -->

    <!-- page-wrapper -->
    <div id="page-wrapper">
        <button style="float: right; margin-top:20px;" type="button" class="btn btn-default" onclick="printDiv('printableArea')">
            <span class="glyphicon glyphicon-print"></span> Print
        </button>
        <div class="row" id="printableArea">
            <form method="post" style="table-layout：fixed" action="TAEvaluationregister.php">
                <p id ='headtitle' style="font-size:30px">Teaching Assistant Evaluation Form</p>
                <p id ='ratestandard' style="color: red; font-size: large;">
                    Please rate the TA’s performance:  <br />
                    4 = Excellent - perform higher than expectations <br />
                    3 = Very good - perform at normal expectations <br />
                    2 = Fair - perform below expectations and should be on probation for one semester <br />
                    1 = Poor - did not perform and is to be dismissed<br />
                    You may ONLY give the overall rating. You are welcome to provide more details.<br />
                </p>
                <table style="border-collapse:separate; border-spacing:0px 5px;">
                    <tr>
                        <td>Term</td>
                    </tr>
                    <tr>
                        <td>
                            <select name="termid" id="termid"  <?php if($isadmin ==false){echo "disabled";} ?>>
                                <?php
                                foreach ($termarray as $arr)
                                {
                                    $p_Termid = $arr["Termid"];
                                    $p_Term = $arr["Term"];
                                    echo "<option value='$p_Termid'";
                                    if(empty($id)==false)
                                    {
                                        if ($p_Termid == $TermID) {
                                            echo "selected";
                                        }
                                    }
                                    echo  "    >
                            $p_Term
                            </option>";
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Teaching Assistant</td>
                    </tr>
                    <tr>
                        <td>
                            <select name="TAInfo" id="TAInfo"  <?php if($isadmin ==false){echo "disabled";} ?> >

                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Instructor</td>
                    </tr>
                    <tr>
                        <td>
                            <select name="InstructorInfo" id="InstructorInfo"  <?php if($isadmin ==false){echo "disabled";} ?> >
                                <?php
                                    foreach ($instructorarray as $arr) {
                                        $email = $arr["email"];
                                        $instructorname = $arr["Name"];
                                        echo "<option value='$email'";
                                        if(empty($id)==false)
                                        {
                                            if ($Instructor==$email)
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
                        <td>Course</td>
                    </tr>
                    <tr>
                        <td>
                            <select name="CourseInfo" id="CourseInfo"   <?php if($isadmin ==false){echo "disabled";} ?> >

                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th style="">Overall rating of TA effectiveness:</th>
                    </tr>
                    <tr>
                        <td ALIGN="left" style="word-wrap:break-word;word-break:break-all;"></td>
                    </tr>
                    <tr>
                        <td>
                            <select name="OverallScore" <?php if($readonly ==true){echo "disabled";} ?> >
                                <?php
                                foreach ($Scorearray as $arr)
                                {
                                    $p_number = $arr["number"];
                                    $p_value = $arr["value"];
                                    echo "<option value='$p_number'";
                                    if(empty($id)==false)
                                    {
                                        if ($p_number == $OverallScore) {
                                            echo "selected";
                                        }
                                    }
                                    echo  "    >
                            $p_value
                            </option>";
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th style="">Do you accept this Teaching Assistant next term?</th>
                    </tr>
                    <tr>
                        <td>
                            <input type="radio" name="IsAcceptNextTerm" id="IsAcceptNextTermyes" value="1"  <?php if(empty($id)==false){ if ($IsAcceptNextTerm=='1'){echo  'checked';}elseif (empty($IsAcceptNextTerm)){echo  'checked';}} else {echo  'checked';} ?>  <?php if($readonly ==true){echo "disabled";} ?> ><label id="labelisacceptnexttermyes"> Yes</label>
                            <input type="radio" name="IsAcceptNextTerm" id="IsAcceptNextTermno" value="0" <?php if(empty($id)==false){ if ($IsAcceptNextTerm=='0'){echo  'checked';}} else {} ?>  <?php if($readonly ==true){echo "disabled";} ?> ><label id="labelisacceptnexttermno">No</label>
                        </td>
                    </tr>
                    <tr>
                        <td>Comment:</td>
                    </tr>
                    <tr>
                        <td><textarea rows="3" cols="30" name="Comment" <?php if($readonly ==true){echo "disabled";} ?> ><?php if(empty($id)==false){ echo $Comment;} ?> </textarea></td>
                    </tr>
                    <tr>
                        <td><input type="submit" name="register_btn" class="Register" value="Submit"  <?php if($readonly ==true){echo "disabled";} ?>></td>
                    </tr>
                    <tr>
                        <th>1.Reliability</th>
                    </tr>
                    <tr class="padding_left">
                        <td ALIGN="left" style="word-wrap:break-word;word-break:break-all;">1.1 Regularity of attendance at course planning/coordinating meetings (if such attendance is a job  requirement)</td>
                    </tr>
                    <tr class="padding_left">
                        <td>
                            <select name="ReliabilityA1" <?php if($readonly ==true){echo "disabled";} ?> >
                                <?php
                                foreach ($Scorearray as $arr)
                                {
                                    $p_number = $arr["number"];
                                    $p_value = $arr["value"];
                                    echo "<option value='$p_number'";
                                    if(empty($id)==false)
                                    {
                                        if ($p_number == $ReliabilityA1) {
                                            echo "selected";
                                        }
                                    }
                                    echo  "    >
                            $p_value
                            </option>";
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr class="padding_left">
                        <td ALIGN="left" style="word-wrap:break-word;word-break:break-all;">1.2 Quality of contributions to course planning/coordinating meetings</td>
                    </tr>
                    <tr class="padding_left">
                        <td>
                            <select name="ReliabilityA2" <?php if($readonly ==true){echo "disabled";} ?> >
                                <?php
                                foreach ($Scorearray as $arr)
                                {
                                    $p_number = $arr["number"];
                                    $p_value = $arr["value"];
                                    echo "<option value='$p_number'";
                                    if(empty($id)==false)
                                    {
                                        if ($p_number == $ReliabilityA2) {
                                            echo "selected";
                                        }
                                    }
                                    echo  "    >
                            $p_value
                            </option>";
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr class="padding_left">
                        <td ALIGN="left" style="word-wrap:break-word;word-break:break-all;">1.3 Regularity of attendance at course lectures (if such attendance is a job requirement)</td>
                    </tr>
                    <tr class="padding_left">
                        <td>
                            <select name="ReliabilityA3" <?php if($readonly ==true){echo "disabled";} ?> >
                                <?php
                                foreach ($Scorearray as $arr)
                                {
                                    $p_number = $arr["number"];
                                    $p_value = $arr["value"];
                                    echo "<option value='$p_number'";
                                    if(empty($id)==false)
                                    {
                                        if ($p_number == $ReliabilityA3) {
                                            echo "selected";
                                        }
                                    }
                                    echo  "    >
                            $p_value
                            </option>";
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr class="padding_left">
                        <td ALIGN="left" style="word-wrap:break-word;word-break:break-all;">1.4 Availability to students during office hours</td>
                    </tr>
                    <tr class="padding_left">
                        <td>
                            <select name="ReliabilityA4" <?php if($readonly ==true){echo "disabled";} ?> >
                                <?php
                                foreach ($Scorearray as $arr)
                                {
                                    $p_number = $arr["number"];
                                    $p_value = $arr["value"];
                                    echo "<option value='$p_number'";
                                    if(empty($id)==false)
                                    {
                                        if ($p_number == $ReliabilityA4) {
                                            echo "selected";
                                        }
                                    }
                                    echo  "    >
                            $p_value
                            </option>";
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th style="">2.Engagement:</th>
                    </tr>
                    <tr>
                        <td ALIGN="left" style="word-wrap:break-word;word-break:break-all;">Demonstration of interest in the course and the class material</td>
                    </tr>
                    <tr>
                        <td>
                            <select name="EngagementA1" <?php if($readonly ==true){echo "disabled";} ?> >
                                <?php
                                foreach ($Scorearray as $arr)
                                {
                                    $p_number = $arr["number"];
                                    $p_value = $arr["value"];
                                    echo "<option value='$p_number'";
                                    if(empty($id)==false)
                                    {
                                        if ($p_number == $EngagementA1) {
                                            echo "selected";
                                        }
                                    }
                                    echo  "    >
                            $p_value
                            </option>";
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th style="">3.Proficiency:</th>
                    </tr>
                    <tr>
                        <td ALIGN="left" style="word-wrap:break-word;word-break:break-all;">Understanding of material covered in the course </td>
                    </tr>
                    <tr>
                        <td>
                            <select name="ProficiencyA1" <?php if($readonly ==true){echo "disabled";} ?> >
                                <?php
                                foreach ($Scorearray as $arr)
                                {
                                    $p_number = $arr["number"];
                                    $p_value = $arr["value"];
                                    echo "<option value='$p_number'";
                                    if(empty($id)==false)
                                    {
                                        if ($p_number == $ProficiencyA1) {
                                            echo "selected";
                                        }
                                    }
                                    echo  "    >
                            $p_value
                            </option>";
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th style="">4.Communication:</th>
                    </tr>
                    <tr>
                        <td ALIGN="left" style="word-wrap:break-word;word-break:break-all;">Effective communication with students</td>
                    </tr>
                    <tr>
                        <td>
                            <select name="CommunicationA1" <?php if($readonly ==true){echo "disabled";} ?> >
                                <?php
                                foreach ($Scorearray as $arr)
                                {
                                    $p_number = $arr["number"];
                                    $p_value = $arr["value"];
                                    echo "<option value='$p_number'";
                                    if(empty($id)==false)
                                    {
                                        if ($p_number == $CommunicationA1) {
                                            echo "selected";
                                        }
                                    }
                                    echo  "    >
                            $p_value
                            </option>";
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th style="">5.Judgement:</th>
                    </tr>
                    <tr>
                        <td ALIGN="left" style="word-wrap:break-word;word-break:break-all;">Good judgement in dealings with students</td>
                    </tr>
                    <tr>
                        <td>
                            <select name="JudgementA1" <?php if($readonly ==true){echo "disabled";} ?> >
                                <?php
                                foreach ($Scorearray as $arr)
                                {
                                    $p_number = $arr["number"];
                                    $p_value = $arr["value"];
                                    echo "<option value='$p_number'";
                                    if(empty($id)==false)
                                    {
                                        if ($p_number == $JudgementA1) {
                                            echo "selected";
                                        }
                                    }
                                    echo  "    >
                            $p_value
                            </option>";
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th style="">6.Tutorials/labs:</th>
                    </tr>
                    <tr class="padding_left">
                        <td ALIGN="left" style="word-wrap:break-word;word-break:break-all;">6.1 Effectiveness in tutorials/labs</td>
                    </tr>
                    <tr class="padding_left">
                        <td>
                            <select name="TutorialslabsA1" <?php if($readonly ==true){echo "disabled";} ?> >
                                <?php
                                foreach ($Scorearray as $arr)
                                {
                                    $p_number = $arr["number"];
                                    $p_value = $arr["value"];
                                    echo "<option value='$p_number'";
                                    if(empty($id)==false)
                                    {
                                        if ($p_number == $TutorialslabsA1) {
                                            echo "selected";
                                        }
                                    }
                                    echo  "    >
                            $p_value
                            </option>";
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr class="padding_left">
                        <td ALIGN="left" style="word-wrap:break-word;word-break:break-all;">6.2 Preparation of tutorial/lab material/assignments</td>
                    </tr>
                    <tr class="padding_left">
                        <td>
                            <select name="TutorialslabsA2" <?php if($readonly ==true){echo "disabled";} ?> >
                                <?php
                                foreach ($Scorearray as $arr)
                                {
                                    $p_number = $arr["number"];
                                    $p_value = $arr["value"];
                                    echo "<option value='$p_number'";
                                    if(empty($id)==false)
                                    {
                                        if ($p_number == $TutorialslabsA2) {
                                            echo "selected";
                                        }
                                    }
                                    echo  "    >
                            $p_value
                            </option>";
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th style="">7.Constructing assignments/tests/exams:</th>
                    </tr>
                    <tr class="padding_left">
                        <td ALIGN="left" style="word-wrap:break-word;word-break:break-all;">7.1 Contribution to development of paper/homework assignments </td>
                    </tr>
                    <tr class="padding_left">
                        <td>
                            <select name="ConstructingA1" <?php if($readonly ==true){echo "disabled";} ?> >
                                <?php
                                foreach ($Scorearray as $arr)
                                {
                                    $p_number = $arr["number"];
                                    $p_value = $arr["value"];
                                    echo "<option value='$p_number'";
                                    if(empty($id)==false)
                                    {
                                        if ($p_number == $ConstructingA1) {
                                            echo "selected";
                                        }
                                    }
                                    echo  "    >
                            $p_value
                            </option>";
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr class="padding_left">
                        <td ALIGN="left" style="word-wrap:break-word;word-break:break-all;">7.2 Contribution to preparation of test/exam questions</td>
                    </tr>
                    <tr class="padding_left">
                        <td>
                            <select name="ConstructingA2" <?php if($readonly ==true){echo "disabled";} ?> >
                                <?php
                                foreach ($Scorearray as $arr)
                                {
                                    $p_number = $arr["number"];
                                    $p_value = $arr["value"];
                                    echo "<option value='$p_number'";
                                    if(empty($id)==false)
                                    {
                                        if ($p_number == $ConstructingA2) {
                                            echo "selected";
                                        }
                                    }
                                    echo  "    >
                            $p_value
                            </option>";
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th style="">8.Grading:</th>
                    </tr>
                    <tr class="padding_left">
                        <td ALIGN="left" style="word-wrap:break-word;word-break:break-all;">8.1 Accuracy and timeliness of grading written assignments </td>
                    </tr>
                    <tr class="padding_left">
                        <td>
                            <select name="GradingA1" <?php if($readonly ==true){echo "disabled";} ?> >
                                <?php
                                foreach ($Scorearray as $arr)
                                {
                                    $p_number = $arr["number"];
                                    $p_value = $arr["value"];
                                    echo "<option value='$p_number'";
                                    if(empty($id)==false)
                                    {
                                        if ($p_number == $GradingA1) {
                                            echo "selected";
                                        }
                                    }
                                    echo  "    >
                            $p_value
                            </option>";
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr class="padding_left">
                        <td ALIGN="left" style="word-wrap:break-word;word-break:break-all;">8.2 Accuracy and timeliness of grading tests/exams</td>
                    </tr>
                    <tr class="padding_left">
                        <td>
                            <select name="GradingA2" <?php if($readonly ==true){echo "disabled";} ?> >
                                <?php
                                foreach ($Scorearray as $arr)
                                {
                                    $p_number = $arr["number"];
                                    $p_value = $arr["value"];
                                    echo "<option value='$p_number'";
                                    if(empty($id)==false)
                                    {
                                        if ($p_number == $GradingA2) {
                                            echo "selected";
                                        }
                                    }
                                    echo  "    >
                            $p_value
                            </option>";
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr class="padding_left">
                        <td ALIGN="left" style="word-wrap:break-word;word-break:break-all;">8.3 Quality of feedback/comments on written assignments/tests </td>
                    </tr>
                    <tr class="padding_left">
                        <td>
                            <select name="GradingA3" <?php if($readonly ==true){echo "disabled";} ?> >
                                <?php
                                foreach ($Scorearray as $arr)
                                {
                                    $p_number = $arr["number"];
                                    $p_value = $arr["value"];
                                    echo "<option value='$p_number'";
                                    if(empty($id)==false)
                                    {
                                        if ($p_number == $GradingA3) {
                                            echo "selected";
                                        }
                                    }
                                    echo  "    >
                            $p_value
                            </option>";
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th style="">9.Test/Exam Invigilation:</th>
                    </tr>
                    <tr>
                        <td ALIGN="left" style="word-wrap:break-word;word-break:break-all;">Effectiveness as test/exam invigilator </td>
                    </tr>
                    <tr>
                        <td>
                            <select name="TestExamA1" <?php if($readonly ==true){echo "disabled";} ?> >
                                <?php
                                foreach ($Scorearray as $arr)
                                {
                                    $p_number = $arr["number"];
                                    $p_value = $arr["value"];
                                    echo "<option value='$p_number'";
                                    if(empty($id)==false)
                                    {
                                        if ($p_number == $TestExamA1) {
                                            echo "selected";
                                        }
                                    }
                                    echo  "    >
                            $p_value
                            </option>";
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><input type="submit" name="register_btn" class="Register" value="Submit"  <?php if($readonly ==true){echo "disabled";} ?>></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>
</body>
</html>
<script>
    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();

        document.body.innerHTML = originalContents;
    }
    $("#termid").change(function () {
        //alert('term begin');
        var val = $(this).val();
        //alert(val);
        var m_id = "<?php if(empty($id)==false){echo $id;}else{echo '';} ; ?>";
        //alert(m_id);

        var coursearray= <?php echo json_encode($coursearray); ?>;
        var m_CourseID= "<?php if(empty($CourseID)==false){echo $CourseID;}else{echo '';} ; ?>";
        //alert(m_CourseID);
        var output ='';
        for(var i=0;i<coursearray.length;i++){
            var id = coursearray[i]['id'];
            var term = coursearray[i]['term'];
            var termid = coursearray[i]['termid'];
            var crn = coursearray[i]['crn'];
            var subject = coursearray[i]['subject'];
            var course = coursearray[i]['course'];
            var title = coursearray[i]['title'];
            var Name = subject+course+title+'-'+crn;
            if(termid==val)
            {
                //alert('equal');
                //alert(id);
                output=output+"<option value='"+id+"'";
                //alert(output);
                //alert(isEmpty(m_id));
                if(!(isEmpty(m_id)))
                {
                    //alert(id);
                    //output=output+"  disabled='disabled' ";
                    if (id == m_CourseID)
                    {
                        output=output+" selected ";
                    }
                }
                output=output+" >"+Name+"</option>";
                //alert(output);
            }
        }
        //alert(output);
        $("#CourseInfo").html(output);
        $("#CourseInfo").change();

        var studentarray= <?php echo json_encode($studentarray); ?>;
        var m_StudentID= "<?php if(empty($StudentID)==false){echo $StudentID;}else{echo '';} ; ?>";
        var output ='';
        for(var i=0;i<studentarray.length;i++){
            var PantherID = studentarray[i]['PantherID'];
            var email = studentarray[i]['email'];
            var termid = studentarray[i]['TermID'];
            var Name = studentarray[i]['Name'];

            if(termid==val)
            {
                output=output+"<option value='"+PantherID+"'";
                if(!(isEmpty(m_id)))
                {
                    //output=output+"  disabled='disabled' ";
                    if (PantherID == m_StudentID)
                    {
                        output=output+" selected ";
                    }
                }
                output=output+" >"+Name+"</option>";
            }
        }
        //alert(output);
        $("#TAInfo").html(output);
        $("#TAInfo").change();

    });
    function isEmpty(str) {
        return (!str || 0 === str.length);
    }
    function init()
    {
        //alert('begin');
        $("#termid").change();
    }
    //window.onload =init;
</script>