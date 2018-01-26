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
$user_role = $_SESSION['user']['role'];
include($root.'/osms.dbconfig.inc');

$error_message = "";
$counter = 0;

$mysqli = new mysqli($hostname,$username, $password,$dbname);

/* check connection */
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}
$html="";


//echo $_SESSION["status"];
$termarray = array();
$sql = "
              select distinct(Term) as Term
			  from tbl_excel_info
			  where Term <>'' and term not like '%Test%' 

            ";
//echo $sql . '<br>';
$result = mysqli_query($mysqli, $sql);

if ($result->num_rows > 0) {
    $i = 0;
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        $termarray[$i] = array();
        $termarray[$i]['Termid'] = $row["Term"];
        $termarray[$i]['Term'] = $row["Term"];
        if($i==0)
		{
            if(!isset($_SESSION['admintermid']) || $_SESSION['admintermid'] == '')
            {
                $_SESSION['admintermid']=$termarray[$i]['Termid'] ;
            }
		}
        $i = $i + 1;
    }
   // echo '$i'.$i;
}

$html.='<div class="row">';
$html.='<tr>';
$html.='<td>';
$html.='<select name="termid" id="termid" >';

$_admintermid =  $_SESSION['admintermid'];
//echo '$_admintermid:'.$_admintermid;
foreach ($termarray as $arr)
{
    $p_Termid = $arr["Termid"];
    $p_Term = $arr["Term"];
    $html.= '<option value="'.$p_Termid.'"';

    //echo '$p_Termid:'.$p_Termid;
    //echo '$_admintermid:'.$_admintermid;
    if($p_Termid==$_admintermid)
    {
        $html.= ' selected ';
    }
    $html.=  '    >'.
                            $p_Term.
                            '</option>';
}
$html.='</select>';
$html.='</td>';
$html.='</tr>';
$html.='<tr>
    		<td><input type="submit" name="option_submit" id="option_submit" value="Submit" onclick=""></td>
		</tr>';
$html.='</div>';

$html.='<div class="row">';
$html.='<div class="col-lg-12">';
$html.='<div class="panel panel-default">';
$html.='<div class="panel-heading">';
$html.='Applications Status Count';
$html.='</div>';
$html.='<div class="panel-body"  style="overflow:auto">';
$html.='<table width="100%" class="table table-striped table-bordered table-hover" id="studentTables-TermStatusCount">';
$html.="<thead>";
$html.="<tr>";
$html .= '<th>Status</th>';
$html .= '<th>Count</th>';
$html .= '<th>Term</th>';
$html .= '<th>Program</th>';
$html.="</tr>";
$html.="</thead>";
$html.="<tbody>";

$_admintermid =  $_SESSION['admintermid'];
$sql = "select * 
		from tbl_faculty_info t1, tbl_student_evaluation t2, tbl_excel_info t3 
		where t1.email = t2.FacultyId  and t2.StudentId = t3.PantherId";
