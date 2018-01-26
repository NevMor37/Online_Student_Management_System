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
// database connection stuff here
if (!isset($_REQUEST['page']))
  $page = 1;
else 
   $page = $_REQUEST['page'];

include('./../../osms.dbconfig.inc');
$error_message = "";

$mysqli = new mysqli($hostname,$username, $password,$dbname);

/* check connection */
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}

$countRes = $mysqli->query("select count(*) as RecordCount from tbl_excel_info");
$res = $countRes->fetch_assoc();
$numrecords = $res['RecordCount'];

$sql = "SELECT * FROM tbl_excel_info LIMIT 1 OFFSET {$page}";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		$PantherId= $row["PantherId"];
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

$sql1 = "SELECT  * FROM tbl_excel_ext_info where PantherID= ".$PantherId;
$sql2 = "SELECT * FROM tbl_student_evaluation WHERE StudentId= ".$PantherId;

$result1 = $mysqli->query($sql1);
$result2 = $mysqli->query($sql2);

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
			
if ($result2->num_rows > 0) {
	// output data of each row
	
	while($row = $result2->fetch_assoc()) {
			$LOR1=$row["LOR1"];
			$LOR2=$row["LOR2"];
			$LOR3=$row["LOR3"];
			$SOP=$row["SOP"];
			$Admit=$row["Recommendation"];
			$Aid=$row["FinAid"];
			$Comments=$row["Comments"];
			$FacultyName=$row["FacultyName"];
			$Date=$row["Date"];
			}
			}
			//$mysqli = new mysqli($hostname,$username, $password,$dbname);
			$sql3 = "SELECT * FROM tbl_faculty_info where FirstName != 'FirstName'";
			$sql4 = "SELECT * FROM tbl_student_evaluation where StudentId = '{$PantherId}'";
			
			$result3 = $mysqli->query($sql3);
			$result4 = $mysqli->query($sql4);
			
$next = $page +1;
$prev = $page -1;
if($next > ($numrecords -1))
	$next = 1;
if($prev < 1)
	$prev = ($numrecords -1);
?>

<html>
		<?php
		include $root.'/links/header.php';
		include $root.'/links/footerLinks.php';
		?>
<body>
<div id="wrapper">

        <!-- Navigation -->
        <?php  
        include $root.'/UI/staff/staffmenu.php';
        ?>
        <!-- /#Navigation -->

		<!-- page-wrapper -->
		<div id="page-wrapper">
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
<td><input type="text" class="form-control" id="applicantName" value="<?=$FirstName.' '.$LastName?>" name="applicantName"></td>
<td><input name="studentNumber" hidden="true" value="<?=$PantherId?>"><input type="text" class="form-control" id="studentNumber" name="studentNumber1" value="<?=($PantherId!=''?'00'.$PantherId:'')?>"></td>
<td><input type="text" class="form-control" id="degree" value="<?=$Program?>" name="degree"></td>
<td><input type="text" class="form-control" id="concentration" value="<?=$Concentration?>" name="concentration"></td>
</tr>
</table>
&nbsp; &nbsp;
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
<td><input type="text" class="form-control"  id="dates1" value="<?=$DateAttendedFrom1?>" name="datefrom1"></td>
<td><input type="text" class="form-control"  id="degree1" value="<?=$Degree1?>" name="degree1"></td>
<td><input type="text" class="form-control"  id="college1" value="<?=$CollegeName1?>" name="college1"></td>
<td><input type="text" class="form-control"  id="gradGPA" value="<?=$GraduateGPA?>" name="gpa1"></td>
</tr>
<tr>
<td><label> 2. </td>
<td><input type="text" class="form-control" id="dates2" value="<?=$DateAttendedFrom2?>" name="datefrom2"></td>
<td><input type="text" class="form-control" id="degree2" value="<?=$Degree2?>" name="degree2"></td>
<td><input type="text" class="form-control" id="college2" value="<?=$CollegeName2?>" name="college2"></td>
<td><input type="text" class="form-control" id="ugGpa" value="<?=$UgGPAOverall?>" name="gpa2"></td>
</tr>
<tr>
<td><label> 3. </td>
<td><input type="text" class="form-control" id="dates3" value="<?=$DateAttendedFrom3?>" name="datefrom3"></td>
<td><input type="text" class="form-control" id="degree3" value="<?=$Degree3?>" name="degree3"></td>
<td><input type="text" class="form-control" id="college3" value="<?=$CollegeName3?>" name="college3" name="gpa3"></td>
<td><input type="text" class="form-control"></td>
</tr>
</table>

