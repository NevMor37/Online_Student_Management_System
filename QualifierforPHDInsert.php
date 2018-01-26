<?php
if (isset($_SERVER['HTTP_HOST']))
{
	if($_SERVER['HTTP_HOST'] == "localhost"){
		$root = realpath($_SERVER["CONTEXT_DOCUMENT_ROOT"]);
		define("ROOT",$root."/student/ogms/public_html");
		$root = ROOT; 
	}
	else{
		$root = realpath($_SERVER["CONTEXT_DOCUMENT_ROOT"]);
	}
}
    else{
	    $root = realpath($_SERVER["CONTEXT_DOCUMENT_ROOT"]);
    }
session_start();
$user_name = $_SESSION['user']['name'] ;
$user_email = $_SESSION['user']['mail'] ;
$user_pantherid = $_SESSION['user']['pid'];
include($root.'/osms.dbconfig.inc');
$db = new mysqli($hostname,$username,$password,$dbname);
if ($db->connect_error){
     die("Connection Failed: ".$conn->connect_error);
}/*
else{
  echo "Connection Successful.";
}
*/
$getName = "SELECT FirstName, MiddleName, LastName, PantherID FROM tbl_student_info WHERE Degree = 'PHD' ORDER BY FirstName ";
        $nameResult = mysqli_query($db, $getName);
        if($nameResult ->num_rows >0){
          $i =0;  
                 while($row = $nameResult->fetch_assoc()){
                        $studentArray[$i] = array();
                        $studentArray[$i]['PantherID'] = $row["PantherID"];
                        $studentArray[$i]['FirstName'] = $row["FirstName"];
                        $studentArray[$i]['MiddleName'] = $row["FirstName"];
                        $studentArray[$i]['LastName'] = $row["LastName"];
                        $i++;
                 }
        }
$getTerm = "SELECT Termid, Term, Startday FROM tbl_term";
$termRes = mysqli_query($db, $getTerm);
if($termRes ->num_rows>0){
     $i =0;
     while($row = $termRes->fetch_assoc()){
           $termArray[$i] = array();
           $termArray[$i]["termid"] = $row["Termid"];
           $termArray[$i]["term"] = $row["Term"];
           $termArray[$i]["startday"] = $row["Startday"];
           $i++;
     }
}

$getFaculty ="SELECT FirstName, MiddleName, LastName, email FROM tbl_faculty_info ORDER BY FirstName";
$FacultyRes = mysqli_query($db, $getFaculty);
if($FacultyRes ->num_rows>0){
       $i =0;
       while($row = $FacultyRes->fetch_assoc()){
             $FacultyArray[$i] = array();
             $FacultyArray[$i]["FirstName"] = $row["FirstName"];
             $FacultyArray[$i]["MiddleName"] = $row["MiddleName"];
             $FacultyArray[$i]["LastName"] = $row["LastName"];
             $FacultyArray[$i]["email"] = $row["email"];
             $i++;
       }
}
?>

<html>
<head>
    <meta charset = "UTF-8">
    <style type = "text/css">
    </style>
</head>
<body>
  <h2>Add new records</h2>
  <form action = "QualifierforPHDInsert.php" method = "POST">
	<strong>Student :<select name = 'student'>
    <?php
        foreach($studentArray as $info){
                 $pID = $info["PantherID"];
                 $fName = $info["FirstName"];
                 $mName = $info["MiddleName"];
                 $lName = $info["LastName"];
                 echo "<option value= '".$pID."'>";
                 echo "$fName $lName";
                 echo "</option>";
        }
        
    ?>
    </select><br />
Term<select name = 'te'>
  <?php
       foreach($termArray as $t){
              $tid = $t["termid"];
              $term = $t["term"];
              echo "<option value = '".$tid."''>$term</option>";
       }  
  ?>
</select><br />






  The objective of the research examination is to assess the student’s potential to begin doctoral-level research.  Please assess the student’s ability of the following: (score 1 to 10 for each).
  <hr />