if($_admintermid !='all')
{
    $sql = $sql . " and t3.Term ='$_admintermid'  ";
}
else
{
    $sql = $sql . " and t3.Term <> -1  ";
}
$result = $mysqli->query($sql);
if ($result->num_rows > 0) {
    $count = 0;
	$MScount=0;
    $MSFacultyAdmitcount=0;
    $MSFacultyRejectcount=0;
    $MSFacultyDeferredcount=0;
    $MSAdvisorAdmitcount=0;
    $MSAdvisorRejectcount=0;
    $MSAdvisorDeferredcount=0;
    $PHDFacultyAdmitcount=0;
    $PHDFacultyRejectcount=0;
    $PHDFacultyDeferredcount=0;
    $PHDAdvisorAdmitcount=0;
    $PHDAdvisorRejectcount=0;
    $PHDAdvisorDeferredcount=0;
    $PHDcount=0;
    $MSCompleteCount =0;
    $MSPendingCount =0;
    $PHDPendingCount =0;
    $PHDCompleteCount =0;
    while($row = $result->fetch_assoc()) {
        $StudentId = $row["StudentId"];
        $ApplicationID=$row["ApplicantID"];
        $PantherId = $row["PantherId"];
        $FirstName=$row["FirstName"];
        $LastName=$row["LastName"];
        $email=$row["EMail"];
        $Program=$row["Program"];
        $Term=$row["Term"];
        $Concentration=$row["Concentration"];
        $linkaddress = $row["linkaddress"];
        $FacEmail = $row["FacultyName"];
        $Recommendation= $row["Recommendation"];
        $AdmissionDecision= $row["AdmissionDecision"];
        $Status= $row["Status"];
        $count=$count+1;
        if( stripos($Program,'Master') || stripos($Program,'MS') )
		{
            $MScount=$MScount+1;
            if($Recommendation=='Admit')
			{
                $MSFacultyAdmitcount=$MSFacultyAdmitcount+1;
			}
			else if($Recommendation=='Reject')
			{
                $MSFacultyRejectcount=$MSFacultyRejectcount+1;
			}
			else if($Recommendation=='Deferred')
			{
                $MSFacultyDeferredcount=$MSFacultyDeferredcount+1;
			}

            if($AdmissionDecision=='Admit')
            {
                $MSAdvisorAdmitcount=$MSAdvisorAdmitcount+1;
            }
            else if($AdmissionDecision=='Reject')
            {
                $MSAdvisorRejectcount=$MSAdvisorRejectcount+1;
            }
            else if($AdmissionDecision=='Deferred')
            {
                $MSAdvisorDeferredcount=$MSAdvisorDeferredcount+1;
            }
            if($Status=='Pending')
			{
                $MSPendingCount=$MSPendingCount+1;
			}
			else if($Status=='Complete')
			{
                $MSCompleteCount=$MSCompleteCount+1;
			}

		}
        if( stripos($Program,'Doctor') || stripos($Program,'PHD') )
        {
            $PHDcount=$PHDcount+1;
            if($Recommendation=='Admit')
            {
                $PHDFacultyAdmitcount=$PHDFacultyAdmitcount+1;
            }
            else if($Recommendation=='Reject')
            {
                $PHDFacultyRejectcount=$PHDFacultyRejectcount+1;
            }
            else if($Recommendation=='Deferred')
            {
                $PHDFacultyDeferredcount=$PHDFacultyDeferredcount+1;
            }

            if($AdmissionDecision=='Admit')
            {
                $PHDAdvisorAdmitcount=$PHDAdvisorAdmitcount+1;
            }
            else if($AdmissionDecision=='Reject')
            {
                $PHDAdvisorRejectcount=$PHDAdvisorRejectcount+1;
            }
            else if($AdmissionDecision=='Deferred')
            {
                $PHDAdvisorDeferredcount=$PHDAdvisorDeferredcount+1;
            }

            if($Status=='Pending')
            {
                $PHDPendingCount=$PHDPendingCount+1;
            }
            else if($Status=='Complete')
            {
                $PHDCompleteCount=$PHDCompleteCount+1;
            }
        }
    }

    $html.="<tr>";
    $html .= '<td>'.'All Applicant'.'</td>';
    $html .= '<td>'.$count.'</td>';
    $html .= '<td>'.$_admintermid.'</td>';
    $html .= '<td>'.'All'.'</td>';
    $html.="</tr>";

    $html.="<tr>";
    $html .= '<td>'.'MS Applicant'.'</td>';
    $html .= '<td>'.$MScount.'</td>';
    $html .= '<td>'.$_admintermid.'</td>';
    $html .= '<td>'.'MS'.'</td>';
    $html.="</tr>";

    $html.="<tr>";
    $html .= '<td>'.'MS Applicant Advisor Admit'.'</td>';
    $html .= '<td>'.$MSAdvisorAdmitcount.'</td>';
    $html .= '<td>'.$_admintermid.'</td>';
    $html .= '<td>'.'MS'.'</td>';
    $html.="</tr>";

    $html.="<tr>";
    $html .= '<td>'.'MS Applicant Advisor Reject'.'</td>';
    $html .= '<td>'.$MSAdvisorRejectcount.'</td>';
    $html .= '<td>'.$_admintermid.'</td>';
    $html .= '<td>'.'MS'.'</td>';
    $html.="</tr>";

    $html.="<tr>";
    $html .= '<td>'.'MS Applicant Advisor Deferred'.'</td>';
    $html .= '<td>'.$MSAdvisorDeferredcount.'</td>';
    $html .= '<td>'.$_admintermid.'</td>';
    $html .= '<td>'.'MS'.'</td>';
    $html.="</tr>";

    $html.="<tr>";
    $html .= '<td>'.'MS Applicant Complete'.'</td>';
    $html .= '<td>'.$MSCompleteCount.'</td>';
    $html .= '<td>'.$_admintermid.'</td>';
    $html .= '<td>'.'MS'.'</td>';
    $html.="</tr>";

    $html.="<tr>";
    $html .= '<td>'.'MS Applicant Pending'.'</td>';
    $html .= '<td>'.$MSPendingCount.'</td>';
    $html .= '<td>'.$_admintermid.'</td>';
    $html .= '<td>'.'MS'.'</td>';
    $html.="</tr>";


    $html.="<tr>";
    $html .= '<td>'.'PHD Applicant'.'</td>';
    $html .= '<td>'.$PHDcount.'</td>';
    $html .= '<td>'.$_admintermid.'</td>';
    $html .= '<td>'.'PHD'.'</td>';
    $html.="</tr>";

    $html.="<tr>";
    $html .= '<td>'.'PHD Applicant Advisor Admit'.'</td>';
    $html .= '<td>'.$PHDAdvisorAdmitcount.'</td>';
    $html .= '<td>'.$_admintermid.'</td>';
    $html .= '<td>'.'PHD'.'</td>';
    $html.="</tr>";

    $html.="<tr>";
    $html .= '<td>'.'PHD Applicant Advisor Reject'.'</td>';
    $html .= '<td>'.$PHDAdvisorRejectcount.'</td>';
    $html .= '<td>'.$_admintermid.'</td>';
    $html .= '<td>'.'PHD'.'</td>';
    $html.="</tr>";

    $html.="<tr>";
    $html .= '<td>'.'PHD Applicant Advisor Deferred'.'</td>';
    $html .= '<td>'.$PHDAdvisorDeferredcount.'</td>';
    $html .= '<td>'.$_admintermid.'</td>';
    $html .= '<td>'.'PHD'.'</td>';
    $html.="</tr>";

    $html.="<tr>";
    $html .= '<td>'.'PHD Applicant Pending'.'</td>';
    $html .= '<td>'.$PHDPendingCount.'</td>';
    $html .= '<td>'.$_admintermid.'</td>';
    $html .= '<td>'.'PHD'.'</td>';
    $html.="</tr>";

    $html.="<tr>";
    $html .= '<td>'.'PHD Applicant Complete'.'</td>';
    $html .= '<td>'.$PHDCompleteCount.'</td>';
    $html .= '<td>'.$_admintermid.'</td>';
    $html .= '<td>'.'PHD'.'</td>';
    $html.="</tr>";
}

$html.="</tbody>";
$html.="</table>";
$html.='</div>';
$html.='</div>';
$html.='</div>';
$html.='</div>';