<br/>

<br/>

<div style="float:left">

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
  <option value="Excellent" <?=$ResearchExp == 'Excellent' ? ' selected="selected"' : '';?>>Excellent</option>
  <option value="Very Good" <?=$ResearchExp == 'Very Good' ? ' selected="selected"' : '';?>>Very Good</option>
  <option value="Good" <?=$ResearchExp == 'Good' ? ' selected="selected"' : '';?>>Good</option>
  <option value="Fair" <?=$ResearchExp == 'Fair' ? ' selected="selected"' : '';?>>Fair</option>
  <option value="New" <?=$ResearchExp == 'New' ? ' selected="selected"' : '';?>>New</option>
</select>
<!-- <input style="margin-left: 20px;" type="text" id="researchExp" value="<?=$GREVerbalPercent?>"> -->

</div>
<br/>

<br/>
<div style="float:left">
<h4>GRE</h4>
<label> Verbal 
<input style="margin-left: 40px;" type="text" id="greVerbalScore" value="<?=$GREVerbalScore?>" name="greV"><input  style="margin-left: 20px;" type="text" id="greVerbalPercent" value="<?=$GREVerbalPercent?>" name="greVP"><span>%</span>
<br/>
<br/>
<label> Quantitative
<input type="text" id="greQuantScore" value="<?=$GREQuantScore?>" name="greQ"><input style="margin-left: 20px;" type="text" id="greQuantPercent" value="<?=$GREQuantPercent?>" name="greQP"><span>%</span>
<br/>
<br/>
<label> Total V+Q
<input style="margin-left:20px;" type="text" id="greTotal" value="<?=$GRETotal?>" name="greTotal">
<br/>
<br/>
<label> Analytical
<input style="margin-left:20px;" type="text" id="greAnaScore" value="<?=$GREAnalyticalScore?>" name="greA"><input style="margin-left: 20px;" type="text" id="greAnaPercent" value="<?=$GREAnalyticalPercent?>" name="greAP"><span>%</span>
<br/>
<br/>
</div>
<div style="float:left; margin-left: 60px; margin-top: 20px;">
<h4 style="display:inline;">TOEFL</h4>
<input style="margin-left:20px;" type="text" id="toeflTotal" value="<?=$TOEFLTotal?>" name="toeflTotal">
</div>
&nbsp; &nbsp;
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
<div id ="feedbackDiv">
<h4 style="display:inline; margin-right:60px;">Letter of Recommendation #1:</h4>
  <input type="checkbox" id="recommend1Excellent" name="lor1" value="Excellent" <?=$LOR1 == 'Excellent' ? 'checked' : '';?>> Excellent </input>
  <input type="checkbox" id="recommend1VGood" name="lor1" value="Very Good" <?=$LOR1 == 'Very Good' ? 'checked' : '';?>> Very Good </input>
  <input type="checkbox" id="recommend1Good" name="lor1" value="Good" <?=$LOR1 == 'Good' ? 'checked' : '';?>> Good </input>
  <input type="checkbox" id="recommend1Fair" name="lor1" value="Fair" <?=$LOR1 == 'Fair' ? 'checked' : '';?>> Fair </input>
  <input type="checkbox" id="recommend1Poor" name="lor1" value="Poor" <?=$LOR1 == 'Poor' ? 'checked' : '';?>> Poor </input>
 <br/>
 <br/>
