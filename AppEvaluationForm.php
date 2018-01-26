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
include $root.'/authenticate.php';
if(isset($_GET['PantherId'])) {
	$patherid = $_GET['PantherId'];
}
if(isset($_GET['Source'])) {
	$Source = $_GET['Source'];
}
if(!$patherid) {
	$patherid = 0;
}


include('./../../osms.dbconfig.inc');
$error_message = "";

$mysqli = new mysqli($hostname,$username, $password,$dbname);

/* check connection */
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}

$sql = "SELECT  * FROM tbl_excel_info where PantherId= ".$patherid;
$sql1 = "SELECT  * FROM tbl_excel_ext_info where PantherID= ".$patherid;
$sql2 = "SELECT * FROM tbl_student_evaluation WHERE StudentId= ".$patherid;
$sql3 = "SELECT * FROM tbl_foundation_courses WHERE StudentId= ".$patherid;

//echo $sql2;
$result = $mysqli->query($sql);
$result1 = $mysqli->query($sql1);
$result2 = $mysqli->query($sql2);
$result3 = $mysqli->query($sql3);

$row_cnt = $result->num_rows;

if ($result->num_rows > 0) {
	// output data of each row
	
	while($row = $result->fetch_assoc()) {

			$PantherId=$row["PantherId"];
			$FirstName=$row["FirstName"];
			$MiddleName=$row["MiddleName"];
			$LastName=$row["LastName"];
			$Program=$row["Program"];
			$Term=$row["Term"];
			$Concentration=$row["Concentration"];
			$GREVerbalScore=$row["GREVerbalScore"];
			$GREVerbalPercent=$row["GREVerbalPercent"];
			$GREQuantScore=$row["GREQuantScore"];
			$GREQuantPercent=$row["GREQuantPercent"];
			$GREAnalyticalScore=$row["GREAnalyticalScore"];
			$GREAnalyticalPercent=$row["GREAnalyticalPercent"];
			$GRETotal=$row["GRETotal"];
			$TOEFLTotal=$row["TOEFLTotal"];
			$Faculty=$row["Faculty"];
			$WorkExp=$row["WorkExp"];
			$ResearchExp=$row["ResearchExp"];
			$CollegeName1=$row["CollegeName1"];
			$DateAttendedFrom1=$row["DateAttendedFrom1"];
			$DateAttendedTo1=$row["DateAttendedTo1"];
			$Major1=$row["Major1"];
			$Degree1=$row["Degree1"];
			$CollegeName2=$row["CollegeName2"];
			$DateAttendedFrom2=$row["DateAttendedFrom2"];
			$DateAttendedTo2=$row["DateAttendedTo2"];
			$Major2=$row["Major2"];
			$Degree2=$row["Degree2"];
			$CollegeName3=$row["CollegeName3"];
			$DateAttendedFrom3=$row["DateAttendedFrom3"];
			$DateAttendedTo3=$row["DateAttendedTo3"];
			$Major3=$row["Major3"];
			$Degree3=$row["Degree3"];
			$UgGPAOverall=$row["UgGPAOverall"];
			$GraduateGPA=$row["GraduateGPA"];
			}
			}

if ($result1->num_rows > 0) {
	// output data of each row
	
	while($row = $result1->fetch_assoc()) {
			$WorkExp=$row["WExp"];
			$ResearchExp=$row["RExp"];
			$DSGrade=$row["ds"];
			$SEGrade=$row["se"];
			$AutomataGrade=$row["automata"];
			$AlgoGrade=$row["algo"];
			$OSGrade=$row["os"];
			$PLGrade=$row["proLan"];
			$CaGrade=$row["ca"];
			$CalculusGrade=$row["calculus"];
			$DMGrade=$row["discrete"];
			$OtherGrade=$row["other"];
			}
			}

			$date = date("Y-m-d");
			
if ($result2->num_rows > 0) {
	// output data of each row
	
	while($row = $result2->fetch_assoc()) {
			$LOR1=$row["LOR1"];
			$LOR2=$row["LOR2"];
			$LOR3=$row["LOR3"];
			$SOP=$row["SOP"];
			$Admit=$row["Recommendation"];
			$Aid=$row["FinAid"];
			$AAid=$row["AFinAid"];
			$Comments=$row["Comments"];
			$AdvisorComments = $row["AdvisorComments"];
			$FacultyName=$row["FacultyName"];
			$Date=($row["Date"]!=""?$row["Date"]:$date);
			$AdvDec=$row["AdmissionDecision"];
			}
			}