$html.='<div class="row">';
$html.='<div class="col-lg-12">';
$html.='<div class="panel panel-default">';
$html.='<div class="panel-heading">';
$html.='Pending/Admit/Reject Applications';
$html.='</div>';
$html.='<div class="panel-body"  style="overflow:auto">';
$html.='<table width="100%" class="table table-striped table-bordered table-hover" id="studentTables-pendingadmitreject">';
$html.="<thead>";
$html.="<tr>";
$html .= '<th>PantherID</th>';
$html .= '<th>Applicant Name</th>';
//$html .= '<th>LastName</th>';
$html .= '<th>Program</th>';
$html .= '<th>Term</th>';
$html .= '<th>Faculty</th>';
$html .= '<th>FacultyDec</th>';
$html .= '<th>AdvisorDec</th>';
$html .= '<th>Financial Aid</th>';
$html .= '<th>Evaluate</th>';
$html.="</tr>";
$html.="</thead>";
$html.="<tbody>";

$_admintermid =  $_SESSION['admintermid'];
$sql = "select * 
		from tbl_faculty_info t1, tbl_student_evaluation t2, tbl_excel_info t3 
		where t1.email = t2.FacultyId  and t2.StudentId = t3.PantherId";
if($_admintermid !='all')
{
    $sql = $sql . " and t3.Term ='$_admintermid'  ";
}
else
{
    $sql = $sql . " and t3.Term <> -1  ";
}
$result = $mysqli->query($sql);
//echo $sql;
if ($result->num_rows > 0) {
    $count = 0;
    while($row = $result->fetch_assoc()) {
        $Status = $row["Status"];
        $count++;
        $StudentId = $row["StudentId"];
        $ApplicationID=$row["ApplicantID"];
// 		$sql1 = "SELECT  * FROM tbl_excel_info where PantherId= ".$StudentId;
// 		$result1 = $mysqli->query($sql1);

// 		if($result1-> num_rows > 0)
// 		{
// 		while($row1 = $result1->fetch_assoc())
// 		{
        $PantherId = $row["PantherId"];
        $FirstName=$row["FirstName"];
        $LastName=$row["LastName"];
        $email=$row["EMail"];
        $Program=$row["Program"];
        $Term=$row["Term"];
        $Concentration=$row["Concentration"];
        $linkaddress = $row["linkaddress"];
        $FacEmail = $row["FacultyName"];
        $Recommendation= $row["Recommendation"];
		$AdmissionDecision= $row["AdmissionDecision"];
        $FinancialAid= $row["FinAid"];
        //echo '$linkaddress:';
        //echo $linkaddress;
// 			$FileName = strtolower($LastName).strtolower($FirstName).'.pdf';
// 			$FullFileName = './../../Applications/'.$FileName;
        //File Access

        $FileName = strtolower($LastName).strtolower($FirstName).'.pdf';
        $files = null;
        try{

            if(is_numeric($ApplicationID))
            {
                $files = glob($root."/Applications/*".$ApplicationID.".pdf");
                if(count($files) != 0)
                {
                    try{
                        $filename = basename($files[0]);
                        $FullFileName = null;
                        $filenamelocation='./../../Applications/'.$filename;

                    }
                    catch (Exception $e1)
                    {
                        echo 'Message: ' .$e1->getMessage();
                    }
                }
                else
                {
                    $filenamelocation = null;
                    $FullFileName ='./../../Applications/'.$FileName;
                    $filename = basename($FullFileName);
                }
            }
            else
            {
                $filenamelocation = null;
                $FullFileName ='./../../Applications/'.$FileName;
                $filename = basename($FullFileName);
            }
        }
        catch (Exception $e)
        {
            echo 'Message: ' .$e->getMessage();
        }
        //File Access End
// 		}

// 		$sql2 = "SELECT  * FROM tbl_student_evaluation where StudentId= '{$StudentId}'";
// 		$result2 = $mysqli->query($sql2);

// 		if($result2-> num_rows > 0)
// 		{
// 			while($row2 = $result2->fetch_assoc())
        $Status = $row["Status"];
// 		}
        $html.="<tr class='odd gradeX'>";
        $html .= '<td>'.$PantherId.'</td>';

// 			if (file_exists($FullFileName)) {
// 				$html .= '<td><a href="'.$FullFileName.'" target="_blank">'.$FirstName.' '.$LastName.'</a></td>';
// 			} else {
// 				$html .= '<td><a href="#">'.$FirstName.' '.$LastName.'</a></td>';
// 			}
        //File Access
//			if(file_exists($filenamelocation)){
//				$html .= '<td><a href="'.$filenamelocation.'" target="_blank">'.$FirstName.' '.$LastName.'</td>';
//			}else if(file_exists($FullFileName)){
//				$html .= '<td><a href="'.$FullFileName.'" target="_blank">'.$FirstName.' '.$LastName.'</a></td>';
//			}
//			else {
//				$html .= '<td>'.$FirstName.' '.$LastName.'</td>';
//				$filename = null;
//				$fileSize = null;
//			}
        //File Access End

        if (empty($linkaddress))
        {
            $html .= '<td>' .$FirstName.' '.$LastName. '</td>';
        }
        else
        {

            $html .= '<td><a href="' . $linkaddress . '" target="_blank">' .$FirstName.' '.$LastName.'</td>';
        }
        //$html .= '<td><a href="./../../Applications/AlamMustafa.pdf">'.$FirstName.' '.$LastName.'</a></td>';
        //$html .= '<td>'.$FirstName.' '.$LastName.'</td>';
        //$html .= '<td>'.$LastName.'</td>';
        $html .= '<td>'.$Program.'</td>';
        $html .= '<td>'.$Term.'</td>';
        //$html .= '<td>'.$email.'</td>';
        $html .= '<td>'.$FacEmail.'</td>';
        $html .= '<td>'.$Recommendation.'</td>';
        $html .= '<td>'.$AdmissionDecision.'</td>';
        $html .= '<td>'.$FinancialAid.'</td>';
        if($user_role=='Admin')
        {
            $html .= '<td><a href="./AppEvaluationForm.php?PantherId=' . $PantherId . '&Source=AdvisorDB" class="btn btn-primary">EvaluateAdv</a></td>';
        }
        else if($user_role=='Staff'){
            $html .= '<td><a href="./AppEvaluationForm.php?PantherId=' . $PantherId . '&Source=AdminDB" class="btn btn-primary">EvaluateSta</a></td>';
        }
        else
		{
            $html .= '<td><a href="./AppEvaluationForm.php?PantherId=' . $PantherId . '&Source=FinalDB" class="btn btn-primary">View</a></td>';

        }
      	$html .= "</tr>";
    }
// 	}
}
else
{
    //echo "No applications assigned";
}
$html.='</tbody>';
$html.='</table>';
$html.='</div>';
$html.='</div>';
$html.='</div>';
$html.='</div>';