First committee member:<select name = 'committeeMember1'>
  <?php
           foreach($FacultyArray as $F){
               $Femail = $F["email"];
               $FFirst = $F["FirstName"];
               $FLast = $F["LastName"];
               echo "<option value = '".$Femail."'>$FFirst $FLast</option>";
           }
  ?>
</select><br />
 <p>A. Overall understanding (all 5 scores must be >= 6 to pass)<br />
       1. Research background (including motivation and basic techniques).
           <select name = "A11">
           <option value = 1 >1</option><option value = 2>2</option><option value = 3>3</option><option value = 4 >4</option><option value = 5 >5</option><option value = 6 >6</option><option value = 7>7</option><option value = 8>8</option><option value = 9>9</option><option value = 10>10</option>
           </select><br />
       2. Clarity of problem formulation. 
           <select name = "A12">
           <option value = 1 >1</option><option value = 2>2</option><option value = 3>3</option><option value = 4 >4</option><option value = 5 >5</option><option value = 6 >6</option><option value = 7>7</option><option value = 8>8</option><option value = 9>9</option><option value = 10>10</option>
           </select><br />
       3. Understanding and explanation of validation (experimental or theoretical).
           <select name = "A13">
           <option value = 1 >1</option><option value = 2>2</option><option value = 3>3</option><option value = 4 >4</option><option value = 5 >5</option><option value = 6 >6</option><option value = 7>7</option><option value = 8>8</option><option value = 9>9</option><option value = 10>10</option>
           </select><br />
       4. Understanding of committee questions.
           <select name = "A14">
           <option value = 1 >1</option><option value = 2>2</option><option value = 3>3</option><option value = 4 >4</option><option value = 5 >5</option><option value = 6 >6</option><option value = 7>7</option><option value = 8>8</option><option value = 9>9</option><option value = 10>10</option>
           </select><br />
       5. Response to committee questions.
           <select name = "A15">
           <option value = 1 >1</option><option value = 2>2</option><option value = 3>3</option><option value = 4 >4</option><option value = 5 >5</option><option value = 6 >6</option><option value = 7>7</option><option value = 8>8</option><option value = 9>9</option><option value = 10>10</option>
           </select><br />
</p><br />
<p>B. Review quality (at least 2 scores >= 6 to pass)<br />
       1. Remarks on related work.
           <select name = "B11">
           <option value = 1 >1</option><option value = 2>2</option><option value = 3>3</option><option value = 4 >4</option><option value = 5 >5</option><option value = 6 >6</option><option value = 7>7</option><option value = 8>8</option><option value = 9>9</option><option value = 10>10</option>
           </select><br />
       2. Remarks on correctness  (proofs and experimental validation). 
         <select name = "B12">
           <option value = 1 >1</option><option value = 2>2</option><option value = 3>3</option><option value = 4 >4</option><option value = 5 >5</option><option value = 6 >6</option><option value = 7>7</option><option value = 8>8</option><option value = 9>9</option><option value = 10>10</option>
           </select><br />
       3. Improvements and future directions.
           <select name = "B13">
           <option value = 1 >1</option><option value = 2>2</option><option value = 3>3</option><option value = 4 >4</option><option value = 5 >5</option><option value = 6 >6</option><option value = 7>7</option><option value = 8>8</option><option value = 9>9</option><option value = 10>10</option>
           </select><br />
       4. Overall written report quality.
          <select name = "B14">
           <option value = 1 >1</option><option value = 2>2</option><option value = 3>3</option><option value = 4 >4</option><option value = 5 >5</option><option value = 6 >6</option><option value = 7>7</option><option value = 8>8</option><option value = 9>9</option><option value = 10>10</option>
           </select><br />
