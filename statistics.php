 <?php
$servername = "localhost";
$username = "ogms";
$password = "ogmsadm";
$dbnam="ogms";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT avg(GREQuantScore) FROM tbl_student_evaluation ";
$result = $conn->query($sql);





$conn->close();
?> 

<?php
$con = mysql_connect("localhost", "ogms", "ogmsadm");
$selectdb = mysql_select_db("ogms",$con);
$result = mysql_query("select * from tbl_excel_info");
$number_of_rows = mysql_num_rows($result);

?>


<?php

$sql    = 'SELECT AVG(GREQuantScore) AS avgColName FROM tbl_excel_info;';
$query  = mysql_query($sql);
$result = mysql_fetch_assoc($query);

$avgq=round($result['avgColName'],2);

?> 


<?php

$sql    = 'SELECT AVG(GREVerbalScore) AS avgColName FROM tbl_excel_info;';
$query  = mysql_query($sql);
$result = mysql_fetch_assoc($query);


$avgv=round($result['avgColName'],2);

?> 



 <?php

$sql    = 'SELECT AVG(GREAnalyticalScore) AS avgColName FROM tbl_excel_info;';
$query  = mysql_query($sql);
$result = mysql_fetch_assoc($query);


$avga=round($result['avgColName'],2);

?> 



<?php
$con = mysql_connect("localhost", "ogms", "ogmsadm");
$selectdb = mysql_select_db("ogms",$con);
$result = mysql_query("select * from tbl_student_evaluation");
$number_of_rows = mysql_num_rows($result);
$eval=$number_of_rows;

?>
 
 
 
<?php
$con = mysql_connect("localhost", "ogms", "ogmsadm");
$selectdb = mysql_select_db("ogms",$con);
$result = mysql_query("select * from tbl_excel_info");
$number_of_rows = mysql_num_rows($result);
$noapp=$number_of_rows;

?>
<?php

$sql    = 'SELECT AVG(TOEFLTotal) AS avgColName FROM tbl_excel_info;';
$query  = mysql_query($sql);
$result = mysql_fetch_assoc($query);


$avgtfl=round($result['avgColName'],2);

?> 

<?php

$sql    = 'SELECT count(1) AS avgColName FROM tbl_student_evaluation where Status="complete";';
$query  = mysql_query($sql);
$result = mysql_fetch_assoc($query);


$complete=round($result['avgColName'],2);

?> 



<?php

$sql    = 'SELECT count(1) AS avgColName FROM tbl_student_evaluation where Status="pending";';
$query  = mysql_query($sql);
$result = mysql_fetch_assoc($query);


$incomplete=round($result['avgColName'],2);

?> 
<?php

$sql    = 'SELECT count(1) AS avgColName FROM tbl_student_evaluation where AdmissionDecision="admit";';
$query  = mysql_query($sql);
$result = mysql_fetch_assoc($query);

$admit=round($result['avgColName'],2);

?> 


<?php

$sql    = 'SELECT count(1) AS avgColName FROM tbl_student_evaluation where AdmissionDecision="reject";';
$query  = mysql_query($sql);
$result = mysql_fetch_assoc($query);


$reject=round($result['avgColName'],2);

?> 




<?php

$sql    = 'SELECT AVG(UgGPAOVerall) AS avgColName FROM tbl_excel_info;';
$query  = mysql_query($sql);
$result = mysql_fetch_assoc($query);


$avgt=round($result['avgColName'],2);

$uggpa=round($result['avgColName'],2);
?>
<?php

$sql    = 'SELECT AVG(GraduateGPA) AS avgColName FROM tbl_excel_info;';
$query  = mysql_query($sql);
$result = mysql_fetch_assoc($query);


$pggpa=round($result['avgColName'],2);


?>
<?php

$sql    = 'SELECT AVG(GRETotal) AS avgColName FROM tbl_excel_info;';
$query  = mysql_query($sql);
$result = mysql_fetch_assoc($query);


$gret=round($result['avgColName'],2);


?>

<!DOCTYPE html>
<html>
<body>

<br>
<br>



&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;





<button onclick="myFunction()">Reload page</button>

<script>
function myFunction() {
    location.reload();
}
</script>

</body>
</html> 


<!DOCTYPE html>
<html lang="en">
<head>
  <title>Student Statistics</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
  <h2 align=left>STUDENT STATISTICS</h2>
  <p><h5 align=left>This table automatically updates as per database changes.</h4></p>            
  <table  class="table" border=4   style="float:left">
    <thead>
      <tr>
        <th>Data Type</th>
        <th>Value</th>
    
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>No. of Applicants</td>
        <td><?php echo $noapp; ?></td>
       
      </tr>
       <tr>
        <td>No. of Applications under Evalution</td>
        <td><?php echo $eval; ?></td>
       
      </tr>
<tr>
        <td>Average Quant Score</td>
        <td><?php echo $avgq; ?></td>
       
      </tr>
      <br>
       <tr>
        <td>Average Verbal Score</td>
        <td><?php echo $avgv; ?></td>
       
      </tr>
      
       <tr>
        <td>Average Analtyical Score</td>
        <td><?php echo $avga; ?></td>
       
      </tr>
<tr>
        <td>Average GRE Score</td>
        <td><?php echo $gret; ?></td>
       
      </tr>
<tr>
        <td>Average TOEFL Score</td>
        <td><?php echo $avgtfl; ?></td>
       
      </tr>
       <tr>
        <td>Average UG GPA</td>
        <td><?php echo $uggpa; ?></td>
       
      </tr>
       <tr>
        <td>Average PG GPA</td>
        <td><?php echo $pggpa; ?></td>
       
      </tr>
      
       <tr>
        <td>No. of Rejected Students</td>
        <td><?php echo $reject; ?></td>
       
      </tr>
       <tr>
        <td>No. of Completed Applications</td>
        <td><?php echo $complete; ?></td>
       
      </tr>
       <tr>
        <td>No. of Pending Applications</td>
        <td><?php echo $incomplete; ?></td>
       
      </tr>
       <tr>
        <td>No. of Accepted Applications</td>
        <td><?php echo $admit; ?></td>
       
      </tr>
     