$html.='<div class="row">';
$html.='<div class="col-lg-12">';
$html.='<div class="panel panel-default">';
$html.='<div class="panel-heading">';
$html.='Pending Applications';
$html.='</div>';
$html.='<div class="panel-body"  style="overflow:auto">';
$html.='<table width="100%" class="table table-striped table-bordered table-hover" id="studentTables-info">';
$html.="<thead>";
$html.="<tr>";
$html .= '<th>PantherID</th>';
$html .= '<th>Applicant Name</th>';
//$html .= '<th>LastName</th>';
$html .= '<th>Program</th>';
$html .= '<th>Term</th>';
$html .= '<th>Concentration</th>';
$html .= '<th>Faculty</th>';
$html .= '<th>Status</th>';
$html .= '<th>Evaluate</th>';
$html.="</tr>";
$html.="</thead>";
$html.="<tbody>";

$sql = "select * from tbl_faculty_info t1, tbl_student_evaluation t2, tbl_excel_info t3 where t1.email = t2.FacultyId and t2.Status='Pending' and t2.StudentId = t3.PantherId";
$result = $mysqli->query($sql);
// echo $sql;
if ($result->num_rows > 0) {
    $count = 0;
    while($row = $result->fetch_assoc()) {
        $Status = "Pending";
        $count++;
        $StudentId = $row["StudentId"];
        $ApplicationID=$row["ApplicantID"];
// 		$sql1 = "SELECT  * FROM tbl_excel_info where PantherId= ".$StudentId;
// 		$result1 = $mysqli->query($sql1);

// 		if($result1-> num_rows > 0)
// 		{
// 		while($row1 = $result1->fetch_assoc())
// 		{
        $PantherId = $row["PantherId"];
        $FirstName=$row["FirstName"];
        $LastName=$row["LastName"];
        $email=$row["EMail"];
        $Program=$row["Program"];
        $Term=$row["Term"];
        $Concentration=$row["Concentration"];
        $linkaddress = $row["linkaddress"];
        $FacEmail = $row["FacultyName"];
        //echo '$linkaddress:';
        //echo $linkaddress;
// 			$FileName = strtolower($LastName).strtolower($FirstName).'.pdf';
// 			$FullFileName = './../../Applications/'.$FileName;
        //File Access

        $FileName = strtolower($LastName).strtolower($FirstName).'.pdf';
        $files = null;
        try{

            if(is_numeric($ApplicationID))
            {
                $files = glob($root."/Applications/*".$ApplicationID.".pdf");
                if(count($files) != 0)
                {
                    try{
                        $filename = basename($files[0]);
                        $FullFileName = null;
                        $filenamelocation='./../../Applications/'.$filename;

                    }
                    catch (Exception $e1)
                    {
                        echo 'Message: ' .$e1->getMessage();
                    }
                }
                else
                {
                    $filenamelocation = null;
                    $FullFileName ='./../../Applications/'.$FileName;
                    $filename = basename($FullFileName);
                }
            }
            else
            {
                $filenamelocation = null;
                $FullFileName ='./../../Applications/'.$FileName;
                $filename = basename($FullFileName);
            }
        }
        catch (Exception $e)
        {
            echo 'Message: ' .$e->getMessage();
        }
        //File Access End
// 		}

// 		$sql2 = "SELECT  * FROM tbl_student_evaluation where StudentId= '{$StudentId}'";
// 		$result2 = $mysqli->query($sql2);

// 		if($result2-> num_rows > 0)
// 		{
// 			while($row2 = $result2->fetch_assoc())
        $Status = $row["Status"];
// 		}
        $html.="<tr class='odd gradeX'>";
        $html .= '<td>'.$PantherId.'</td>';

// 			if (file_exists($FullFileName)) {
// 				$html .= '<td><a href="'.$FullFileName.'" target="_blank">'.$FirstName.' '.$LastName.'</a></td>';
// 			} else {
// 				$html .= '<td><a href="#">'.$FirstName.' '.$LastName.'</a></td>';
// 			}
        //File Access
//			if(file_exists($filenamelocation)){
//				$html .= '<td><a href="'.$filenamelocation.'" target="_blank">'.$FirstName.' '.$LastName.'</td>';
//			}else if(file_exists($FullFileName)){
//				$html .= '<td><a href="'.$FullFileName.'" target="_blank">'.$FirstName.' '.$LastName.'</a></td>';
//			}
//			else {
//				$html .= '<td>'.$FirstName.' '.$LastName.'</td>';
//				$filename = null;
//				$fileSize = null;
//			}
        //File Access End

        if (empty($linkaddress))
        {
            $html .= '<td>' .$FirstName.' '.$LastName. '</td>';
        }
        else
        {

            $html .= '<td><a href="' . $linkaddress . '" target="_blank">' .$FirstName.' '.$LastName.'</td>';
        }
        //$html .= '<td><a href="./../../Applications/AlamMustafa.pdf">'.$FirstName.' '.$LastName.'</a></td>';
        //$html .= '<td>'.$FirstName.' '.$LastName.'</td>';
        //$html .= '<td>'.$LastName.'</td>';
        $html .= '<td>'.$Program.'</td>';
        $html .= '<td>'.$Term.'</td>';
        $html .= '<td>'.$Concentration.'</td>';
        //$html .= '<td>'.$email.'</td>';
        $html .= '<td>'.$FacEmail.'</td>';
        $html .= '<td>'.$Status.'</td>';
        if($Status == 'Complete')
            $html .= '<td><a href="./AppEvaluationForm.php?PantherId='.$PantherId.'&Source=AdminDB" class="btn btn-primary" disabled>Evaluate</a></td>';
        else
            $html .= '<td><a href="./AppEvaluationForm.php?PantherId='.$PantherId.'&Source=AdminDB" class="btn btn-primary">Evaluate</a></td>';
        $html .= "</tr>";
    }
// 	}
}
else
{
    //echo "No applications assigned";
}
$html.='</tbody>';
$html.='</table>';
$html.='</div>';
$html.='</div>';
$html.='</div>';
$html.='</div>';






