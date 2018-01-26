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

if (isset($_POST['options']) && count($_POST['options']) <= 3) {
    // OK
} else {
    // Not OK
}

if(isset($_POST['register_btn']))
{
    $TermID=($_POST['termid']);
    $PantherID = ($_POST['PantherID']);
    $FirstName = ($_POST['FirstName']);
    $MiddleName = ($_POST['MiddleName']);
    $LastName =($_POST['LastName']);
    $Email = ($_POST['Email']);
    $Degree = ($_POST['Degree']);
    $Hasassistanship = ($_POST['Hasassistanship']);
    $Position= ($_POST['Position']);
    $course1 = ($_POST['course1']);
    $course2 = ($_POST['course2']);
    $course3 = ($_POST['course3']);
    $course4 = ($_POST['course4']);
    $course5 = ($_POST['course5']);
    $StartTerm = ($_POST['Startterm']);
    $Semesters = ($_POST['semesters']);
    $Advisor =($_POST['advisor']);
    $CurrentInstructor = ($_POST['instructor']);
    $CurrentGPA = ($_POST['currentgpa']);
    $preferencefacultymember1 = ($_POST['preferencefacultymember1']);
    $preferencefacultymember2 = ($_POST['preferencefacultymember2']);
    $preferencefacultymember3 = ($_POST['preferencefacultymember3']);
    $Submitevaluation=($_POST['submitevaluation']);
    $ReceiveAssistantshipFromAnotherDepartment = ($_POST['receiveassistantshipfromanotherdepartment']);
    $NameOfAnotherDepartment =($_POST['nameofanotherdepartment']);
    $HoursOfAnotherDepartment = ($_POST['hoursofanotherdepartment']);

    if($PantherID =='')
    {
        echo 'You should login first.';
        exit();
    }

    $sql = "SELECT se.value as TAApplicationstartdatetime,se2.value as TAApplicationEnddatetime
             FROM tbl_settings as se ,
            tbl_settings as se2
            where se.Name = 'TAApplicationstartdatetime' and se2.Name = 'TAApplicationEnddatetime'
                    ";
    $result = mysqli_query($db, $sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            $TAApplicationstartdatetime = $row["TAApplicationstartdatetime"];
            $TAApplicationEnddatetime = $row["TAApplicationEnddatetime"];
        }
    }
    $now=date('Y-m-d H:i:s');

    //echo '$now:'.$now . '$TAApplicationstartdatetime:'.$TAApplicationstartdatetime .'$TAApplicationEnddatetime:'.$TAApplicationEnddatetime;
    if(($now < $TAApplicationstartdatetime )  )
    {
        echo 'Your Application is coming soon .';
        exit();
    }
    if(($now > $TAApplicationEnddatetime ) )
    {
        echo 'Your Application has passed the deadline .';
        exit();
    }
    //echo $course1;
    $Courses = '';
    //echo '$course1:' .$course1.$_POST['course1'];
    if(empty($course1)==false)
    {
        $Courses=$Courses.$course1 .',';
    }
    if(empty($course2)==false)
    {
        $Courses=$Courses.$course2 .',';
    }
    if(empty($course3)==false)
    {
        $Courses=$Courses.$course3 .',';
    }
    if(empty($course4)==false)
    {
        $Courses=$Courses.$course4 .',';
    }
    if(empty($course5)==false)
    {
        $Courses=$Courses.$course5 .',';
    }
    if(substr($Courses,strlen($Courses)-1,1)==',')
    {
        $Courses=substr($Courses,0,strlen($Courses)-1);
    }
    $Preferencefacultymembers = '';
    // echo '$preferencefacultymember1:' .$preferencefacultymember1;
    //echo '$Preferencefacultymembers 1:'.$Preferencefacultymembers;
    {
        $Preferencefacultymembers=$Preferencefacultymembers .$preferencefacultymember1 .',';
    }
    //echo '$Preferencefacultymembers 2:'.$Preferencefacultymembers;
    if(empty($preferencefacultymember2)==false)
        if(empty($preferencefacultymember1)==false)
        {
            $Preferencefacultymembers=$Preferencefacultymembers .$preferencefacultymember2 .',';
        }
    if(empty($preferencefacultymember3)==false)
    {
        $Preferencefacultymembers=$Preferencefacultymembers .$preferencefacultymember3 .',';
    }
    //echo '$Preferencefacultymembers end:'.$Preferencefacultymembers;
    //echo 'substr:'.substr($Preferencefacultymembers,strlen($Preferencefacultymembers)-1,1);
    if(substr($Preferencefacultymembers,strlen($Preferencefacultymembers)-1,1)==',')
    {
        $Preferencefacultymembers=substr($Preferencefacultymembers,0,strlen($Preferencefacultymembers)-1);
    }
    //echo '$Preferencefacultymembers finally:'.$Preferencefacultymembers;
    $sql = "
            select ga.GAApplicationID as GAApplicationID,ga.TermID as TermID,te.Term as Term,ga.PantherID as PantherID,
                        ga.FirstName,ga.MiddleName,ga.LastName,ga.Degree,
                        ga.Email as Email,ga.Hasassistanship as Hasassistanship,ga.Position as Position,
                        ga.Courses as Courses,ga.StartTerm as StartTerm, ga.Semesters as Semesters,
                        ga.Advisor as Advisor,ga.CurrentInstructor as CurrentInstructor,ga.CurrentGPA as CurrentGPA,
                        ga.Preferencefacultymembers as Preferencefacultymembers,
                        ga.Submitevaluation as Submitevaluation,
                        ga.ReceiveAssistantshipFromAnotherDepartment as ReceiveAssistantshipFromAnotherDepartment,
                        ga.NameOfAnotherDepartment as NameOfAnotherDepartment,
                        ga.HoursOfAnotherDepartment as HoursOfAnotherDepartment,
                        ga.Status as Status
                        from tbl_gaapplication as ga 
                        left JOIN  tbl_term as te on te.Termid = ga.TermID 
                        where ga.TermID=
             " . $TermID .' and PantherID =' .$PantherID;
    //echo $sql;
    $result = mysqli_query($db, $sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            //echo "id: " . $row["id"] . "<br>";
            $id = $row["GAApplicationID"];
            //update
            $Status = $row['Status'];
        }
        if( $_SESSION["status"]=='update') {
            $updatesql = "update tbl_gaapplication 
                           set FirstName ='$FirstName',MiddleName ='$MiddleName',LastName ='$LastName',
                           Degree ='$Degree',Email ='$Email',Hasassistanship='$Hasassistanship',
                           Position='$Position',Courses ='$Courses',StartTerm ='$StartTerm',
                           Semesters ='$Semesters',Advisor ='$Advisor',CurrentInstructor ='$CurrentInstructor',
                           CurrentGPA ='$CurrentGPA',Preferencefacultymembers ='$Preferencefacultymembers',
                           Submitevaluation = '$Submitevaluation',
                           ReceiveAssistantshipFromAnotherDepartment ='$ReceiveAssistantshipFromAnotherDepartment',
                           NameOfAnotherDepartment ='$NameOfAnotherDepartment',HoursOfAnotherDepartment ='$HoursOfAnotherDepartment'
                           where GAApplicationID = " . $id;
            //echo $updatesql;
            $updateresult = mysqli_query($db, $updatesql);
        }
        else
        {
            $errormsg = 'This Application has existed!';
            //echo $errormsg;
            $_SESSION['message']= $errormsg;
            die($errormsg);

           //header('Location: '.$_SERVER['PHP_SELF']);
           // exit();
           // echo "<mce:script language=javascript><! alert($errormsg);history.back(); //  ></mce:script>";
           // exit($errormsg);
        }
    }
    else{
        //insert
        $Status = 'Pending';
        $insertsql = "INSERT INTO tbl_gaapplication(TermID,PantherID,FirstName,MiddleName,LastName,Degree,Email,
                                    Hasassistanship,Position,
                                    Courses,StartTerm,Semesters,Advisor,CurrentInstructor,CurrentGPA,
                                    Preferencefacultymembers,Submitevaluation,ReceiveAssistantshipFromAnotherDepartment,
                                    NameOfAnotherDepartment,HoursOfAnotherDepartment,Status) 
                    VALUES ('$TermID','$PantherID','$FirstName', '$MiddleName', '$LastName', '$Degree', '$Email',
                    '$Hasassistanship','$Position',
                    '$Courses', '$StartTerm', '$Semesters', '$Advisor', '$CurrentInstructor', '$CurrentGPA', 
                    '$Preferencefacultymembers','$Submitevaluation', '$ReceiveAssistantshipFromAnotherDepartment', 
                    '$NameOfAnotherDepartment', '$HoursOfAnotherDepartment', '$Status')";
        //echo $insertsql;
        $insertresult = mysqli_query($db, $insertsql);
    }

    //mysqli_query($db,$sql);
    //$_SESSION['message']="You are now logged in";
    header("location:GTAApplicationdashboard.php");  //redirect home page
}
else
{
    if(empty($_GET['id'])==false) {
        if (is_numeric($_GET['id'])) {

            $id = (int)$_GET['id'];
            //echo $id;
            //$sql = "select id,username from user where id = " . $id;
            $sql = "
                        select ga.GAApplicationID as GAApplicationID,ga.TermID as TermID,te.Term as Term,ga.PantherID as PantherID,
                                    ga.FirstName,ga.MiddleName,ga.LastName,ga.Degree,
                                    ga.Email as Email,ga.Hasassistanship as Hasassistanship,ga.Position as Position,
                                    ga.Courses as Courses,ga.StartTerm as StartTerm, ga.Semesters as Semesters,
                                    ga.Advisor as Advisor,ga.CurrentInstructor as CurrentInstructor,ga.CurrentGPA as CurrentGPA,
                                    ga.Preferencefacultymembers as Preferencefacultymembers,
                                    ga.Submitevaluation as Submitevaluation,
                                    ga.ReceiveAssistantshipFromAnotherDepartment as ReceiveAssistantshipFromAnotherDepartment,
                                    ga.NameOfAnotherDepartment as NameOfAnotherDepartment,
                                    ga.HoursOfAnotherDepartment as HoursOfAnotherDepartment,
                                    ga.Status as Status
                                    from tbl_gaapplication as ga 
                                    left JOIN  tbl_term as te on te.Termid = ga.TermID
                                    where ga.GAApplicationID=
                         " . $id;
            //echo $sql;
            $result = mysqli_query($db, $sql);

            if ($result->num_rows > 0) {
                // output data of each row
                while ($row = $result->fetch_assoc()) {
                    //echo "id: " . $row["id"] . "<br>";
                    $id = $row["GAApplicationID"];
                    $TermID = $row["TermID"];
                    $Term = $row["Term"];
                    $PantherID = $row["PantherID"];
                    $FirstName = $row["FirstName"];
                    $MiddleName = $row["MiddleName"];
                    $LastName = $row["LastName"];
                    $Email = $row["Email"];
                    $Degree = $row["Degree"];
                    $Hasassistanship = $row["Hasassistanship"];
                    $Position = $row["Position"];
                    $Courses = $row["Courses"];
                    $StartTerm = $row["StartTerm"];
                    $Semesters = $row["Semesters"];
                    $Advisor = $row["Advisor"];
                    $CurrentInstructor = $row["CurrentInstructor"];
                    $CurrentGPA = $row["CurrentGPA"];
                    $Preferencefacultymembers = $row["Preferencefacultymembers"];
                    $Submitevaluation=$row["Submitevaluation"];
                    $ReceiveAssistantshipFromAnotherDepartment = $row["ReceiveAssistantshipFromAnotherDepartment"];
                    $NameOfAnotherDepartment = $row["NameOfAnotherDepartment"];
                    $HoursOfAnotherDepartment = $row["HoursOfAnotherDepartment"];
                    $Status= $row["Status"];

                    $Course = explode(",", $Courses);
                    $CourseCount = count($Course);
                    $CourseNumber = 1;
                    $course1='';
                    $course2='';
                    $course3='';
                    $course4='';
                    $course5='';
                    if($CourseNumber<=$CourseCount)
                    {
                        $course1 = $Course[$CourseNumber-1];
                        $CourseNumber =$CourseNumber+1;
                    }
                    if($CourseNumber<=$CourseCount)
                    {
                        $course2 = $Course[$CourseNumber-1];
                        $CourseNumber =$CourseNumber+1;
                    }
                    if($CourseNumber<=$CourseCount)
                    {
                        $course3 = $Course[$CourseNumber-1];
                        $CourseNumber =$CourseNumber+1;
                    }
                    if($CourseNumber<=$CourseCount)
                    {
                        $course4 = $Course[$CourseNumber-1];
                        $CourseNumber =$CourseNumber+1;
                    }
                    if($CourseNumber<=$CourseCount)
                    {
                        $course5 = $Course[$CourseNumber-1];
                        $CourseNumber =$CourseNumber+1;
                    }
                    $preferencefacultymember1='';
                    $preferencefacultymember2='';
                    $preferencefacultymember3='';
                    //echo $Preferencefacultymembers;
                    $Preferencefacultymember = explode(",", $Preferencefacultymembers);
                    $PreferencefacultymemberCount = count($Preferencefacultymember);
                    $PreferencefacultymemberNumber = 1;
                    if($PreferencefacultymemberNumber<=$PreferencefacultymemberCount)
                    {
                        $preferencefacultymember1 = $Preferencefacultymember[$PreferencefacultymemberNumber-1];
                        $PreferencefacultymemberNumber =$PreferencefacultymemberNumber+1;
                    }
                    if($PreferencefacultymemberNumber<=$PreferencefacultymemberCount)
                    {
                        $preferencefacultymember2 = $Preferencefacultymember[$PreferencefacultymemberNumber-1];
                        $PreferencefacultymemberNumber =$PreferencefacultymemberNumber+1;
                    }
                    if($PreferencefacultymemberNumber<=$PreferencefacultymemberCount)
                    {
                        $preferencefacultymember3 = $Preferencefacultymember[$PreferencefacultymemberNumber-1];
                        $PreferencefacultymemberNumber =$PreferencefacultymemberNumber+1;
                    }
                    //echo $preferencefacultymember1;
                    //echo $preferencefacultymember2;
                    //echo     $preferencefacultymember3;
                    $_SESSION["id"] = $id;
                    //echo $TakePartinTime;

                }
            } else {
                //echo "0 results";
                $PantherID=(int)$user_pantherid;
                $email=$user_email;
            }
            $_SESSION["status"] = "update";
            //echo $_SESSION["status"];
        }
    }
    else
    {
        $_SESSION["status"] = "create";
        //echo $_SESSION["status"];
        $PantherID=(int)$user_pantherid;
        $Email=$user_email;
    }

    //echo $_SESSION["status"];
    $termarray = array();
    $sql = "select Termid,Term,Startday,Endday
            from tbl_term";
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
    $coursearray = array();
    $sql = "SELECT SchoolCourseID,Subject,Course,CourseName,Credit,Prerequisites,Description 
                                  FROM tbl_schoolcourse ";
    $result = mysqli_query($db,$sql);

    if ($result->num_rows > 0) {
        $i = 0;
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            $coursearray[$i] = array();
            $coursearray[$i]['SchoolCourseID'] = $row["SchoolCourseID"];
            $coursearray[$i]['Subject'] = $row["Subject"];
            $coursearray[$i]['Course'] = $row["Course"];
            $coursearray[$i]['CourseName'] = $row["CourseName"];
            $coursearray[$i]['Credit'] = $row["Credit"];
            $coursearray[$i]['Prerequisites'] = $row["Prerequisites"];
            $coursearray[$i]['Description'] = $row["Description"];
            $i = $i + 1;
        }
    }

    $facultyarray = array();
    $sql = "select PantherID,email, CONCAT(coalesce(FirstName,' ') , IF(MiddleName = '', ' ', IFNULL(MiddleName,' ')),coalesce(LastName,' ')) as Name
              from tbl_faculty_info ";
    //echo $sql . '<br>';
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

    $readonly =false;
    $isadmin=false;
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
    </style>
