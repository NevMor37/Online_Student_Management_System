
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
//<?php
//session_start();
//
////connect to database
//$db=mysqli_connect("localhost","root","hu1015","authentication");

$termarray = array();

$sql = "select Termid ,Term ,StartDay , EndDay 
                from tbl_term 
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
        $i = $i + 1;
    }
}
if(!isset($_SESSION['termid']) || $_SESSION['termid'] == '')
{
     $_SESSION['termid']='all';
}
if(!isset($_SESSION['starttermid']) || $_SESSION['starttermid'] == '')
{
    $_SESSION['starttermid']='all';
}
if(!isset($_SESSION['degree']) || $_SESSION['degree'] == '')
{
    $_SESSION['degree']='all';
}
//echo 'termid:'.$_SESSION['termid'].'starttermid:'.$_SESSION['starttermid'].'degree:'.$_SESSION['degree'];

?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<div id="dialogoverlay" style="display: none; opacity: .8; position: fixed; top: 0px; left: 0px; background: #FFF; width: 100%; z-index: 10;"></div>
<div id="dialogbox" style="display: none; position: fixed; background: #000; border-radius:7px; width:550px; z-index: 10;">
    <div style="background:#FFF; margin:8px;">
        <div id="dialogboxhead" style="background: #666; font-size:19px; padding:10px; color:#CCC;"></div>
        <div id="dialogboxbody" style="background:#333; padding:20px; color:#FFF;"></div>
        <div id="dialogboxfoot" style="background: #666; padding:10px; text-align:right;"></div>
    </div>
</div>

<p>Add New Graduate Assistantship Application</p>
<p id="post_1">
    <input type="submit" value="Apply" style =" cursor: default;" onclick="Confirm.render('Do you want to begin this application?','delete_post','post_1')"/>
</p>
<div>
<tr>
    <td>select a term you want to view:</td>
    <td>
        <select name="termid" id="termid" >
            <?php
            if (isset($_SESSION['termid']) && $_SESSION['termid'] != '')
            {
                $m_termid = $_SESSION['termid'];
            }