$sql1 = "SELECT StudentId FROM tbl_student_evaluation WHERE Status='Complete' and (AdmissionDecision is null or AdmissionDecision = '')";
$sql2 = "SELECT StudentId FROM tbl_student_evaluation WHERE Status='Pending' and FacultyId != 'cao@gsu.edu'";
$result1 = $mysqli->query($sql1);
$result2 = $mysqli->query($sql2);



$html.='<div class="row">';
$html.='<div class="col-lg-12">';
$html.='<div class="panel panel-default">';
$html.='<div class="panel-heading">';
$html.='Pending at Director Of Graduate Studies and Completed by Faculty';
$html.='</div>';
$html.='<div class="panel-body"  style="overflow:auto">';
$html.='<table width="100%" class="table table-striped table-bordered table-hover" id="studentTables-AdvisorPending">';
$html.="<thead>";
$html.="<tr>";		
$html .= '<th>PantherID</th>';
$html .= '<th>Applicant Name</th>';
$html .= '<th>Program</th>';
$html .= '<th>Term</th>';
$html .= '<th>GRE Total</th>';
$html .= '<th>TOEFL Total</th>';
$html .= '<th>Faculty Decision</th>'; 
$html .= '<th>GPA</th>';
$html .= '<th>Faculty</th>';
if($user_role == "Admin")
	$html .= '<th>View</th>';
else
	$html .= '<th>Review</th>';
$html.="</tr>";
$html.="</thead>";
$html.="<tbody>";

if ($result1->num_rows > 0) {
$count = 0;
while($row1 = $result1->fetch_assoc()) {	
		$StudentId = $row1["StudentId"];
		
		$sql3 = "SELECT  * FROM tbl_excel_info ex1,tbl_student_evaluation te1 where te1.StudentId=ex1.PantherId and  ex1.PantherId= ".$StudentId;
		$result3 = $mysqli->query($sql3);
		
		if($result3-> num_rows > 0) {

            while ($row3 = $result3->fetch_assoc()) {
                $PantherId = $row3["PantherId"];
                $FirstName = $row3["FirstName"];
                $LastName = $row3["LastName"];
                $email = $row3["EMail"];
                $Program = $row3["Program"];
                $Term = $row3["Term"];
                $Concentration = $row3["Concentration"];
                $FacEmail = $row3["FacultyName"];
                $GREVerbalScore = $row3["GREVerbalScore"];
                $GREQuantScore = $row3["GREQuantScore"];
                $TOEFLTotal = $row3["TOEFLTotal"];
                $FacultyD = $row3["Recommendation"];
                $GRETotal = $GREVerbalScore + $GREQuantScore;
                $gpa = $row3["GraduateGPA"];
                if (!$gpa) {
                    $gpa = $row3["UgGPAOverall"];
                }//File Access
                $ApplicationID = $row3["ApplicantID"];
                $linkaddress = $row3["linkaddress"];
                $FileName = strtolower($LastName) . strtolower($FirstName) . '.pdf';
                $files = null;
                if (is_numeric($ApplicationID)) {
                    $files = glob($root . "/Applications/*" . $ApplicationID . ".pdf");

                    if (count($files) != 0) {
                        $filename = basename($files[0]);
                        $FullFileName = null;
                        $filenamelocation = './../../Applications/' . $filename;

                    } else {
                        $filenamelocation = null;
                        $FullFileName = './../../Applications/' . $FileName;

                        $filename = basename($FullFileName);
                    }
                } else {
                    $filenamelocation = null;
                    $FullFileName = './../../Applications/' . $FileName;

                    $filename = basename($FullFileName);
                }
                //File Access End
            }



            $html .= "<tr class='odd gradeX'>";
            $html .= '<td>' . $PantherId . '</td>';
            if (file_exists($filenamelocation)) {
                //$html .= '<td><a href="'.$filenamelocation.'" target="_blank">'.$FirstName.' '.$LastName.'</td>';
                $html .= '<td><a href="' . $filenamelocation . '" target="_blank">' . $FirstName . ' ' .$LastName. '</td>';
            } else if (file_exists($FullFileName)) {
                //$html .= '<td><a href="'.$FullFileName.'" target="_blank">'.$FirstName.' '.$LastName.'</a></td>';
                $html .= '<td><a href="' . $FullFileName . '" target="_blank">' . $FirstName . ' '.$LastName . '</a></td>';
            } else if (!empty($linkaddress)) {
                $html .= '<td><a href="' . $linkaddress . '" target="_blank">' . $FirstName . ' '.$LastName . '</td>';
			}
			else {
                //$html .= '<td>'.$FirstName.' '.$LastName.'</td>';
                $html .= '<td>' . $FirstName.$LastName . '</td>';
                $filename = null;
                $fileSize = null;
            }
            $html .= '<td>' . $Program . '</td>';
            //echo 'link:'.$linkaddress;
//            if (empty($linkaddress))
//            {
//                $html .= '<td>' . $Program . '</td>';
//            }
//            else
//			{
//
//            	$html .= '<td><a href="' . $linkaddress . '" target="_blank">' . $Program . '</td>';
//        	}
			//$html .= '<td>'.$Program.'</td>';
			$html .= '<td>'.$Term.'</td>';
			$html .= '<td>'.$GRETotal.'</td>';
			$html .= '<td>'.$TOEFLTotal.'</td>';
			
			$html .= '<td>'.$FacultyD.'</td>';
			$html .= '<td>'.$gpa.'</td>';
			$html .= '<td>'.$FacEmail.'</td>';

			if($user_role != "Admin")
			{
				$html .= '<td><a href="./AppEvaluationForm.php?PantherId=' . $StudentId . '&Source=AdminDB" class="btn btn-primary"">View</a></td>';
			}
			else
			{
				$html .= '<td><a href="./AppEvaluationForm.php?PantherId=' . $StudentId . '&Source=AdvisorDB" class="btn btn-primary"">Review</a></td>';
			}

			$html .= "</tr>";
		}
	}
}

