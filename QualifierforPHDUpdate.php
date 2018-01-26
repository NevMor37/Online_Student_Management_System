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
}
/*
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
<?php

       if(!empty($_GET["id"])){
             if(is_numeric($_GET["id"])){
                 $id = $_GET["id"];
                 $_SESSION["id"] = $id;
                 //echo $id;
                 $sql = "
                    SELECT qa.TermID, qa.StudentID, qa.Committeemember1, qa.Committeemember2, qa.Committeemember3, qa.CommitteememberComment1, qa.CommitteememberComment2, qa.CommitteememberComment3, ex.Committeemember, ex.MainQuality, ex.SubQuality, ex.Value
                    FROM tbl_qualifier AS qa 
                    LEFT JOIN tbl_qualifier_extra as ex 
                    on ex.QualifierID = qa.QualifierID 
                    WHERE qa.QualifierID = "
                    .$id;
                  //echo $sql;
                  $Sres = mysqli_query($db, $sql);
                 
                  $runtime = 0;
                  if($Sres ->num_rows > 0){
                          while($row =mysqli_fetch_assoc($Sres)){
                            if($runtime == 0){
                              $tID = $row["TermID"];
                                $sID = $row["StudentID"];
                                $getCommitteemember1 = $row["Committeemember1"];
                                $getCommitteemember2 = $row["Committeemember2"];
                                $getCommitteemember3 = $row["Committeemember3"];
                                $getCommitteememberComment1 = $row["CommitteememberComment1"];
                                $getCommitteememberComment2 = $row["CommitteememberComment2"];
                                $getCommitteememberComment3 = $row["CommitteememberComment3"];
                                $getCommitteeC1 = $row["CommitteememberComment1"];
                                $getCommitteeC2 = $row["CommitteememberComment2"];
                                $getCommitteeC3 = $row["CommitteememberComment3"];
                                $runtime = 1;
                                /*
                                echo $tID." ".$sID." ".$getCommitteemember1." ".$getCommitteemember2." ".$getCommitteemember3." ".$getCommitteememberComment1." ".$getCommitteememberComment2." ".$getCommitteememberComment3."<br />";*/
                                
                            }
                            
                                $MainQuality = $row["MainQuality"];
                                $SubQuality = $row["SubQuality"];
                                $Value = $row["Value"]; 
                                switch($MainQuality){
                                     case 'A':
                                        switch($SubQuality){
                                           case 'A11':
                                           $A11 = $Value;
                                           case 'A12':
                                           $A12 = $Value;
                                           case 'A13':
                                           $A13 = $Value;
                                           case 'A14':
                                           $A14 = $Value;
                                           case 'A15':
                                           $A15 = $Value;
                                           case 'A21':
                                           $A21 = $Value;
                                           case 'A22':
                                           $A22 = $Value;
                                           case 'A23':
                                           $A23 = $Value;
                                           case 'A24':
                                           $A24 = $Value;
                                           case 'A25':
                                           $A25 = $Value;
                                           case 'A31':
                                           $A31 = $Value;
                                           case 'A32':
                                           $A32 = $Value;
                                           case 'A33':
                                           $A33 = $Value;
                                           case 'A34':
                                           $A34 = $Value;
                                           case 'A35':
                                           $A35 = $Value;
                                           default:
                                           break;
                                           }
                                      case 'B':
                                      switch($SubQuality){
                                           case 'B11':
                                           $B11 = $Value;
                                           case 'B12':
                                           $B12 = $Value;
                                           case 'B13':
                                           $B13 = $Value;
                                           case 'B14':
                                           $B14 = $Value;
                                           
                                           case 'B21':
                                           $B21 = $Value;
                                           case 'B22':
                                           $B22 = $Value;
                                           case 'B23':
                                           $B23 = $Value;
                                           case 'B24':
                                           $B24 = $Value;
                                           
                                           case 'B31':
                                           $B31 = $Value;
                                           case 'B32':
                                           $B32 = $Value;
                                           case 'B33':
                                           $B33 = $Value;
                                           case 'B34':
                                           $B34 = $Value;
          
                                           default:
                                           break;
                                           }
                                      case 'C':
                                      switch($SubQuality){
                                           case 'C11':
                                           $C11 = $Value;
                                           case 'C12':
                                           $C12 = $Value;
                                           case 'C13':
                                           $C13 = $Value;
                                           case 'C14':
                                           $C14 = $Value;
                                           
                                           case 'C21':
                                           $C21 = $Value;
                                           case 'C22':
                                           $C22 = $Value;
                                           case 'C23':
                                           $C23 = $Value;
                                           case 'C24':
                                           $C24 = $Value;
                                           
                                           case 'C31':
                                           $C31 = $Value;
                                           case 'C32':
                                           $C32 = $Value;
                                           case 'C33':
                                           $C33 = $Value;
                                           case 'C34':
                                           $C34 = $Value;
          
                                           default:
                                           break;
                                           }
                                      case 'D':
                                        switch($SubQuality){
                                           case 'D11':
                                           $D11 = $Value;
                                           case 'D21':
                                           $D21 = $Value;
                                           case 'D31':
                                           $D31 = $Value;
                                        }

                               }
    
                          }
                  }
                  
             }
       }