//            echo "<option value='all' ";
//            if($m_termid=="all")
//            {
//                echo " selected ";
//            }
//            echo " >All Term</option>";
            foreach ($termarray as $arr)
            {
                $p_Termid = $arr["Termid"];
                $p_Term = $arr["Term"];
                echo '$p_Termid'.$p_Termid;
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
    </br>
<tr>
    <td>select a start term you want to view:</td>
    <td>
        <select name="starttermid" id="starttermid" >
            <?php
            if (isset($_SESSION['starttermid']) && $_SESSION['starttermid'] != '')
            {
                $m_starttermid = $_SESSION['starttermid'];
            }
            echo "<option value='all' ";
            if($m_starttermid=="all")
            {
                echo " selected ";
            }
            echo " >All Term</option>";
            foreach ($termarray as $arr)
            {
                $p_Termid = $arr["Termid"];
                $p_Term = $arr["Term"];
                echo '$p_Termid'.$p_Termid;
                echo "<option value='$p_Termid'";

                if($m_starttermid==$p_Termid)
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
    </br>
<tr>
    <td>select a degree you want to view:</td>
    <td>
        <select name="degree" id="degree" <?php if($readonly ==true){echo "disabled";} ?> >
            <?php
            if (isset($_SESSION['degree']) && $_SESSION['degree'] != '')
            {
                $m_termid = $_SESSION['degree'];
            }
            echo "<option value='all' ";
            if($m_termid=="all")
            {
                echo " selected ";
            }
            echo " >All Students</option>";
            echo "<option value='MS' ";
            if($m_termid=="MS")
            {
                echo " selected ";
            }
            echo " >MS</option>";
            echo "<option value='PHD' ";
            if($m_termid=="PHD")
            {
                echo " selected ";
            }
            echo " >PHD</option>";

            ?>
        </select>
    </td>
</tr>
    </br>
<tr>
    <td><input type="submit" name="option_submit" id="option_submit" value="Submit" onclick=""></td>
</tr>
<br>
<tr>
    <td><input type="submit" name="option_ToWaitingList" id="option_ToWaitingList" value="ToWaitingList" onclick="" style="visibility: hidden;"></td>
    <td><input type="submit" name="option_ToPending" id="option_ToPending" value="ToPending" onclick="" style="visibility: hidden;"></td>
    <td><input type="submit" name="option_ToReject" id="option_ToReject" value="ToReject" onclick="" style="visibility: hidden;"></td>
</tr>
    <br>
    <tr>
       <td><input type="submit" name="option_Publish" id="option_Publish" value="Publish" onclick="" style="visibility: visible;"></td>
        <td><input type="submit" name="option_UnPublish" id="option_UnPublish" value="UnPublish" onclick="" style="visibility: visible;"></td>
    </tr>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body"  style="overflow:auto">
                <table width="100%" class="table table-striped table-bordered table-hover" id="GTAApplicationadmin-view">
                    <thead>
                    <!-- Head -->
                    <tr>
                        <th>Name</th><th>Degree</th>
                        <th>StartTerm</th>
                        <th>Advisor</th>
                        <th>GPA</th><th>Status</th><th>Accept/Decline</th>
                        <th>Operations</th>
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


                   // echo 'termid:'.$_SESSION['termid'].'starttermid:'.$_SESSION['starttermid'].'degree:'.$_SESSION['degree'];
                    //echo '<br>';
                    $cur_termid = $_SESSION['termid'];
                    $cur_starttermid = $_SESSION['starttermid'];
                    $cur_degree = $_SESSION['degree'];
                    $isall =false;
                    if(($cur_termid=='all') && ($cur_starttermid=='all') && ($cur_degree=='all'))
                    {
                        $isall=true;
                    }
                    $instructortermarray = array();
                    //$m_instructortermarray= array();
                    $instructorsql = " 
                         select distinct(sc.Facultyid) as Facultyid
                        from tbl_schedule  as sc 
                        where  Termid='$cur_termid' and  sc.Facultyid<>'' 
                        and sc.Facultyid not in (select email from tbl_faculty_info )
         ";

                    $instructorresult = mysqli_query($db, $instructorsql);

                    if ($instructorresult->num_rows > 0) {
                        $i = 0;
                        while ($instructorrow = $instructorresult->fetch_assoc())
                        {
                            $instructortermarray[$i] = array();
                            $instructortermarray[$i]['email'] = $instructorrow["Facultyid"];
                            //array_push($m_instructortermarray,$instructorrow["Facultyid"]);
                            $i=$i+1;
                        }
                    }


                    $sql = "
                            select ga.GAApplicationID as GAApplicationID,ga.TermID as TermID,te.Term as Term,ga.PantherID as PantherID,
                                CONCAT(coalesce(ga.FirstName,' ') , IF(ga.MiddleName = '', ' ', IFNULL(ga.MiddleName,' ')),coalesce(ga.LastName,' ')) as Name,
                                ga.Email as email,ga.Courses as Courses,ga.Degree as Degree,te2.Term as StartTerm, ga.Semesters as Semesters,
                                ga.Advisor as Advisor,ga.CurrentInstructor as CurrentInstructor,ga.CurrentGPA as CurrentGPA,
                                ga.Preferencefacultymembers as Preferencefacultymembers,ga.Status as Status,ga.IsAccept as IsAccept
                                from tbl_gaapplication as ga
                                left JOIN  tbl_term as te on te.Termid = ga.TermID
                                left JOIN  tbl_term as te2 on te2.Termid = ga.StartTerm 
                            ";

                    if(!$isall)
                    {
                        $sql = $sql . " where ";
                        if($cur_termid !='all')
                        {
                            $sql = $sql . " ga.TermID = ".$cur_termid ."  ";

                        }
                        else
                        {
                            $sql = $sql . " ga.TermID <> -1  ";
                        }
                        if($cur_starttermid !='all')
                        {
                            $sql = $sql . " and ga.StartTerm = ".$cur_starttermid ."  ";
                        }
                        else
                        {
                            $sql = $sql . " and ga.StartTerm <> -1  ";
                        }
                        if($cur_degree !='all')
                        {
                            $sql = $sql . " and ga.Degree = '$cur_degree'";
                        }
                        else
                        {
                            $sql = $sql . " and ga.Degree <> '' ";
                        }
                        $m_instructortermarray = array_column($instructortermarray, 'email');
                       // echo var_dump($m_instructortermarray);
                        $sql = $sql . " and ga.Email not in ( '" . implode($m_instructortermarray, "', '") . "' )";

                    }


                    $sql = $sql." order by ga.Degree desc,te2.Term desc";

                    //echo $sql;
                    $result = mysqli_query($db, $sql);

                    while($row=mysqli_fetch_assoc($result))
                    {
                        $Courses=$row["Courses"];
                        $Course = explode(",", $Courses);
                        $m_Courses='';
                        $CurrentGPA = $row["CurrentGPA"];
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
                        $IsAccept=  $row["IsAccept"];
                        $m_IsAccept='';
                        if($IsAccept=='1')
                        {
                            $m_IsAccept='Accept';
                        }
                        else if($IsAccept=='-1')
                        {
                            $m_IsAccept='Reject';
                        }
                        echo '<tr>'.
                           // '<td>' . $row["Term"] .
                            '<td>' . '<input type="checkbox" value="'.$row["GAApplicationID"].'"></label>'.
                            '<label><a href="#" class="hover" id="' . $row["GAApplicationID"].'">'.$row["Name"]. '</a></label>' .
                            '</td><td>' . $row["Degree"] .
                            '</td><td>' . $row["StartTerm"] .
                            //'</td><td>' . $row["Semesters"] .
                            '</td><td>' . $m_advisor .
                            '</td><td>' . $row["CurrentGPA"] .
                            '</td><td>' . $row["Status"] .
                            '</td><td>'.$m_IsAccept.
                            '</td><td>'.
                            '<input type="button" value="A" name ="btnApproved" onclick="getId(this,'. $row["GAApplicationID"].')" label="Approved"/>'.
                            '<input type="button" value="W" name ="btnWaitinglist" onclick="getId(this,'. $row["GAApplicationID"].')" label="Waitinglist" />'.
                            '<input type="button" value="P" name ="btnPending" onclick="getId(this,'. $row["GAApplicationID"].')" label="Pending" />'.
                            '<input type="button" value="Rj" name ="btnReject" onclick="getId(this,'. $row["GAApplicationID"].')" label="Reject" /><br>';
                        echo '<input type="button" value="Rm" name ="btnRemove" onclick="IsDelete(this,'. $row["GAApplicationID"].')" label="Remove"/>';
                        echo "<input type='button' value='Up' name ='btnUpdate' onclick=\"window.location.href='GTAApplicationadminregister.php?id=".$row["GAApplicationID"]."'\">
                                <input type='button' value='Vi' name ='btnView' onclick=\"window.location.href='GTAApplicationadminregisterview.php?id=". $row["GAApplicationID"]."'\"/>
                                <br>";
                        echo '<input type="button" value="Emp" name ="btnEmpty" onclick="updateAccept(this,'. $row["GAApplicationID"].')" label="Empty"/>';
                        echo '<input type="button" value="Acc" name ="btnAccept" onclick="updateAccept(this,'. $row["GAApplicationID"].')" label="Accept"/>';
                        echo '<input type="button" value="Rej" name ="btnNotAccept" onclick="updateAccept(this,'. $row["GAApplicationID"].')" label="Reject"/>';
                            '</td>'.
                            '</tr>';


                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>




<script>
    $(document).ready(function(){

        $('.hover').tooltip({
            title: fetchData,
            html: true,
            placement: 'right'
        });

        function fetchData()
        {
            var fetch_data = '';
            var element = $(this);
            var id = element.attr("id");
            $.ajax({
                url:"GTAApplicationadminfetch.php",
                method:"POST",
                async: false,
                data:{id:id},
                success:function(data)
                {
                    fetch_data = data;

                }
            });
            return fetch_data;
        }

        $('#option_submit').click(function(){
            //var status=$('option:selected',this.termid).text();
            alert('option_submit');
            var termid= $('#termid').find('option:selected').attr('value');
           //alert(termid);
           var starttermid =  $('#starttermid').find('option:selected').attr('value');
           // alert(starttermid);
           var degree =  $('#degree').find('option:selected').attr('value');
            //alert(degree);
            //alert('termid:'+termid+'starttermid:'+starttermid+'degree:'+degree);
            var name ='';
            var value='';
            name = 'termid';
            value = termid;
            var termidreturn = changesession(name,value);
           // alert(termidreturn);

            name = 'starttermid';
            value = starttermid;
            var starttermidreturn = changesession(name,value);

            name = 'degree';
            value = degree;
            var degreereturn = changesession(name,value);
//            if(termidreturn || starttermidreturn || degreereturn)
//            {
                window.location.reload();
            //}

        });

        function  changesession(name,value)
        {
            var returnvalue =0;
            $.ajax({
                url:"GTAApplicationadminchangesession.php", //the page containing php script
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
    });

    $(":checkbox").on('click', function () {
        var checkbox_value = "";
        var count = 0;
        $(":checkbox").each(function () {
            var ischecked = $(this).is(":checked");
            if (ischecked) {
                count += 1;

                checkbox_value += $(this).val() + "|";
            }
        });
        if(count)
        {
            $("#option_ToWaitingList").attr("style", "visibility: visible");
            $("#option_ToPending").attr("style", "visibility: visible");
            $("#option_ToReject").attr("style", "visibility: visible");
        }
        else
        {
            $("#option_ToWaitingList").attr("style", "visibility: hidden");
            $("#option_ToPending").attr("style", "visibility: hidden");
            $("#option_ToReject").attr("style", "visibility: hidden");
        }
//         alert(checkbox_value);
    });

    $('#option_ToWaitingList').on('click', function (e) {
        var count = 0;
        var checkbox_value = "";
        $(":checkbox").each(function () {
            var ischecked = $(this).is(":checked");
            if (ischecked) {
                count += 1;

                checkbox_value += $(this).val() + ",";
            }
        });
        //alert(checkbox_value);
        var status='Waitinglist';
        updatestatus(checkbox_value,status,true);

    });

    $('#option_ToPending').on('click', function (e) {
        var count = 0;
        var checkbox_value = "";
        $(":checkbox").each(function () {
            var ischecked = $(this).is(":checked");
            if (ischecked) {
                count += 1;

                checkbox_value += $(this).val() + ",";
            }
        });
        //alert(checkbox_value);
        var status='Pending';
        updatestatus(checkbox_value,status,true);

    });


    $('#option_ToReject').on('click', function (e) {
        var count = 0;
        var checkbox_value = "";
        $(":checkbox").each(function () {
            var ischecked = $(this).is(":checked");
            if (ischecked) {
                count += 1;

                checkbox_value += $(this).val() + ",";
            }
        });
        //alert(checkbox_value);
        var status='Reject';
        updatestatus(checkbox_value,status,true);

    });

    function updatestatus(checkbox_value,status,Batch) {
        $.ajax({
            type: 'post',
            url: './changeapplicantstatus.php',
            data: { ApplicantIDs:checkbox_value,Status:status,Batch:Batch},
            success: function (php_script_response) {
                var responseText = php_script_response;
                if (/success/i.test(responseText))
                {

//     			  $('#modal_content').html(responseText);
//     			  $('#myModal1').modal('show');
                    $("#facselect").attr("style", "visibility: hidden");
                    $("#response").attr("Style", "color: green;");
                    $('#response').html(responseText);
                    window.location.reload();
                }
                else
                {
//             	  $('#modal_content').html(responseText);
//     			  $('#modal_content').html('Application is already assigned to the selected faculty for evaluation');
//     			  $('#myModal1').modal('show');
                    $("#response").attr("class", "color: red;");
                    $('#response').html(responseText);
                }
            }
        });
    }

    $('#option_Publish').on('click', function (e) {
        var count = 0;

        var e = document.getElementById("termid");
        var stre = e.options[e.selectedIndex].value;
        var TermID =stre;
        var status='1';
        //alert('stre:'+stre);
        //alert('option_Publish');
        updatepublishstatus(TermID,status,true);
        //alert('option_Publish finish');
    });

    $('#option_UnPublish').on('click', function (e) {
        var count = 0;
        var e = document.getElementById("termid");
        var stre = e.options[e.selectedIndex].value;
        var TermID =stre;
        var status='0';
        //alert('option_UnPublish');
        updatepublishstatus(TermID,status,true);

    });

    function updatepublishstatus(TermID,status) {
        $.ajax({
            type: 'post',
            url: './changeapplicantpublishstatus.php',
            data: { TermID:TermID,Status:status},
            success: function (php_script_response) {
                var responseText = php_script_response;
                //alert(TermID);
                //alert(responseText);
                if (/success/i.test(responseText))
                {

//     			  $('#modal_content').html(responseText);
//     			  $('#myModal1').modal('show');
                    $("#facselect").attr("style", "visibility: hidden");
                    $("#response").attr("Style", "color: green;");
                    $('#response').html(responseText);
                    //window.location.reload();
                }
                else
                {
//             	  $('#modal_content').html(responseText);
//     			  $('#modal_content').html('Application is already assigned to the selected faculty for evaluation');
//     			  $('#myModal1').modal('show');
                    $("#response").attr("class", "color: red;");
                    $('#response').html(responseText);
                }
            }
        });
    }

    function  getId(element,id)
    {
//        alert("row:" + element.parentNode.parentNode.rowIndex +
//            " - column:" + element.parentNode.cellIndex+
//            " - value:"+element.value+
//            " - id:"+id
//            );


        var m_operate = element.value;
        var m_id = id;
        var fetch_data = '';
        if(m_operate=='A')
        {
            m_operate='Approved';
        }
        else if(m_operate =='W')
        {
            m_operate='Waitinglist';
        }
        else if(m_operate=='P')
        {
            m_operate='Pending';
        }
        else if(m_operate=='Rj')
        {
            m_operate='Reject';
        }



//        alert("row:" + element.parentNode.parentNode.rowIndex +
//            " - column:" + element.parentNode.cellIndex+
//            " - value:"+m_operate+
//            " - id:"+m_id
//        );

        $.ajax({
            url:"GTAApplicationadminupdatestatus.php", //the page containing php script
            type: "POST", //request type
            async: false,
            data:{id:m_id,operate:m_operate},
            //data:{id:m_id},
            success:function(data)
            {
                //fetch_data = data;
                //alert( data);
                if(data =='success')
                {
                    window.location.reload();
                }
            }

           //return fetch_data;
        });
    }

    function  updateAccept(element,id)
    {
        var m_operate = element.value;
        var m_id = id;
        var fetch_data = '';
        //alert(m_id);
        if(m_operate=='Acc')
        {
            m_operate='1';
        }
        else if(m_operate =='Rej')
        {
            m_operate='-1';
        }
        else if(m_operate =='Emp')
        {
            m_operate='0';
        }


        $.ajax({
            url:"GTAApplicationupdateAccept.php", //the page containing php script
            type: "POST", //request type
            async: false,
            data:{id:m_id,isaccept:m_operate},
            //data:{id:id},
            success:function(data)
            {
                if(/success/i.test(data))
                {
                    window.location.reload();
                }
            }

            //return fetch_data;
        });
    }

    function IsNew()
    {//（true or false）
        if(confirm("Are you a new graduate student？"))
        {//
            location.href="GTAApplicationadminnewregister.php";
        }
        else
        {
            location.href="GTAApplicationadminregister.php";
        }
    }

    function IsDelete(element,id)
    {//（true or false）
        if(confirm("Do you want to delete this record？"))
        {//
            location.href="GTAApplicationadminremove.php?id="+id;
        }
    }

    function Redirect(url)
    {//（true or false）
            //alert(url);
            location.href=url;
    }

//    $("#termid").change(function() {
//        $selectvalue =$('option:selected',this).attr('value');
//        $selectname=$('option:selected',this).text();
//        //alert($selectvalue);
//        var selecttermid = $selectvalue;
//       // alert(selecttermid);
//        $.ajax({
//            url:"GTAApplicationadminchangetermsession.php", //the page containing php script
//            type: "POST", //request type
//            async: false,
//            data:{termid:selecttermid},
//            //data:{id:m_id},
//            success:function(data)
//            {
//                //alert(data);
//                if(data =='success')
//                {
//                    window.location.reload();
//                }
//            }
//        });
//
//
//    }).change();


    function Submitcondition()
    {
        //alert('submit');
        var termid = this.getElementById("termid").text();
        alert(termid);
        //var starttermid =  document.getElementById('starttermid').attr('value');
        //var degree =  document.getElementById('degree').attr('value');
        //alert('termid:'termid+'starttermid:'+starttermid+'degree'+degree);
    }

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
            //location.href="GTAApplicationadminregister.php";

        }
        this.yes = function(op,id) {
            //if(op == "delete_post") {
            //deletePost(id);
            //}
            document.getElementById('dialogbox').style.display = "none";
            document.getElementById('dialogoverlay').style.display = "none";
            location.href="GTAApplicationadminregister.php";

        }
    }
    var Confirm = new CustomConfirm();         ﻿
</script>

<style>
    label {
        display: inherit;
        width: 14em;
    }
</style>