</p><br />
<p>C. Oral Presentation quality (at least 2 scores >= 6 to pass)<br />
       1. Slide quality.
           <select name = "C11">
           <option value = 1 >1</option><option value = 2>2</option><option value = 3>3</option><option value = 4 >4</option><option value = 5 >5</option><option value = 6 >6</option><option value = 7>7</option><option value = 8>8</option><option value = 9>9</option><option value = 10>10</option>
           </select><br />
       2. Understandability (ability to convey concepts). 
           <select name = "C12">
           <option value = 1 >1</option><option value = 2>2</option><option value = 3>3</option><option value = 4 >4</option><option value = 5 >5</option><option value = 6 >6</option><option value = 7>7</option><option value = 8>8</option><option value = 9>9</option><option value = 10>10</option>
           </select><br />
       3. Speaking freely (not just reading the slides.)
           <select name = "C13">
           <option value = 1 >1</option><option value = 2>2</option><option value = 3>3</option><option value = 4 >4</option><option value = 5 >5</option><option value = 6 >6</option><option value = 7>7</option><option value = 8>8</option><option value = 9>9</option><option value = 10>10</option>
           </select><br />
       4. Time allocation.
           <select name = "C14">
           <option value = 1 >1</option><option value = 2>2</option><option value = 3>3</option><option value = 4 >4</option><option value = 5 >5</option><option value = 6 >6</option><option value = 7>7</option><option value = 8>8</option><option value = 9>9</option><option value = 10>10</option>
           </select><br />
</p><br />
<p>D. Overall Score (pass / fail)
<select name = "D11"><option value = "pass">pass</option><option value = "fail" >fail</option></select><br />
</p><br />
       Comments: (Required for each score less than 6)<br />
       <textarea name = "comm1" rows = "8" cols = "40">
       </textarea><br />
<hr />
Second committee member:<select name = 'committeeMember2'>
  <?php
           foreach($FacultyArray as $F){
               $Femail = $F["email"];
               $FFirst = $F["FirstName"];
               $FLast = $F["LastName"];
               echo "<option value = '".$Femail."'>$FFirst $FLast</option>";
           }
  ?>
<p>A. Overall understanding (all 5 scores must be >= 6 to pass)<br />
       1. Research background (including motivation and basic techniques).
           <select name = "A21">
           <option value = 1 >1</option><option value = 2>2</option><option value = 3>3</option><option value = 4 >4</option><option value = 5 >5</option><option value = 6 >6</option><option value = 7>7</option><option value = 8>8</option><option value = 9>9</option><option value = 10>10</option>
           </select><br />
       2. Clarity of problem formulation. 
           <select name = "A22">
           <option value = 1 >1</option><option value = 2>2</option><option value = 3>3</option><option value = 4 >4</option><option value = 5 >5</option><option value = 6 >6</option><option value = 7>7</option><option value = 8>8</option><option value = 9>9</option><option value = 10>10</option>
           </select><br />
       3. Understanding and explanation of validation (experimental or theoretical).
           <select name = "A23">
           <option value = 1 >1</option><option value = 2>2</option><option value = 3>3</option><option value = 4 >4</option><option value = 5 >5</option><option value = 6 >6</option><option value = 7>7</option><option value = 8>8</option><option value = 9>9</option><option value = 10>10</option>
           </select><br />
       4. Understanding of committee questions.
           <select name = "A24">
           <option value = 1 >1</option><option value = 2>2</option><option value = 3>3</option><option value = 4 >4</option><option value = 5 >5</option><option value = 6 >6</option><option value = 7>7</option><option value = 8>8</option><option value = 9>9</option><option value = 10>10</option>
           </select><br />
       5. Response to committee questions.
           <select name = "A25">
           <option value = 1 >1</option><option value = 2>2</option><option value = 3>3</option><option value = 4 >4</option><option value = 5 >5</option><option value = 6 >6</option><option value = 7>7</option><option value = 8>8</option><option value = 9>9</option><option value = 10>10</option>
           </select><br />