</head>
<!-- /#Header -->
<body>
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
    include $root.'/UI/student/StudentMenu.php';
    ?>
    <!-- /#Navigation -->

    <!-- page-wrapper -->
    <div id="page-wrapper">
        <button style="float: right; margin-top:20px;" type="button" class="btn btn-default" onclick="printDiv('printableArea')">
            <span class="glyphicon glyphicon-print"></span> Print
        </button>
        <div class="row" id="printableArea">
            <form method="post" style="table-layout：fixed" action="transfercreditregister.php">
            <p id ='headtitle' style="font-size:30px">REQUEST FOR TRANSFER OF GRADUATE COURSES</p>
                <tr>
                    <td>Which term do you need to transfer credit?</td>
                </tr>
            </br>
                <tr>
                    <td>
                        <select name="termid" <?php if($readonly ==true){echo "disabled";} ?> >
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
                </br>
                <tr>
                    <td>PantherID:</td>
                </tr>
                <tr>
                    <td><input type="text" name="PantherID" class="textInput"   value="<?php if(empty($PantherID)==false){ echo $PantherID;} ?>" <?php if($isadmin == false){echo "readonly='readonly'";} ?>  ></td>
                </tr>
                </br>
                <tr>
                    <td>FirstName:</td>
                </tr>
                <tr>
                    <td><input type="text" name="FirstName" class="textInput"   value="<?php if(empty($FirstName)==false){ echo $FirstName;} ?>"  <?php if($readonly ==true){echo "readonly='readonly'";} ?>   placeholder="ex.Jody"></td>
                </tr>
                </br>
                <tr>
                    <td>MiddleName:</td>
                </tr>
                <tr>
                    <td><input type="text" name="MiddleName" class="textInput"   value="<?php if(empty($id)==false){ echo $MiddleName;} ?>"  <?php if($readonly ==true){echo "readonly='readonly'";} ?>  placeholder="ex.B" ></td>
                </tr>
                </br>
                <tr>
                    <td>LastName:</td>
                </tr>
                <tr>
                    <td><input type="text" name="LastName" class="textInput"   value="<?php if(empty($id)==false){ echo $LastName;} ?>"  <?php if($readonly ==true){echo "readonly='readonly'";} ?>  placeholder="ex.Green"></td>
                </tr>
                </br>
                <tr>
                    <td>Email:</td>
                </tr>
                <tr>
                    <td><input type="text" name="Email" class="textInput"   value="<?php if(empty($Email)==false){ echo $Email;} ?>"  <?php if($isadmin ==false){echo "readonly='readonly'";} ?>  ></td>
                </tr>
                </br>
                <tr>
                    <td>Degree:</td>
                </tr>
                <tr>
                    <td>
                        <select name="Degree" id="Degree">
                            <option value='MS'<?php if(empty($id)==false){ if ('MS' == $Degree) {echo "selected";}} ?>  <?php if($readonly ==true){echo "disabled";} ?> >MS</option>
                            <option value='PHD'<?php if(empty($id)==false){ if ('PHD' == $Degree) {echo "selected";}} ?>  <?php if($readonly ==true){echo "disabled";} ?> >PHD</option>
                        </select>
                    </td>
                </tr>
                </br>
               <tr>
                   <td>Major:</td>
               </tr>
               <tr>
                   <td>
                       <select name="Major" id="Major">
                           <option value='MS'<?php if(empty($id)==false){ if ('MS' == $Degree) {echo "selected";}} ?>  <?php if($readonly ==true){echo "disabled";} ?> >MS</option>
                           <option value='PHD'<?php if(empty($id)==false){ if ('PHD' == $Degree) {echo "selected";}} ?>  <?php if($readonly ==true){echo "disabled";} ?> >PHD</option>
                       </select>
                   </td>
               </tr>
                </br>
                <tr>
                    <td ALIGN="left" style="word-wrap:break-word;word-break:break-all;">Instructions
                        Please attach a copy of the course syllabus for each course you list below. If a course syllabus is unavailable, you must obtain satisfactory
                        documentation of the course content from the institution where the course was taken. A complete official transcript must be on file in order
                        for transfer credit to be awarded. Transfer credit is subject to approval by the dean of the College of Arts and Sciences.
                        The transfer of graduate courses request form should be submitted to the department for initial evaluation and then forwarded to the
                        Graduate Services Office for review. Once reviewed, the appropriate area associate dean’s signature is obtained and this form is returned
                        to Graduate Services at the above address.
                        Master’s candidates: A maximum of 6 semester hours of transfer credit may be applied to your degree program. A request for more than six
                        hours must be accompanied by a Petition for Deviation from Graduate Bulletin Regulations, which is available from the Office of Graduate
                        Studies.
                        Doctoral candidates: A maximum of 30 semester hours of transfer credit may be applied to your degree program.
                        Note: Course work transferred from other institutions is subject to the seven-year time limit on requirements for the master’s degree and the
                        ten-year time limit on requirements for the doctorate. Please contact the Graduate Office for further information.
                        STATEMENT OF STUDENT
                        I request that the following graduate course(s) be applied to my degree program at Georgia State University:
                    </td>
                </tr>
                </br>
                <tr>
                    <td ALIGN="left" style="word-wrap:break-word;word-break:break-all;">Please list your top five course to transfer credit:</td>
                </tr>
                </br>
               <table frame="border">
                </br>
               <tr>
                   <td>
                       Term
                   </td>
                   <td>
                       Year
                   </td>
                   <td>
                       Has Credit(Sem)
                   </td>
                   <td>
                       Has Credit(Qtr)
                   </td>
                   <td>
                       Grade
                   </td>
                   <td>
                       Course Prefix & Number
                   </td>
                   <td>
                       Course Title
                   </td>
                   <td>
                       Equivalent Course Prefix & Number
                   </td>
                   <td>
                       Upload File Name
                   </td>
                   <td>
                        Status
                   </td>

               </tr>
                <tr>
                    <td>
                        <select name="Term" id="Term">
                            <option value='Term'<?php if(empty($id)==false){ if ('Spring' == $Degree) {echo "selected";}} ?>  <?php if($readonly ==true){echo "disabled";} ?> >Spring</option>
                            <option value='Term'<?php if(empty($id)==false){ if ('Summer' == $Degree) {echo "selected";}} ?>  <?php if($readonly ==true){echo "disabled";} ?> >Summer</option>
                            <option value='Term'<?php if(empty($id)==false){ if ('Fall' == $Degree) {echo "selected";}} ?>  <?php if($readonly ==true){echo "disabled";} ?> >Fall</option>
                        </select>
                    </td>
                    <td>
                        <input type="number" name="year1" step="1" min="<?php echo (date("Y")-10);?>" max="<?php echo date("Y"); ?>"  class="textInput" value="<?php if(empty($id)==false){ echo $starttime1;} else { echo date("Y");} ?>" >
                    </td>
                    <td>
                        <input type="number" name="creditem1" step="0.5" min="0"  max="10"  class="textInput" value="<?php if(empty($id)==false){ echo $starttime1;} else { echo 0;} ?>" >
                    </td>
                    <td>
                        <input type="number" name="crediqtr1" step="0.5" min="0" max="10"  class="textInput" value="<?php if(empty($id)==false){ echo $starttime1;} else { echo 0;} ?>" >
                    </td>
                    <td>
                        <input type="text" name="coursegrade"  class="textInput" value="<?php if(empty($id)==false){ echo $starttime1;} else { echo '';} ?>" >
                    </td>
                   <td>
                       <input type="number" name="currentgpa" step="0.5" min="0.0" max="10" class="textInput"   value="<?php if(empty($id)==false){ echo $CurrentGPA;} else {echo '0.5';} ?>"  <?php if($readonly ==true){echo "readonly='readonly'";} ?>  >
                   </td>
                    <td>
                        <input type="text" name="coursetitle"  class="textInput" value="<?php if(empty($id)==false){ echo $starttime1;} else { echo '';} ?>" >
                    </td>
                    <td>
                        <select name="course1" <?php if($readonly ==true){echo "disabled";} ?> >
                            <?php
                            $p_Course = explode( ',', $Courses );
                            echo "<option value=''>NULL</option>";
                            foreach ($coursearray as $arr) {
                                $SchoolCourseID = $arr["SchoolCourseID"];
                                $Subject = $arr["Subject"];
                                $Course = $arr["Course"];
                                $CourseName = $arr["CourseName"];
                                $SubCourse = $Subject . $Course;
                                echo "<option value='$SubCourse'";
                                if(empty($id)==false)
                                {
                                    if ($SubCourse == $course1) {
                                        echo "selected";
                                    }
                                }
                                echo  "    >
                                $SubCourse.$CourseName
                                </option>";
                            }
                            ?>
                        </select>
                    </td>
               </br>
                </tr>
               </table>
                <tr>
                    <td>Status:</td>
                </tr>
                <tr>
                    <td ALIGN="left">
                        <select name="status" <?php if($isadmin ==false){echo "disabled";} ?> >
                            <option value='Waitinglist' <?php if(empty($id)==false){ if ('Waitinglist'==$Status){echo 'selected';}} ?> >Waitinglist</option>
                            <option value='Approve' <?php if(empty($id)==false){ if ('Approve'==$Status){echo 'selected';}} ?>  >Approve</option>
                            <option value='Reject'  <?php if(empty($id)==false){ if ('Reject'==$Status){echo 'selected';}} ?> >Reject</option>
                            <option value='Pending' <?php if(empty($id)==false){ if ('Pending'==$Status){echo 'selected';}}else {echo 'selected';} ?> >Pending</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><input type="submit" name="register_btn" class="Register" value="Submit"  <?php if($readonly ==true){echo "disabled";} ?>></td>
                </tr>

        </form>
        </div>
    </div>
</div>
</body>
</html>
<script>

</script>