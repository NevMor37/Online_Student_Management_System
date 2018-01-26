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

//if(isset($_POST["id"]))
//{
//    $output = '';
//    $id= $_POST["id"];
//    if(is_numeric($id))
//    {
//        $sql="select
//            ts.PantherID as id,
//            CONCAT(coalesce(ts.FirstName,' ') , IF(ts.MiddleName = '', ' ', IFNULL(ts.MiddleName,' ')),coalesce(ts.LastName,' ')) as name,
//            ts.email as email
//            from tbl_student_info as ts
//                        where  ts.PantherID = " . $id;
//        $result = mysqli_query($db, $sql);
//        if ($result->num_rows > 0)
//        {
//            while ($row = mysqli_fetch_array($result))
//            {
//
//                $output =  '
//
//              <p><label>ID : </label>' . $row['id'] . '</p>
//              <p><label>Name : </label>' . $row['name'] . '</p>
//              <p><label>Email : </label>' . $row['email'] . '</p>
//            ';
//
//            }
//        }
//    }
//    echo $output;
//}


if(isset($_POST["id"]))
{
    $output = '';
    $id= $_POST["id"];
    $coursearray = array();
    $termid=$_POST["termid"];
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

    $sql = "select ga.GAApplicationID as GAApplicationID,ga.TermID as TermID,te.Term as Term,ga.PantherID as PantherID,
                                    CONCAT(coalesce(ga.FirstName,' '),coalesce(ga.MiddleName,' '),coalesce(ga.LastName,' ')) as Name,
                                    ga.Email as email,ga.Courses as Courses,te2.Term as StartTerm, ga.Semesters as Semesters,
                                    ga.Advisor as Advisor,ga.CurrentInstructor as CurrentInstructor,ga.CurrentGPA as CurrentGPA,
                                    ga.Preferencefacultymembers as Preferencefacultymembers,ga.Status as Status,
                                    ga.Takecourses as Takecourses,
                                    ga.Tacourses as Tacourses,
                                    ga.IsNewStudent as IsNewStudent,
                                    ga.ReceiveAssistantshipFromAnotherDepartment as ReceiveAssistantshipFromAnotherDepartment,
                                    ga.NameOfAnotherDepartment as NameOfAnotherDepartment,
                                    ga.HoursOfAnotherDepartment as HoursOfAnotherDepartment
                                    from tbl_gaapplication as ga 
                                    left JOIN  tbl_term as te on te.Termid = ga.TermID
                                    left JOIN  tbl_term as te2 on te2.Termid = ga.StartTerm WHERE ga.PantherID=$id and ga.TermID = $termid
                  ";
    $result = mysqli_query($db, $sql);
    while($row = mysqli_fetch_array($result))
    {
        $p_email =$row["Email"];
        $Courses=$row["Courses"];
        $Course = explode(",", $Courses);
        $m_Courses='';
        foreach ($Course as $arr)
        {
            // echo '$arr:'.$arr;
            foreach ($coursearray as $p_arr)
            {
                $Subject = $p_arr["Subject"];
                $Course = $p_arr["Course"];
                $CourseName = $p_arr["CourseName"];
                $SubCourse =   $Course;
                //echo '$SubCourse:' .$SubCourse;
                if($SubCourse==$arr)
                {
                    //echo '$CourseName:'.$CourseName;
                    $m_Courses =$m_Courses . $SubCourse.',';
                    //echo '$m_Courses:'.$m_Courses;
                }
            }
            //echo '<br>';
        }
        if(substr($m_Courses,strlen($m_Courses)-1,1)==',')
        {
            $m_Courses=substr($m_Courses,0,strlen($m_Courses)-1);
        }

        $advisor=$row["Advisor"];
        $m_advisor='';
        foreach ($facultyarray as $p_arr)
        {
            $email = $p_arr["email"];
            $Name = $p_arr["Name"];
            //echo '$email:' .$email;
            //echo '$Name:' .$Name;
            if($advisor==$email)
            {
                $m_advisor =$Name;
            }
        }
        $currentinstructor=$row["CurrentInstructor"];
        $m_currentinstructor='';
        foreach ($instructorarray as $p_arr)
        {
            $email = $p_arr["email"];
            $Name = $p_arr["Name"];
            //echo '$email:' .$email;
            //echo '$Name:' .$Name;
            if($currentinstructor==$email)
            {
                $m_currentinstructor =$Name;
            }
        }

        $preferencefacultymembers=$row["Preferencefacultymembers"] ;
        $preferencefacultymember = explode(",", $preferencefacultymembers);
        $m_preferencefacultymembers='';
        foreach ($preferencefacultymember as $arr)
        {
            // echo '$arr:'.$arr;
            foreach ($facultyarray as $p_arr)
            {
                $email = $p_arr["email"];
                $Name = $p_arr["Name"];
                if($email==$arr)
                {
                    //echo '$CourseName:'.$CourseName;
                    $m_preferencefacultymembers =$m_preferencefacultymembers . $Name .',';
                    //echo '$m_Courses:'.$m_Courses;
                }
            }
            //echo '<br>';
        }
        if(substr($m_preferencefacultymembers,strlen($m_preferencefacultymembers)-1,1)==',')
        {
            $m_preferencefacultymembers=substr($m_preferencefacultymembers,0,strlen($m_preferencefacultymembers)-1);
        }
        $PantherID = $row['PantherID'];

        //Takecourses
        $Takecourses = $row['Takecourses'];
        $p_course =$Takecourses;
        $temp_Course = explode(",", $p_course);
        $w_Courses = '';
        foreach ($temp_Course as $arr) {
            // echo '$arr:'.$arr;
            foreach ($coursearray as $p_arr) {
                $Subject = $p_arr["Subject"];
                $Course = $p_arr["Course"];
                $CourseName = $p_arr["CourseName"];
                $SubCourse = $Course;
                //echo '$SubCourse:' .$SubCourse;
                if ($SubCourse == $arr) {
                    //echo '$CourseName:'.$CourseName;
                    //$w_Courses = $w_Courses . $SubCourse . '.' . $CourseName . ',';
                    $w_Courses = $w_Courses . $SubCourse .  ',';
                    //echo '$m_Courses:'.$m_Courses;
                }
            }
            //echo '<br>';
        }
        if (substr($w_Courses, strlen($w_Courses) - 1, 1) == ',') {
            $w_Courses = substr($w_Courses, 0, strlen($w_Courses) - 1);
        }
        $m_Takecourses = $w_Courses;
        //

        $Tacourses = $row['Tacourses'];
        $p_course =$Tacourses;
        $temp_Course = explode(",", $p_course);
        $w_Courses = '';
        foreach ($temp_Course as $arr) {
            // echo '$arr:'.$arr;
            foreach ($coursearray as $p_arr) {
                $Subject = $p_arr["Subject"];
                $Course = $p_arr["Course"];
                $CourseName = $p_arr["CourseName"];
                $SubCourse = $Course;
                //echo '$SubCourse:' .$SubCourse;
                if ($SubCourse == $arr) {
                    //echo '$CourseName:'.$CourseName;
                    //$w_Courses = $w_Courses . $SubCourse . '.' . $CourseName . ',';
                    $w_Courses = $w_Courses . $SubCourse . ',';
                    //echo '$m_Courses:'.$m_Courses;
                }
            }
            //echo '<br>';
        }
        if (substr($w_Courses, strlen($w_Courses) - 1, 1) == ',') {
            $w_Courses = substr($w_Courses, 0, strlen($w_Courses) - 1);
        }
        $m_Tacourses = $w_Courses;

        //<p><label>TermID : '.$row['TermID'].'</label></p>
        // <p><label>GAApplicationID : '.$row['GAApplicationID'].'</label></p>
//        $output = '
//          <p><label>PantherID : '.$row['PantherID'].'</label></p>
//          <p><label>Term : '.$row['Term'].'</label></p>
//          <p><label>Course : </label></p>
//          <p><label>'.$Courses.'</label></p>
//          <p><label>StartTerm : '.$row['StartTerm'].'</label></p>
//          <p><label>Semesters : '.$row['Semesters'].'</label></p>
//          <p><label>Advisor : '.$m_advisor.'</label></p>
//          <p><label>Currentinstructor : '.$m_currentinstructor.'</label></p>
//          <p><label>GPA : '.$row['CurrentGPA'].'</label></p>
//          <p><label>Preferencefacultymembers : '.$m_preferencefacultymembers.'</label></p>
//          <p><label>Status : '.$row["Status"].'</label></p>
//        ';

        $ReceiveAssistantshipFromAnotherDepartment = $row['ReceiveAssistantshipFromAnotherDepartment'];
        $NameOfAnotherDepartment = $row['NameOfAnotherDepartment'];
        $HoursOfAnotherDepartment = $row['HoursOfAnotherDepartment'];

        $output = '         
          <p><label>StartTerm : ' . $row['StartTerm'] . '</label></p>
          <p><label>Advisor : ' . $m_advisor . '</label></p>
          <p><label>Currentinstructor : ' . $m_currentinstructor . '</label></p>
          <p><label>GPA : ' . $row['CurrentGPA'] . '</label></p>
          <p><label>Prefered Courses : ' . $m_Courses . '</label></p>
          <p><label>TBT Courses : ' . $m_Takecourses . '</label></p>
          <p><label>TAed Courses : ' . $m_Tacourses . '</label></p>
        ';
        if($ReceiveAssistantshipFromAnotherDepartment==0)
        {
            $output = $output.
                '<p><label>Other Support : No'  . '</label></p>
                    ';
        }
        else if ($ReceiveAssistantshipFromAnotherDepartment==1)
        {
            $output = $output.
                '<p><label>Other Support : Yes'  . '</label></p>
                     <p><label>' . $NameOfAnotherDepartment .' '.$HoursOfAnotherDepartment . '</label></p>
                    ';
        }


        $output = $output .'<p><label>Foundation Course:';
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
                $DataStructure = $rowFoundation["DS"];
                $SoftwareEngineering = $rowFoundation["SE"];
                $Algorithms = $rowFoundation["AA"];
                $OperatingSystems = $rowFoundation["OS"];
                $ProgrammingLanguageConcepts = $rowFoundation["PL"];
                $ComputerArchitecture = $rowFoundation["CA"];
                $Automata = $rowFoundation["Automata"];
                $Calculus = $rowFoundation["Calculus"];
                $DataMining = $rowFoundation["DM"];
                if($DataStructure==1)
                {
                    $output = $output .'DataStructure/CSC 3410<br>';
                }
                if($SoftwareEngineering==1)
                {
                    $output = $output .'Software Engineering/CSC 6350<br>';
                }
                if($Algorithms==1)
                {
                    $output = $output .'Algorithms/CSC 6520<br>';
                }
                if($OperatingSystems==1)
                {
                    $output = $output .'Operating Systems/CSC 6320<br>';
                }
                if($ProgrammingLanguageConcepts==1)
                {
                    $output = $output .'Programming Language Concepts/CSC 6330<br>';
                }
                if($ComputerArchitecture==1)
                {
                    $output = $output .'Computer Architecture/CSC 6210<br>';
                }
                if($Automata==1)
                {
                    $output = $output .'Automata/CSC 6510<br>';
                }
                if($Calculus==1)
                {
                    $output = $output .'Calculus/MATH 2211 and MATH 2212<br>';
                }
                if($DataMining==1)
                {
                    $output = $output .'DataMining/CSC 6740<br>';
                }

            }
        } else {
            //echo "0 results";
        }
        $output = $output .'</label></p>';

        //TOEFL/IELTS, GRE

       // $output = $output . '<p><label>TOEFL/IELTS and GRE:</label></p>';
        $sqlRecordExam = " select GREVerbalScore,GREQuantScore,GREAnalyticalScore,TOEFLTotal
                                from tbl_excel_info
                                where PantherId =" . $PantherID;
        //echo $sqlFoundation;
        $resultRecordExam = mysqli_query($db, $sqlRecordExam);

        if ($resultRecordExam->num_rows > 0)
        {

            // output data of each row
            while ($rowRecordExam = $resultRecordExam->fetch_assoc())
            {
                $GREVerbalScore = $rowRecordExam["GREVerbalScore"];
                $GREQuantScore = $rowRecordExam["GREQuantScore"];
                $GRETotal = $GREVerbalScore+$GREQuantScore;
                $GREAnalyticalScore = $rowRecordExam["GREAnalyticalScore"];
                $TOEFLTotal = $rowRecordExam["TOEFLTotal"];

//                $output = $output . '<p><label>GREVerbalScore : ' . $GREVerbalScore . '</label></p>';
//                $output = $output . '<p><label>GREQuantScore : ' .$GREQuantScore  . '</label></p>';
//                $output = $output . '<p><label>GRETotal : ' . $GRETotal . '</label></p>';
//                $output = $output . '<p><label>GREAnalyticalScore : ' . $GREAnalyticalScore . '</label></p>';
//                $output = $output . '<p><label>TOEFLTotal : ' .$TOEFLTotal  . '</label></p>';
            }
        }
        $output = $output . '';




    }
    echo $output;
}



?>