<h4 style="display:inline; margin-right:60px;">Letter of Recommendation #2:</h4>
  <input type="checkbox" id="recommend2Excellent" name="lor2" value="Excellent" <?=$LOR2 == 'Excellent' ? 'checked' : '';?>/> Excellent
  <input type="checkbox" id="recommend2VGood" name="lor2" value="Very Good" <?=$LOR2 == 'Very Good' ? 'checked' : '';?>/> Very Good
  <input type="checkbox" id="recommend2Good" name="lor2" value="Good" <?=$LOR2 == 'Good' ? 'checked' : '';?>/> Good
  <input type="checkbox" id="recommend2Fair" name="lor2" value="Fair" <?=$LOR2 == 'Fair' ? 'checked' : '';?>/> Fair
  <input type="checkbox" id="recommend2Poor" name="lor2" value="Poor" <?=$LOR2 == 'Poor' ? 'checked' : '';?>/> Poor
 <br/>
 <br/>
<h4 style="display:inline; margin-right:60px;">Letter of Recommendation #3:</h4>
  <input type="checkbox" id="recommend3Excellent" name="lor3" value="Excellent" <?=$LOR3 == 'Excellent' ? 'checked' : '';?>/> Excellent
  <input type="checkbox" id="recommend3VGood" name="lor3" value="Very Good" <?=$LOR3 == 'Very Good' ? 'checked' : '';?>/> Very Good
  <input type="checkbox" id="recommend3Good" name="lor3" value="Good" <?=$LOR3 == 'Good' ? 'checked' : '';?>/> Good
  <input type="checkbox" id="recommend3Fair" name="lor3" value="Fair" <?=$LOR3 == 'Fair' ? 'checked' : '';?>/> Fair
  <input type="checkbox" id="recommend3Poor" name="lor3" value="Poor" <?=$LOR3 == 'Poor' ? 'checked' : '';?>/> Poor
 <br/>
 <br/>
<h4 style="display:inline; margin-right:10px;">Statement of background and goals: </h4>
  <input type="checkbox" id="sopExcellent" name="sop" value="Excellent" <?=$SOP == 'Excellent' ? 'checked' : '';?>/> Excellent
  <input type="checkbox" id="sopVGood" name="sop" value="Very Good" <?=$SOP == 'Very Good' ? 'checked' : '';?>/> Very Good
  <input type="checkbox" id="sopGood" name="sop" value="Good" <?=$SOP == 'Good' ? 'checked' : '';?>/> Good
  <input type="checkbox" id="sopFair" name="sop" value="Fair" <?=$SOP == 'Fair' ? 'checked' : '';?>/> Fair
  <input type="checkbox" id="sopPoor" name="sop" value="Poor" <?=$SOP == 'Poor' ? 'checked' : '';?>/> Poor
  <br/>
&nbsp;
<h3>Recommendation</h3>
  <input type="checkbox" id="recommAdmit" name="admit" value="Admit" <?=$Admit == 'Admit' ? 'checked' : '';?>/> Admit - Full Status (unqualified admission)
  <br/>
  <input type="checkbox" id="recommReject" name="admit" value="Reject" <?=$Admit == 'Reject' ? 'checked' : '';?>/> Reject - Will NOT reconsider
  <br/>
  <input type="checkbox" id="recommDeferred" name="admit" value="Deferred" <?=$Admit == 'Deferred' ? 'checked' : '';?>/> Action Deferred (needs undergraduate coursework indicated below) - Post-baccalaureate status
<br/>
<br/>
<h4>Financial Aid:</h4>
  <input type="checkbox"id="aidSRecomm" name="aid" value="Strongly" <?=$Aid == 'Strongly' ? 'checked' : '';?>/> Strongly recommend
  <input type="checkbox" id="aidRecomm" name="aid" value="Recommend" <?=$Aid == 'Recommend' ? 'checked' : '';?>/> Recommend
  <input type="checkbox" id="aidWRecomm" name="aid" value="Weakly" <?=$Aid == 'Weakly' ? 'checked' : '';?>/> Weakly recommend
  <input type="checkbox" id="aidNotRecomm" name="aid" value="No" <?=$Aid == 'No' ? 'checked' : '';?>/> Do not recommend