</p><br />
<p>B. Review quality (at least 2 scores >= 6 to pass)
       1. Remarks on related work.
           <select name = "B21">
           <option value = 1 >1</option><option value = 2>2</option><option value = 3>3</option><option value = 4 >4</option><option value = 5 >5</option><option value = 6 >6</option><option value = 7>7</option><option value = 8>8</option><option value = 9>9</option><option value = 10>10</option>
           </select><br />
       2. Remarks on correctness  (proofs and experimental validation). 
         <select name = "B22">
           <option value = 1 >1</option><option value = 2>2</option><option value = 3>3</option><option value = 4 >4</option><option value = 5 >5</option><option value = 6 >6</option><option value = 7>7</option><option value = 8>8</option><option value = 9>9</option><option value = 10>10</option>
           </select><br />
       3. Improvements and future directions.
           <select name = "B23">
           <option value = 1 >1</option><option value = 2>2</option><option value = 3>3</option><option value = 4 >4</option><option value = 5 >5</option><option value = 6 >6</option><option value = 7>7</option><option value = 8>8</option><option value = 9>9</option><option value = 10>10</option>
           </select><br />
       4. Overall written report quality.
          <select name = "B24">
           <option value = 1 >1</option><option value = 2>2</option><option value = 3>3</option><option value = 4 >4</option><option value = 5 >5</option><option value = 6 >6</option><option value = 7>7</option><option value = 8>8</option><option value = 9>9</option><option value = 10>10</option>
           </select><br />
