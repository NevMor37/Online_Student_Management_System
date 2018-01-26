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

$coursearray = array();
$sql = "SELECT SchoolCourseID,Subject,Course,Credit,Prerequisites,Description 
        FROM tbl_schoolcourse
                                 ";

$result = mysqli_query($db, $sql);

if ($result->num_rows > 0) {
    $i = 0;
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        $coursearray[$i] = array();
        $coursearray[$i]['SchoolCourseID'] = $row["SchoolCourseID"];
        $coursearray[$i]['Subject'] = $row["Subject"];
        $coursearray[$i]['Course'] = $row["Course"];
        $i = $i + 1;
    }
}

$Pantherid = $user_pantherid;
$Islogin=false;
if(empty($Pantherid))
{

    $Islogin=false;
    echo 'Please login in first!';
}
else
{
    $Pantherid = (int)$Pantherid;
    $Islogin=true;
}
?>
<?php
if(isset($_SESSION['message']))
{
    echo "<div id='error_msg'>".$_SESSION['message']."</div>";
    unset($_SESSION['message']);
}
?>
<div id="dialogoverlay" style="display: none; opacity: .8; position: fixed; top: 0px; left: 0px; background: #FFF; width: 100%; z-index: 10;"></div>
<div id="dialogbox" style="display: none; position: fixed; background: #000; border-radius:7px; width:550px; z-index: 10;">
    <div style="background:#FFF; margin:8px;">
        <div id="dialogboxhead" style="background: #666; font-size:19px; padding:10px; color:#CCC;"></div>
        <div id="dialogboxbody" style="background:#333; padding:20px; color:#FFF;"></div>
        <div id="dialogboxfoot" style="background: #666; padding:10px; text-align:right;"></div>
    </div>
</div>



<?php
if($Islogin==true) {
//    echo '
//        <div>
//            <a href="GTAApplicationregister.php">Add New Graduate Assistantship Application</a><br>
//        </div>
//        ';
    echo '
            <p>Add New Graduate Assistantship and Appointment Preference Application</p>
           ';
    echo '
<p id="post_1">  
    <input type="submit" value="Apply" style =" cursor: default;" onclick="Confirm.render(\'Do you want to gegin this application?\',\'delete_post\',\'post_1\')"/>
</p>
          ';
}
?>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body"  style="overflow:auto">
                <table width="100%" class="table table-striped table-bordered table-hover" id="GTAApplication-view">
                    <thead>
                    <!-- Head -->
                    <tr>
                        <th>Term</th><th>PantherID</th><th>Name</th>
                        <th>StartTerm</th>
                        <th>Semesters</th>
                        <th>GPA</th><th>Status</th>
                        <th>View</th><th>Update</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

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
                                         CONCAT(coalesce(ga.FirstName,' ') , IF(ga.MiddleName = '', ' ', IFNULL(ga.MiddleName,' ')),coalesce(ga.LastName,' ')) as Name,
                                        ga.Email as email,ga.Courses as Courses,te2.Term as StartTerm, ga.Semesters as Semesters,
                                        ga.Advisor as Advisor,ga.CurrentInstructor as CurrentInstructor,ga.CurrentGPA as CurrentGPA,
                                        ga.Preferencefacultymembers as Preferencefacultymembers,ga.Status as Status,
                                        ga.IsPublish as IsPublish
                                        from tbl_gaapplication as ga 
                                        left JOIN  tbl_term as te on te.Termid = ga.TermID
                                        left JOIN  tbl_term as te2 on te2.Termid = ga.StartTerm
                                        where ga.PantherID = '$Pantherid'
                                        order by te.Startday,ga.PantherID
                                     ";
                        //echo $sql;
                        $result = mysqli_query($db, $sql);

                        while($row=mysqli_fetch_assoc($result))
                        {
                        $status = $row["Status"];
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
                                $SubCourse = $Subject . $Course;
                                //echo '$SubCourse:' .$SubCourse;
                                if($SubCourse==$arr)
                                {
                                    //echo '$CourseName:'.$CourseName;
                                    $m_Courses =$m_Courses . $SubCourse.'.'.$CourseName .',';
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
                        $m_status=$row["Status"];
                        $m_CurrentGPA=$row["CurrentGPA"];
                        $m_IsPublish= $row["m_IsPublish"];
                        echo '<tr><td>' . $row["Term"] .
                            '</td><td>' . $row["PantherID"] .
                            '</td><td>' . $row["Name"] .
                            //'</td><td>' . $m_Courses .
                            '</td><td>' . $row["StartTerm"] .
                            '</td><td>' . $row["Semesters"] .
                            // '</td><td>' . $m_advisor .
                            // '</td><td>' . $m_currentinstructor .
                            '</td><td>' . $m_CurrentGPA ;
                            //'</td><td>' . $m_preferencefacultymembers ;
                         if(m_IsPublish=='1')
                         {
                                echo   '</td><td>' . $m_status;
                         }
                         else
                         {
                             echo   '</td><td>' . 'Pending';
                         }
                         echo '</td><td><a href="GTAApplicationregisterview.php?id=' . $row["GAApplicationID"] . ' "\">View</a>';
                        if($m_status =='Pending') {
                            echo '</td><td><a href="GTAApplicationregister.php?id= ' . $row["GAApplicationID"] . '" >Update</a>';
                        }
                        else
                        {
                            echo '</td><td>Update';
                        }

                        echo '</td></tr>';

                    }

                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<script>
    function CustomConfirm() {
        this.render = function(dialog,op,id) {
            var winW = window.innerWidth;
            var winH = window.innerHeight;
            var dialogoverlay = document.getElementById('dialogoverlay');
            var dialogbox = document.getElementById('dialogbox');
            dialogoverlay.style.display = "block";
            dialogoverlay.style.height = winH+"px";
            dialogbox.style.left = (winW/2) - (550 * .5)+"px";
            dialogbox.style.top = "100px";
            dialogbox.style.display = "block";

            document.getElementById('dialogboxhead').innerHTML = "Confirm your action";
            document.getElementById('dialogboxbody').innerHTML = dialog;
            document.getElementById('dialogboxfoot').innerHTML ='<button onclick="Confirm.yes(\''+op+'\',\''+id+'\')">Yes</button>        <button onclick="Confirm.no()">No</button>';

        }
        this.no = function() {
            document.getElementById('dialogbox').style.display = "none";
            document.getElementById('dialogoverlay').style.display = "none";
            //location.href="GTAApplicationregister.php";

        }
        this.yes = function(op,id) {
            //if(op == "delete_post") {
            //deletePost(id);
            //}
            document.getElementById('dialogbox').style.display = "none";
            document.getElementById('dialogoverlay').style.display = "none";
            location.href="GTAApplicationregister.php";

        }
    }
    var Confirm = new CustomConfirm();         ﻿
</script>