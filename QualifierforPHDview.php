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

$phdarray = array();
$sql = "select PantherID,email, CONCAT(coalesce(FirstName,' ') , IF(MiddleName = '', ' ', IFNULL(MiddleName,' ')),coalesce(LastName,' ')) as Name
              from tbl_student_info
              where Degree='PHD' ";
//echo $sql . '<br>';
$result = mysqli_query($db, $sql);

if ($result->num_rows > 0) {
    $i = 0;
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        $phdarray[$i] = array();
        $phdarray[$i]['PantherID'] = $row["PantherID"];
        $phdarray[$i]['email'] = $row["email"];
        $phdarray[$i]['Name'] = $row["Name"];
        $i = $i + 1;
    }
}

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



?>
<?php
    if(isset($_SESSION['message']))
    {
         echo "<div id='error_msg'>".$_SESSION['message']."</div>";
         unset($_SESSION['message']);
    }
?>

<div>
    <td><a href="QualifierforPHDInsert.php?" >Insert</a></td>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body"  style="overflow:auto">
                <table width="100%" class="table table-striped table-bordered table-hover" id="qualifier-view">
                    <thead>
                    <!-- Head -->
                        <tr>
                            <th>QualifierID</th><th>TermID</th><th>Student</th><th>Committeeemembers</th><th>Update</th><th>Remove</th>
                        </tr>
                    </thead>
                    <tbody>
                    	 <?php

                        $sql = "SELECT QualifierID as id,
                        		TermID as termid,
                        		Committeemember1 as Committeemember1,
                        		Committeemember2 as Committeemember2,
                        		Committeemember3 as Committeemember3,
                        		Committeemember4 as Committeemember4,
                        		Committeemember5 as Committeemember5,
                        		StudentID as StudentID                              
                                FROM tbl_qualifier
                                 ";

                        $result = mysqli_query($db, $sql);

                        while($row=mysqli_fetch_assoc($result))
                        {
                            $termid = $row["termid"];
                            $m_term= '';
                            foreach ($termarray as $arr)
                            {
                                $p_Termid = $arr["Termid"];
                                $p_Term = $arr["Term"];
                                //echo '$p_Termid:'.$p_Termid;
                                //echo '$p_Term:'.$p_Term;
                                if ($p_Termid == $termid)
                                {
                                    $m_term = $p_Term;
                                }
                            }

                            $StudentID = $row["StudentID"];
                            $m_Student= '';
                            $phdarray[$i]['PantherID'] = $row["PantherID"];
                            $phdarray[$i]['email'] = $row["email"];
                            $phdarray[$i]['Name'] = $row["Name"];
                            foreach ($phdarray as $arr)
                            {
                                $p_PantherID = $arr["PantherID"];
                                $p_email = $arr["email"];
                                $p_Name = $arr["Name"];
                                //echo '$p_Termid:'.$p_Termid;
                                //echo '$p_Term:'.$p_Term;
                                if ($p_PantherID == $StudentID)
                                {
                                    $m_Student = $p_Name;
                                }
                            }

                            $Committeemember1 = $row["Committeemember1"];
                            $Committeemember2 = $row["Committeemember2"];
                            $Committeemember3 = $row["Committeemember3"];
                            $m_Committeemembers='';
                            foreach ($facultyarray as $arr)
                            {
                                $p_email = $arr["email"];
                                $p_Name = $arr["Name"];
                                //echo '$p_Termid:'.$p_Termid;
                                //echo '$p_Term:'.$p_Term;
                                if ($p_email == $Committeemember1)
                                {
                                    $m_Committeemembers = $m_Committeemembers.$p_Name.',';
                                }
                                if ($p_email == $Committeemember2)
                                {
                                    $m_Committeemembers = $m_Committeemembers.$p_Name.',';
                                }
                                if ($p_email == $Committeemember3)
                                {
                                    $m_Committeemembers = $m_Committeemembers.$p_Name.',';
                                }
                            }
                            if(substr($m_Committeemembers,strlen($m_Committeemembers)-1,1)==',')
                            {
                                $m_Committeemembers=substr($m_Committeemembers,0,strlen($m_Committeemembers)-1);
                            }

                            echo '<tr><td>' . $row["id"] .
                            	'</td><td>' . $m_term .
                                '</td><td>' . $m_Student .
                                '</td><td>' . $m_Committeemembers.
                                '</td><td><a href="QualifierforPHDUpdate.php?id=' . $row["id"] . '" >Update</a></td><td><a href="QualifierforPHDdelete.php?id=' . $row["id"] . ' "\">Remove</a></td></tr>';
                        }

                        ?>