$html.='</tbody>';
$html.='</table>';
$html.='</div>';
$html.='</div>';
$html.='</div>';
$html.='</div>';
// }


//Admission Report Start

$html.='<div class="row">';
$html.='<div class="col-lg-12">';
$html.='<div class="panel panel-default">';
$html.='<div class="panel-heading">';
$html.='Applications Status of MS Students(all are Average Values)';
$html.='</div>';
$html.='<div class="panel-body"  style="overflow:auto">';
$html.='<table width="100%" class="table table-striped table-bordered table-hover" id="studentTables-Status">';
$html.="<thead>";
$html.="<tr>";
$html .= '<th>Status</th>';
$html .= '<th>Count</th>';
$html .= '<th>GRE Quant</th>';
$html .= '<th>GRE Verbal</th>';
$html .= '<th>GRE Analtyical</th>';
$html .= '<th>TOEFL Total</th>';
$html.="</tr>";
$html.="</thead>";
$html.="<tbody>";
$sql321 = "select  COUNT(IFNULL(t1.AdmissionDecision,'Not Evaluated')) as Count,avg(GREVerbalScore)  as GREV,avg(GREQuantScore)  as GREQ,avg(GREAnalyticalScore)  as GREA,avg(IF(abs(t2.TOEFLTotal)=0||t2.TOEFLTotal='',NULL,t2.TOEFLTotal)) as TTotal,IFNULL(t1.AdmissionDecision,'Not Evaluvated')  as STATUS from tbl_student_evaluation t1,tbl_excel_info t2 where t1.StudentId=t2.PantherId AND t2.Program='MS' group by t1.AdmissionDecision";
$result321 = $mysqli->query($sql321);
if ($result321->num_rows > 0) {
	$count = 0;
	while($row321 = $result321->fetch_assoc()) {
		$SCount = $row321["Count"];
		$GREV = $row321["GREV"];
		$GREQ = $row321["GREQ"];
		$GREA = $row321["GREA"];
		$TTotal = $row321["TTotal"];
		$STATUS = $row321["STATUS"];
		$html.="<tr>";
		$html .= '<td>'.(($STATUS=="Admit")?"Admited":(($STATUS=="Reject")?"Rejected":$STATUS)).'</td>';
		$html .= '<td>'.$SCount.'</td>';
		$html .= '<td>'.round($GREQ,2).'</td>';
		$html .= '<td>'.round($GREV,2).'</td>';
		$html .= '<td>'.round($GREA,2).'</td>';
		$html .= '<td>'.round($TTotal,2).'</td>';
		$html.="</tr>";
	}
}

$html.="</tbody>";
$html.="</table>";
$html.='</div>';
$html.='</div>';
$html.='</div>';
$html.='</div>';
//Admission Report End


//Admission Report Start

$html.='<div class="row">';
$html.='<div class="col-lg-12">';
$html.='<div class="panel panel-default">';
$html.='<div class="panel-heading">';
$html.='Applications Status of PhD Students(all are Average Values)';
$html.='</div>';
$html.='<div class="panel-body"  style="overflow:auto">';
$html.='<table width="100%" class="table table-striped table-bordered table-hover" id="studentTables-Status">';
$html.="<thead>";
$html.="<tr>";
$html .= '<th>Status</th>';
$html .= '<th>Count</th>';
$html .= '<th>GRE Quant</th>';
$html .= '<th>GRE Verbal</th>';
$html .= '<th>GRE Analtyical</th>';
$html .= '<th>TOEFL Total</th>';
$html.="</tr>";
$html.="</thead>";
$html.="<tbody>";


