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
    echo 'begin';
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

    $takecourse1 = ($_POST['takecourse1']);
    $takecourse2 = ($_POST['takecourse2']);
    $takecourse3 = ($_POST['takecourse3']);

    $tacourse1 = ($_POST['tacourse1']);
    $tacourse2 = ($_POST['tacourse2']);
    $tacourse3 = ($_POST['tacourse3']);
    $tacourse4 = ($_POST['tacourse4']);
    $tacourse5 = ($_POST['tacourse5']);

    $Supportothercourse = ($_POST['supportothercourse']);
    //echo '$Supportothercourse'.$Supportothercourse;
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
    $IsNewStudent =  ($_POST['IsNewStudent']);
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
    $temp_Course = '';
    //echo '$course1:' .$course1.$_POST['course1'];
    if(empty($course1)==false)
    {
        $temp_Course=$temp_Course.$course1 .',';
    }
    if(empty($course2)==false)
    {
        $temp_Course=$temp_Course.$course2 .',';
    }
    if(empty($course3)==false)
    {
        $temp_Course=$temp_Course.$course3 .',';
    }
    if(empty($course4)==false)
    {
        $temp_Course=$temp_Course.$course4 .',';
    }
    if(empty($course5)==false)
    {
        $temp_Course=$temp_Course.$course5 .',';
    }
    if(substr($temp_Course,strlen($temp_Course)-1,1)==',')
    {
        $temp_Course=substr($temp_Course,0,strlen($temp_Course)-1);
    }
    $Courses=$temp_Course;

    $TakeCourses = '';
    $temp_Course = '';
    //echo '$course1:' .$course1.$_POST['course1'];
    if(empty($takecourse1)==false)
    {
        $temp_Course=$temp_Course.$takecourse1 .',';
    }
    if(empty($takecourse2)==false)
    {
        $temp_Course=$temp_Course.$takecourse2 .',';
    }
    if(empty($takecourse3)==false)
    {
        $temp_Course=$temp_Course.$takecourse3 .',';
    }
    if(substr($temp_Course,strlen($temp_Course)-1,1)==',')
    {
        $temp_Course=substr($temp_Course,0,strlen($temp_Course)-1);
    }
    $TakeCourses=$temp_Course;

    $TACourses = '';
    $temp_Course = '';
    //echo '$course1:' .$course1.$_POST['course1'];
    if(empty($tacourse1)==false)
    {
        $temp_Course=$temp_Course.$tacourse1 .',';
    }
    if(empty($tacourse2)==false)
    {
        $temp_Course=$temp_Course.$tacourse2 .',';
    }
    if(empty($tacourse3)==false)
    {
        $temp_Course=$temp_Course.$tacourse3 .',';
    }
    if(empty($tacourse4)==false)
    {
        $temp_Course=$temp_Course.$tacourse4 .',';
    }
    if(empty($tacourse5)==false)
    {
        $temp_Course=$temp_Course.$tacourse5 .',';
    }
    if(substr($temp_Course,strlen($temp_Course)-1,1)==',')
    {
        $temp_Course=substr($temp_Course,0,strlen($temp_Course)-1);
    }
    $TACourses=$temp_Course;
    //echo '$TACourses:'.$TACourses;
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

    if($IsNewStudent=='1')
    {
        $CurrentGPA='0';
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
                        ga.Status as Status,
                        ga.Takecourses as Takecourses,
                        ga.Tacourses as Tacourses,
                        ga.Supportothercourse as Supportothercourse,
                        ga.IsNewStudent as IsNewStudent
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
                           NameOfAnotherDepartment ='$NameOfAnotherDepartment',HoursOfAnotherDepartment ='$HoursOfAnotherDepartment',
                           Takecourses ='$TakeCourses',Tacourses ='$TACourses',Supportothercourse = '$Supportothercourse',
                           IsNewStudent='$IsNewStudent'
                           where GAApplicationID = " . $id;
            echo $updatesql;
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
                                    NameOfAnotherDepartment,HoursOfAnotherDepartment,Status, Takecourses,
                                    Tacourses,Supportothercourse,IsNewStudent) 
                    VALUES ('$TermID','$PantherID','$FirstName', '$MiddleName', '$LastName', '$Degree', '$Email',
                    '$Hasassistanship','$Position',
                    '$Courses', '$StartTerm', '$Semesters', '$Advisor', '$CurrentInstructor', '$CurrentGPA', 
                    '$Preferencefacultymembers','$Submitevaluation', '$ReceiveAssistantshipFromAnotherDepartment', 
                    '$NameOfAnotherDepartment', '$HoursOfAnotherDepartment', '$Status','$TakeCourses',
                    '$TACourses','$Supportothercourse','$IsNewStudent'
                    )";
        echo $insertsql;
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
                                    ga.Status as Status,
                                    ga.Takecourses as Takecourses,
                                    ga.Tacourses as Tacourses,
                                    ga.Supportothercourse as Supportothercourse,
                                    ga.IsNewStudent as IsNewStudent
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
                    $Takecourses= $row["Takecourses"];
                    $Tacourses= $row["Tacourses"];
                    $Supportothercourse= $row["Supportothercourse"];
                    $IsNewStudent= $row["IsNewStudent"];

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

                    $Course = explode(",", $Takecourses);
                    $CourseCount = count($Course);
                    $CourseNumber = 1;
                    $takecourse1='';
                    $takecourse2='';
                    $takecourse3='';
                    if($CourseNumber<=$CourseCount)
                    {
                        $takecourse1 = $Course[$CourseNumber-1];
                        $CourseNumber =$CourseNumber+1;
                    }
                    if($CourseNumber<=$CourseCount)
                    {
                        $takecourse2 = $Course[$CourseNumber-1];
                        $CourseNumber =$CourseNumber+1;
                    }
                    if($CourseNumber<=$CourseCount)
                    {
                        $takecourse3 = $Course[$CourseNumber-1];
                        $CourseNumber =$CourseNumber+1;
                    }

                    //echo '$TAcourses'.$Tacourses;
                    $Course = explode(",", $Tacourses);
                    $CourseCount = count($Course);
                    $CourseNumber = 1;
                    $tacourse1='';
                    $tacourse2='';
                    $tacourse3='';
                    $tacourse4='';
                    $tacourse5='';
                    if($CourseNumber<=$CourseCount)
                    {
                        $tacourse1 = $Course[$CourseNumber-1];
                        $CourseNumber =$CourseNumber+1;
                    }
                    if($CourseNumber<=$CourseCount)
                    {
                        $tacourse2 = $Course[$CourseNumber-1];
                        $CourseNumber =$CourseNumber+1;
                    }
                    if($CourseNumber<=$CourseCount)
                    {
                        $tacourse3 = $Course[$CourseNumber-1];
                        $CourseNumber =$CourseNumber+1;
                    }
                    if($CourseNumber<=$CourseCount)
                    {
                        $tacourse4 = $Course[$CourseNumber-1];
                        $CourseNumber =$CourseNumber+1;
                    }
                    if($CourseNumber<=$CourseCount)
                    {
                        $tacourse5 = $Course[$CourseNumber-1];
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

                    $sqlFoundation = " SELECT st.PantherId,st.FirstName as FirstName,st.MiddleName as MiddleName,st.LastName as LastName,
                            st.EMail as EMail,st.Term as Term,st.Program,fo.StudentId, fo.DS, fo.SE, fo.AA, 
                            fo.OS, fo.PL, fo.CA, fo.Automata, fo.Calculus, fo.DM, fo.Date
                            FROM tbl_foundation_courses as fo
                            inner join tbl_student_evaluation as ev on ev.StudentId = fo.StudentId
                            inner join tbl_excel_info as st on st.PantherId = fo.StudentId
                            where ev.Status='Complete' and ev.AdmissionDecision='Admit'
                                 and fo.StudentId=" . $PantherID;
                    //echo $sqlFoundation;
                    $resultFoundation = mysqli_query($db, $sqlFoundation);

                    if ($resultFoundation->num_rows > 0) {
                        // output data of each row
                        while ($rowFoundation = $resultFoundation->fetch_assoc()) {
                            $PantherID = $rowFoundation["PantherId"];
                            //echo '$PantherID'.$PantherID;
                            $DataStructure = $rowFoundation["DS"];
                            $SoftwareEngineering = $rowFoundation["SE"];
                            $Algorithms = $rowFoundation["AA"];
                            $OperatingSystems = $rowFoundation["OS"];
                            $ProgrammingLanguageConcepts = $rowFoundation["PL"];
                            $ComputerArchitecture = $rowFoundation["CA"];
                            $Automata = $rowFoundation["Automata"];
                            $Calculus = $rowFoundation["Calculus"];
                            $DiscreteMathematics = $rowFoundation["DM"];
                        }
                    } else {
                        //echo "0 results";
                    }

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
    $sql = "
                select Termid,Term,Startday,Endday
                from tbl_term
                where Termid in(
								select termid 
								from 
								(
								select Termid,Term,Startday,Endday,DATEDIFF(DATE_ADD(Startday,INTERVAL 30 DAY),Now()) 
								from tbl_term
								where DATEDIFF(DATE_ADD(Startday,INTERVAL 30 DAY),Now()) >0 
								order by DATEDIFF(DATE_ADD(Startday,INTERVAL 30 DAY),Now())  ASC 
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
								select Termid,Term,Startday,Endday,DATEDIFF(DATE_ADD(Startday,INTERVAL 30 DAY),Now()) 
								from tbl_term
								where DATEDIFF(DATE_ADD(Startday,INTERVAL 30 DAY),Now()) >0 
								order by DATEDIFF(DATE_ADD(Startday,INTERVAL 30 DAY),Now())  ASC 
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

    $readonly =true;
    $isadmin=true;
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
            <form method="post" style="table-layout：fixed" action="GTAApplicationregister.php">
                <p id ='headtitle' style="font-size:30px">GTA Teaching Preference Form</p>
                <table>
                    <tr>
                        <td>Are you a new graduate student?</td>
                    </tr>
                    <tr>
                        <td>
                            <select name="IsNewStudent" id="IsNewStudent">
                                <option value='1'<?php if(empty($id)==false){ if ('1' == $IsNewStudent) {echo "selected";}} ?>  <?php if($readonly ==true){echo "disabled";} ?> >Yes</option>
                                <option value='0'<?php if(empty($id)==false){ if ('0' == $IsNewStudent) {echo "selected";}} ?>  <?php if($readonly ==true){echo "disabled";} ?> >No</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>For which term do you request funding?</td>
                    </tr>
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
                    <tr>
                        <td>PantherID:</td>
                    </tr>
                    <tr>
                        <td><input type="text" name="PantherID" class="textInput"   value="<?php if(empty($PantherID)==false){ echo $PantherID;} ?>" <?php if($isadmin == false){echo "readonly='readonly'";} ?>  ></td>
                    </tr>
                    <tr>
                        <td>FirstName:</td>
                    </tr>
                    <tr>
                        <td><input type="text" name="FirstName" class="textInput"   value="<?php if(empty($FirstName)==false){ echo $FirstName;} ?>"  <?php if($readonly ==true){echo "readonly='readonly'";} ?>   placeholder="ex.Jody"></td>
                    </tr>
                    <tr>
                        <td hidden>MiddleName:</td>
                    </tr>
                    <tr>
                        <td><input type="hidden" name="MiddleName" class="textInput"   value="<?php if(empty($id)==false){ echo $MiddleName;} ?>"  <?php if($readonly ==true){echo "readonly='readonly'";} ?>  placeholder="ex.B" ></td>
                    </tr>
                    <tr>
                        <td>LastName:</td>
                    </tr>
                    <tr>
                        <td><input type="text" name="LastName" class="textInput"   value="<?php if(empty($id)==false){ echo $LastName;} ?>"  <?php if($readonly ==true){echo "readonly='readonly'";} ?>  placeholder="ex.Green"></td>
                    </tr>
                    <tr>
                        <td>Email:</td>
                    </tr>
                    <tr>
                        <td><input type="text" name="Email" class="textInput"   value="<?php if(empty($Email)==false){ echo $Email;} ?>"  <?php if($isadmin ==false){echo "readonly='readonly'";} ?>  ></td>
                    </tr>
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
                    <tr>
                        <td>0.Do you have an assistanship with our deparment?</td>
                    </tr>
                    <tr>
                        <td>
                            <input type="radio" name="Hasassistanship" value="1"  <?php if(empty($id)==false){ if ($Hasassistanship=='1'){echo  'checked';}} else {echo  'checked';} ?>  <?php if($readonly ==true){echo "disabled";} ?> > Yes
                            <input type="radio" name="Hasassistanship" value="0" <?php if(empty($id)==false){ if ($Hasassistanship=='0'){echo  'checked';}} else {} ?>  <?php if($readonly ==true){echo "disabled";} ?> > No
                        </td>
                    </tr>
                    <tr>
                        <td ALIGN="left" style="word-wrap:break-word;word-break:break-all;" hidden>//2.Please select your job preference</td>
                    </tr>
                    <tr>
                        <td>
                            <select name="Position" hidden>
                                <option value='GA'<?php if(empty($id)==false){ if ('GA' == $Position) {echo "selected";}} ?>  <?php if($readonly ==true){echo "disabled";} ?> >Grader</option>
                                <option value='LA'<?php if(empty($id)==false){ if ('LA' == $Position) {echo "selected";}} ?>  <?php if($readonly ==true){echo "disabled";} ?> >Lab Instructor</option>
                                <option value='TA'<?php if(empty($id)==false){ if ('TA' == $Position) {echo "selected";}} ?>  <?php if($readonly ==true){echo "disabled";} ?> >Tutor</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td ALIGN="left" style="word-wrap:break-word;word-break:break-all;">1.Please list your top five preferred courses (CSCxxxx: course title):</td>
                    </tr>
                    <tr>
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
                                    $SubCourse =  $Course;
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
                            <select name="course2" <?php if($readonly ==true){echo "disabled";} ?> >
                                <?php
                                $p_Course = explode( ',', $Courses );
                                echo "<option value=''>NULL</option>";
                                foreach ($coursearray as $arr) {
                                    $SchoolCourseID = $arr["SchoolCourseID"];
                                    $Subject = $arr["Subject"];
                                    $Course = $arr["Course"];
                                    $CourseName = $arr["CourseName"];
                                    $SubCourse = $Course;
                                    echo "<option value='$SubCourse'";
                                    if(empty($id)==false)
                                    {
                                        if ($SubCourse == $course2) {
                                            echo "selected";
                                        }
                                    }
                                    echo  "    >
                                $SubCourse.$CourseName
                                </option>";
                                }
                                ?>
                            </select>
                            <select name="course3" <?php if($readonly ==true){echo "disabled";} ?> >
                                <?php
                                $p_Course = explode( ',', $Courses );
                                echo "<option value=''>NULL</option>";
                                foreach ($coursearray as $arr) {
                                    $SchoolCourseID = $arr["SchoolCourseID"];
                                    $Subject = $arr["Subject"];
                                    $Course = $arr["Course"];
                                    $CourseName = $arr["CourseName"];
                                    $SubCourse =  $Course;
                                    echo "<option value='$SubCourse'";
                                    if(empty($id)==false)
                                    {
                                        if ($SubCourse == $course3) {
                                            echo "selected";
                                        }
                                    }
                                    echo  "    >
                                $SubCourse.$CourseName
                                </option>";
                                }
                                ?>
                            </select>
                            <select name="course4" <?php if($readonly ==true){echo "disabled";} ?> >
                                <?php
                                $p_Course = explode( ',', $Courses );
                                echo "<option value=''>NULL</option>";
                                foreach ($coursearray as $arr) {
                                    $SchoolCourseID = $arr["SchoolCourseID"];
                                    $Subject = $arr["Subject"];
                                    $Course = $arr["Course"];
                                    $CourseName = $arr["CourseName"];
                                    $SubCourse =  $Course;
                                    echo "<option value='$SubCourse'";
                                    if(empty($id)==false)
                                    {
                                        if ($SubCourse == $course4) {
                                            echo "selected";
                                        }
                                    }
                                    echo  "    >
                                $SubCourse.$CourseName
                                </option>";
                                }
                                ?>
                            </select>
                            <select name="course5" <?php if($readonly ==true){echo "disabled";} ?> >
                                <?php
                                $p_Course = explode( ',', $Courses );
                                echo "<option value=''>NULL</option>";
                                foreach ($coursearray as $arr) {
                                    $SchoolCourseID = $arr["SchoolCourseID"];
                                    $Subject = $arr["Subject"];
                                    $Course = $arr["Course"];
                                    $CourseName = $arr["CourseName"];
                                    $SubCourse =  $Course;
                                    echo "<option value='$SubCourse'";
                                    if(empty($id)==false)
                                    {
                                        if ($SubCourse == $course5) {
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
                    </tr>
                    <tr>
                        <td>2.List THREE potential courses you plan to take in the next semester.</td>
                    </tr>
                    <tr>
                        <td>
                            <select name="takecourse1" <?php if($readonly ==true){echo "disabled";} ?> >
                                <?php
                                $p_Course = explode( ',', $Courses );
                                echo "<option value=''>NULL</option>";
                                foreach ($coursearray as $arr) {
                                    $SchoolCourseID = $arr["SchoolCourseID"];
                                    $Subject = $arr["Subject"];
                                    $Course = $arr["Course"];
                                    $CourseName = $arr["CourseName"];
                                    $SubCourse =  $Course;
                                    echo "<option value='$SubCourse'";
                                    if(empty($id)==false)
                                    {
                                        if ($SubCourse == $takecourse1) {
                                            echo "selected";
                                        }
                                    }
                                    echo  "    >
                                $SubCourse.$CourseName
                                </option>";
                                }
                                ?>
                            </select>
                            <select name="takecourse2" <?php if($readonly ==true){echo "disabled";} ?> >
                                <?php
                                $p_Course = explode( ',', $Courses );
                                echo "<option value=''>NULL</option>";
                                foreach ($coursearray as $arr) {
                                    $SchoolCourseID = $arr["SchoolCourseID"];
                                    $Subject = $arr["Subject"];
                                    $Course = $arr["Course"];
                                    $CourseName = $arr["CourseName"];
                                    $SubCourse = $Course;
                                    echo "<option value='$SubCourse'";
                                    if(empty($id)==false)
                                    {
                                        if ($SubCourse == $takecourse2) {
                                            echo "selected";
                                        }
                                    }
                                    echo  "    >
                                $SubCourse.$CourseName
                                </option>";
                                }
                                ?>
                            </select>
                            <select name="takecourse3" <?php if($readonly ==true){echo "disabled";} ?> >
                                <?php
                                $p_Course = explode( ',', $Courses );
                                echo "<option value=''>NULL</option>";
                                foreach ($coursearray as $arr) {
                                    $SchoolCourseID = $arr["SchoolCourseID"];
                                    $Subject = $arr["Subject"];
                                    $Course = $arr["Course"];
                                    $CourseName = $arr["CourseName"];
                                    $SubCourse =  $Course;
                                    echo "<option value='$SubCourse'";
                                    if(empty($id)==false)
                                    {
                                        if ($SubCourse == $takecourse3) {
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
                    </tr>
                    <tr>
                        <td hidden>//4.List the top three faculty members you would like to work with as a Graduate Assistant, in order of preference.</td>
                    </tr>
                    <tr>
                        <td>
                            <select name="preferencefacultymember1" <?php if($readonly ==true){echo "disabled";} ?> hidden >
                                <?php
                                echo "<option value=''>NULL</option>";
                                foreach ($facultyarray as $arr) {
                                    $email = $arr["email"];
                                    $facultyname = $arr["Name"];
                                    echo "<option value='$email'";
                                    if(empty($id)==false)
                                    {
                                        if ($preferencefacultymember1==$email)
                                        {
                                            echo "selected";
                                        }
                                    }
                                    echo  "    >
                            $facultyname
                            </option>";
                                }
                                ?>
                            </select>
                            <select name="preferencefacultymember2" <?php if($readonly ==true){echo "disabled";} ?> hidden>
                                <?php
                                echo "<option value=''>NULL</option>";
                                foreach ($facultyarray as $arr) {
                                    $email = $arr["email"];
                                    $facultyname = $arr["Name"];
                                    echo "<option value='$email'";
                                    if(empty($id)==false)
                                    {
                                        if ($preferencefacultymember2==$email)
                                        {
                                            echo "selected";
                                        }
                                    }
                                    echo  "    >
                            $facultyname
                            </option>";
                                }
                                ?>
                            </select>
                            <select name="preferencefacultymember3" <?php if($readonly ==true){echo "disabled";} ?> hidden>
                                <?php
                                echo "<option value=''>NULL</option>";
                                foreach ($facultyarray as $arr) {
                                    $email = $arr["email"];
                                    $facultyname = $arr["Name"];
                                    echo "<option value='$email'";
                                    if(empty($id)==false)
                                    {
                                        if ($preferencefacultymember3==$email)
                                        {
                                            echo "selected";
                                        }
                                    }
                                    echo  "    >
                            $facultyname
                            </option>";
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>3.When did get enroll in our program?</td>
                    </tr>
                    <tr>
                        <td>
                            <select name="Startterm" <?php if($readonly ==true){echo "disabled";} ?> >
                                <?php
                                foreach ($termarray as $arr)
                                {
                                    $p_Termid = $arr["Termid"];
                                    $p_Term = $arr["Term"];
                                    echo "<option value='$p_Termid'";
                                    if(empty($id)==false)
                                    {
                                        if ($p_Termid == $StartTerm) {
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
                        <td>4.List the courses (Up to 5 course) where you have been a TA?</td>
                    </tr>
                    <tr>
                        <td>
                            <select name="tacourse1" <?php if($readonly ==true){echo "disabled";} ?> >
                                <?php
                                $p_Course = explode( ',', $Courses );
                                echo "<option value=''>NULL</option>";
                                foreach ($coursearray as $arr) {
                                    $SchoolCourseID = $arr["SchoolCourseID"];
                                    $Subject = $arr["Subject"];
                                    $Course = $arr["Course"];
                                    $CourseName = $arr["CourseName"];
                                    $SubCourse =  $Course;
                                    echo "<option value='$SubCourse'";
                                    if(empty($id)==false)
                                    {
                                        if ($SubCourse == $tacourse1) {
                                            echo "selected";
                                        }
                                    }
                                    echo  "    >
                                $SubCourse.$CourseName
                                </option>";
                                }
                                ?>
                            </select>
                            <select name="tacourse2" <?php if($readonly ==true){echo "disabled";} ?> >
                                <?php
                                $p_Course = explode( ',', $Courses );
                                echo "<option value=''>NULL</option>";
                                foreach ($coursearray as $arr) {
                                    $SchoolCourseID = $arr["SchoolCourseID"];
                                    $Subject = $arr["Subject"];
                                    $Course = $arr["Course"];
                                    $CourseName = $arr["CourseName"];
                                    $SubCourse = $Course;
                                    echo "<option value='$SubCourse'";
                                    if(empty($id)==false)
                                    {
                                        if ($SubCourse == $tacourse2) {
                                            echo "selected";
                                        }
                                    }
                                    echo  "    >
                                $SubCourse.$CourseName
                                </option>";
                                }
                                ?>
                            </select>
                            <select name="tacourse3" <?php if($readonly ==true){echo "disabled";} ?> >
                                <?php
                                $p_Course = explode( ',', $Courses );
                                echo "<option value=''>NULL</option>";
                                foreach ($coursearray as $arr) {
                                    $SchoolCourseID = $arr["SchoolCourseID"];
                                    $Subject = $arr["Subject"];
                                    $Course = $arr["Course"];
                                    $CourseName = $arr["CourseName"];
                                    $SubCourse =  $Course;
                                    echo "<option value='$SubCourse'";
                                    if(empty($id)==false)
                                    {
                                        if ($SubCourse == $tacourse3) {
                                            echo "selected";
                                        }
                                    }
                                    echo  "    >
                                $SubCourse.$CourseName
                                </option>";
                                }
                                ?>
                            </select>
                            <select name="tacourse4" <?php if($readonly ==true){echo "disabled";} ?> >
                                <?php
                                $p_Course = explode( ',', $Courses );
                                echo "<option value=''>NULL</option>";
                                foreach ($coursearray as $arr) {
                                    $SchoolCourseID = $arr["SchoolCourseID"];
                                    $Subject = $arr["Subject"];
                                    $Course = $arr["Course"];
                                    $CourseName = $arr["CourseName"];
                                    $SubCourse =  $Course;
                                    echo "<option value='$SubCourse'";
                                    if(empty($id)==false)
                                    {
                                        if ($SubCourse == $tacourse4) {
                                            echo "selected";
                                        }
                                    }
                                    echo  "    >
                                $SubCourse.$CourseName
                                </option>";
                                }
                                ?>
                            </select>
                            <select name="tacourse5" <?php if($readonly ==true){echo "disabled";} ?> >
                                <?php
                                $p_Course = explode( ',', $Courses );
                                echo "<option value=''>NULL</option>";
                                foreach ($coursearray as $arr) {
                                    $SchoolCourseID = $arr["SchoolCourseID"];
                                    $Subject = $arr["Subject"];
                                    $Course = $arr["Course"];
                                    $CourseName = $arr["CourseName"];
                                    $SubCourse =  $Course;
                                    echo "<option value='$SubCourse'";
                                    if(empty($id)==false)
                                    {
                                        if ($SubCourse == $tacourse5) {
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
                    </tr>
                    <tr>
                        <td hidden>5.Did/will you apply for support from other departments?</td>
                    </tr>
                    <tr>
                        <td>
                            <input type="hidden" name="supportothercourse" id="supportothercourse" value="0" <?php if(empty($id)==false){ if ($Supportothercourse=='0'){echo  'checked';}} else {echo  'checked';} ?>  <?php if($readonly ==true){echo "disabled";} ?> ><label hidden id="labelsupportothercourseno">No</label>
                            <input type="hidden" name="supportothercourse" id="supportothercourse" value="1" <?php if(empty($id)==false){ if ($Supportothercourse=='1'){echo  'checked';}} else {} ?>  <?php if($readonly ==true){echo "disabled";} ?> ><label hidden id="labelsupportothercourseyes">Yes</label>
                        </td>
                    </tr>
                    <tr>
                        <td name="semesterstitle" id="semesterstitle">6.How many semesters have you been a graduate assistant in the CS department?</td>
                    </tr>
                    <tr>
                        <td>
                            <input type="radio" name="semesters" id="semester0" value="0"  <?php if(empty($id)==false){ if ($Semesters=='0'){echo  'checked';}} else {echo  'checked';} ?>  <?php if($readonly ==true){echo "disabled";} ?> > <label id="labelsemester0">0</label>
                            <input type="radio" name="semesters" id="semester1" value="1" <?php if(empty($id)==false){ if ($Semesters=='1'){echo  'checked';}} else {} ?>  <?php if($readonly ==true){echo "disabled";} ?> > <label id="labelsemester1">1</label>
                            <input type="radio" name="semesters" id="semester2" value="2" <?php if(empty($id)==false){ if ($Semesters=='2'){echo  'checked';}} else {} ?>  <?php if($readonly ==true){echo "disabled";} ?> > <label id="labelsemester2">2</label>
                            <input type="radio" name="semesters" id="semester3" value="3" <?php if(empty($id)==false){ if ($Semesters=='3'){echo  'checked';}} else {} ?>  <?php if($readonly ==true){echo "disabled";} ?> > <label id="labelsemester3">3</label>
                            <input type="radio" name="semesters" id="semester4" value="4" <?php if(empty($id)==false){ if ($Semesters=='4'){echo  'checked';}} else {} ?>   <?php if($readonly ==true){echo "disabled";} ?> > <label id="labelsemester4">4</label>
                            <input type="radio" name="semesters" id="semester5" value="5" <?php if(empty($id)==false){ if ($Semesters=='5'){echo  'checked';}} else {} ?>   <?php if($readonly ==true){echo "disabled";} ?> > <label id="labelsemester5">>4</label>
                        </td>
                    </tr>
                    <tr>
                        <td name="advisortitle" id="advisortitle">7. Who is your advisor (if any)? At the end of current semester, an evaluation from your advisor will be needed.(only for current students)</td>
                    </tr>
                    <tr>
                        <td ALIGN="left">
                            <select name="advisor" id="advisor" <?php if($readonly ==true){echo "disabled";} ?> >
                                <?php
                                echo "<option value=''>N/A</option>";
                                foreach ($facultyarray as $arr) {
                                    $email = $arr["email"];
                                    $facultyname = $arr["Name"];
                                    echo "<option value='$email'";
                                    if(empty($id)==false)
                                    {
                                        if ($Advisor==$email)
                                        {
                                            echo "selected";
                                        }
                                    }
                                    echo  "    >
                            $facultyname
                            </option>";
                                }
                                ?>
                            </select>
                        </td>

                    </tr>
                    <tr>
                        <td name="instructortitle" id="instructortitle">8. As a TA, who do you currently work with or worked with in the last semester? At the end of current semester, an evaluation from the instructor will be needed.</td>
                    </tr>
                    <tr>
                        <td ALIGN="left">
                            <select name="instructor" id="instructor" <?php if($readonly ==true){echo "disabled";} ?> >
                                <?php
                                echo "<option value=''>NULL</option>";
                                foreach ($instructorarray as $arr) {
                                    $email = $arr["email"];
                                    $instructorname = $arr["Name"];
                                    echo "<option value='$email'";
                                    if(empty($id)==false)
                                    {
                                        if ($CurrentInstructor==$email)
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
                        <td name="gpatitle" id="gpatitle">9. What is your GPA (only for current students)?</td>
                    </tr>
                    <tr>
                        <td><input type="number" name="currentgpa" id="currentgpa" step="0.1" min="0" max="4.3" class="textInput"   value="<?php if(empty($id)==false){ echo $CurrentGPA;} else {echo '0.1';} ?>"  <?php if($readonly ==true){echo "readonly='readonly'";} ?>  ></td>
                    </tr>
                    <tr>
                        <td  name="submitevaluationtitle" id="submitevaluationtitle">
                            10.If funded last semester, did you submit the evaluation from your research/project advisor and instructor?</td>
                    </tr>
                    <tr>
                        <td>
                            <input type="radio" name="submitevaluation" id="submitevaluationyes" value="1"  <?php if(empty($id)==false){ if ($Submitevaluation=='1'){echo  'checked';}} else {echo  'checked';} ?>  <?php if($readonly ==true){echo "disabled";} ?> ><label id="labelsubmitevaluationyes"> Yes</label>
                            <input type="radio" name="submitevaluation" id="submitevaluationno" value="0" <?php if(empty($id)==false){ if ($Submitevaluation=='0'){echo  'checked';}} else {} ?>  <?php if($readonly ==true){echo "disabled";} ?> ><label id="labelsubmitevaluationno">No</label>
                            <input type="radio" name="submitevaluation" id="submitevaluationna" value="-1" <?php if(empty($id)==false){ if ($Submitevaluation=='-1'){echo  'checked';}} else {} ?>  <?php if($readonly ==true){echo "disabled";} ?> ><label id="labelsubmitevaluationna"> N/A</label>
                        </td>
                    </tr>
                    <tr>
                        <td name="receiveassistantshipfromanotherdepartmenttitle" id="receiveassistantshipfromanotherdepartmenttitle">
                            11. Are you expecting to receive a graduate assistantship from another department/unit at GSU? </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="radio" name="receiveassistantshipfromanotherdepartment" id="receiveassistantshipfromanotherdepartmentno" value="0" <?php if(empty($id)==false){ if ($ReceiveAssistantshipFromAnotherDepartment=='0'){echo  'checked';}} else {echo  'checked';} ?>  <?php if($readonly ==true){echo "disabled";} ?> ><label id="labelreceiveassistantshipfromanotherdepartmentno">No</label>
                            <input type="radio" name="receiveassistantshipfromanotherdepartment" id="receiveassistantshipfromanotherdepartmentyes" value="1" <?php if(empty($id)==false){ if ($ReceiveAssistantshipFromAnotherDepartment=='1'){echo  'checked';}} else {} ?>  <?php if($readonly ==true){echo "disabled";} ?> ><label id="labelreceiveassistantshipfromanotherdepartmentyes">Yes</label>
                        </td>
                    </tr>
                    <tr>
                        <td name="anotherdepartmenttitle" id="anotherdepartmenttitle">
                            12. If you answered yes to question 11, please provide details (department, number of hours per week)</td>
                    </tr>
                    <tr>
                        <td>
                            <input type="text" name="nameofanotherdepartment" id ="nameofanotherdepartment" class="textInput"   value="<?php if(empty($id)==false){ echo $NameOfAnotherDepartment;} ?>"  <?php if($readonly ==true){echo "readonly='readonly'";} ?>  placeholder="ex.Mathematics" >
                            <input type="text" name="hoursofanotherdepartment" id="hoursofanotherdepartment" class="textInput"   value="<?php if(empty($id)==false){ echo $HoursOfAnotherDepartment;} ?>"  <?php if($readonly ==true){echo "readonly='readonly'";} ?>  placeholder="ex.20" >
                        </td>
                    </tr>
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

                    <tr>
                        <td>
                            Data Structures:
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="text" name="datastructure" disabled value="<?php if(empty($DataStructure)==false){ echo $DataStructure;} ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Software Engineering:
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="text" name="softwareengineering" disabled value="<?php if(empty($SoftwareEngineering)==false){ echo $SoftwareEngineering;} ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Design and Analysis of Algorithms:
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="text" name="algorithms" disabled value="<?php if(empty($Algorithms)==false){ echo $Algorithms;} ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Operating Systems:
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="text" name="operatingsystems" disabled value="<?php if(empty($OperatingSystems)==false){ echo $OperatingSystems;} ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Programming Language Concepts:
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="text" name="programminglanguageconcepts" disabled value="<?php if(empty($ProgrammingLanguageConcepts)==false){ echo $ProgrammingLanguageConcepts;} ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Computer Architecture:
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="text" name="computerarchitecture" disabled value="<?php if(empty($ComputerArchitecture)==false){ echo $ComputerArchitecture;} ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Automata:
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="text" name="automata" disabled value="<?php if(empty($Automata)==false){ echo $Automata;} ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Calculus:
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="text" name="calculus" disabled value="<?php if(empty($Calculus)==false){ echo $Calculus;} ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Discrete Mathematics :
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="text" name="discretemathematics" disabled value="<?php if(empty($DiscreteMathematics)==false){ echo $DiscreteMathematics;} ?>"/>
                        </td>
                    </tr>

                </table>
            </form>
        </div>
    </div>
</div>
</body>
</html>
<script>
    $("#Degree").change(function() {
        //alert('P1');
        $selectvalue =$('option:selected',this).attr('value');
        //alert($selectvalue);
        if($selectvalue=="MS")//MS
        {
            document.getElementById("headtitle").innerHTML = "GTA Teaching Preference Form";

            document.getElementById("anotherdepartmenttitle").style.display  = 'inherit';
            document.getElementById("nameofanotherdepartment").style.display  = 'inherit';
            document.getElementById("hoursofanotherdepartment").style.display  = 'inherit';

            document.getElementById("receiveassistantshipfromanotherdepartmenttitle").style.display  = 'inherit';
            document.getElementById("receiveassistantshipfromanotherdepartmentyes").style.display  = '';
            document.getElementById("receiveassistantshipfromanotherdepartmentno").style.display  = '';
            document.getElementById("labelreceiveassistantshipfromanotherdepartmentyes").style.display  = '';
            document.getElementById("labelreceiveassistantshipfromanotherdepartmentno").style.display  = '';

            var IsNewStudent = document.getElementById("IsNewStudent");
            var strIsNewStudent = IsNewStudent.options[IsNewStudent.selectedIndex].value;
            //alert (strIsNewStudent);
            if(strIsNewStudent=='0')
            {
                document.getElementById("submitevaluationtitle").style.display  = 'inherit';
                document.getElementById("submitevaluationyes").style.display   = '';
                document.getElementById("submitevaluationno").style.display   = '';
                document.getElementById("submitevaluationna").style.display   = '';
            }
            else
            {
                document.getElementById("submitevaluationtitle").style.display = 'none';
                document.getElementById("submitevaluationyes").style.display = 'none';
                document.getElementById("submitevaluationno").style.display = 'none';
                document.getElementById("submitevaluationna").style.display = 'none';
            }



            document.getElementById("labelsubmitevaluationyes").style.display   = '';
            document.getElementById("labelsubmitevaluationno").style.display   = '';
            document.getElementById("labelsubmitevaluationna").style.display   = '';
        }
        else if($selectvalue =="PHD")//PHD
        {
            document.getElementById("headtitle").innerHTML = "GTA Teaching Preference Form";

            document.getElementById("anotherdepartmenttitle").style.display  = 'none';
            document.getElementById("nameofanotherdepartment").style.display  = 'none';
            document.getElementById("hoursofanotherdepartment").style.display  = 'none';

            document.getElementById("receiveassistantshipfromanotherdepartmenttitle").style.display  = 'none';
            document.getElementById("receiveassistantshipfromanotherdepartmentyes").style.display  = 'none';
            document.getElementById("receiveassistantshipfromanotherdepartmentno").style.display  = 'none';
            document.getElementById("labelreceiveassistantshipfromanotherdepartmentyes").style.display  = 'none';
            document.getElementById("labelreceiveassistantshipfromanotherdepartmentno").style.display  = 'none';

            document.getElementById("submitevaluationtitle").style.display  = 'none';
            document.getElementById("submitevaluationyes").style.display   = 'none';
            document.getElementById("submitevaluationno").style.display   = 'none';
            document.getElementById("submitevaluationna").style.display   = 'none';
            document.getElementById("labelsubmitevaluationyes").style.display   = 'none';
            document.getElementById("labelsubmitevaluationno").style.display   = 'none';
            document.getElementById("labelsubmitevaluationna").style.display   = 'none';

        }

    }).change();

    $("#IsNewStudent").change(function() {
        //alert('P1');
        $selectvalue =$('option:selected',this).attr('value');
        //alert($selectvalue);
        if($selectvalue=="0")//old student
        {
            //document.getElementById("currentgpa").value="0.1";
            var degree = document.getElementById("Degree");
            var strdegree = degree.options[degree.selectedIndex].value;
            //alert (strdegree);
            if(strdegree=='MS')
            {
                document.getElementById("submitevaluationtitle").style.display = '';
                document.getElementById("submitevaluationyes").style.display = '';
                document.getElementById("submitevaluationno").style.display = '';
                document.getElementById("submitevaluationna").style.display = '';
                document.getElementById("labelsubmitevaluationyes").style.display   = '';
                document.getElementById("labelsubmitevaluationno").style.display   = '';
                document.getElementById("labelsubmitevaluationna").style.display   = '';
            }
            else
            {
                document.getElementById("submitevaluationtitle").style.display = 'none';
                document.getElementById("submitevaluationyes").style.display = 'none';
                document.getElementById("submitevaluationno").style.display = 'none';
                document.getElementById("submitevaluationna").style.display = 'none';
                document.getElementById("labelsubmitevaluationyes").style.display   = 'none';
                document.getElementById("labelsubmitevaluationno").style.display   = 'none';
                document.getElementById("labelsubmitevaluationna").style.display   = 'none';
            }
            document.getElementById("semesterstitle").style.display   = '';
            document.getElementById("semester0").style.display   = '';
            document.getElementById("semester1").style.display   = '';
            document.getElementById("semester2").style.display   = '';
            document.getElementById("semester3").style.display   = '';
            document.getElementById("semester4").style.display   = '';
            document.getElementById("semester5").style.display   = '';
            document.getElementById("labelsemester0").style.display   = '';
            document.getElementById("labelsemester1").style.display   = '';
            document.getElementById("labelsemester2").style.display   = '';
            document.getElementById("labelsemester3").style.display   = '';
            document.getElementById("labelsemester4").style.display   = '';
            document.getElementById("labelsemester5").style.display   = '';

            document.getElementById("instructortitle").style.display   = '';
            document.getElementById("instructor").style.display   = '';

            document.getElementById("gpatitle").style.display  = '';
            document.getElementById("currentgpa").style.display  = '';
        }
        else if($selectvalue =="1")//New student
        {
            document.getElementById("submitevaluationtitle").style.display  = 'none';
            document.getElementById("submitevaluationyes").style.display  = 'none';
            document.getElementById("submitevaluationno").style.display  = 'none';
            document.getElementById("submitevaluationna").style.display  = 'none';
            document.getElementById("labelsubmitevaluationyes").style.display   = 'none';
            document.getElementById("labelsubmitevaluationno").style.display   = 'none';
            document.getElementById("labelsubmitevaluationna").style.display   = 'none';

            document.getElementById("semesterstitle").style.display   = 'none';
            document.getElementById("semester0").style.display   = 'none';
            document.getElementById("semester1").style.display   = 'none';
            document.getElementById("semester2").style.display   = 'none';
            document.getElementById("semester3").style.display   = 'none';
            document.getElementById("semester4").style.display   = 'none';
            document.getElementById("semester5").style.display   = 'none';
            document.getElementById("labelsemester0").style.display   = 'none';
            document.getElementById("labelsemester1").style.display   = 'none';
            document.getElementById("labelsemester2").style.display   = 'none';
            document.getElementById("labelsemester3").style.display   = 'none';
            document.getElementById("labelsemester4").style.display   = 'none';
            document.getElementById("labelsemester5").style.display   = 'none';

            document.getElementById("instructortitle").style.display   = 'none';
            document.getElementById("instructor").style.display   = 'none';

            document.getElementById("gpatitle").style.display  = 'none';
            document.getElementById("currentgpa").style.display  = 'none';
        }

    }).change();

    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();

        document.body.innerHTML = originalContents;
    }
</script>