?>
<html>
<head>
</head>
<body>
  <h2>Update Page</h2>
  <form action = "QualifierforPHDUpdate.php" method = "POST">
    Student :<select name = 'student'>
      <?php
               foreach($studentArray as $info){
                 $pID = $info["PantherID"];
                 $fName = $info["FirstName"];
                 $mName = $info["MiddleName"];
                 $lName = $info["LastName"];
                 if($pID != $sID){
                 echo "<option value= '".$pID."'>";
                 echo "$fName $lName";
                 echo "</option>";

                 }else if($pID == $sID){
                 echo "<option value= '".$pID."'";
                 echo "selected".">";
                 echo "$fName $lName";
                 echo "</option>";
                 }
               }
      ?>
    </select>
    Term : <select name = 'term'>
           <?php
                  foreach($termArray as $t){
                       $term = $t["term"];
                       $termid = $t["termid"];
                       if($termid != $tid){
                           echo "<option value = '".$termid."''>";
                           echo "$term</option>";
                       }else{
                            echo "<option value = '".$termid."'";
                            echo "selected >"."$term</option>";

                       }
                  }
           ?>
           </select><br /><br />
        <Strong>The objective of the research examination is to assess the student’s potential to begin doctoral-level research.  Please assess the student’s ability of the following: (score 1 to 10 for each).</Strong>
  <hr />

  First committee member:<select name = 'committeeMember1'>
  <?php
           foreach($FacultyArray as $F){
               $Femail = $F["email"];
               $FFirst = $F["FirstName"];
               $FLast = $F["LastName"];
               if($Femail != $getCommitteemember1){
                  echo "<option value = '".$Femail."'>$FFirst $FLast</option>";
              }else{
                  echo "<option value = '".$Femail."' selected>$FFirst $FLast</option>";
              }
           }
  ?>