if ($result3->num_rows > 0) {
	$foundation_courses = array();

	while($row = $result3->fetch_assoc()) {
        if ($row["DS"] == 1) {
            $foundation_courses['ds'] = '1';
        } else {
            $foundation_courses['ds'] = '0';
        }
        if ($row["SE"] == 1) {
            $foundation_courses['se'] = '1';
        } else {
            $foundation_courses['se'] = '0';
        }
        if ($row["AA"] == 1) {
            $foundation_courses['aa'] = '1';
        }
        if ($row["OS"] == 1) {
            $foundation_courses['os'] = '1';
        }else {
            $foundation_courses['os'] = '0';
        }

        if ($row["PL"] == 1) {
            $foundation_courses['pl'] = '1';
        }else {
            $foundation_courses['pl'] = '0';
        }
        if ($row["CA"] == 1) {
            $foundation_courses['ca'] = '1';
        }else {
            $foundation_courses['ca'] = '0';
        }
        if ($row["Automata"] == 1) {
            $foundation_courses['automata'] = '1';
        }else {
            $foundation_courses['automata'] = '0';
        }
        if ($row["Calculus"] == 1) {
            $foundation_courses['calculus'] = 'calculus';
        }else {
            $foundation_courses['calculus'] = '0';
        }
        if ($row["DM"] == 1) {
            $foundation_courses['dm'] = 'dm';
        }else {
            $foundation_courses['dm'] = '0';
        }
    }
}

echo 'foundation_courses:'.implode(",", $foundation_courses);
if($Source == "AdminDB")
{
	$Nsql = "select * from tbl_student_evaluation where  StudentId = (select min(StudentId) from tbl_faculty_info f1,tbl_student_evaluation s1 where s1.status='Complete' and s1.FacultyId = f1.email and f1.email=(select FacultyId from tbl_student_evaluation s2 where  s2.StudentId= ".$patherid.") and s1.StudentId >".$patherid.")";
	$Psql = "select * from tbl_student_evaluation where  StudentId = (select max(StudentId) from tbl_faculty_info f1,tbl_student_evaluation s1 where s1.status='Complete' and s1.FacultyId = f1.email and f1.email=(select FacultyId from tbl_student_evaluation s2 where  s2.StudentId= ".$patherid.") and s1.StudentId <".$patherid.")";
	
}else if($Source == "FacultyDB")
{	
	$Nsql = "select * from tbl_student_evaluation where StudentId = (select min(StudentId) from tbl_faculty_info f1,tbl_student_evaluation s1 where s1.status='Pending'   and  s1.FacultyId = f1.email and f1.email=(select FacultyId from tbl_student_evaluation s2 where  s2.StudentId= ".$patherid.") and s1.StudentId >".$patherid.")";
	$Psql = "select * from tbl_student_evaluation where StudentId = (select max(StudentId) from tbl_faculty_info f1,tbl_student_evaluation s1 where s1.status='Pending'   and  s1.FacultyId = f1.email and f1.email=(select FacultyId from tbl_student_evaluation s2 where  s2.StudentId= ".$patherid.") and s1.StudentId <".$patherid.")";
}
//echo $sql2;
$Nresult = $mysqli->query($Nsql);
$Presult = $mysqli->query($Psql);

$NpantherID=$patherid;
$PPantherID = $pantherId;

$row_cnt = $Nresult->num_rows;

if ($Nresult->num_rows > 0) {
	// output data of each row

	while($row = $Nresult->fetch_assoc()) {

		$NpantherID=$row["StudentId"];
	}
}
if ($Presult->num_rows > 0) {
	// output data of each row

	while($row = $Presult->fetch_assoc()) {

		$PPantherID=$row["StudentId"];
	}
}
//echo $DSGrade;
//echo preg_replace("/[^A-Za-z0-9]/", '', strtolower($DSGrade));
//echo $AutomataGrade;
//echo preg_replace("/[^A-Za-z0-9]/", '', strtolower($AutomataGrade));

//echo '$Source'.$Source;
//if ($Source!= "AdvisorDB")
//{
//    echo 'false';
//}
//else
//{
//    echo 'true';
//}
?>
<html lang="en">
	<!-- Header -->
		<?php
		include $root.'/links/header.php';
		include $root.'/links/footerLinks.php';
		?>
		<style>
		input[type=text], select {
			text-align: center;
		}
		</style>
