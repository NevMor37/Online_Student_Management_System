<?php
#Testing http://localhost/OSMS/UI/staff/StudentEvaluvation.php?PantherId=1234

// if(isset($_GET['PantherId'])) {
// 	$patherid = $_GET['PantherId'];
// }
// else 
// {
// 	$patherid = 2257627;
// }
if(!$patherid)
{
	$patherid = 2257627;
}

#echo $patherid;

include('./../../osms.dbconfig.inc');
$error_message = "";
$counter = 0;

$mysqli = new mysqli($hostname,$username, $password,$dbname);

/* check connection */
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}


$sql = "SELECT  * FROM tbl_excel_info where PantherId= ".$patherid;
$result = $mysqli->query($sql);
$row_cnt = $result->num_rows;
$html ="";

if ($result->num_rows > 0) {
	// output data of each row
	
	while($row = $result->fetch_assoc()) {


			#echo "username: " . $row["userid"]. " - password : " . $row["password"]. " -email: " . $row["email"]. "<br>";
			$PantherId=$row["PantherId"];
			$FirstName=$row["FirstName"];
			$MiddleName=$row["MiddleName"];
			$LastName=$row["LastName"];
			$AlternativeLastName=$row["AlternativeLastName"];
			$Program=$row["Program"];
			$Term=$row["Term"];
			$Concentration=$row["Concentration"];
			$EMail=$row["EMail"];
			$GREDate=$row["GREDate"];
			$GREVerbalScore=$row["GREVerbalScore"];
			$GREVerbalPercent=$row["GREVerbalPercent"];
			$GREQuantScore=$row["GREQuantScore"];
			$GREQuantPercent=$row["GREQuantPercent"];
			$GREAnalyticalScore=$row["GREAnalyticalScore"];
			$GREAnalyticalPercent=$row["GREAnalyticalPercent"];
			$TOEFLDateofTest=$row["TOEFLDateofTest"];
			$TOEFLTestType=$row["TOEFLTestType"];
			$TOEFLTotal=$row["TOEFLTotal"];
			$TOEFLReading=$row["TOEFLReading"];
			$TOEFLListening=$row["TOEFLListening"];
			$TOEFLSpeaking=$row["TOEFLSpeaking"];
			$TOEFLWriting=$row["TOEFLWriting"];
			$UgGPAOverall=$row["UgGPAOverall"];
			$UgGPAMajor=$row["UgGPAMajor"];
			$GraduateGPA=$row["GraduateGPA"];
			$Faculty1=$row["Faculty1"];
			$Faculty2=$row["Faculty2"];
			$Faculty3=$row["Faculty3"];
			$Race=$row["Race"];
			$Ethnicity=$row["Ethnicity"];
			$Gender=$row["Gender"];
			$CitizenshipCountry=$row["CitizenshipCountry"];
			$CitizenshipStatus=$row["CitizenshipStatus"];
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

			$html.='<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">';

			$html.="<tr class='odd gradeX'>";

			$html .= '<td>'.$PantherId.'</td>';
			$html .= '<td>'.$FirstName.'</td>';
			$html .= '<td>'.$MiddleName.'</td>';
			$html .= '<td>'.$LastName.'</td>';
			$html .= '<td>'.$AlternativeLastName.'</td>';
			$html .= '<td>'.$Program.'</td>';
			$html .= '<td>'.$Term.'</td>';
			$html .= '<td>'.$Concentration.'</td>';
			$html .= '<td>'.$EMail.'</td>';
			$html .= '<td>'.$GREDate.'</td>';
			$html .= '<td>'.$GREVerbalScore.'</td>';
			$html .= '<td>'.$GREVerbalPercent.'</td>';
			$html .= '<td>'.$GREQuantScore.'</td>';
			$html .= '<td>'.$GREQuantPercent.'</td>';
			$html .= '<td>'.$GREAnalyticalScore.'</td>';
			$html .= '<td>'.$GREAnalyticalPercent.'</td>';
			$html .= '<td>'.$TOEFLDateofTest.'</td>';
			$html .= '<td>'.$TOEFLTestType.'</td>';
			$html .= '<td>'.$TOEFLTotal.'</td>';
			$html .= '<td>'.$TOEFLReading.'</td>';
			$html .= '<td>'.$TOEFLListening.'</td>';
			$html .= '<td>'.$TOEFLSpeaking.'</td>';
			$html .= '<td>'.$TOEFLWriting.'</td>';
			$html .= '<td>'.$UgGPAOverall.'</td>';
			$html .= '<td>'.$UgGPAMajor.'</td>';
			$html .= '<td>'.$GraduateGPA.'</td>';
			$html .= '<td>'.$Faculty1.'</td>';
			$html .= '<td>'.$Faculty2.'</td>';
			$html .= '<td>'.$Faculty3.'</td>';
			$html .= '<td>'.$Race.'</td>';
			$html .= '<td>'.$Ethnicity.'</td>';
			$html .= '<td>'.$Gender.'</td>';
			$html .= '<td>'.$CitizenshipCountry.'</td>';
			$html .= '<td>'.$CitizenshipStatus.'</td>';
			$html .= '<td>'.$CollegeName1.'</td>';
			$html .= '<td>'.$DateAttendedFrom1.'</td>';
			$html .= '<td>'.$DateAttendedTo1.'</td>';
			$html .= '<td>'.$Major1.'</td>';
			$html .= '<td>'.$Degree1.'</td>';
			$html .= '<td>'.$CollegeName2.'</td>';
			$html .= '<td>'.$DateAttendedFrom2.'</td>';
			$html .= '<td>'.$DateAttendedTo2.'</td>';
			$html .= '<td>'.$Major2.'</td>';
			$html .= '<td>'.$Degree2.'</td>';
			$html .= '<td>'.$CollegeName3.'</td>';
			$html .= '<td>'.$DateAttendedFrom3.'</td>';
			$html .= '<td>'.$DateAttendedTo3.'</td>';
			$html .= '<td>'.$Major3.'</td>';
			$html .= '<td>'.$Degree3.'</td>';

			$html .= "</tr>";
	}
			$html.='</table>';
// 			echo $html;
}