$sql321 = "select  COUNT(IFNULL(t1.AdmissionDecision,'Not Evaluated')) as Count,avg(GREVerbalScore)  as GREV,avg(GREQuantScore)  as GREQ,avg(GREAnalyticalScore)  as GREA,avg(IF(abs(t2.TOEFLTotal)=0||t2.TOEFLTotal='',NULL,t2.TOEFLTotal)) as TTotal,IFNULL(t1.AdmissionDecision,'Not Evaluvated')  as STATUS from tbl_student_evaluation t1,tbl_excel_info t2 where t1.StudentId=t2.PantherId AND t2.Program='PhD' group by t1.AdmissionDecision";
$result321 = $mysqli->query($sql321);
if ($result321->num_rows > 0) {
	$count = 0;
	while($row321 = $result321->fetch_assoc()) {
		$SCount = $row321["Count"];
		$GREV = $row321["GREV"];
		$GREQ = $row321["GREQ"];
		$GREA = $row321["GREA"];
		$TTotal = $row321["TTotal"];
		$STATUS = $row321["STATUS"];
		$html.="<tr>";
		$html .= '<td>'.(($STATUS=="Admit")?"Admited":(($STATUS=="Reject")?"Rejected":$STATUS)).'</td>';
		$html .= '<td>'.$SCount.'</td>';
		$html .= '<td>'.round($GREQ,2).'</td>';
		$html .= '<td>'.round($GREV,2).'</td>';
		$html .= '<td>'.round($GREA,2).'</td>';
		$html .= '<td>'.round($TTotal,2).'</td>';
		$html.="</tr>";
	}
}

$html.="</tbody>";
$html.="</table>";
$html.='</div>';
$html.='</div>';
$html.='</div>';
$html.='</div>';
//Admission Report End


$sql12 = "SELECT StudentId FROM tbl_student_evaluation WHERE Status='Complete' and AdmissionDecision is not null";
$result12 = $mysqli->query($sql12);


$html.='<div class="row">';
$html.='<div class="col-lg-12">';
$html.='<div class="panel panel-default">';
$html.='<div class="panel-heading">';
$html.='Completed by both Director Of Graduate Studies and Faculty';
$html.='</div>';
$html.='<div class="panel-body"  style="overflow:auto">';
$html.='<table width="100%" class="table table-striped table-bordered table-hover" id="studentTables-Completed">';
$html.="<thead>";
$html.="<tr>";
$html .= '<th>PantherID</th>';
$html .= '<th>Applicant Name</th>';
$html .= '<th>Program</th>';
$html .= '<th>Term</th>';
$html .= '<th>GRE Total</th>';
$html .= '<th>TOEFL Total</th>';
$html .= '<th>Faculty Decision</th>';
$html .= '<th>Advisor Decision</th>';
$html .= '<th>Faculty</th>';
if($user_role == "Admin")
	$html .= '<th>View</th>';
	else
		$html .= '<th>Review</th>';
		$html.="</tr>";
		$html.="</thead>";
		$html.="<tbody>";

		if ($result12->num_rows > 0) {
			$count = 0;
			while($row12 = $result12->fetch_assoc()) {
				$StudentId = $row12["StudentId"];
				$sql3 = "SELECT  * FROM tbl_excel_info ex1,tbl_student_evaluation te1 where te1.StudentId=ex1.PantherId and  ex1.PantherId= ".$StudentId;
				$result3 = $mysqli->query($sql3);

				if($result3-> num_rows > 0)
				{
					while($row3 = $result3->fetch_assoc())
					{
						$PantherId = $row3["PantherId"];
						$FirstName=$row3["FirstName"];
						$LastName=$row3["LastName"];
						$email=$row3["EMail"];
						$Program=$row3["Program"];
						$Term=$row3["Term"];
						$Concentration=$row3["Concentration"];
						$FacEmail = $row3["FacultyName"];
						$GREVerbalScore=$row3["GREVerbalScore"];
						$GREQuantScore=$row3["GREQuantScore"];
						$TOEFLTotal=$row3["TOEFLTotal"];
						$FacultyD=$row3["Recommendation"];
						$AdvisorD = $row3["AdmissionDecision"];
						$GRETotal = $GREVerbalScore + $GREQuantScore;
						//File Access
			$ApplicationID=$row3["ApplicantID"];

                        $linkaddress=$row3["linkaddress"];
			$FileName = strtolower($LastName).strtolower($FirstName).'.pdf';
			$files = null;
			if(is_numeric($ApplicationID))
			{
				$files = glob($root."/Applications/*".$ApplicationID.".pdf");
			
				if(count($files) != 0)
				{
					$filename = basename($files[0]);
					$FullFileName = null;
					$filenamelocation='./../../Applications/'.$filename;
					
				}
				else
				{
					$filenamelocation = null;
					$FullFileName ='./../../Applications/'.$FileName;
					
					$filename = basename($FullFileName);
				}
			}
			else
			{
				$filenamelocation = null;
				$FullFileName ='./../../Applications/'.$FileName;
				
				$filename = basename($FullFileName);
			}
			//File Access End
					}

					$html.="<tr class='odd gradeX'>";
					$html .= '<td>'.$PantherId.'</td>';
                    if (!empty($linkaddress))
                    {
                        $html .= '<td><a href="' . $linkaddress . '" target="_blank">' .$FirstName.' '.$LastName.'</td>';
                    }
                    else {
                        if (file_exists($filenamelocation)) {
                            $html .= '<td><a href="' . $filenamelocation . '" target="_blank">' . $FirstName . ' ' . $LastName . '</td>';
                        } else if (file_exists($FullFileName)) {
                            $html .= '<td><a href="' . $FullFileName . '" target="_blank">' . $FirstName . ' ' . $LastName . '</a></td>';
                        } else {
                            $html .= '<td>' . $FirstName . ' ' . $LastName . '</td>';
                            $filename = null;
                            $fileSize = null;
                        }
                    }
					$html .= '<td>' . $Program . '</td>';

					$html .= '<td>'.$Term.'</td>';
					$html .= '<td>'.$GRETotal.'</td>';
					$html .= '<td>'.$TOEFLTotal.'</td>';
						
					$html .= '<td>'.$FacultyD.'</td>';
					$html .= '<td>'.$AdvisorD.'</td>';
					$html .= '<td>'.$FacEmail.'</td>';

						$html .= '<td><a href="./AppEvaluationForm.php?PantherId='.$StudentId.'&Source=FinalDB" class="btn btn-primary"">View</a></td>';
							$html .= "</tr>";
				}
			}
		}

				$html.='</tbody>';
				$html.='</table>';
				$html.='</div>';
				$html.='</div>';
				$html.='</div>';
				$html.='</div>';

