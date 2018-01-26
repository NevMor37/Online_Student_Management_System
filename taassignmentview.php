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
        if($i==0)
        {
            if(!isset($_SESSION['tatermid']) || $_SESSION['tatermid'] == '' || $_SESSION['tatermid'] === 'all')
            {
                $_SESSION['tatermid']=$termarray[$i]['Termid'];
            }
            if(!isset($_SESSION['emailtermid']) || $_SESSION['emailtermid'] == '')
            {
                $_SESSION['emailtermid']=$termarray[$i]['Termid'];
            }
        }
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


$m_termid = $_SESSION['tatermid'];
//echo $m_termid;
$m_emailtermid = $_SESSION['emailtermid'];


?>

<?php
    if(isset($_SESSION['message']))
    {
         echo "<div id='error_msg'>".$_SESSION['message']."</div>";
         unset($_SESSION['message']);
    }
?>
<div>
    <a href="taassignmentregister.php">Add New Assignment</a><br>
</div>
<div>
    <tr>
        <td>select a term you want to view:</td>
        <td>
            <select name="termid" id="termid" >
                <?php
                if (isset($_SESSION['tatermid']) && $_SESSION['tatermid'] != '')
                {
                    $m_termid = $_SESSION['tatermid'];
                }
                foreach ($termarray as $arr)
                {
                    $p_Termid = $arr["Termid"];
                    $p_Term = $arr["Term"];
                    //echo '$p_Termid'.$p_Termid;
                    echo '$m_Termid:'.$m_termid;
                    echo "<option value='$p_Termid'";

                    if($m_termid==$p_Termid)
                    {
                        echo " selected ";
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
        <td><input type="submit" name="option_submit" id="option_submit" value="Submit" onclick="optionsubmit()"></td>
    </tr>
    <br>
    <tr>
        <td>select a term you want to send email:</td>
        <td>
            <select name="emailtermid" id="emailtermid" >
                <?php
                if (isset($_SESSION['emailtermid']) && $_SESSION['emailtermid'] != '')
                {
                    $m_termid = $_SESSION['emailtermid'];
                }
                foreach ($termarray as $arr)
                {
                    $p_Termid = $arr["Termid"];
                    $p_Term = $arr["Term"];
                    //echo '$p_Termid'.$p_Termid;
                    echo '$m_Termid:'.$m_termid;
                    echo "<option value='$p_Termid'";

                    if($m_emailtermid==$p_Termid)
                    {
                        echo " selected ";
                    }
                    echo  "    >
                            $p_Term
                            </option>";
                }
                ?>
            </select>
        </td>
    </tr>
    <br>
    <tr>
        <td>Course Number Range:</td>
    </tr>
    <tr>
        <td><input type="number" step="1" min="1" max="9999" name="coursestartnumber" id="coursestartnumber" class="textInput"   value="1" ></td>
        <td> to </td>
        <td><input type="number" step="1" min="1" max="9999" name="courseendnumber" id="courseendnumber" class="textInput"   value="9999" ></td>
    </tr>
    <br>
    <tr>
        <td>Would you like to send mail to the course in selected term?</td>
    </tr>
    <tr>
        <td><input type="submit" name="mail_submit" id="mail_submit" value="SendMail" onclick="SendTermcoursemail()"></td>
    </tr>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body"  style="overflow:auto">
                <table width="100%" class="table table-striped table-bordered table-hover" id="taassignment-view">
                    <thead>
                    <!-- Head -->
                        <tr>
                            <th>Course</th>
                            <th>CRN</th>
                            <th>Title</th>
                            <th>Instructor</th>
                            <th>Assignment</th>
                            <th>Operate</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $currterm = $_SESSION['tatermid'];
                            $applicantarray = array();
                        $sql = " select ga.GAApplicationID as id, 
                                        CONCAT(coalesce(ga.FirstName,' ') , IF(ga.MiddleName = '', ' ', IFNULL(ga.MiddleName,' ')),coalesce(ga.LastName,' ')) as name,
                                        ga.PantherID as pantherid,
                                        ga.Email as email,
                                        ga.Termid as termid,
                                        ga.Courses as course,
                                        ga.Degree as degree
                                        from tbl_gaapplication as ga 
                                        where ga.Status<>''
                                 ";


                                $sql=$sql. " and ga.Termid = $currterm ";

                            $result = mysqli_query($db, $sql);

                            if ($result->num_rows > 0)
                            {
                                $i = 0;
                                // output data of each row
                                while ($row = $result->fetch_assoc())
                                {
                                    $applicantarray[$i] = array();
                                    $applicantarray[$i]['id'] = $row["id"];
                                    $applicantarray[$i]['name'] = $row["name"];
                                    $applicantarray[$i]['email'] = $row["email"];
                                    $applicantarray[$i]['termid'] = $row["termid"];
                                    $applicantarray[$i]['course'] = $row["course"];
                                    $applicantarray[$i]['degree'] = $row["degree"];
                                    $i = $i + 1;
                                }
                            }

                            $sql = "
                                SELECT   
                                    ti.TAAssignmentID as id,
                                    ti.CourseID as courseid,
                                    ter.Term as term,
                                    tc.CRN as crn,
                                    tc.Subject as subject,                                       
                                    tc.Course as course,
                                    tc.Title as title,
                                    sc.Actual as actual,
                                    sc.CrosslistActual as crosslistactual,
                                    sc.Facultyid as facultyid,
                                    sc.Termid,
                                    ti.TANumber as tanumber,
                                    ti.GANumber as ganumber,
                                    ti.LANumber as lanumber,
                                    te.TAAssignmentExtraID as taassignmentextraid,
                                    te.Instance as instance,
                                    te.Assignment as assignment,
                                    te.PantherID as pantherid,
                                    CONCAT(coalesce(ga.FirstName,' ') , IF(ga.MiddleName = '', ' ', IFNULL(ga.MiddleName,' ')),coalesce(ga.LastName,' ')) as name,
                                    ga.Degree as degree,
                                    ga.email as email
                                    FROM tbl_taassignment_info as ti
                                    left JOIN tbl_course as tc on tc.Courseid=ti.CourseID
                                    left JOIN tbl_schedule as sc on tc.Courseid=sc.CourseID and sc.Instance =1
                                    LEFT JOIN tbl_term as ter on ter.Termid = sc.Termid
                                    left join tbl_taassignment_extra as te on te.TAAssignmentID = ti.TAAssignmentID 
                                    LEFT JOIN tbl_gaapplication as ga on ga.PantherID = te.PantherID and ga.TermID=ter.Termid
                                    ";

                            $sql = $sql." where sc.Termid =  $currterm ";
                            $sql = $sql. " order by ter.Startday desc,tc.Course,tc.CRN,te.Assignment,te.Instance
                                            ";
                           //echo $sql;
                        $result = mysqli_query($db, $sql);

                        $m_id='';
                        $m_courseid='';
                        $m_term='';
                        $m_crn='';
                        $m_subject='';
                        $m_course='';
                        $m_title='';
                        $m_actual='';
                        $m_crosslistactual = '';
                        $m_facultyName = '';
                        $m_tanumber='';
                        $m_ganumber='';
                        $m_lanumber='';
                        $m_taassignmentextraid='';
                        $m_instance='';
                        $m_assignment='';
                        $m_pantherid='';
                        $m_name='';
                        $m_email='';
                        $m_degree ='';
                        $courseassignment='';

                        while($row=mysqli_fetch_assoc($result))
                        {
                            $id = $row["id"];
                            $courseid = $row["courseid"];
                            $term =$row["term"];
                            $crn =$row["crn"];
                            $subject =$row["subject"];
                            $course = $row["course"];
                            $title = $row["title"];
                            $actual = $row["actual"];
                            $crosslistactual = $row["crosslistactual"];
                            $facultyid = $row["facultyid"];
                            $tanumber = $row["tanumber"];
                            $ganumber = $row["ganumber"];
                            $lanumber = $row["lanumber"];
                            $taassignmentextraid = $row["taassignmentextraid"];
                            $instance = $row["instance"];
                            $assignment = $row["assignment"];
                            $pantherid = $row["pantherid"];
                            $name = $row["name"];
                            $email = $row["email"];
                            $degree= $row["degree"];
                //            echo 'id:';
                //            echo $id;
                //            echo 'end.';
                            $m_instructor ='';
                            foreach ($instructorarray as $arr)
                            {
                                $p_email = $arr["email"];
                                $instructorname = $arr["Name"];
                                if($facultyid==$p_email)
                                {
                                    $m_instructor=$instructorname;
                                }
                            }

                            if($m_id=='')
                            {
                                $m_id = $id;
                                $m_courseid=$courseid;
                                $m_term=$term;
                                $m_crn=$crn;
                                $m_subject=$subject;
                                $m_course=$course;
                                $m_title=$title;
                                $m_actual=$actual;
                                $m_crosslistactual = $crosslistactual;
                                $m_facultyName = $m_instructor;
                                $m_tanumber=$tanumber;
                                $m_ganumber=$ganumber;
                                $m_lanumber=$lanumber;
                                $m_taassignmentextraid=$taassignmentextraid;
                                $m_assignment = $assignment;
                                $m_instance = $instance;
                                $m_pantherid=$pantherid;
                                $m_name = $name;
                                $m_email=$email;
                                $m_degree = $degree;

                                $courseassignment='';
                                if(!empty($assignment))
                                {
                                    $courseassignment = $assignment.':'.$name.'('.$degree.')';
                                }
                            }
                            elseif ($m_id == $id)
                            {
                                if($m_assignment == $assignment)
                                {
                                    $courseassignment = $courseassignment .','.$name. '('.$degree.')';
                                }
                                else
                                {
                                    $courseassignment = $courseassignment .' '.$assignment.':'.$name. '('.$degree.')';
                                }
                                $m_assignment = $assignment;
                                $m_instance = $instance;
                                $m_name = $name;
                                $m_degree = $degree;
                            }
                            else
                            {
                                //                echo 'id null;';
                                echo '<tr>' .
//                                '<td>' . $m_id .
//                                '<td>' . $courseid .
//                                    '<td>' . $m_term .
//                                    '</td><td>' . $m_subject .
                                    '</td><td>' . $m_course .
                                    '</td><td>' . $m_crn .
                                    '</td><td>' . $m_title .
                                    '</td><td>' . $m_facultyName .
                                    '</td><td>' . $courseassignment .
                                    '</td><td>' .
                                    '<input type="button" value="Update" name ="btnUpdate" onclick="Redirect(this,'. $m_id.')"/><br>' .
                                    '<input type="button" value="Remove" name ="btnRemove" onclick="IsDelete(this,'. $m_id.')"/><br>' .
                                    '<input type="button" value="Sendmail" name ="btnSendmail" onclick="Sendcoursemail(this,'. $m_id.')"/><br>' .
                                    '</td></tr>';

                                $m_id = $id;

                                $m_courseid = $courseid;
                                $m_course = $course;
                                $m_crn=$crn;
                                $m_title = $title;
                                $m_facultyName = $m_instructor;
                                $m_tanumber = $tanumber;
                                $m_ganumber = $ganumber;
                                $m_lanumber = $lanumber;
                                $m_taassignmentextraid = $taassignmentextraid;
                                $m_instance = $instance;
                                $m_assignment = $assignment;
                                $m_pantherid = $pantherid;
                                $m_name = $name;
                                $m_email = $email;

                                if(!empty($assignment))
                                {
                                    $courseassignment = $assignment.':'.$name.'('.$degree.')';
                                }
                                else
                                {
                                    $courseassignment='';
                                }
                            }
                        }
                        if(!empty($id)) {
                            echo '<tr>' .
                        //                               '<td>' . $id .
                        //                                '<td>' . $courseid .
                        //                            '<td>' . $term .
                        //                            '</td><td>' . $subject .
                                '</td><td>' . $course .
                                '</td><td>' . $crn .
                                '</td><td>' . $title .
                                '</td><td>' . $m_facultyName .
                                '</td><td>' . $courseassignment .
                                '</td><td>' .
                                '<input type="button" value="Update" name ="btnUpdate" onclick="Redirect(this,' . $id . ')"/><br>' .
                                '<input type="button" value="Remove" name ="btnRemove" onclick="IsDelete(this,' . $id . ')"/><br>' .
                                '<input type="button" value="Sendmail" name ="btnSendmail" onclick="Sendcoursemail(this,' . $id . ')"/><br>' .
                                '</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>

    function optionsubmit ()
    {
        //alert("option");
        //var status=$('option:selected',this.termid).text();
        var termid= $('#termid').find('option:selected').attr('value');
        var name ='';
        var value='';
        //alert(termid);
        name = 'tatermid';
        value = termid;
            var termidreturn = changesession(name,value);
            // alert(termidreturn);

//            if(termidreturn)
//            {
                window.location.reload();
//            }

    }

    function  changesession(name,value)
    {
        var returnvalue =0;
        $.ajax({
            url:"taassignmentchangesession.php", //the page containing php script
            type: "POST", //request type
            async: false,
            data:{name:name,value:value},
            //data:{id:m_id},
            success:function(data)
            {
                //alert(data);
                if(data =='success')
                {
                    returnvalue=1;
                }
                else
                {
                    returnvalue= 0;
                }
            }
        });
        //alert('name:'+name+'value:'+value+'returnvalue:'+returnvalue);
        return returnvalue;
    }

    function IsDelete(element,id)
    {//（true or false）
        if(confirm("Do you want to delete this record？"))
        {//
            location.href="taassignmentremove.php?id="+id;
        }
    }

    function Redirect(element,id)
    {
            location.href="taassignmentregister.php?id="+id;
    }
    function Sendcoursemail(element,id)
    {
        if(confirm("Do you want to send mail？"))
        {//
            //location.href = "taassignmentsendmail.php?id=" + id;
            //var returnvalue = Sendemail(id);
            var m_returnvalue= null;
            var returnvalue =null;
            Sendemail(id,function(m_returnvalue) {
                returnvalue=  m_returnvalue
            });
            //alert(returnvalue);
            if(returnvalue ==1)
            {
                alert('success!');
            }
            else
            {
                alert('fail');
            }
        }
    }

    function SendTermcoursemail()
    {
        var emailtermid =document.getElementById('emailtermid');
        var selectedmailtermvalue =emailtermid.options[emailtermid.selectedIndex].value;
        var selectedmailtermText =emailtermid.options[emailtermid.selectedIndex].text;
        var coursestartnumber=document.getElementById('coursestartnumber').value;
        var courseendnumber=document.getElementById('courseendnumber').value;
        if(coursestartnumber > courseendnumber)
        {
            alert( 'Course Start Number should not more than Course End Number!');
            return
        }
        //var message = 'Do you want to send mail to the course in this term？';
        var message = '';
        message = message+'Do you want to send mail to';
        message=message+ ' Course ' +coursestartnumber;
        message=message+ ' to ';
        message=message+ ' Course ' +courseendnumber;
        message=message+ ' in Term ' +selectedmailtermText;
        message=message+ ' in Termid ' +selectedmailtermvalue;
        message=message+ '?';
        if(confirm(message))
        {//
            //var returnvalue = Sendemail(id);
            //alert('TAAssignmentArray begin ');
            //alert('termid:'+selectedmailtermvalue+'coursestartnumber:'+coursestartnumber+'courseendnumber:'+courseendnumber);
            var TAAssignmentArray = null;
            $.ajax({
                url:"taassignmentfetchassignment.php", //the page containing php script
                type: "POST", //request type
                async: false,
                data:{termid:selectedmailtermvalue,coursestartnumber:coursestartnumber,courseendnumber:courseendnumber},
                success:function(data)
                {
                    //alert(data);
                    TAAssignmentArray = jQuery.parseJSON(data);
                }
            });
           //alert('TAAssignmentArray end');
            var ReturnMsg = '';
            for (var key in TAAssignmentArray)
            {
                var TAAssignmentID = TAAssignmentArray[key].TAAssignmentID;
                var Subject = TAAssignmentArray[key].Subject;
                var Course = TAAssignmentArray[key].Course;
                var Instructor = TAAssignmentArray[key].Instructor;
                //alert(TAAssignmentID);
                var m_returnvalue= null;
                var returnvalue =null;
                Sendemail(TAAssignmentID,function(m_returnvalue) {
                    returnvalue=  m_returnvalue
                });
                //alert(returnvalue);
                if(returnvalue ==1)
                {
                    ReturnMsg = ReturnMsg+Subject+Course+' ' +Instructor+' success.'+'\n';
                }
                else
                {
                   ReturnMsg = ReturnMsg+Subject+Course+' ' +Instructor+' fail!'+'\n';
                    //ReturnMsg = ReturnMsg+TAAssignmentID+' '+Subject+Course+' fail!';
                }
            }
            alert(ReturnMsg);
        }
    }

    function Sendemail(m_id,callback)
    {
        //alert('m_id:'+m_id);
        var returnvalue =0;
        $.ajax({
            url:"taassignmentsendmail.php", //the page containing php script
            type: "POST", //request type
            async: false,
            data:{id:m_id},
            success:function(data)
            {
                //var returnvalue =0;
                //alert(data);
                //alert(data.toString() =='success');
                if(data.toString() =='success')
                {
                    returnvalue=1;
                }
                else
                {
                    returnvalue= 0;
                }
                //alert('Sendemail'+'returnvalue:'+returnvalue);
                callback(returnvalue);
            }
        });

    }





</script>

<style>
    label {
        display: inherit;
        width: 12em;
    }
</style>