<!-- /#Header -->
<body>

	<!-- wrapper -->
    <div id="wrapper">

        <!-- Navigation -->
        <?php  
        include $root.'/UI/staff/staffmenu.php';
        ?>
        <!-- /#Navigation -->

		<!-- page-wrapper -->
		<div id="page-wrapper">
		<div style="float: left; margin-top:20px;" > <a href="./AppEvaluationForm.php?PantherId=<?php echo $PPantherID  ?>&Source=<?php echo $Source; ?>" class="btn btn-primary" <?php if($PPantherID==0){echo 'style="visibility : hidden;"';}?>>previous</a></div>
		<div style="float: right; margin-top:20px;" > <a href="./AppEvaluationForm.php?PantherId=<?php echo $NpantherID  ?>&Source=<?php echo $Source; ?>" class="btn btn-primary" <?php if($NpantherID==$patherid){echo 'style="visibility : hidden;"';}?>>Next</a></div>
		
    <button style="float: right; margin-top:20px;" type="button" class="btn btn-default" onclick="printDiv('printableArea')">
      <span class="glyphicon glyphicon-print"></span> Print
    </button>
         <div class="row" id="printableArea">
<div style="width:100%; margin: 0 auto; border: 2px solid #000000; padding: 10px 10px 10px 10px">
<form>
<div style="width:100%; margin: 0 auto;">
	<h3 style="margin: 0; text-align:center">Georgia State University</h3>
	<h3 style="margin: 0; text-align:center">Department of Computer Science</h3>
	<h3 style="text-align:center;">GRADUATE APPLICATION EVALUATION</h3>
	<h4 style="margin: 0; text-align:center;">(Subject to Open Records Act)</h4>
</div>
<br/>
<br/>

<table class="table table-bordered table-responsive table-default table-striped" style="table-layout: fixed;">
<tr>
<th>Applicant</th>
<th>Student Number</th>
<th>Degree</th>
<th>Concentration</th>
</tr>
<tr>
<td><input type="text" class="form-control" id="applicantName" value="<?=$FirstName.' '.$LastName?>" name="applicantName" readonly="readonly"></td>
<td><input name="studentNumber" hidden="true" value="<?=$PantherId?>"><input type="text" class="form-control" id="studentNumber" name="studentNumber1" value="<?=($PantherId!=''?'00'.$PantherId:'')?>" readonly="readonly"></td>
<td><input type="text" class="form-control" id="degree" value="<?=$Program?>" readonly="readonly"></td>
<td><input type="text" class="form-control" id="concentration" value="<?=$Concentration?>" readonly="readonly"></td>
</tr>
</table>
&nbsp; &nbsp;
<div id="mainDiv">
<table class="table table-bordered table-responsive table-default table-striped">
<tr>
<th  >S.NO</th>
<th  >Years Attended</th>
<th  >Degree/ Diploma</th>
<th  >Institution</th>
<th  >GPA</th>
</tr>
<tr>
<td><label> 1. </td>
<td><input type="text" class="form-control"  id="dates1" value="<?php if(!empty($DateAttendedFrom1)){echo $DateAttendedFrom1.'-'.$DateAttendedTo1;}?>"></td>
<td><input type="text" class="form-control"  id="degree1" value="<?=$Degree1?>"></td>
<td><input type="text" class="form-control"  id="college1" value="<?=$CollegeName1?>"></td>
<td><input type="text" class="form-control"  id="gradGPA" value="<?php if(!empty($GraduateGPA)){echo 'GraduateGPA:'.$GraduateGPA;}?>"></td>
</tr>
<tr>
<td><label> 2. </td>
<td><input type="text" class="form-control" id="dates2" value="<?php if(!empty($DateAttendedFrom2)){echo $DateAttendedFrom2.'-'.$DateAttendedTo2;}?>"></td>
<td><input type="text" class="form-control" id="degree2" value="<?=$Degree2?>"></td>
<td><input type="text" class="form-control" id="college2" value="<?=$CollegeName2?>"></td>
<td><input type="text" class="form-control" id="ugGpa" value="<?php if(!empty($UgGPAOverall)){echo 'UndergraduateGPA:'.$UgGPAOverall;}?>"></td>
</tr>
<tr>
<td><label> 3. </td>
<td><input type="text" class="form-control" id="dates3" value="<?php if(!empty($DateAttendedFrom3)){echo $DateAttendedFrom3.'-'.$DateAttendedTo3;}?>"></td>
<td><input type="text" class="form-control" id="degree3" value="<?=$Degree3?>"></td>
<td><input type="text" class="form-control" id="college3" value="<?=$CollegeName3?>"></td>
<td><input type="text" class="form-control"></td>
</tr>
</table>

<br/>


