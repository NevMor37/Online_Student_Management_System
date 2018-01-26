
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



$termarray = array();

$sql = "
        select Termid ,Term ,Startday,EndDay,abs(TIMESTAMPDIFF(DAY,Startday,NOW())) as daydiff
        from tbl_term 
        order  by daydiff
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

if(!isset($_SESSION['termid']) || $_SESSION['termid'] == '')
{
     $_SESSION['termid']='all';
}
if(!isset($_SESSION['degree']) || $_SESSION['degree'] == '')
{
    $_SESSION['degree']='all';
}
if(!isset($_SESSION['facultyid']) || $_SESSION['facultyid'] == '')
{
    $_SESSION['facultyid']='all';
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

<div>
    <tr>
        <td>Please select the parameters you want to view:</td>
    </tr>
    </br>
</div>
<tr>
    <td>Term:</td>
    <td>
        <select name="termid" id="termid" >
            <?php
            if (isset($_SESSION['termid']) && $_SESSION['termid'] != '')
            {
                $m_termid = $_SESSION['termid'];
            }

            if($m_termid=="all")
            {
                echo " selected ";
            }
            foreach ($termarray as $arr)
            {
                $p_Termid = $arr["Termid"];
                $p_Term = $arr["Term"];
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
    <td>Degree:</td>
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
        <td>Facuty:</td>
        <td>
            <select name="facultyid" id="facultyid" >
                <?php
                if (isset($_SESSION['facultyid']) && $_SESSION['facultyid'] != '')
                {
                    $m_facultyid = $_SESSION['facultyid'];
                }
                echo "<option value='all'>All Faculty</option> ";
                if($m_facultyid=="all")
                {
                    echo " selected ";
                }
                foreach ($instructorarray as $arr)
                {
                    $p_id = $arr["email"];
                    $p_name = $arr["Name"];
                    echo "<option value='$p_id'";
                    if($m_facultyid==$p_id)
                    {
                        echo " selected ";
                    }
                    echo  "    >
                            $p_name
                            </option>";
                }
                ?>
            </select>
        </td>
    </tr>
    </br>
<tr>
    <td><input type="submit" name="option_submit" id="option_submit" value="Submit" onclick=""></td>
</tr>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body"  style="overflow:auto">
                <table width="100%" class="table table-striped table-bordered table-hover" id="GTAApplicationreport-view">
                    <thead>
                    <!-- Head -->
                    <tr>
                        <th>Term</th><th>Pantherid</th>
                        <th>Last Name</th>
                        <th>First Name</th><th>Degree</th>
                        <th>Email</th><th>Class</th>
                        <th>Faculty</th><th>CRN</th><th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                   // echo 'termid:'.$_SESSION['termid'].'starttermid:'.$_SESSION['starttermid'].'degree:'.$_SESSION['degree'];
                    //echo '<br>';
                    $cur_termid = $_SESSION['termid'];
                    $cur_degree = $_SESSION['degree'];
                    $cur_faculty = $_SESSION['facultyid'];
                    $sql = "
                          select DATE_FORMAT(te.Startday,'%Y%m') as Startday,ga.PantherID,ga.FirstName,ga.LastName,
                                  ga.Degree,ga.Email,cou.Course,cou.CRN,sc.Facultyid,ga.Status
                            from tbl_gaapplication as ga
                            LEFT JOIN tbl_term as te on te.Termid=ga.TermID
                            LEFT JOIN tbl_taassignment_extra as ex on ex.PantherID = ga.PantherID
                            LEFT JOIN tbl_taassignment_info as inf on inf.TAAssignmentID = ex.TAAssignmentID
                            LEFT JOIN tbl_course as cou on cou.Courseid = inf.CourseID
                            LEFT JOIN tbl_schedule as sc on sc.Courseid = cou.Courseid and sc.Termid = te.Termid 
                            and sc.Instance=1";
                    $sql=$sql ."  where ";
                    if(empty($cur_termid) || $cur_termid=='all')
                    {
                        $sql=$sql ."  ga.TermID <> -1 ";
                    }
                    else
                    {
                        $sql=$sql ."  ga.TermID =$cur_termid ";
                    }
                    $sql=$sql ." and ";
                    if(empty($cur_degree) || $cur_degree=='all')
                    {
                        $sql=$sql ."  ga.Degree <> -1 ";
                    }
                    else
                    {
                        $sql=$sql ."  ga.Degree =$cur_degree ";
                    }
                    $sql=$sql ." and ";
                    if(empty($cur_faculty) || $cur_faculty=='all')
                    {
                        $sql=$sql ."  sc.Facultyid <> -1 ";
                    }
                    else
                    {
                        $sql=$sql ."  sc.Facultyid ='$cur_faculty' ";
                    }
                    $sql=$sql ."  order by te.Startday,ga.Degree,ga.LastName,ga.FirstName
                                 ";
                    //echo $sql;
                    $result = mysqli_query($db, $sql);

                    while($row=mysqli_fetch_assoc($result))
                    {
                        $currentinstructor=$row["Facultyid"];
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

                        echo '<tr>'.
                            '<td>' . $row["Startday"] .
                            '</td><td>' . $row["PantherID"] .
                            '</td><td>' . $row["FirstName"] .
                            '</td><td>' . $row["LastName"] .
                            '</td><td>' . $row["Degree"] .
                            '</td><td>' . $row["Email"] .
                            '</td><td>' . $row["Course"] .
                            '</td><td>' . $m_currentinstructor .
                            '</td><td>' . $row["CRN"] .
                             '</td><td>' . $row["Status"] .
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

<div>

    <tr>
        <td><input type="submit" name="download_file"  id="download_file" class="Register" value="Download" onclick=""></td>
    </tr>

</div>



<script>
    $(document).ready(function(){

        $('#option_submit').click(function(){
            //var status=$('option:selected',this.termid).text();
            var termid= $('#termid').find('option:selected').attr('value');
           //alert(termid);

           var degree =  $('#degree').find('option:selected').attr('value');
            //alert(degree);
            var facultyid =  $('#facultyid').find('option:selected').attr('value');
            // alert(starttermid);

            //alert('termid:'+termid+'starttermid:'+starttermid+'degree:'+degree);
            var name ='';
            var value='';
            name = 'termid';
            value = termid;
            var termidreturn = changesession(name,value);
           // alert(termidreturn);

            name = 'facultyid';
            value = facultyid;
            var facultyidreturn = changesession(name,value);

            name = 'degree';
            value = degree;
            var degreereturn = changesession(name,value);
//            if(termidreturn || facultyidreturn || degreereturn)
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
    $('#download_file').click(function(){
        //var status=$('option:selected',this.termid).text();
        var termid= $('#termid').find('option:selected').attr('value');
        //alert(termid);

        var degree =  $('#degree').find('option:selected').attr('value');
        //alert(degree);
        var facultyid =  $('#facultyid').find('option:selected').attr('value');
        //alert(facultyid);

        $.ajax({
            url:"GTAApplicationadminreportgegerateexcelandsave.php", //the page containing php script
            type: "POST", //request type
            async: false,
            data:{termid:termid,degree:degree,facultyid:facultyid},
            //data:{id:m_id},
            dataType:'json'
//            success:function(data)
//            {
//                alert(data);
//                if(data =='success')
//                {
//                    returnvalue=1;
//                }
//                else
//                {
//                    returnvalue= 0;
//                }
//            }
        }).done(function(data){
            var $a = $("<a>");
            $a.attr("href",data.file);
            $("body").append($a);
            $a.attr("download","Report.xls");
            $a[0].click();
            $a.remove();
        });

    });


</script>