</p><br />
<p>C. Oral Presentation quality (at least 2 scores >= 6 to pass)<br />
       1. Slide quality.
           <select name = "C21">
           <option value = 1 >1</option><option value = 2>2</option><option value = 3>3</option><option value = 4 >4</option><option value = 5 >5</option><option value = 6 >6</option><option value = 7>7</option><option value = 8>8</option><option value = 9>9</option><option value = 10>10</option>
           </select><br />
       2. Understandability (ability to convey concepts). 
           <select name = "C22">
           <option value = 1 >1</option><option value = 2>2</option><option value = 3>3</option><option value = 4 >4</option><option value = 5 >5</option><option value = 6 >6</option><option value = 7>7</option><option value = 8>8</option><option value = 9>9</option><option value = 10>10</option>
           </select><br />
       3. Speaking freely (not just reading the slides.
           <select name = "C23">
           <option value = 1 >1</option><option value = 2>2</option><option value = 3>3</option><option value = 4 >4</option><option value = 5 >5</option><option value = 6 >6</option><option value = 7>7</option><option value = 8>8</option><option value = 9>9</option><option value = 10>10</option>
           </select><br />
       4. Time allocation.
           <select name = "C24">
           <option value = 1 >1</option><option value = 2>2</option><option value = 3>3</option><option value = 4 >4</option><option value = 5 >5</option><option value = 6 >6</option><option value = 7>7</option><option value = 8>8</option><option value = 9>9</option><option value = 10>10</option>
           </select><br />
</p><br />
<p>D. Overall Score (pass / fail)
<select name = "D21"><option value = "pass">pass</option><option value = "fail" >fail</option></select><br />
</p><br />
       Comments: (Required for each score less than 6)<br />
       <textarea name = "comm2" rows = "8" cols = "40">
       </textarea><br />
<hr />
Third committee member:<select name = 'committeeMember3'>
  <?php
           foreach($FacultyArray as $F){
               $Femail = $F["email"];
               $FFirst = $F["FirstName"];
               $FLast = $F["LastName"];
               echo "<option value = '".$Femail."'>$FFirst $FLast</option>";
           }
  ?>
<p>A. Overall understanding (all 5 scores must be >= 6 to pass)<br />
       1. Research background (including motivation and basic techniques).
           <select name = "A31">
           <option value = 1 >1</option><option value = 2>2</option><option value = 3>3</option><option value = 4 >4</option><option value = 5 >5</option><option value = 6 >6</option><option value = 7>7</option><option value = 8>8</option><option value = 9>9</option><option value = 10>10</option>
           </select><br />
       2. Clarity of problem formulation. 
           <select name = "A32">
           <option value = 1 >1</option><option value = 2>2</option><option value = 3>3</option><option value = 4 >4</option><option value = 5 >5</option><option value = 6 >6</option><option value = 7>7</option><option value = 8>8</option><option value = 9>9</option><option value = 10>10</option>
           </select><br />
       3. Understanding and explanation of validation (experimental or theoretical).
           <select name = "A33">
           <option value = 1 >1</option><option value = 2>2</option><option value = 3>3</option><option value = 4 >4</option><option value = 5 >5</option><option value = 6 >6</option><option value = 7>7</option><option value = 8>8</option><option value = 9>9</option><option value = 10>10</option>
           </select><br />
       4. Understanding of committee questions.
           <select name = "A34">
           <option value = 1 >1</option><option value = 2>2</option><option value = 3>3</option><option value = 4 >4</option><option value = 5 >5</option><option value = 6 >6</option><option value = 7>7</option><option value = 8>8</option><option value = 9>9</option><option value = 10>10</option>
           </select><br />
       5. Response to committee questions.
           <select name = "A35">
           <option value = 1 >1</option><option value = 2>2</option><option value = 3>3</option><option value = 4 >4</option><option value = 5 >5</option><option value = 6 >6</option><option value = 7>7</option><option value = 8>8</option><option value = 9>9</option><option value = 10>10</option>
           </select><br />
</p><br />
<p>B. Review quality (at least 2 scores >= 6 to pass)
       1. Remarks on related work.
           <select name = "B31">
           <option value = 1 >1</option><option value = 2>2</option><option value = 3>3</option><option value = 4 >4</option><option value = 5 >5</option><option value = 6 >6</option><option value = 7>7</option><option value = 8>8</option><option value = 9>9</option><option value = 10>10</option>
           </select><br />
       2. Remarks on correctness  (proofs and experimental validation). 
         <select name = "B32">
           <option value = 1 >1</option><option value = 2>2</option><option value = 3>3</option><option value = 4 >4</option><option value = 5 >5</option><option value = 6 >6</option><option value = 7>7</option><option value = 8>8</option><option value = 9>9</option><option value = 10>10</option>
           </select><br />
       3. Improvements and future directions.
           <select name = "B33">
           <option value = 1 >1</option><option value = 2>2</option><option value = 3>3</option><option value = 4 >4</option><option value = 5 >5</option><option value = 6 >6</option><option value = 7>7</option><option value = 8>8</option><option value = 9>9</option><option value = 10>10</option>
           </select><br />
       4. Overall written report quality.
          <select name = "B34">
           <option value = 1 >1</option><option value = 2>2</option><option value = 3>3</option><option value = 4 >4</option><option value = 5 >5</option><option value = 6 >6</option><option value = 7>7</option><option value = 8>8</option><option value = 9>9</option><option value = 10>10</option>
           </select><br />
</p><br />
<p>C. Oral Presentation quality (at least 2 scores >= 6 to pass)<br />
       1. Slide quality.
           <select name = "C31">
           <option value = 1 >1</option><option value = 2>2</option><option value = 3>3</option><option value = 4 >4</option><option value = 5 >5</option><option value = 6 >6</option><option value = 7>7</option><option value = 8>8</option><option value = 9>9</option><option value = 10>10</option>
           </select><br />
       2. Understandability (ability to convey concepts). 
           <select name = "C32">
           <option value = 1 >1</option><option value = 2>2</option><option value = 3>3</option><option value = 4 >4</option><option value = 5 >5</option><option value = 6 >6</option><option value = 7>7</option><option value = 8>8</option><option value = 9>9</option><option value = 10>10</option>
           </select><br />
       3. Speaking freely (not just reading the slides.
           <select name = "C33">
           <option value = 1 >1</option><option value = 2>2</option><option value = 3>3</option><option value = 4 >4</option><option value = 5 >5</option><option value = 6 >6</option><option value = 7>7</option><option value = 8>8</option><option value = 9>9</option><option value = 10>10</option>
           </select><br />
       4. Time allocation.
           <select name = "C34">
           <option value = 1 >1</option><option value = 2>2</option><option value = 3>3</option><option value = 4 >4</option><option value = 5 >5</option><option value = 6 >6</option><option value = 7>7</option><option value = 8>8</option><option value = 9>9</option><option value = 10>10</option>
           </select><br />
</p><br />
<p>D. Overall Score (pass / fail)
<select name = "D31"><option value = 'pass'>pass</option><option value = 'fail' >fail</option></select><br />
</p><br />
       Comments: (Required for each score less than 6)<br />
       <textarea name = "comm3" rows = "8" cols = "40">
       </textarea><br />
<input type = "submit" name = "submit"><br />
</form>
</body>
</html>
<?php
       function InsertS($db,$id, $main, $sub, $value, $comem){
          $sql =" INSERT INTO tbl_qualifier_extra(QualifierID, MainQuality, SubQuality, Value, Committeemember) VALUES ($id, '$main', '$sub', '$value', '$comem')
                ";
                //echo $sql;
                $db->query($sql);
       }
       function InsertC($db,$com1,$com2,$com3,$comem1, $comem2, $comem3, $stu, $te){
       $sql = "
          INSERT INTO tbl_qualifier(Committeemember1, Committeemember2, Committeemember3, CommitteememberComment1, CommitteememberComment2, CommitteememberComment3, StudentID, TermID)
          VALUES ('$com1', '$com2', '$com3', '$comem1', '$comem2', '$comem3', '$stu', '$te');
          ";
       //echo $sql;
        
       $result = mysqli_query($db,$sql);
       $genid = mysqli_insert_id($db);
       return $genid;
}
       if(isset($_POST["submit"])){

               $a11 = $_POST["A11"];
               $a12 = $_POST["A12"];
               $a13 = $_POST["A13"];
               $a14 = $_POST["A14"];
               $a15 = $_POST["A15"];
               $b11 = $_POST["B11"];
               $b12 = $_POST["B12"];
               $b13 = $_POST["B13"];
               $b14 = $_POST["B14"];
               $c11 = $_POST["C11"];
               $c12 = $_POST["C12"];
               $c13 = $_POST["C13"];
               $c14 = $_POST["C14"];
               $d11 = $_POST["D11"];
               $co1 = $_POST["comm1"];
               $co2 = $_POST["comm2"];
               $co3 = $_POST["comm3"];
               $com1 = $_POST["committeeMember1"];
               $com2 = $_POST["committeeMember2"];
               $com3 = $_POST["committeeMember3"];
               $a21 = $_POST["A21"];
               $a22 = $_POST["A22"];
               $a23 = $_POST["A23"];
               $a24 = $_POST["A24"];
               $a25 = $_POST["A25"];
               $b21 = $_POST["B21"];
               $b22 = $_POST["B22"];
               $b23 = $_POST["B23"];
               $b24 = $_POST["B24"];
               $c21 = $_POST["C21"];
               $c22 = $_POST["C22"];
               $c23 = $_POST["C23"];
               $c24 = $_POST["C24"];
               $d21 = $_POST["D21"];
               $a21 = $_POST["A21"];
               $a22 = $_POST["A22"];
               $a23 = $_POST["A23"];
               $a24 = $_POST["A24"];
               $a25 = $_POST["A25"];
               $b21 = $_POST["B21"];
               $b22 = $_POST["B22"];
               $b23 = $_POST["B23"];
               $b24 = $_POST["B24"];
               $c21 = $_POST["C21"];
               $c22 = $_POST["C22"];
               $c23 = $_POST["C23"];
               $c24 = $_POST["C24"];
               $d21 = $_POST["D21"];
               $a31 = $_POST["A31"];
               $a32 = $_POST["A32"];
               $a33 = $_POST["A33"];
               $a34 = $_POST["A34"];
               $a35 = $_POST["A35"];
               $b31 = $_POST["B31"];
               $b32 = $_POST["B32"];
               $b33 = $_POST["B33"];
               $b34 = $_POST["B34"];
               $c31 = $_POST["C31"];
               $c32 = $_POST["C32"];
               $c33 = $_POST["C33"];
               $c34 = $_POST["C34"];
               $d31 = $_POST["D31"];
               /*
              echo $co1."<br />";
              echo $co2."<br />";
              echo $co3."<br />";
              echo $com1."<br />";
              echo $com2."<br />";
              echo $com3."<br />";
               
*/
              //echo $d11." ".$d21." ".$d31."<br />";
              $Stu = $_POST["student"];
              $ter = $_POST["te"];
              $newID = InsertC($db,$com1, $com2, $com3, $co1, $co2, $co3,$Stu, $ter);
              //echo $newID;
              
              InsertS($db, $newID, 'A', 'A11', $a11, $com1);
              InsertS($db, $newID, 'A', 'A12', $a12, $com1);
              InsertS($db, $newID, 'A', 'A13', $a13, $com1);
              InsertS($db, $newID, 'A', 'A14', $a14, $com1);
              InsertS($db, $newID, 'A', 'A15', $a15, $com1);
              InsertS($db, $newID, 'B', 'B11', $b11, $com1);
              InsertS($db, $newID, 'B', 'B12', $b12, $com1);
              InsertS($db, $newID, 'B', 'B13', $b13, $com1);
              InsertS($db, $newID, 'B', 'B14', $b14, $com1);
              InsertS($db, $newID, 'C', 'C11', $c11, $com1);
              InsertS($db, $newID, 'C', 'C12', $c12, $com1);
              InsertS($db, $newID, 'C', 'C13', $c13, $com1);
              InsertS($db, $newID, 'C', 'C14', $c14, $com1);
              InsertS($db, $newID, 'D', 'D11', $d11, $com1);

              InsertS($db, $newID, 'A', 'A21', $a21, $com2);
              InsertS($db, $newID, 'A', 'A22', $a22, $com2);
              InsertS($db, $newID, 'A', 'A23', $a23, $com2);
              InsertS($db, $newID, 'A', 'A24', $a24, $com2);
              InsertS($db, $newID, 'A', 'A25', $a25, $com2);
              InsertS($db, $newID, 'B', 'B21', $b21, $com2);
              InsertS($db, $newID, 'B', 'B22', $b22, $com2);
              InsertS($db, $newID, 'B', 'B23', $b23, $com2);
              InsertS($db, $newID, 'B', 'B24', $b24, $com2);
              InsertS($db, $newID, 'C', 'C21', $c21, $com2);
              InsertS($db, $newID, 'C', 'C22', $c22, $com2);
              InsertS($db, $newID, 'C', 'C23', $c23, $com2);
              InsertS($db, $newID, 'C', 'C24', $c24, $com2);
              InsertS($db, $newID, 'D', 'D21', $d21, $com2);


              InsertS($db, $newID, 'A', 'A31', $a31, $com3);
              InsertS($db, $newID, 'A', 'A32', $a32, $com3);
              InsertS($db, $newID, 'A', 'A33', $a33, $com3);
              InsertS($db, $newID, 'A', 'A34', $a34, $com3);
              InsertS($db, $newID, 'A', 'A35', $a35, $com3);
              InsertS($db, $newID, 'B', 'B31', $b31, $com3);
              InsertS($db, $newID, 'B', 'B32', $b32, $com3);
              InsertS($db, $newID, 'B', 'B33', $b33, $com3);
              InsertS($db, $newID, 'B', 'B34', $b34, $com3);
              InsertS($db, $newID, 'C', 'C31', $c31, $com3);
              InsertS($db, $newID, 'C', 'C32', $c32, $com3);
              InsertS($db, $newID, 'C', 'C33', $c33, $com3);
              InsertS($db, $newID, 'C', 'C34', $c34, $com3);
              InsertS($db, $newID, 'D', 'D31', $d31, $com3);

           header("location:QualifierforPHDviewdashboard.php");  //redirect home page


       }

?>