<div style="float:left">
<h4>GRE</h4>
<label> Verbal 
<input style="margin-left: 40px;" type="text" id="greVerbalScore" value="<?=$GREVerbalScore?>"><input  style="margin-left: 20px;" type="text" id="greVerbalPercent" value="<?=$GREVerbalPercent?>"><span>%</span>
<br/>
<br/>
<label> Quantitative
<input type="text" id="greQuantScore" value="<?=$GREQuantScore?>"><input style="margin-left: 20px;" type="text" id="greQuantPercent" value="<?=$GREQuantPercent?>"><span>%</span>
<br/>
<br/>
<label> Total V+Q
    <input style="margin-left:20px;" type="text" id="greTotal" value="<?php if(empty($GRETotal)){echo ($GREQuantScore+$GREVerbalScore);} else {echo $GRETotal;}?>">
    <br/>
<br/>
<label> Analytical
<input style="margin-left:20px;" type="text" id="greAnaScore" value="<?=$GREAnalyticalScore?>"><input style="margin-left: 20px;" type="text" id="greAnaPercent" value="<?=$GREAnalyticalPercent?>"><span>%</span>
<br/>
<br/>
</div>
<div style="float:left; margin-left: 60px; margin-top: 20px;">
<h4 style="display:inline;">TOEFL</h4>
<input style="margin-left:20px;" type="text" id="toeflTotal" value="<?=$TOEFLTotal?>">
</div>
</div>
    <br>


    <div style="float:left" id="workExpandresearchExpDiv">
        <div>
            <h4 style="color: red;"> Make sure you check the work experience, research experience and  the transcript for all foundation courses listed below.</h4>
        </div>
        <label style="font-weight: bold;"> Work Experience
            <select id="workExp" name="workExp">
                <option value="None" <?=$WorkExp == 'None' ? ' selected="selected"' : '';?>>None</option>
                <option value="1 Year" <?=$WorkExp == '1 Year' ? ' selected="selected"' : '';?>>1 Year</option>
                <option value="2 Years" <?=$WorkExp == '2 Years' ? ' selected="selected"' : '';?>>2 Years</option>
                <option value="3 Years" <?=$WorkExp == '3 Years' ? ' selected="selected"' : '';?>>3 years</option>
                <option value="4 Years" <?=$WorkExp == '4 Years' ? ' selected="selected"' : '';?>>> 4 Years</option>
            </select>
            <!-- <input style="margin-left: 40px;" type="" id="workExp" value="<?=$GREVerbalScore?>"> -->
            &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;<label style="font-weight: bold;"> Research Experience
                <select id="researchExp" name="researchExp">
                    <option value="New" <?=$ResearchExp == 'New' ? ' selected="selected"' : '';?>>New</option>
                    <option value="Fair" <?=$ResearchExp == 'Fair' ? ' selected="selected"' : '';?>>Fair</option>
                    <option value="Good" <?=$ResearchExp == 'Good' ? ' selected="selected"' : '';?>>Good</option>
                    <option value="Very Good" <?=$ResearchExp == 'Very Good' ? ' selected="selected"' : '';?>>Very Good</option>
                    <option value="Excellent" <?=$ResearchExp == 'Excellent' ? ' selected="selected"' : '';?>>Excellent</option>
                </select>
                <!-- <input style="margin-left: 20px;" type="text" id="researchExp" value="<?=$GREVerbalPercent?>"> -->

    </div>
    <br/>