</select><br />
<p>A. Overall understanding (all 5 scores must be >= 6 to pass)<br />
    1. Research background (including motivation and basic techniques).
       <select name = "A11">
        <?php
                  for($i = 1; $i<=10; $i++){
                       if($i !=$A11){
                        echo "<option value = '".$i."'>$i</option>";
                       }else{
                          echo "<option value = '".$i."' selected>$i</option>";
                       }
                  }
        ?>
       </select><br />
      2. Clarity of problem formulation.
      <select name = "A12">
        <?php
                  for($i = 1; $i<=10; $i++){
                       if($i !=$A12){
                        echo "<option value = '".$i."'>$i</option>";
                       }else{
                          echo "<option value = '".$i."' selected>$i</option>";
                       }
                  }
        ?>
       </select><br />
      3. Understanding and explanation of validation (experimental or theoretical).
      <select name = "A13">
       <?php
                  for($i = 1; $i<=10; $i++){
                       if($i !=$A13){
                        echo "<option value = '".$i."'>$i</option>";
                       }else{
                          echo "<option value = '".$i."' selected>$i</option>";
                       }
                  }
        ?>
       </select><br />
      4. Understanding of committee questions.
      <select name = "A14">
       <?php
                  for($i = 1; $i<=10; $i++){
                       if($i !=$A14){
                        echo "<option value = '".$i."'>$i</option>";
                       }else{
                          echo "<option value = '".$i."' selected>$i</option>";
                       }
                  }
        ?>
       </select><br />
       5. Response to committee questions.
       <select name = "A15">
       <?php
                  for($i = 1; $i<=10; $i++){
                       if($i !=$A15){
                        echo "<option value = '".$i."'>$i</option>";
                       }else{
                          echo "<option value = '".$i."' selected>$i</option>";
                       }
                  }
        ?>
       </select><br />
     </p><br />
     <p>B. Review quality (at least 2 scores >= 6 to pass)<br />
      1. Remarks on related work.
      <select name = "B11">
       <?php
                  for($i = 1; $i<=10; $i++){
                       if($i !=$B11){
                        echo "<option value = '".$i."'>$i</option>";
                       }else{
                          echo "<option value = '".$i."' selected>$i</option>";
                       }
                  }
        ?>
       </select><br />
       2. Remarks on correctness  (proofs and experimental validation).
       <select name = "B12">
       <?php
                  for($i = 1; $i<=10; $i++){
                       if($i !=$B12){
                        echo "<option value = '".$i."'>$i</option>";
                       }else{
                          echo "<option value = '".$i."' selected>$i</option>";
                       }
                  }
        ?>
       </select><br />
       3. Improvements and future directions.
        <select name = "B13">
       <?php
                  for($i = 1; $i<=10; $i++){
                       if($i !=$B13){
                        echo "<option value = '".$i."'>$i</option>";
                       }else{
                          echo "<option value = '".$i."' selected>$i</option>";
                       }
                  }
        ?>
       </select><br />
       4. Overall written report quality.
       <select name = "B14">
       <?php
                  for($i = 1; $i<=10; $i++){
                       if($i !=$B14){
                        echo "<option value = '".$i."'>$i</option>";
                       }else{
                          echo "<option value = '".$i."' selected>$i</option>";
                       }
                  }
        ?>
       </select><br />
       </p><br />
       <p>C. Oral Presentation quality (at least 2 scores >= 6 to pass)<br />
       1. Slide quality.
       <select name = "C11">
       <?php
                  for($i = 1; $i<=10; $i++){
                       if($i !=$C11){
                        echo "<option value = '".$i."'>$i</option>";
                       }else{
                          echo "<option value = '".$i."' selected>$i</option>";
                       }
                  }
        ?>
       </select><br />
       2. Understandability (ability to convey concepts).
       <select name = "C12">
       <?php
                  for($i = 1; $i<=10; $i++){
                       if($i !=$C12){
                        echo "<option value = '".$i."'>$i</option>";
                       }else{
                          echo "<option value = '".$i."' selected>$i</option>";
                       }
                  }
        ?>
       </select><br />
       3. Speaking freely (not just reading the slides.)
       <select name = "C13">
       <?php
                  for($i = 1; $i<=10; $i++){
                       if($i !=$C13){
                        echo "<option value = '".$i."'>$i</option>";
                       }else{
                          echo "<option value = '".$i."' selected>$i</option>";
                       }
                  }
        ?>
       </select><br />
       4. Time allocation.
       <select name = "C14">
       <?php
                  for($i = 1; $i<=10; $i++){
                       if($i !=$C14){
                        echo "<option value = '".$i."'>$i</option>";
                       }else{
                          echo "<option value = '".$i."' selected>$i</option>";
                       }
                  }
        ?>
       </select><br />
       </p><br />