?>

		<!-- page-wrapper -->
        <div id="page-wrapper">
        
 
<!--         <div id="studentInfoDiv" class="row"> -->
       <?php 
//          include '../../StudentExcel.php';
         ?>
<!--         </div>  -->
			<!--  Search -->
				<div class="row">
					<br>
		 			<div class="input-group custom-search-form">
						<input type="text" class="form-control" placeholder="Panther ID or Name...">
				 			<span class="input-group-btn">
					 			<button class="btn btn-default" type="button">
					 				<i class="fa fa-search"></i>
					 			</button>
				 			</span>
		 			</div>
	 			</div>
 			<!--  /#Search -->	
            <!-- /input-group -->
            <?php 
            if($patherid != 110)
            {
            ?>
 <!--  Student Evaluvation Form -->

            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?php echo $FirstName.' '.$LastName?></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Profile Details: (PantherId: <?=($PantherId!=''?'00'.$PantherId:'Student Id')?>)
                        </div>
                        <div class="panel-body">
                        	<div class="row">
                        		<div class="col-lg-12">
                        		<fieldset disabled>
                        			<div class="col-lg-6">
                            	<table style="width: 100%">
                            	
<tr><td><div class="form-group"><label for="fname" style="margin-top: 7px;">First Name</label></div></td><td><div class="form-group"><input id="fname" class="form-control" style="width: 90%" value="<?=$FirstName?>"></div></td></tr>
<tr><td><div class="form-group"><label for="fname" style="margin-top: 7px;">Last Name</label></div></td><td><div class="form-group"><input id="fname" class="form-control" style="width: 90%" value="<?=$LastName?>"></div></td></tr>
<tr><td><div class="form-group"><label for="fname" style="margin-top: 7px;">Program</label></div></td><td><div class="form-group"><input id="fname" class="form-control" style="width: 90%" value="<?=$Program?>"></div></td></tr>
<tr><td><div class="form-group"><label for="fname" style="margin-top: 7px;">Concentration</label></div></td><td><div class="form-group"><input id="fname" class="form-control" style="width: 90%" value="<?=$Concentration?>"></div></td></tr>
<tr><td><div class="form-group"><label for="fname" style="margin-top: 7px;">GRE Date</label></div></td><td><div class="form-group"><input id="fname" class="form-control" style="width: 90%" value="<?=$GREDate?>"></div></td></tr>
<tr><td><div class="form-group"><label for="fname" style="margin-top: 7px;">GRE Verbal %</label></div></td><td><div class="form-group"><input id="fname" class="form-control" style="width: 90%" value="<?=$GREVerbalPercent?>"></div></td></tr>
<tr><td><div class="form-group"><label for="fname" style="margin-top: 7px;">GRE Quant %</label></div></td><td><div class="form-group"><input id="fname" class="form-control" style="width: 90%" value="<?=$GREQuantPercent?>"></div></td></tr>
<tr><td><div class="form-group"><label for="fname" style="margin-top: 7px;">GRE Analytical %</label></div></td><td><div class="form-group"><input id="fname" class="form-control" style="width: 90%" value="<?=$GREAnalyticalPercent?>"></div></td></tr>
<tr><td><div class="form-group"><label for="fname" style="margin-top: 7px;">TOEFL Test Type</label></div></td><td><div class="form-group"><input id="fname" class="form-control" style="width: 90%" value="<?=$TOEFLTestType?>"></div></td></tr>
<tr><td><div class="form-group"><label for="fname" style="margin-top: 7px;">TOEFL Reading</label></div></td><td><div class="form-group"><input id="fname" class="form-control" style="width: 90%" value="<?=$TOEFLReading?>"></div></td></tr>
<tr><td><div class="form-group"><label for="fname" style="margin-top: 7px;">TOEFL Speaking</label></div></td><td><div class="form-group"><input id="fname" class="form-control" style="width: 90%" value="<?=$TOEFLSpeaking?>"></div></td></tr>
<tr><td><div class="form-group"><label for="fname" style="margin-top: 7px;">Ug Overall GPA</label></div></td><td><div class="form-group"><input id="fname" class="form-control" style="width: 90%" value="<?=$UgGPAOverall?>"></div></td></tr>
<tr><td><div class="form-group"><label for="fname" style="margin-top: 7px;">Graduate GPA</label></div></td><td><div class="form-group"><input id="fname" class="form-control" style="width: 90%" value="<?=$GraduateGPA?>"></div></td></tr>
<tr><td><div class="form-group"><label for="fname" style="margin-top: 7px;">Faculty 2</label></div></td><td><div class="form-group"><input id="fname" class="form-control" style="width: 90%" value="<?=$GraduateGPA?>"></div></td></tr>
<tr><td><div class="form-group"><label for="fname" style="margin-top: 7px;">Race</label></div></td><td><div class="form-group"><input id="fname" class="form-control" style="width: 90%" value="<?=$Race?>"></div></td></tr>
<tr><td><div class="form-group"><label for="fname" style="margin-top: 7px;">Gender</label></div></td><td><div class="form-group"><input id="fname" class="form-control" style="width: 90%" value="<?=$Gender?>"></div></td></tr>
<tr><td><div class="form-group"><label for="fname" style="margin-top: 7px;">Citizenship Status</label></div></td><td><div class="form-group"><input id="fname" class="form-control" style="width: 90%" value="<?=$CitizenshipStatus?>"></div></td></tr>
<tr><td><div class="form-group"><label for="fname" style="margin-top: 7px;">Attended From 1</label></div></td><td><div class="form-group"><input id="fname" class="form-control" style="width: 90%" value="<?=$DateAttendedFrom1?>"></div></td></tr>
<tr><td><div class="form-group"><label for="fname" style="margin-top: 7px;">Major 1</label></div></td><td><div class="form-group"><input id="fname" class="form-control" style="width: 90%" value="<?=$Major1?>"></div></td></tr>
<tr><td><div class="form-group"><label for="fname" style="margin-top: 7px;">College Name 2</label></div></td><td><div class="form-group"><input id="fname" class="form-control" style="width: 90%" value="<?=$CollegeName2?>"></div></td></tr>
<tr><td><div class="form-group"><label for="fname" style="margin-top: 7px;">Attended To 2</label></div></td><td><div class="form-group"><input id="fname" class="form-control" style="width: 90%" value="<?=$DateAttendedTo2?>"></div></td></tr>
<tr><td><div class="form-group"><label for="fname" style="margin-top: 7px;">Degree 2</label></div></td><td><div class="form-group"><input id="fname" class="form-control" style="width: 90%" value="<?=$Degree2?>"></div></td></tr>
<tr><td><div class="form-group"><label for="fname" style="margin-top: 7px;">Attended From 3</label></div></td><td><div class="form-group"><input id="fname" class="form-control" style="width: 90%" value="<?=$DateAttendedFrom3?>"></div></td></tr>
<tr><td><div class="form-group"><label for="fname" style="margin-top: 7px;">Major 3</label></div></td><td><div class="form-group"><input id="fname" class="form-control" style="width: 90%" value="<?=$Major3?>"></div></td></tr>

                            	</table>
                            	</div>
                            	<div class="col-lg-6">
                            	<table style="width: 100%">
                            	
<tr><td><div class="form-group"><label for="fname" style="margin-top: 7px;">Middle Name</label></div></td><td><div class="form-group"><input id="fname" class="form-control" style="width: 90%" value="<?=$MiddleName?>"></div></td></tr>
<tr><td><div class="form-group"><label for="fname" style="margin-top: 7px;">Alternative Last Name</label></div></td><td><div class="form-group"><input id="fname" class="form-control" style="width: 90%" value="<?=$AlternativeLastName?>"></div></td></tr>
<tr><td><div class="form-group"><label for="fname" style="margin-top: 7px;">Term</label></div></td><td><div class="form-group"><input id="fname" class="form-control" style="width: 90%" value="<?=$Term?>"></div></td></tr>
<tr><td><div class="form-group"><label for="fname" style="margin-top: 7px;">Email</label></div></td><td><div class="form-group"><input id="fname" class="form-control" style="width: 90%" value="<?=$EMail?>"></div></td></tr>
<tr><td><div class="form-group"><label for="fname" style="margin-top: 7px;">GRE Verbal Score</label></div></td><td><div class="form-group"><input id="fname" class="form-control" style="width: 90%" value="<?=$GREVerbalScore?>"></div></td></tr>
<tr><td><div class="form-group"><label for="fname" style="margin-top: 7px;">GRE Quant Score</label></div></td><td><div class="form-group"><input id="fname" class="form-control" style="width: 90%" value="<?=$GREQuantScore?>"></div></td></tr>
<tr><td><div class="form-group"><label for="fname" style="margin-top: 7px;">GRE Analytical Score</label></div></td><td><div class="form-group"><input id="fname" class="form-control" style="width: 90%" value="<?=$GREAnalyticalScore?>"></div></td></tr>
<tr><td><div class="form-group"><label for="fname" style="margin-top: 7px;">GRE Total</label></div></td><td><div class="form-group"><input id="fname" class="form-control" style="width: 90%" value="<?=$GREVerbalScore+$GREQuantScore?>"></div></td></tr>
<tr><td><div class="form-group"><label for="fname" style="margin-top: 7px;">TOEFL Date</label></div></td><td><div class="form-group"><input id="fname" class="form-control" style="width: 90%" value="<?=$TOEFLDateofTest?>"></div></td></tr>
<tr><td><div class="form-group"><label for="fname" style="margin-top: 7px;">TOEFL Total</label></div></td><td><div class="form-group"><input id="fname" class="form-control" style="width: 90%" value="<?=$TOEFLTotal?>"></div></td></tr>
<tr><td><div class="form-group"><label for="fname" style="margin-top: 7px;">TOEFL Listening</label></div></td><td><div class="form-group"><input id="fname" class="form-control" style="width: 90%" value="<?=$TOEFLListening?>"></div></td></tr>
<tr><td><div class="form-group"><label for="fname" style="margin-top: 7px;">TOEFL Writing</label></div></td><td><div class="form-group"><input id="fname" class="form-control" style="width: 90%" value="<?=$TOEFLWriting?>"></div></td></tr>
<tr><td><div class="form-group"><label for="fname" style="margin-top: 7px;">Ug GPA Major</label></div></td><td><div class="form-group"><input id="fname" class="form-control" style="width: 90%" value="<?=$UgGPAMajor?>"></div></td></tr>
<tr><td><div class="form-group"><label for="fname" style="margin-top: 7px;">Faculty 1</label></div></td><td><div class="form-group"><input id="fname" class="form-control" style="width: 90%" value="<?=$Faculty1?>"></div></td></tr>
<tr><td><div class="form-group"><label for="fname" style="margin-top: 7px;">Faculty 3</label></div></td><td><div class="form-group"><input id="fname" class="form-control" style="width: 90%" value="<?=$Faculty3?>"></div></td></tr>
<tr><td><div class="form-group"><label for="fname" style="margin-top: 7px;">Ethnicity</label></div></td><td><div class="form-group"><input id="fname" class="form-control" style="width: 90%" value="<?=$Ethnicity?>"></div></td></tr>
<tr><td><div class="form-group"><label for="fname" style="margin-top: 7px;">Citizenship Country</label></div></td><td><div class="form-group"><input id="fname" class="form-control" style="width: 90%" value="<?=$CitizenshipCountry?>"></div></td></tr>
<tr><td><div class="form-group"><label for="fname" style="margin-top: 7px;">College Name 1</label></div></td><td><div class="form-group"><input id="fname" class="form-control" style="width: 90%" value="<?=$CollegeName1?>"></div></td></tr>
<tr><td><div class="form-group"><label for="fname" style="margin-top: 7px;">DateAttended To 1</label></div></td><td><div class="form-group"><input id="fname" class="form-control" style="width: 90%" value="<?=$DateAttendedTo1?>"></div></td></tr>
<tr><td><div class="form-group"><label for="fname" style="margin-top: 7px;">Degree 1</label></div></td><td><div class="form-group"><input id="fname" class="form-control" style="width: 90%" value="<?=$Degree1?>"></div></td></tr>
<tr><td><div class="form-group"><label for="fname" style="margin-top: 7px;">Attended From 2</label></div></td><td><div class="form-group"><input id="fname" class="form-control" style="width: 90%" value="<?=$DateAttendedFrom2?>"></div></td></tr>
<tr><td><div class="form-group"><label for="fname" style="margin-top: 7px;">Major 2</label></div></td><td><div class="form-group"><input id="fname" class="form-control" style="width: 90%" value="<?=$Major2?>"></div></td></tr>
<tr><td><div class="form-group"><label for="fname" style="margin-top: 7px;">College Name 3</label></div></td><td><div class="form-group"><input id="fname" class="form-control" style="width: 90%" value="<?=$CollegeName3?>"></div></td></tr>
<tr><td><div class="form-group"><label for="fname" style="margin-top: 7px;">Attended To 3</label></div></td><td><div class="form-group"><input id="fname" class="form-control" style="width: 90%" value="<?=$DateAttendedTo3?>"></div></td></tr>
<tr><td><div class="form-group"><label for="fname" style="margin-top: 7px;">Degree 3</label></div></td><td><div class="form-group"><input id="fname" class="form-control" style="width: 90%" value="<?=$Degree3?>"></div></td></tr>
                          	
                            	
                            	</table>
                            	</div>
                            	</fieldset>
                        		</div>
                        	
                        	</div>
                        
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
        </div>
<!--  /#Student Evaluvation Form -->
			<?php
            }
			?>
        <!-- /#page-wrapper -->
</div>