<div id="foundationcourseDiv">
<h3 style="clear:both;">Computer Science Courses</h3>
<table class="table table-bordered table-responsive table-default table-striped" style="table-layout: fixed;">
<tr>
<td><label for="ds">Data Structures</label><input type="text" class="form-control" id="ds" name="ds" value="<?=$DSGrade?>"></td>
<td><label for="se">Software Engineering</label><input type="text" class="form-control" id="se" name="se" value="<?=$SEGrade?>"></td>
<td><label for="automata">Automata</label><input type="text" class="form-control" id="automata" name="automata" value="<?=$AutomataGrade?>"></td>
<td><input type="text" class="form-control"></td>
</tr>
<tr>
<td><label for="algo">Algorithm Analysis</label><input type="text" class="form-control" id="algo" name="algo" value="<?=$AlgoGrade?>"></td>
<td><label for="os">Operating Systems</label><input type="text" class="form-control" id="os" name="os" value="<?=$OSGrade?>"></td>
<td><input type="text" class="form-control"></td>
<td><input type="text" class="form-control"></td>
</tr>
<tr>
<td><label for="proLan">Programming Languages</label><input type="text" class="form-control" id="proLan" name="proLan" value="<?=$PLGrade?>"></td>
<td><label for="ca">Computer Architecture</label><input type="text" class="form-control" id="ca" name="ca" value="<?=$CaGrade?>"></td>
<td><input type="text" class="form-control"></td>
<td><input type="text" class="form-control"></td>
</tr>
</table>
&nbsp; &nbsp;
<h3>Mathematics Courses</h3>
<table class="table table-bordered table-responsive table-default table-striped" style="table-layout: fixed;">
<tr>
<td colspan="2"><label for="calculus">Calculus(at least two courses required)</label><input type="text" class="form-control" id="calculus" name="calculus" value="<?=$CalculusGrade?>"></td>
<td><label for="discrete">Discrete Mathematics</label><input type="text" class="form-control" id="discrete" name="discrete" value="<?=$DMGrade?>"></td>
<td> <label  >Other</label><input type="text" class="form-control" id="other" name="other" value="<?=$OtherGrade?>"></td>
</tr>
<tr>
<td><input type="text" class="form-control"></td>
<td><input type="text" class="form-control"></td>
<td><input type="text" class="form-control"></td>
<td><input type="text" class="form-control"></td>
</tr>
</table>
</div>
<div id="subDiv">
<div id="lor1Div">
<h4 style="display:inline; margin-right:60px;">Letter of Recommendation #1:</h4>
  <input type="checkbox" id="recommend1Excellent" name="lor1" value="Excellent" <?=$LOR1 == 'Excellent' ? 'checked' : '';?>> Excellent </input>
  <input type="checkbox" id="recommend1VGood" name="lor1" value="Very Good" <?=$LOR1 == 'Very Good' ? 'checked' : '';?>> Very Good </input>
  <input type="checkbox" id="recommend1Good" name="lor1" value="Good" <?=$LOR1 == 'Good' ? 'checked' : '';?>> Good </input>
  <input type="checkbox" id="recommend1Fair" name="lor1" value="Fair" <?=$LOR1 == 'Fair' ? 'checked' : '';?>> Fair </input>
  <input type="checkbox" id="recommend1Poor" name="lor1" value="Poor" <?=$LOR1 == 'Poor' ? 'checked' : '';?>> Poor </input>
  <input type="checkbox" id="recommend1Poor" name="lor1" value="Poor" <?=$LOR1 == 'NA' ? 'checked' : '';?> > NA 
</div>
 <br/>
 <br/>
<div id="lor2Div">
<h4 style="display:inline; margin-right:60px;">Letter of Recommendation #2:</h4>
  <input type="checkbox" id="recommend2Excellent" name="lor2" value="Excellent" <?=$LOR2 == 'Excellent' ? 'checked' : '';?>/> Excellent
  <input type="checkbox" id="recommend2VGood" name="lor2" value="Very Good" <?=$LOR2 == 'Very Good' ? 'checked' : '';?>/> Very Good
  <input type="checkbox" id="recommend2Good" name="lor2" value="Good" <?=$LOR2 == 'Good' ? 'checked' : '';?>/> Good
  <input type="checkbox" id="recommend2Fair" name="lor2" value="Fair" <?=$LOR2 == 'Fair' ? 'checked' : '';?>/> Fair
  <input type="checkbox" id="recommend2Poor" name="lor2" value="Poor" <?=$LOR2 == 'Poor' ? 'checked' : '';?>/> Poor
  <input type="checkbox" id="recommend1Poor" name="lor1" value="Poor" <?=$LOR2 == 'NA' ? 'checked' : '';?> > NA 
</div>
 <br/>
 <br/>
<div id="lor3Div">
<h4 style="display:inline; margin-right:60px;">Letter of Recommendation #3:</h4>
  <input type="checkbox" id="recommend3Excellent" name="lor3" value="Excellent" <?=$LOR3 == 'Excellent' ? 'checked' : '';?>/> Excellent
  <input type="checkbox" id="recommend3VGood" name="lor3" value="Very Good" <?=$LOR3 == 'Very Good' ? 'checked' : '';?>/> Very Good
  <input type="checkbox" id="recommend3Good" name="lor3" value="Good" <?=$LOR3 == 'Good' ? 'checked' : '';?>/> Good
  <input type="checkbox" id="recommend3Fair" name="lor3" value="Fair" <?=$LOR3 == 'Fair' ? 'checked' : '';?>/> Fair
  <input type="checkbox" id="recommend3Poor" name="lor3" value="Poor" <?=$LOR3 == 'Poor' ? 'checked' : '';?>/> Poor
  <input type="checkbox" id="recommend1Poor" name="lor1" value="Poor" <?=$LOR3 == 'NA' ? 'checked' : '';?> > NA 
</div>
 <br/>
 <br/>