<p>D. Overall Score (pass / fail)
<select name = 'D11'>
     <?php
             if($D11 == 'pass'){
                    echo "<option value = 'pass' selected >pass</option>";
             }else{
              echo "<option value = 'fail' selected >fail</option>";
             }
     ?>
</select><br />
Comments: (Required for each score less than 6)<br />
       <textarea name = "committeeC1" rows = "8" cols = "40">
        <?php echo $getCommitteeC1;?>
       </textarea><br />
</p><br /><hr />


Second committee member:<select name = 'committeeMember2'>
  <?php
           foreach($FacultyArray as $F){
               $Femail = $F["email"];
               $FFirst = $F["FirstName"];
               $FLast = $F["LastName"];
               if($Femail != $getCommitteemember2){
                  echo "<option value = '".$Femail."'>$FFirst $FLast</option>";
              }else{
                  echo "<option value = '".$Femail."' selected>$FFirst $FLast</option>";
              }
           }
  ?>
</select><br />
<p>A. Overall understanding (all 5 scores must be >= 6 to pass)<br />
    1. Research background (including motivation and basic techniques).
       <select name = "A21">
        <?php
                  for($i = 1; $i<=10; $i++){
                       if($i !=$A21){
                        echo "<option value = '".$i."'>$i</option>";
                       }else{
                          echo "<option value = '".$i."' selected>$i</option>";
                       }
                  }
        ?>
       </select><br />
      2. Clarity of problem formulation.
      <select name = "A22">
        <?php
                  for($i = 1; $i<=10; $i++){
                       if($i !=$A22){
                        echo "<option value = '".$i."'>$i</option>";
                       }else{
                          echo "<option value = '".$i."' selected>$i</option>";
                       }
                  }
        ?>
       </select><br />
      3. Understanding and explanation of validation (experimental or theoretical).
      <select name = "A23">
       <?php
                  for($i = 1; $i<=10; $i++){
                       if($i !=$A23){
                        echo "<option value = '".$i."'>$i</option>";
                       }else{
                          echo "<option value = '".$i."' selected>$i</option>";
                       }
                  }
        ?>
       </select><br />
      4. Understanding of committee questions.
      <select name = "A24">
       <?php
                  for($i = 1; $i<=10; $i++){
                       if($i !=$A24){
                        echo "<option value = '".$i."'>$i</option>";
                       }else{
                          echo "<option value = '".$i."' selected>$i</option>";
                       }
                  }
        ?>
       </select><br />
       5. Response to committee questions.
       <select name = "A25">
       <?php
                  for($i = 1; $i<=10; $i++){
                       if($i !=$A25){
                        echo "<option value = '".$i."'>$i</option>";
                       }else{
                          echo "<option value = '".$i."' selected>$i</option>";
                       }
                  }
        ?>
       </select><br />
     </p><br />
     <p>B. Review quality (at least 2 scores >= 6 to pass)<br />
      1. Remarks on related work.
      <select name = "B21">
       <?php
                  for($i = 1; $i<=10; $i++){
                       if($i !=$B21){
                        echo "<option value = '".$i."'>$i</option>";
                       }else{
                          echo "<option value = '".$i."' selected>$i</option>";
                       }
                  }
        ?>
       </select><br />
       2. Remarks on correctness  (proofs and experimental validation).
       <select name = "B22">
       <?php
                  for($i = 1; $i<=10; $i++){
                       if($i !=$B22){
                        echo "<option value = '".$i."'>$i</option>";
                       }else{
                          echo "<option value = '".$i."' selected>$i</option>";
                       }
                  }
        ?>
       </select><br />
       3. Improvements and future directions.
        <select name = "B23">
       <?php
                  for($i = 1; $i<=10; $i++){
                       if($i !=$B23){
                        echo "<option value = '".$i."'>$i</option>";
                       }else{
                          echo "<option value = '".$i."' selected>$i</option>";
                       }
                  }
        ?>
       </select><br />
       4. Overall written report quality.
       <select name = "B24">
       <?php
                  for($i = 1; $i<=10; $i++){
                       if($i !=$B24){
                        echo "<option value = '".$i."'>$i</option>";
                       }else{
                          echo "<option value = '".$i."' selected>$i</option>";
                       }
                  }
        ?>
       </select><br />
       </p><br />
       <p>C. Oral Presentation quality (at least 2 scores >= 6 to pass)<br />
       1. Slide quality.
       <select name = "C21">
       <?php
                  for($i = 1; $i<=10; $i++){
                       if($i !=$C21){
                        echo "<option value = '".$i."'>$i</option>";
                       }else{
                          echo "<option value = '".$i."' selected>$i</option>";
                       }
                  }
        ?>
       </select><br />
       2. Understandability (ability to convey concepts).
       <select name = "C22">
       <?php
                  for($i = 1; $i<=10; $i++){
                       if($i !=$C22){
                        echo "<option value = '".$i."'>$i</option>";
                       }else{
                          echo "<option value = '".$i."' selected>$i</option>";
                       }
                  }
        ?>
       </select><br />
       3. Speaking freely (not just reading the slides.)
       <select name = "C23">
       <?php
                  for($i = 1; $i<=10; $i++){
                       if($i !=$C23){
                        echo "<option value = '".$i."'>$i</option>";
                       }else{
                          echo "<option value = '".$i."' selected>$i</option>";
                       }
                  }
        ?>
       </select><br />
       4. Time allocation.
       <select name = "C24">
       <?php
                  for($i = 1; $i<=10; $i++){
                       if($i !=$C24){
                        echo "<option value = '".$i."'>$i</option>";
                       }else{
                          echo "<option value = '".$i."' selected>$i</option>";
                       }
                  }
        ?>
       </select><br />
       </p><br />
