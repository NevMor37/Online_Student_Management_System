
<?phpif(!$patherid) {	//echo $patherid;	$patherid = 0;}include('./../../osms.dbconfig.inc');$error_message = "";$mysqli = new mysqli($hostname,$username, $password,$dbname);/* check connection */if (mysqli_connect_errno()) {	printf("Connect failed: %s\n", mysqli_connect_error());	exit();}$sql = "SELECT  * FROM tbl_excel_info where PantherId= ".$patherid;$sql1 = "SELECT  * FROM tbl_excel_ext_info where PantherID= ".$patherid;$sql2 = "SELECT * FROM tbl_student_evaluation WHERE StudentId= ".$patherid;//echo $sql;$result = $mysqli->query($sql);$result1 = $mysqli->query($sql1);$result2 = $mysqli->query($sql2);$row_cnt = $result->num_rows;if ($result->num_rows > 0) {	// output data of each row		while($row = $result->fetch_assoc()) {			$PantherId=$row["PantherId"];			$FirstName=$row["FirstName"];			$MiddleName=$row["MiddleName"];			$LastName=$row["LastName"];			$Program=$row["Program"];			$Term=$row["Term"];			$Concentration=$row["Concentration"];			$GREVerbalScore=$row["GREVerbalScore"];			$GREVerbalPercent=$row["GREVerbalPercent"];$TOEFLSpeaking=$row["TOEFLSpeaking"];	$Ethnicity=$row["Ethnicity"];		$GREQuantScore=$row["GREQuantScore"];			$GREQuantPercent=$row["GREQuantPercent"];			$GREAnalyticalScore=$row["GREAnalyticalScore"];			$GREAnalyticalPercent=$row["GREAnalyticalPercent"];			$GRETotal=$row["GRETotal"];			$TOEFLTotal=$row["TOEFLTotal"];			$Faculty=$row["Faculty"];			$WorkExp=$row["WorkExp"];			$ResearchExp=$row["ResearchExp"];			$CollegeName1=$row["CollegeName1"];			$DateAttendedFrom1=$row["DateAttendedFrom1"];			$DateAttendedTo1=$row["DateAttendedTo1"];			$Major1=$row["Major1"];			$Degree1=$row["Degree1"];			$CollegeName2=$row["CollegeName2"];			$DateAttendedFrom2=$row["DateAttendedFrom2"];			$DateAttendedTo2=$row["DateAttendedTo2"];			$Major2=$row["Major2"];			$Degree2=$row["Degree2"];			$CollegeName3=$row["CollegeName3"];			$DateAttendedFrom3=$row["DateAttendedFrom3"];			$DateAttendedTo3=$row["DateAttendedTo3"];			$Major3=$row["Major3"];			$Degree3=$row["Degree3"];			$UgGPAOverall=$row["UgGPAOverall"];			$GraduateGPA=$row["GraduateGPA"];			}			}if ($result1->num_rows > 0) {	// output data of each row		while($row = $result1->fetch_assoc()) {			$WorkExp=$row["WExp"];			$ResearchExp=$row["RExp"];			$DSGrade=$row["ds"];			$SEGrade=$row["se"];			$AutomataGrade=$row["automata"];			$AlgoGrade=$row["algo"];			$OSGrade=$row["os"];			$PLGrade=$row["proLan"];			$CaGrade=$row["ca"];			$CalculusGrade=$row["calculus"];			$DMGrade=$row["discrete"];			$OtherGrade=$row["other"];			}			}			if ($result2->num_rows > 0) {	// output data of each row		while($row = $result2->fetch_assoc()) {			$LOR1=$row["LOR1"];			$LOR2=$row["LOR2"];			$LOR3=$row["LOR3"];			$SOP=$row["SOP"];			$Admit=$row["Recommendation"];			$Aid=$row["FinAid"];			$Comments=$row["Comments"];			$FacultyName=$row["FacultyName"];			$Date=$row["Date"];			}			}?>
	<h3 style="margin: 0; text-align:center">Georgia State University</h3>
	<h3 style="margin: 0; text-align:center">Department of Computer Science</h3>
	<h3 style="text-align:center;">UPDATE STUDENT INFORMATION</h3>
	<h4 style="margin: 0; text-align:center;"></h4>
</div>
	
      <title>Update a Record in MySQL Database</title>
   </head>
   
   <body>
      <?php
         if(isset($_POST['update'])) {
            $dbhost = 'localhost';
            $dbuser = 'ogms';
            $dbpass = 'ogmsadm';
            
            $conn = mysql_connect($dbhost, $dbuser, $dbpass);
            
            if(! $conn ) {
               die('Could not connect: ' . mysql_error());
            }
            
            $PantherId = $_POST['PantherId'];
            $firstname = $_POST['firstname'];
            $lastname = $_POST['lastname'];
            $concentration = $_POST['concentration'];
            $term = $_POST['term'];
            $email = $_POST['email'];
            $altname = $_POST['altname'];
            $middlename = $_POST['middlename'];
            $program = $_POST['program'];
            $gred = $_POST['gred'];
            $grev = $_POST['grev'];
            $grevp = $_POST['grevp'];
            $greq = $_POST['greq'];
            $greqp = $_POST['greqp'];
            $anals = $_POST['anals'];
            $analp = $_POST['analp'];
            $toefld = $_POST['toefld'];
            $toefltt = $_POST['toefltt'];
            $toefls = $_POST['toefls'];
            $toeflr = $_POST['toeflr'];
            $toeflw = $_POST['toeflw'];
            $toefll = $_POST['toefll'];
            $toeflsp = $_POST['toeflsp'];
            $uggpa = $_POST['uggpa'];
            $ugmaj = $_POST['ugmaj'];
            $pggpa = $_POST['pggpa'];
            $race = $_POST['race'];
            $ethn = $_POST['ethn'];
            $gender = $_POST['gender'];
              $f1=$_POST['f1'];
            $f2=$_POST['f2'];
            $f3=$_POST['f3'];
    
             $race = $_POST['race'];
            $ethn = $_POST['ethn'];
            $gender = $_POST['gender'];
            $nation = $_POST['nation'];
            $status = $_POST['status'];
            $cn1 = $_POST['cn1'];
            $sd1 = $_POST['sd1'];
            $ed1 = $_POST['ed1'];
            $maj1 = $_POST['maj1'];
            $d1 = $_POST['d1'];
             $grets=$_POST['grets'];
           $cn2 = $_POST['cn2'];
            $sd2 = $_POST['sd2'];
            $ed2 = $_POST['ed2'];
            $maj2 = $_POST['maj2'];
            $d2 = $_POST['d2'];
           $cn3 = $_POST['cn3'];
            $sd3 = $_POST['sd3'];
            $ed3 = $_POST['ed3'];
            $maj3 = $_POST['maj3'];
            $d3 = $_POST['d3'];
   $sql = "UPDATE tbl_excel_info SET FirstName = '$firstname' , LastName='$lastname',MiddleName ='$middlename' , Term='$term', EMail='$email', Concentration='$concentration', Program='$program', GREDate='$gred', GREVerbalScore='$grev', GREVerbalPercent='$grevp',GREQuantScore='$greq',GREQuantPercent='$greqp',GRETotal='$grets', GREAnalyticalScore='$anals',GREAnalyticalPercent='$analp',TOEFLDateofTest='$toefld' , TOEFLTestType='$toefltt', TOEFLTotal='$toefls', TOEFLReading='$toeflr', ToeflListening='$toefll', ToeflSpeaking='$toeflsp', TOEFLWriting='$toeflw', UgGPAOverall='$uggpa' , UgGPAMajor='$ugmaj', GraduateGPA='$pggpa', Faculty1='$f1' ,Faculty2='$f2', Faculty3='$f3', Race='$race', Ethnicity='$ethn', Gender='$gender', CitizenshipCountry='$nation' ,CitizenshipStatus='$status', CollegeName1='$cn1', DateAttendedFrom1='$sd1', DateAttendedTo1='$ed1', Major1='$maj1', Degree1='$d1',CollegeName2='$cn2', DateAttendedFrom2='$sd2', DateAttendedTo2='$ed2', Major2='$maj2', Degree2='$d2',CollegeName3='$cn3', DateAttendedFrom3='$sd3', DateAttendedTo3='$ed3', Major3='$maj3', Degree3='$d3' WHERE PantherId = '$PantherId'";
 

            mysql_select_db('ogms');
            $retval = mysql_query( $sql, $conn );
            
            if(! $retval ) {
               die('Could not update data: ' . mysql_error());
            }
            echo "Updated data successfully\n";
            
            mysql_close($conn);
         }else {
            ?>
               <form method = "post" action = "<?php $_PHP_SELF ?>">
           

PantherID:
 
<input type="number" name="PantherId" size=100 value="<?=($PantherId!=''?'00'.$PantherId:'')?>"
/>
&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;

First Name:

 
<input type="text" name="firstname"  value="<?=$FirstName?>"/>

<br>
<br>
<br>
Last Name:

 
<input type="text" name="lastname" value="<?=$LastName?>"
/>
&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; 

Middle Name:

<input type="text" name="middlename"  value="<?=$MiddleName?>"/>

&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;

<br>
<br>
<br>


Term:


<input type="text" name="term"  value="<?=$Term?>"/>
&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;

Email:


<input type="text" name="email" value="<?=$Email?>"/>

&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;

<br>
<br>
<br>
Concentration:
 
<input type="text" name="concentration"  value="<?=$Concentration?>"/>


&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;

Program:
 
<input type="text" name="program" value="<?=$Program?>"/>

<br>
<br>
<br>
GRE Date:
 
<input type="date" name="gred" value="<?=$GREDate?>"/>


&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; 

GRE Verbal Score:
 
<input type="text" name="grev" value="<?=$GREVerbalScore?>"/>

<br>
<br>
<br>
GRE Verbal Percent:
 
<input type="date" name="grevp" value="<?=$GREVerbalPercent?>"/>


&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;

GRE Total Score:
 
<input type="number" name="grets" value="<?=$GREVerbalScore?>"/>

<br>
<br>
<br>
 

GRE Quant Score:
 
<input type="number" name="greq" value="<?=$GREQuantScore?>"/>

&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;

GRE Quant Percentile:
 
<input type="number" name="greqp" value="<?=$GREQuantPercent?>"/>

<br>
<br>
<br>
Analytical W. Score:
 
<input type="number" name="anals" value="<?=$GREAnalyticalScore?>"
/>
&nbsp; &nbsp; &nbsp;&nbsp;  

Analytical W. Percentile:
 
<input type="number" name="analp" value="<?=$GREAnalyticalPercent?>"/>

&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;

<br>
<br>
<br>

Toefl Date:
 
<input type="date" name="toefld" value="<?=$TOEFLDate?>"/>
&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; 
Toefl Test Type:
 
<input type="text" name="toefltt" value="<?=$TOEFLTestType?>"/>

&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;

<br>
<br>
<br>
Toefl Score:
 
<input type="number" name="toefls" value="<?=$TOEFLTotal?>"/>


&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;

Toefl Reading:
 
<input type="number" name="toeflr" value="<?=$TOEFLReading?>"/>

<br>
<br>
<br>
Toefl Writing:
 
<input type="text" name="toeflw" value="<?=$TOEFLWriting?>"/>


&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;

Toefl Listening:
 
<input type="text" name="toefll" value="<?=$TOEFLListening?>"/>

<br>
<br>
<br>

   TOEFL Speaking:
 
<input type="text" name="toeflsp" value="<?=$TOELSpeaking?>"
/>
&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; 

UG GPA:
 
<input type="text" name="uggpa" value="<?=$UgGPAOverall?>"/>

&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;

<br>
<br>
<br>

UG GPA Major:
 
<input type="text" name="ugmaj" value="<?=$UgGPAMajor?>"/>
&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; 
Graduate GPA:
 
<input type="text" name="pggpa" value="<?=$GraduateGPAOverall?>"/>

&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;

<br>
<br>
<br>
Faculty1:
 
<input type="text" name="f1" value="<?=$Faculty1?>"/>


&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;

Faculty2:
 
<input type="text" name="f2" value="<?=$Faculty2?>"/>

<br>
<br>
<br>
Faculty3:
 
<input type="text" name="f3" value="<?=$Faculty3?>"/>


&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;

Race:
 
<input type="text" name="race" value="<?=$Race?>"/>

<br>
<br>
<br>      

 Ethnicity:
 
<input type="text" name="ethn" value="<?=$Ethnicity?>"/>
&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; 

Gender:
 
<input type="text" name="gender" value="<?=$Gender?>"/>

&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;

<br>
<br>
<br>

Citizen Nation:
 
<input type="text" name="nation" value="<?=$CitizenshipCountry?>"/>
&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; 

Citizen Status:
 
<input type="text" name="status" value="<?=$CitizenshipStatus?>"/>

&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;

<br>
<br>
<br>
College Name 1:
 
<input type="text" name="cn1" value="<?=$CollegeName1?>"/>


&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;

Start Date-1:
 
<input type="text" name="sd1" value="<?=$DateAttendedFrom1?>"/>

<br>
<br>
<br>
End Date-1:
 
<input type="text" name="ed1" value="<?=$DateAttendedTo1?>"/>


&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;

Major 1:
 
<input type="text" name="maj1" value="<?=$Major1?>"/>

<br>
<br>
<br>      

Degree 1:
 
<input type="text" name="d1" value="<?=$Degree1?>"/>       



<br>
<br>
<br> 


College Name 2:
 
<input type="text" name="cn2" value="<?=$CollegeName2?>"/>


&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;

Start Date-2:
 
<input type="text" name="sd2" value="<?=$DateAttendedFrom2?>"/>

<br>
<br>
<br>
End Date-2:
 
<input type="text" name="ed2" value="<?=$DateAttendedTo2?>"/>


&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;

Major 2:
 
<input type="text" name="maj2" value="<?=$Major2?>"/>

<br>
<br>
<br>      

Degree 2:
 
<input type="text" name="d2" value="<?=$Degree2?>"/>       



<br>
<br>
<br> 

College Name 3:
 
<input type="text" name="cn3" value="<?=$CollegeName3?>"/>


&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;

Start Date-3:
 
<input type="text" name="sd3" value="<?=$DateAttendedFrom3?>"/>

<br>
<br>
<br>
End Date-3:
 
<input type="text" name="ed3" value="<?=$DateAttendedTo3?>"/>


&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;

Major 3:
 
<input type="text" name="maj3" value="<?=$Major3?>"/>

<br>
<br>
<br>      

Degree 3:
 
<input type="text" name="d3" value="<?=$Degree3?>"/>       



<br>
<br>
<br> 


     </tr>
                   </tr>
                  
                     <tr>
                        <td width = "100"> </td>
                        <td>
                           <input name = "update" type = "submit" 
                              id = "update" value = "Update">
                        </td>
                     </tr>
                  
                  </table>
               </form>
            <?php
         }
      ?>
      
   </body>
</html>