<div id="sopDiv">
<h4 style="display:inline; margin-right:10px;">Statement of background and goals: </h4>
  <input type="checkbox" id="sopExcellent" name="sop" value="Excellent" <?=$SOP == 'Excellent' ? 'checked' : '';?>/> Excellent
  <input type="checkbox" id="sopVGood" name="sop" value="Very Good" <?=$SOP == 'Very Good' ? 'checked' : '';?>/> Very Good
  <input type="checkbox" id="sopGood" name="sop" value="Good" <?=$SOP == 'Good' ? 'checked' : '';?>/> Good
  <input type="checkbox" id="sopFair" name="sop" value="Fair" <?=$SOP == 'Fair' ? 'checked' : '';?>/> Fair
  <input type="checkbox" id="sopPoor" name="sop" value="Poor" <?=$SOP == 'Poor' ? 'checked' : '';?>/> Poor
  <input type="checkbox" id="recommend1Poor" name="lor1" value="Poor" <?=$LOR1 == 'NA' ? 'checked' : '';?> > NA 
</div>
  <br/>
&nbsp;
<div id="recDiv">
<h3>Recommendation</h3>
  <input type="checkbox" id="recommAdmit" name="admit" value="Admit" <?=$Admit == 'Admit' ? 'checked' : '';?>/> Admit - Full Status (unqualified admission)
  <br/>
  <input type="checkbox" id="recommReject" name="admit" value="Reject" <?=$Admit == 'Reject' ? 'checked' : '';?>/> Reject - Will NOT reconsider
  <br/>
  <input type="checkbox" id="recommDeferred" name="admit" value="Deferred" <?=$Admit == 'Deferred' ? 'checked' : '';?>/> Action Deferred (needs undergraduate coursework indicated below) - Post-baccalaureate status
</div>
<br/>
<br/>
<div id="aidDiv">
<h4>Financial Aid:</h4>
  <input type="checkbox" id="aidSRecomm" name="aid" value="Strongly" <?=$Aid == 'Strongly' ? 'checked' : '';?>/> Strongly recommend
  <input type="checkbox" id="aidRecomm" name="aid" value="Recommend" <?=$Aid == 'Recommend' ? 'checked' : '';?>/> Recommend
  <input type="checkbox" id="aidWRecomm" name="aid" value="Weakly" <?=$Aid == 'Weakly' ? 'checked' : '';?>/> Weakly recommend
  <input type="checkbox" id="aidNotRecomm" name="aid" value="No" <?=$Aid == 'No' ? 'checked' : '';?>/> Do not recommend
</div>
<br/>
<br/>
<h4>Comments</h4>
<textarea cols="100" rows="5" id="comments" name="comments"><?php echo htmlspecialchars($Comments); ?></textarea>
<br/>
<br/>
<table class="table table-bordered table-responsive table-default table-striped">
<tr>
<td><label for="facultyName">Faculty Member </label><input style="margin-left:10px; width:250px;" <?php if($Source != "AdvisorDB" && $Source != "Admin") echo( ' readonly="readonly" ') ?>  type="text" id="facultyName" name="facultyName" value="<?=$FacultyName?>"></td>
<td><label for="date">Date</label><input style="margin-left:10px;"  <?php if($Source != "AdvisorDB" && $Source != "Admin") echo( ' readonly="readonly" ') ?>  type="date" id="date" name="date" value="<?=$Date?>"></td>
</tr>
</table>
<br/>
<br/>
<div id="advisorDiv">
<hr style="border-width:2px;">
<h4 align="center"><b>Director Of Graduate Studies</b></h4>
<br/>

