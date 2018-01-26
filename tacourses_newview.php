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
        if($i==0)
        {
            if(!isset($_SESSION['coursetermid']) || $_SESSION['coursetermid'] == '')
            {
                $_SESSION['coursetermid']=$termarray[$i]['Termid'];
            }
        }
        $i = $i + 1;
    }
}

?>


<?php
    if(isset($_SESSION['message']))
    {
         echo "<div id='error_msg'>".$_SESSION['message']."</div>";
         unset($_SESSION['message']);
    }
?>
<div>
    <a href="tacourse_newregister.php">Add New Course</a><br>
</div>

<div>
    <tr>
        <td>select a term you want to view:</td>
        <td>
            <select name="termid" id="termid" >
                <?php
                if (isset($_SESSION['coursetermid']) && $_SESSION['coursetermid'] != '')
                {
                    $m_termid = $_SESSION['coursetermid'];
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
</div>
<div class="row">
    <div class="col-lg-12" >
        <div class="panel panel-default" >
            <div class="panel-body"  style="overflow:auto">
                <table width="100%" class="table table-striped table-bordered table-hover" id="tacourse-view">
                    <thead>
                        <!-- Head -->
                        <tr>
                            <th>Subj</th><th>Crse</th><th>Title</th><th>Instructor</th><th>CRN</th>
                            <th>Act</th><th>XL Act</th>
                            <th>Term</th>
                            <th>Update</th><th>Remove</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        $currterm = $_SESSION['coursetermid'];

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
                                        sc.Facultyid  as email,
                                        sc2.Facultyid  as email2,
                                        te.Startday as startdate,
                                        te.Endday as enddate,
                                        te.Term as term,
                                        sc.Location as location,
                                        sc2.Location as location2,
                                        sc.Days as days1,
                                        sc.Starttime as starttime1,
                                        sc.Endtime as endtime1,
                                        sc2.Days as days2,
                                        sc2.Starttime as starttime2,
                                        sc2.Endtime as endtime2
                                        from tbl_course as c
                                        left JOIN tbl_schedule as sc on sc.Courseid= c.Courseid and sc.Instance=1
                                        left JOIN tbl_campus as ca on ca.Campusid=sc.Campusid
                                        left JOIN tbl_term as te on te.Termid = sc.Termid
                                        left JOIN tbl_schedule as sc2 on sc2.Courseid= c.Courseid and sc2.Instance=2";

                        $sql=$sql." where sc.Termid =  $currterm ";
                        $sql=$sql." order by te.Startday DESC,c.Course,c.CRN ";
                        $result = mysqli_query($db, $sql);

                        //echo $sql;
                        $m_coursearray = array();
                        $coursecount= 0;
                        while($row=mysqli_fetch_assoc($result))
                        {
                            $instructor_mail=$row["email"];
                            $instructor2_mail=$row["email2"];
                            $instructor = '';
                            $instructor2 = '';
                            foreach ($instructorarray as $p_arr)
                            {
                                $email = $p_arr["email"];
                                $Name = $p_arr["Name"];
                                if($instructor_mail==$email)
                                {
                                    $instructor =$Name;
                                }
                                if($instructor2_mail==$email)
                                {
                                    $instructor2 =$Name;
                                }
                            }
                            $id = $row["id"];
                            $subj = $row["subj"];
                            $crse = $row["crse"];
                            $title = $row["title"];
                            $crn = $row["crn"];
                            $act = $row["act"];
                            $xlact = $row["xlact"];
                            $term = $row["term"];
                            $m_coursearray[$coursecount] = array();
                            $m_coursearray[$coursecount]['id'] = $id;
                            $m_coursearray[$coursecount]['email'] = $email;
                            $m_coursearray[$coursecount]['Name'] = $Name;
                            $m_coursearray[$coursecount]['instructor'] = $instructor;
                            $m_coursearray[$coursecount]['instructor2'] = $instructor2;
                            $m_coursearray[$coursecount]['subj'] = $subj;
                            $m_coursearray[$coursecount]['crse'] = $crse;
                            $m_coursearray[$coursecount]['title'] = $title;
                            $m_coursearray[$coursecount]['crn'] = $crn;
                            $m_coursearray[$coursecount]['act'] = $act;
                            $m_coursearray[$coursecount]['xlact'] = $xlact;
                            $m_coursearray[$coursecount]['term'] = $term;
                            $coursecount=$coursecount+1;
                        }

                        //filter the 6000s course which is the same as 4000s
                        $arr_length = count($m_coursearray);
                        $temp_array = array();
                        $tempnumber=0;
                        for($i=0;$i<$arr_length;$i++)
                        {
                            $m_coursenumber=$m_coursearray[$i]['crse'];
                            if($m_coursenumber>4000 && $m_coursenumber<4999)
                            {
                                $temp_array[$tempnumber] = array();
                                $temp_array[$tempnumber] = $m_coursearray[$i];
                                $tempnumber=$tempnumber+1;
                            }
                        }
                        $number = 0;
                        for($i=0;$i<$arr_length;$i++)
                        {
                            $m_coursenumber=(int)$m_coursearray[$i]['crse'];
                            if ($m_coursenumber>6000 && $m_coursenumber<6999)
                            {
                                $IsSame = false;
                                for ($j=0;$j<$tempnumber;$j++)
                                {
                                    $p_coursenumber = $temp_array[$j]['crse'];
                                    $p_coursenumber=$p_coursenumber+2000;
                                    if($p_coursenumber==$m_coursenumber)
                                    {
                                        $IsSame=true;
                                    }
                                }
                                if(!$IsSame)
                                {
                                    $coursearray[$number] = array();
                                    $coursearray[$number] = $m_coursearray[$i];
                                    $number = $number + 1;
                                }
                            }
                            else
                            {
                                $coursearray[$number] = array();
                                $coursearray[$number] = $m_coursearray[$i];
                                $number=$number+1;
                            }
                        }

                        $coursearray_length = count($coursearray);
                        for($i=0;$i<$coursearray_length;$i++)
                        {
                            echo"<tr>".
                                '<td>'.$coursearray[$i]["subj"]. '</td><td>'.$coursearray[$i]["crse"].
                                '</td><td>'.$coursearray[$i]["title"].
                                '</td><td>'.$coursearray[$i]["instructor"]."<td>" .$coursearray[$i]["crn"].
                                '</td><td>'.$coursearray[$i]["act"]. '</td><td>'.$coursearray[$i]["xlact"].
                                '</td><td>';
                            echo $coursearray[$i]["term"];
                            echo '</td><td><input type="button" value="Update" name ="btnUpdate" onclick="Redirect(this,'. $coursearray[$i]["id"].')"/>' .
                                '</td><td><input type="button" value="Remove" name ="btnRemove" onclick="IsDelete(this,'. $coursearray[$i]["id"].')"/>' .
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
        name = 'coursetermid';
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
            url:"tacoursechangesession.php", //the page containing php script
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
            location.href="tacourse_newremove.php?id="+id;
        }
    }
    function Redirect(element,id)
    {
        location.href="tacourse_newregister.php?id="+id;
    }
</script>