<br/>
<br/>
<h4>Comments</h4>
<textarea cols="100" rows="5" id="comments" name="comments"><?php echo htmlspecialchars($Comments); ?></textarea>
<br/>
<br/>
<table class="table table-bordered table-responsive table-default table-striped">
<tr>
<td><label for="facultyName">Faculty Member </label><input style="margin-left:10px; width:250px;  type="text" id="facultyName" name="facultyName" value="<?=$FacultyName?>"></td>
<td><label for="date">Date</label><input style="margin-left:10px;" type="text" id="date" name="date" value="<?=$Date?>"></td>
</tr>
</table>
</div>
		<div style="text-align: -webkit-center;">
			 <input type="submit" value="Save" class="btn btn-secondary"/>
			 <div id="responce"></div>
		</div>
</form>
</div>
</div>
<br/>
<br/>
<div style="width:40%; margin: 0 auto;">
	<h4 style="display:inline; margin-right:10px;">Select Faculty: </h4>
	<select id="facultyNamedd" name="facultyNamedd">
	<option value="-1">--  Faculty Name  --</option>
	<?php	
	 $AssignedFaculty= "-1";
	 if ($result4->num_rows > 0) {
			 while($row4 = $result4->fetch_assoc()) { 
			 $AssignedFaculty = $row4["FacultyId"];
		 }
		 }

	if ($result3->num_rows > 0) {
			while($row3 = $result3->fetch_assoc()) {
					echo "<option value=".$row3['email'].">".$row3['FirstName'].' '.$row3['LastName']."</option>";
			}
	}

	?>
	</select>
	<a href="#" style="margin-left: 20px" class="btn btn-primary" id="assignBtn">Assign</a>
	<span id="response"></span>
</div>
</div>
</div>
<div class="modal fade" id="myModal1">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">OSMS - Admin</h4>
      </div>
      <div class="modal-body" id="modal_content">
       
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">OK</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
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
        <p>Student data is saved.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">OK</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script type="text/javascript">
$(function () {
    $('#assignBtn').on('click', function (e) {

	var selvalue = $('select[name=facultyNamedd]').val();
      $.ajax({
        type: 'post',
        url: './AssignStudentToFaculty.php',
        data: { StudentId: <?=$PantherId?>, FacultyId: selvalue },
        success: function (php_script_response) {
          var responseText = php_script_response;
		  if (/Successfully/i.test(responseText))
          {	  
			  $('#modal_content').html(responseText);
			  $('#myModal1').modal('show');
          }
          else
          {
			  $('#modal_content').html(responseText);
			  $('#myModal1').modal('show');
          }
        }    
      });
    });
  });
$( document ).ready(function() {
		$("#feedbackDiv *").prop('disabled',true);
		 $("#facultyNamedd").val("<?=$AssignedFaculty?>");
  });

$(function () {
    $('form').on('submit', function (e) {

	var form_data = $('form').serialize();
	//alert(form_data);
      e.preventDefault();

      $.ajax({
        type: 'post',
        url: './SaveStudentInfo.php',
        data: form_data,
        success: function (php_script_response) {
          var responcetext = php_script_response;
          if (/Data Updated/i.test(responcetext) || /Data Saved/i.test(responcetext) )
                    {
						$('#myModal').modal('show');
                    }
          else
          {
          $("#responce").attr("class", "color: red;");
          $('#responce').html(responcetext);
          }
        }
      });
    });
  });
  
  function printDiv(divName) {
    var printContents = document.getElementById(divName).innerHTML;
    var originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents;

    window.print();

    document.body.innerHTML = originalContents;
}
</script>
  <br/>
  <br/>
  
  <div class="container">
  <ul class="pager">
    <li><a href="test.php?page=<?= $prev ?>">Previous</a></li>
    <li><a href="test.php?page=<?= $next ?>">Next</a></li>
  </ul>
</div>

</body>
</html>