$html.='<div class="row">';
$html.='<div class="col-lg-12">';
$html.='<div class="panel panel-default">';
$html.='<div class="panel-heading">';
$html.='Pending at Faculty';
$html.='</div>';
$html.='<div class="panel-body"  style="overflow:auto">';
$html.='<table width="100%" class="table table-striped table-bordered table-hover" id="studentTables-PendingatFaculty">';
$html.="<thead>";
$html.="<tr>";		
$html .= '<th>PantherID</th>';
$html .= '<th>Applicant Name</th>';
$html .= '<th>Program</th>';
$html .= '<th>Term</th>';
$html .= '<th>Assigned On</th>';
$html .= '<th>Reminded On</th>';
$html .= '<th>Faculty</th>';
if($user_role != "Admin")
$html .= '<th>Remind</th>';
$html .= '<th>View</th>';
$html.="</tr>";
$html.="</thead>";
$html.="<tbody>";

if ($result2->num_rows > 0) {
$count = 0;
while($row2 = $result2->fetch_assoc()) {	
		$StudentId = $row2["StudentId"];
		$sql4 = "SELECT  * FROM tbl_excel_info  ex1,tbl_student_evaluation te1 where te1.StudentId=ex1.PantherId and  ex1. PantherId= ".$StudentId;
		$result4 = $mysqli->query($sql4);
		
		if($result4-> num_rows > 0)
		{
		while($row4 = $result4->fetch_assoc()) 
		{
			$PantherId = $row4["PantherId"];
			$FirstName=$row4["FirstName"];
			$LastName=$row4["LastName"];
			$email=$row4["EMail"];
			$Program=$row4["Program"];
			$Term=$row4["Term"];
			$Concentration=$row4["Concentration"];
			$FacEmail = $row4["FacultyName"];
			$assignDate = $row4["AssignDate"];
			$remaindedOn = $row4["RemindDate"];
			//File Access
			$ApplicationID=$row4["ApplicantID"];
            $linkaddress=$row["linkaddress"];
			$FileName = strtolower($LastName).strtolower($FirstName).'.pdf';
			$files = null;
			if(is_numeric($ApplicationID))
			{
				$files = glob($root."/Applications/*".$ApplicationID.".pdf");
					
				if(count($files) != 0)
				{
					$filename = basename($files[0]);
					$FullFileName = null;
					$filenamelocation='./../../Applications/'.$filename;
						
				}
				else
				{
					$filenamelocation = null;
					$FullFileName ='./../../Applications/'.$FileName;
						
					$filename = basename($FullFileName);
				}
			}
			else
			{
				$filenamelocation = null;
				$FullFileName ='./../../Applications/'.$FileName;
			
				$filename = basename($FullFileName);
			}
			//File Access End
			
		}

			$html.="<tr class='odd gradeX'>";	
			$html .= '<td>'.$PantherId.'</td>';
		if(file_exists($filenamelocation)){	
								$html .= '<td><a href="'.$filenamelocation.'" target="_blank">'.$FirstName.' '.$LastName.'</td>';
							}else if(file_exists($FullFileName)){
								$html .= '<td><a href="'.$FullFileName.'" target="_blank">'.$FirstName.' '.$LastName.'</a></td>';
							}
							else {
								$html .= '<td>'.$FirstName.' '.$LastName.'</td>';
								$filename = null;
								$fileSize = null;
							}
            if (empty($linkaddress))
            {
                $html .= '<td>' . $Program . '</td>';
            }
            else
            {

                $html .= '<td><a href="' . $linkaddress . '" target="_blank">' . $Program . '</td>';
            }
			//$html .= '<td>'.$Program.'</td>';
			$html .= '<td>'.$Term.'</td>';
			$html .= '<td>'.$assignDate.'</td>';
			$html .= '<td>'.$remaindedOn.'</td>';
			$html .= '<td>'.$FacEmail.'</td>';
			if($user_role != "Admin")
			$html .= '<td><a href="#" id="'.$PantherId.'" class="btn btn-primary" onclick="remindFaculty(this.id);">Remind</a></td>';
			$html .= '<td><a href="./AppEvaluationForm.php?PantherId='.$StudentId.'&Source=FinalDB" class="btn btn-primary"">View</a></td>';
			$html .= "</tr>";
		}
}
}

$html.='</tbody>';
$html.='</table>';
$html.='</div>';
$html.='</div>';
$html.='</div>';
$html.='</div>';

$mysqli->close();

echo $html;
?>