<div id="foundationCourses" style="float: left; margin-right: 40px;">
<h5><b><i>Please select Foundation Courses for this applicant</i></b></h5>
<div style="float: left; margin-right: 15px;">
<div class="checkbox"><label ><input type="checkbox" name="foundationCourses[0]" value="ds" <?php if(!empty($foundation_courses)) { if($foundation_courses['ds']==1){echo 'checked';}} else if(empty($DSGrade)|| (preg_replace("/[^A-Za-z0-9]/", '', strtolower($DSGrade))=='na')){ echo 'checked';}?>>Data Structures 3410</label></div>
<div class="checkbox"><label ><input type="checkbox" name="foundationCourses[1]" value="se" <?php if(!empty($foundation_courses)) { if($foundation_courses['se']==1){echo 'checked';}} else if(empty($SEGrade)|| (preg_replace("/[^A-Za-z0-9]/", '', strtolower($SEGrade))=='na')){ echo 'checked';}?>>Software Engineering 6350</label></div>
</div>
<div style="float: left; margin-right: 15px;">
<div class="checkbox"><label ><input type="checkbox" name="foundationCourses[2]" value="aa" <?php if(!empty($foundation_courses)) { if($foundation_courses['aa']==1){echo 'checked';}}  else if(empty($AlgoGrade)|| (preg_replace("/[^A-Za-z0-9]/", '', strtolower($AlgoGrade))=='na')){ echo 'checked';}?>>Algorithm Analysis 6520</label></div>
<div class="checkbox"><label ><input type="checkbox" name="foundationCourses[3]" value="os" <?php if(!empty($foundation_courses)) { if($foundation_courses['os']==1){echo 'checked';}}  else if(empty($OSGrade)|| (preg_replace("/[^A-Za-z0-9]/", '', strtolower($OSGrade))=='na')){ echo 'checked';}?>>Operating Systems 6320</label></div>
</div>
<div style="float: left; margin-right: 15px;">
<div class="checkbox"><label ><input type="checkbox" name="foundationCourses[4]" value="pl" <?php if(!empty($foundation_courses)) { if($foundation_courses['pl']==1){echo 'checked';}}  else if(empty($PLGrade)|| (preg_replace("/[^A-Za-z0-9]/", '', strtolower($PLGrade))=='na')){ echo 'checked';}?>>Programming Language 6330</label></div>
<div class="checkbox"><label ><input type="checkbox" name="foundationCourses[5]" value="ca" <?php if(!empty($foundation_courses)) { if($foundation_courses['ca']==1){echo 'checked';}}  else if(empty($CaGrade)|| (preg_replace("/[^A-Za-z0-9]/", '', strtolower($CaGrade))=='na')){ echo 'checked';}?>>Computer Architecture 6210</label></div>
</div>
<div style="float: left; margin-right: 10px;">
<div class="checkbox"><label ><input type="checkbox" name="foundationCourses[6]" value="automata" <?php if(!empty($foundation_courses)) { if($foundation_courses['automata']==1){echo 'checked';}}  else if(empty($AutomataGrade)|| (preg_replace("/[^A-Za-z0-9]/", '', strtolower($AutomataGrade))=='na')){ echo 'checked';}?>>Automata 6510</label></div>
<div class="checkbox"><label ><input type="checkbox" name="foundationCourses[7]" value="dm" <?php if(!empty($foundation_courses)) { if($foundation_courses['dm']==1){echo 'checked';}}  else if(empty($DMGrade)|| (preg_replace("/[^A-Za-z0-9]/", '', strtolower($DMGrade))=='na')){ echo 'checked';}?>>Discrete Mathematics 2510</label></div>
</div>
<div style="float: left; margin-right: 10px;">
<div class="checkbox"><label ><input type="checkbox" name="foundationCourses[8]" value="calculus" <?php if(!empty($foundation_courses)) { if($foundation_courses['calculus']==1){echo 'checked';}}  else if(empty($CalculusGrade)|| (preg_replace("/[^A-Za-z0-9]/", '', strtolower($CalculusGrade))=='na')){ echo 'checked';}?>>CalculusI/II</label></div>
</div>

</div>

<div style="text-align: center;">
<br/>
<h5 style="text-align: left;"><b><i>Advisor Comments</i></b></h5>
<textarea cols="100" rows="5" id="advisorcomments" name="advisorcomments"><?php echo htmlspecialchars($AdvisorComments); ?></textarea>
</div>
<br/>
<div style="width: 100%; text-align:center; display: inline-block;">

<div id="AaidDiv" style="margin-right: 40px; text-align:left; display: inherit; vertical-align:top;">
<h4>Financial Aid:</h4>
  <div class="checkbox" ><label><input type="checkbox" id="aidSRecomm" name="aaid" value="Strongly" <?=$AAid == 'Strongly' ? 'checked' : '';?>/> Strongly recommend</label></div>
  <div class="checkbox" ><label><input type="checkbox" id="aidRecomm" name="aaid" value="Recommend" <?=$AAid == 'Recommend' ? 'checked' : '';?>/> Recommend</label></div>
  <div class="checkbox" ><label><input type="checkbox" id="aidWRecomm" name="aaid" value="Weakly" <?=$AAid == 'Weakly' ? 'checked' : '';?>/> Weakly recommend</label></div>
  <div class="checkbox" ><label><input type="checkbox" id="aidNotRecomm" name="aaid" value="No" <?=$AAid == 'No' ? 'checked' : '';?>/> Do not recommend</label></div>
</div>

<div id="finalEvaluation" style=" margin-right: 40px; text-align:left; display: inherit; vertical-align:top;">
<h4><i>Final Decision</i></h4>
  <div class="checkbox"><label><input type="checkbox" id="advisorAdmit" name="advdec" value="Admit" <?=$AdvDec == 'Admit' ? 'checked' : '';?>/> Admit</label></div>
  <div class="checkbox"><label><input type="checkbox" id="advisorReject" name="advdec" value="Reject" <?=$AdvDec == 'Reject' ? 'checked' : '';?>/> Reject</label></div>
  <div class="checkbox"><label><input type="checkbox" id="advisorDeferred" name="advdec" value="Deferred" <?=$AdvDec == 'Deferred' ? 'checked' : '';?>/> Deferred</label></div>