<p>D. Overall Score (pass / fail)
<select name = 'D21'>
     <?php
             if($D21 == 'pass'){
                    echo "<option value = 'pass' selected >pass</option>";
             }else{
              echo "<option value = 'fail' selected >fail</option>";
             }
     ?>
</select><br />
Comments: (Required for each score less than 6)<br />
       <textarea name = "committeeC2" rows = "8" cols = "40">
        <?php echo $getCommitteeC2;?>
       </textarea><br />
</p><br /><hr />

Third committee member:<select name = 'committeeMember3'>
  <?php
           foreach($FacultyArray as $F){
               $Femail = $F["email"];
               $FFirst = $F["FirstName"];
               $FLast = $F["LastName"];
               if($Femail != $getCommitteemember3){
                  echo "<option value = '".$Femail."'>$FFirst $FLast</option>";
              }else{
                  echo "<option value = '".$Femail."' selected>$FFirst $FLast</option>";
              }
           }
  ?>
</select><br />
<p>A. Overall understanding (all 5 scores must be >= 6 to pass)<br />
    1. Research background (including motivation and basic techniques).
       <select name = "A31">
        <?php
                  for($i = 1; $i<=10; $i++){
                       if($i !=$A31){
                        echo "<option value = '".$i."'>$i</option>";
                       }else{
                          echo "<option value = '".$i."' selected>$i</option>";
                       }
                  }
        ?>
       </select><br />
      2. Clarity of problem formulation.
      <select name = "A32">
        <?php
                  for($i = 1; $i<=10; $i++){
                       if($i !=$A32){
                        echo "<option value = '".$i."'>$i</option>";
                       }else{
                          echo "<option value = '".$i."' selected>$i</option>";
                       }
                  }
        ?>
       </select><br />
      3. Understanding and explanation of validation (experimental or theoretical).
      <select name = "A33">
       <?php
                  for($i = 1; $i<=10; $i++){
                       if($i !=$A33){
                        echo "<option value = '".$i."'>$i</option>";
                       }else{
                          echo "<option value = '".$i."' selected>$i</option>";
                       }
                  }
        ?>
       </select><br />
      4. Understanding of committee questions.
      <select name = "A34">
       <?php
                  for($i = 1; $i<=10; $i++){
                       if($i !=$A34){
                        echo "<option value = '".$i."'>$i</option>";
                       }else{
                          echo "<option value = '".$i."' selected>$i</option>";
                       }
                  }
        ?>
       </select><br />
       5. Response to committee questions.
       <select name = "A35">
       <?php
                  for($i = 1; $i<=10; $i++){
                       if($i != $A35){
                        echo "<option value = '".$i."'>$i</option>";
                       }else{
                          echo "<option value = '".$i."' selected>$i</option>";
                       }
                  }
        ?>
       </select><br />
     </p><br />
     <p>B. Review quality (at least 2 scores >= 6 to pass)<br />
      1. Remarks on related work.
      <select name = "B31">
       <?php
                  for($i = 1; $i<=10; $i++){
                       if($i !=$B31){
                        echo "<option value = '".$i."'>$i</option>";
                       }else{
                          echo "<option value = '".$i."' selected>$i</option>";
                       }
                  }
        ?>
       </select><br />
       2. Remarks on correctness  (proofs and experimental validation).
       <select name = "B32">
       <?php
                  for($i = 1; $i<=10; $i++){
                       if($i !=$B32){
                        echo "<option value = '".$i."'>$i</option>";
                       }else{
                          echo "<option value = '".$i."' selected>$i</option>";
                       }
                  }
        ?>
       </select><br />
       3. Improvements and future directions.
        <select name = "B33">
       <?php
                  for($i = 1; $i<=10; $i++){
                       if($i !=$B33){
                        echo "<option value = '".$i."'>$i</option>";
                       }else{
                          echo "<option value = '".$i."' selected>$i</option>";
                       }
                  }
        ?>
       </select><br />
       4. Overall written report quality.
       <select name = "B34">
       <?php
                  for($i = 1; $i<=10; $i++){
                       if($i !=$B34){
                        echo "<option value = '".$i."'>$i</option>";
                       }else{
                          echo "<option value = '".$i."' selected>$i</option>";
                       }
                  }
        ?>
       </select><br />
       </p><br />
       <p>C. Oral Presentation quality (at least 2 scores >= 6 to pass)<br />
       1. Slide quality.
       <select name = "C31">
       <?php
                  for($i = 1; $i<=10; $i++){
                       if($i !=$C31){
                        echo "<option value = '".$i."'>$i</option>";
                       }else{
                          echo "<option value = '".$i."' selected>$i</option>";
                       }
                  }
        ?>
       </select><br />
       2. Understandability (ability to convey concepts).
       <select name = "C32">
       <?php
                  for($i = 1; $i<=10; $i++){
                       if($i !=$C32){
                        echo "<option value = '".$i."'>$i</option>";
                       }else{
                          echo "<option value = '".$i."' selected>$i</option>";
                       }
                  }
        ?>
       </select><br />
       3. Speaking freely (not just reading the slides.)
       <select name = "C33">
       <?php
                  for($i = 1; $i<=10; $i++){
                       if($i !=$C33){
                        echo "<option value = '".$i."'>$i</option>";
                       }else{
                          echo "<option value = '".$i."' selected>$i</option>";
                       }
                  }
        ?>
       </select><br />
       4. Time allocation.
       <select name = "C34">
       <?php
                  for($i = 1; $i<=10; $i++){
                       if($i !=$C34){
                        echo "<option value = '".$i."'>$i</option>";
                       }else{
                          echo "<option value = '".$i."' selected>$i</option>";
                       }
                  }
        ?>
       </select><br />
       </p><br />
