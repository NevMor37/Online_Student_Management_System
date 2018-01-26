
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


$Pantherid = $user_pantherid;
$Email=$user_email;
$Role=$user_role ;
$Islogin=false;
if(empty($Email))
{

    $Islogin=false;
    echo 'Please login in first!';
}
else
{
    $Islogin=true;
}
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
        if($i==0) {
            if (!isset($_SESSION['TAEvaluationtermid']) || $_SESSION['TAEvaluationtermid'] == '') {
                $_SESSION['TAEvaluationtermid'] = $termarray[$i]['Termid'];
            }
        }
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

$TAEvaluationtermid=$_SESSION['TAEvaluationtermid'];
$studentarray = array();
$sql = "select PantherID,email, 
        CONCAT(coalesce(FirstName,' ') , IF(MiddleName = '', ' ', IFNULL(MiddleName,' ')),coalesce(LastName,' ')) as Name
        from tbl_gaapplication as ga
        where ga.TermID = '$TAEvaluationtermid' ";
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
        $i = $i + 1;
    }
}

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
    if($user_role=='Staff' or $user_role=='Admin' ) {
        echo '
            <p>Add New TA Evaluation Application</p>
           ';
        echo '
            <p id="post_1">  
                <input type="submit" value="Apply" style =" cursor: default;" onclick="Confirm.render(\'Do you want to add new application?\',\'delete_post\',\'post_1\',\'TAEvaluationregister.php\')"/>
            </p>
                      ';
    }
}
?>

<div>
    <tr>
        <td>select a term you want to view:</td>
        <td>
            <select name="termid" id="termid" >
                <?php
                if (isset($_SESSION['TAEvaluationtermid']) && $_SESSION['TAEvaluationtermid'] != '')
                {
                    $m_termid = $_SESSION['TAEvaluationtermid'];
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
        <td><input type="submit" name="option_submit" id="option_submit" value="Submit" onclick=""></td>
    </tr>
    <br>
</div>


<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body"  style="overflow:auto">
                <table width="100%" class="table table-striped table-bordered table-hover" id="TAEvaluation-view">
                    <thead>
                    <!-- Head -->
                    <tr>
                        <th>Term</th><th>Instructor</th><th>Course</th>
                        <th>TAName</th><th>Score</th><th>Operation</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $t_Termid=$TAEvaluationtermid;

                    $sql = "select taev.TaEvaluationID,taev.StudentID,taev.Instructor,taev.Term,taev.CourseID,taev.OverallScore,co.Course,co.Title
                            from tbl_taevaluation as taev
                            inner JOIN tbl_gaapplication as ga on ga.PantherID=taev.StudentID and ga.TermID=taev.Term
                            INNER JOIN tbl_course as co on co.Courseid=taev.CourseID 
                            INNER JOIN tbl_schedule as sc on sc.Courseid=co.Courseid and sc.Instance='1' 
                            where taev.Term='$t_Termid' 
                           
                                 ";
                    if($Role =='Admin' or $Role =='Staff' ) {
                        $sql = $sql. " and taev.Instructor<>'' ";
                    }
                    elseif ($Role =='Faculty')
                    {
                        $sql = $sql. " and taev.Instructor='$Email' ";
                    }
                    else
                    {
                        $sql = $sql. " and taev.Instructor='$Email' ";
                    }
                    $sql = $sql. ' order by taev.Instructor, ga.FirstName';
                    //echo $sql;
                    $result = mysqli_query($db, $sql);

                    while($row=mysqli_fetch_assoc($result))
                    {
                        $TaEvaluationID=$row["TaEvaluationID"];
                        $Instructor=$row["Instructor"];
                        $m_Instructor='';
                        foreach ($instructorarray as $p_arr)
                        {
                            $email = $p_arr["email"];
                            $Name = $p_arr["Name"];
                            //echo '$email:' .$email;
                            //echo '$Name:' .$Name;
                            if($Instructor==$email)
                            {
                                $m_Instructor =$Name;
                            }
                        }

                        $StudentID=$row["StudentID"];
                        $m_Student='';
                        foreach ($studentarray as $p_arr)
                        {
                            $ID = $p_arr["PantherID"];
                            $email = $p_arr["email"];
                            $Name = $p_arr["Name"];
                            //echo '$email:' .$email;
                            //echo '$Name:' .$Name;
                            if($StudentID==$ID)
                            {
                                $m_Student =$Name;
                            }
                        }

                        $termarray[$i]['Termid'] = $row["Termid"];
                        $termarray[$i]['Term'] = $row["Term"];
                        $Termid=$row["Term"];
                        $m_Term='';
                        foreach ($termarray as $p_arr)
                        {
                            $ID = $p_arr["Termid"];
                            $Name = $p_arr["Term"];
                            //echo '$email:' .$email;
                            //echo '$Name:' .$Name;
                            if($ID==$Termid)
                            {
                                $m_Term =$Name;
                            }
                        }

                        $m_Course=$row["Course"];
                        $m_Title=$row["Title"];
                        $m_OverallScore=$row["OverallScore"];

                        echo '<tr><td>' . $m_Term .
                            //'</td><td>' . $Instructor .
                            '</td><td>' . $m_Instructor .
                            '</td><td>' . $m_Course.' '.$m_Title .
                            //'</td><td>' . $StudentID .
                            '</td><td>' . $m_Student .
                            '</td><td>' . $m_OverallScore;
                        echo '</td><td>';
                        if($Role =="Admin" or $Role =="Staff" ) {
                            echo "<input type='button' value='Update' name ='btnUpdate' onclick=\"window.location.href='TAEvaluationregister.php?id=".$TaEvaluationID."'\">
                                ";
                            echo '<input type="button" value="Remove" name ="btnRemove" onclick="IsDelete(this,'. $TaEvaluationID.')" label="Remove"/>';
                        }
                        else if($Role =="Faculty" or $Role =="Student")
                        {
                            echo "
                                  <input type='button' value='Update' name ='btnUpdate' onclick=\"window.location.href='TAEvaluationregister.php?id=".$TaEvaluationID."'\">
                                ";
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
    $(document).ready(function(){

        $('#option_submit').click(function(){
            //var status=$('option:selected',this.termid).text();
            //alert('option_submit');
            var termid= $('#termid').find('option:selected').attr('value');
            //alert(termid);
            var name ='';
            var value='';
            name = 'TAEvaluationtermid';
            value = termid;
            var termidreturn = changesession(name,value);
            // alert(termidreturn);


            window.location.reload();
        });

        function  changesession(name,value)
        {
            var returnvalue =0;
            $.ajax({
                url:"TAEvaluationchangesession.php", //the page containing php script
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




    function IsDelete(element,id)
    {//（true or false）
        if(confirm("Do you want to delete this record？"))
        {//
            location.href="TAEvaluationremove.php?id="+id;
        }
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
            location.href="TAEvaluationregister.php";

        }
    }
    var Confirm = new CustomConfirm();         ﻿
</script>