</div>
</div>

</div>

<br style="clear: both;"/>
<br/>
		<div style="text-align: -webkit-center;">
			 <input type="submit" value="Submit" class="btn btn-secondary"/>
			 <div id="response"></div>
		</div>
</div>
</form>
</div>
</div>
<div class="modal fade" id="myModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">OSMS - Admin</h4>
      </div>
      <div class="modal-body">
        <p>Your feedback on Student Application is submitted.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">OK</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="myModal1">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">OSMS - Admin</h4>
      </div>
      <div class="modal-body">
        <p>Please fill in all the required fields before submitting your evaluation.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">OK</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript">
function printDiv(divName) {
    var printContents = document.getElementById(divName).innerHTML;
    var originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents;

    window.print();

    document.body.innerHTML = originalContents;
}

$( document ).ready(function() {
		$("#mainDiv *").prop('disabled',true); 
		$("#recommReject").change(function() {
		    if(this.checked) {
		        //Do stuff
		    	$("#aidDiv").hide(1000);
		    }			     
		});
		$("#recommAdmit").change(function() {
		    if(this.checked) {
		        //Do stuff
		    	$("#aidDiv").show(1000);
		    }			     
		});
		$("#recommDeferred").change(function() {
		    if(this.checked) {
		        //Do stuff
		    	$("#aidDiv").show(1000);
		    }			     
		});
		
		var src = '<?php echo $Source; ?>';
		 if(src === 'AdminDB')
		 {
			 // $("#subDiv *").prop('disabled',true);
			  $("#advisorDiv").hide();
		 }
		 else if(src== 'FacultyDB')
		 {
			 $("#advisorDiv").hide();
		 }
		  else if(src == 'AdvisorDB')
		  {
			  $("#subDiv *").prop('disabled',false); 
		  }
		  else if(src == 'FinalDB')
		  {
			  $("#subDiv *").prop('disabled',true);
              $("#advisorDiv *").prop('disabled',true);
			  $("#foundationcourseDiv *").prop('disabled',true);
			  $("#feedbacksubmit *").hide();
			  
		  }

	$('input[type="checkbox"]').on('change', function() {
    $('input[name="' + this.name + '"]').not(this).prop('checked', false);
});
  });
$(function () {
    $('form').on('submit', function (e) {

	var form_data = $('form').serialize();
      e.preventDefault();


    var isSelected1 = false;
    $("#lor1Div input").each(function(){
		if($(this).is(':checked'))
			isSelected1 = true;
	});
	
	var isSelected2 = false;
    $("#lor2Div input").each(function(){
		if($(this).is(':checked'))
			isSelected2 = true;
	});
	
	var isSelected3 = false;
    $("#lor3Div input").each(function(){
		if($(this).is(':checked'))
			isSelected3 = true;
	});
	
	var isSelected4 = false;
    $("#sopDiv input").each(function(){
		if($(this).is(':checked'))
			isSelected4 = true;
	});
	
	var isSelected5 = false;
    var Selected5value = "";
    $("#recDiv input").each(function(){
		if($(this).is(':checked')) {
            isSelected5 = true;
            Selected5value = $(this).val();
            //alert(Selected5value);
        }
	});
	
	var isSelected6 = false;
    $("#aidDiv input").each(function(){
		if($(this).is(':checked'))
			isSelected6 = true;
	});
    var src = '<?php echo $Source; ?>';
    //alert(src);

    //if(!(src=='AdminDB'||src=='AdvisorDB') && (!isSelected1 || !isSelected2 || !isSelected3 || !isSelected4 || !isSelected5 || (Selected5value !='Reject' && !isSelected6)))
        if((!(src=='AdminDB'||src=='AdvisorDB')) && (!isSelected1 || !isSelected2 || !isSelected3 || !isSelected4 || !isSelected5 || (Selected5value !='Reject' && !isSelected6))) {
          alert('same');
            $('#myModal1').modal('show');
        }
   else
   {
       //alert('begin to update');
      $.ajax({
        type: 'post',
        url: './SaveFacultyFeedback.php',
        data: form_data,
        success: function (php_script_response) {
          var responsetext = php_script_response;
          if (/Feedback Submitted/i.test(responsetext))
          {
			  $('#myModal').modal('show');
				$("#response").attr("Style", "color: green;");
				$('#response').html(responsetext);
          }
          else
          {
			  $("#response").attr("class", "color: red;");
			  $('#response').html(responsetext);
          }
        }
      });
   }
    });
  });
</script>
</body>

</html>