<p>D. Overall Score (pass / fail)
<select name = 'D31'>
     <?php
             if($D31 == 'pass'){
                    echo "<option value = 'pass' selected >pass</option>";
             }else{
              echo "<option value = 'fail' selected >fail</option>";
             }
     ?>
</select><br />
Comments: (Required for each score less than 6)<br />
       <textarea name = "committeeC3" rows = "8" cols = "40">
        <?php echo $getCommitteeC3;?>
       </textarea><br />
</p><br /><hr />

<input type = 'submit' name = 'submit'>
</form>

</body>
</html>
<?php
     function UpdateE($db,$id, $main, $sub, $value, $comem){
          $sql =" UPDATE tbl_qualifier_extra SET Value = '$value' WHERE QualifierID = $id AND MainQuality = '$main' AND SubQuality = '$sub' AND Committeemember = '$comem';
                ";
                //echo $sql;
                $db->query($sql);
       }
       function UpdateM($db,$com1,$com2,$com3,$comC1, $comC2, $comC3, $stu, $te){
       $sql = "
          UPDATE tbl_qualifier SET Committeemember1 ='$com1', Committeemember2 ='$com2', Committeemember3 ='$com3', CommitteememberComment1 ='$comC1',  CommitteememberComment2 ='$comC2', CommitteememberComment3 ='$comC3', TermID ='$te' WHERE StudentID ='$stu'
          ";
       //echo $sql;
       $db->query($sql);
}
?>
<?php
         $id =  $_SESSION['id'];
         if(!empty($_POST["submit"])){
                  $committeeMem1 = $_POST['committeeMember1'];
                  $committeeMem2 = $_POST['committeeMember2'];
                  $committeeMem3 = $_POST['committeeMember3'];
                  $committeeC1 = $_POST["committeeC1"];
                  $committeeC2 = $_POST["committeeC2"];
                  $committeeC3 = $_POST["committeeC3"];
                  $term = $_POST["term"];
                  $sID = $_POST["student"];

                  $A11 = $_POST['A11'];
                  $A12 = $_POST['A12'];
                  $A13 = $_POST['A13'];
                  $A14 = $_POST['A14'];
                  $A15 = $_POST['A15'];
                  $A21 = $_POST['A21'];
                  $A22 = $_POST['A22'];
                  $A23 = $_POST['A23'];
                  $A24 = $_POST['A24'];
                  $A25 = $_POST['A25'];
                  $A31 = $_POST['A31'];
                  $A32 = $_POST['A32'];
                  $A33 = $_POST['A33'];
                  $A34 = $_POST['A34'];
                  $A35 = $_POST['A35'];

                  $B11 = $_POST["B11"];
                  $B12 = $_POST["B12"];
                  $B13 = $_POST["B13"];
                  $B14 = $_POST["B14"];
                  $B21 = $_POST["B21"];
                  $B22 = $_POST["B22"];
                  $B23 = $_POST["B23"];
                  $B24 = $_POST["B24"];
                  $B31 = $_POST["B31"];
                  $B32 = $_POST["B32"];
                  $B33 = $_POST["B33"];
                  $B34 = $_POST["B34"];

                  $C11 = $_POST["C11"];
                  $C12 = $_POST["C12"];
                  $C13 = $_POST["C13"];
                  $C14 = $_POST["C14"];
                  $C21 = $_POST["C21"];
                  $C22 = $_POST["C22"];
                  $C23 = $_POST["C23"];
                  $C24 = $_POST["C24"];
                  $C31 = $_POST["C31"];
                  $C32 = $_POST["C32"];
                  $C33 = $_POST["C33"];
                  $C34 = $_POST["C34"];

                  $D11 = $_POST["D11"];
                  $D21 = $_POST["D21"];
                  $D31 = $_POST["D31"];

                  UpdateM($db, $committeeMem1, $committeeMem2, $committeeMem3, $committeeC1, $committeeC2, $committeeC3, $sID, $term);


                  UpdateE($db, $id, 'A', 'A11', $A11, $committeeMem1);
                  UpdateE($db, $id, 'A', 'A12', $A12, $committeeMem1);
                  UpdateE($db, $id, 'A', 'A13', $A13, $committeeMem1);
                  UpdateE($db, $id, 'A', 'A14', $A14, $committeeMem1);
                  UpdateE($db, $id, 'A', 'A15', $A15, $committeeMem1);
                  UpdateE($db, $id, 'A', 'A21', $A21, $committeeMem2);
                  UpdateE($db, $id, 'A', 'A22', $A22, $committeeMem2);
                  UpdateE($db, $id, 'A', 'A23', $A23, $committeeMem2);
                  UpdateE($db, $id, 'A', 'A24', $A24, $committeeMem2);
                  UpdateE($db, $id, 'A', 'A25', $A25, $committeeMem2);
                  UpdateE($db, $id, 'A', 'A31', $A31, $committeeMem3);
                  UpdateE($db, $id, 'A', 'A32', $A32, $committeeMem3);
                  UpdateE($db, $id, 'A', 'A33', $A33, $committeeMem3);
                  UpdateE($db, $id, 'A', 'A34', $A34, $committeeMem3);
                  UpdateE($db, $id, 'A', 'A35', $A35, $committeeMem3); 

                  UpdateE($db, $id, 'B', 'B11', $B11, $committeeMem1);
                  UpdateE($db, $id, 'B', 'B12', $B12, $committeeMem1);
                  UpdateE($db, $id, 'B', 'B13', $B13, $committeeMem1);
                  UpdateE($db, $id, 'B', 'B14', $B14, $committeeMem1);
                  UpdateE($db, $id, 'B', 'B21', $B21, $committeeMem2);
                  UpdateE($db, $id, 'B', 'B22', $B22, $committeeMem2);
                  UpdateE($db, $id, 'B', 'B23', $B23, $committeeMem2);
                  UpdateE($db, $id, 'B', 'B24', $B24, $committeeMem2);
                  UpdateE($db, $id, 'B', 'B31', $B31, $committeeMem3);
                  UpdateE($db, $id, 'B', 'B32', $B32, $committeeMem3);
                  UpdateE($db, $id, 'B', 'B33', $B33, $committeeMem3);
                  UpdateE($db, $id, 'B', 'B34', $B34, $committeeMem3);

                  UpdateE($db, $id, 'C', 'C11', $C11, $committeeMem1);
                  UpdateE($db, $id, 'C', 'C12', $C12, $committeeMem1);
                  UpdateE($db, $id, 'C', 'C13', $C13, $committeeMem1);
                  UpdateE($db, $id, 'C', 'C14', $C14, $committeeMem1);
                  UpdateE($db, $id, 'C', 'C21', $C21, $committeeMem2);
                  UpdateE($db, $id, 'C', 'C22', $C22, $committeeMem2);
                  UpdateE($db, $id, 'C', 'C23', $C23, $committeeMem2);
                  UpdateE($db, $id, 'C', 'C24', $C24, $committeeMem2);
                  UpdateE($db, $id, 'C', 'C31', $C31, $committeeMem3);
                  UpdateE($db, $id, 'C', 'C32', $C32, $committeeMem3);
                  UpdateE($db, $id, 'C', 'C33', $C33, $committeeMem3);
                  UpdateE($db, $id, 'C', 'C34', $C34, $committeeMem3);

                  UpdateE($db, $id, 'D', 'D11', $D11, $committeeMem1);
                  UpdateE($db, $id, 'D', 'D21', $D21, $committeeMem2);
                  UpdateE($db, $id, 'D', 'D31', $D31, $committeeMem3);
                /*
                 $url = "Update_Qualifier.php";
                 header("Location:  $url");
                */

             header("location:QualifierforPHDviewdashboard.php");  //redirect home page






         }
?>