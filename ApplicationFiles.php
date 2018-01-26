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
?>
<html lang="en">
	<!-- Header -->
		<?php 
		include $root.'/links/header.php';
		?>
	<!-- Header -->
<body>

    <div id="wrapper">

        <!-- Navigation -->
         <?php 
        include $root.'/UI/staff/staffmenu.php';
        ?>
        <!-- Navigation -->

		<!-- page-wrapper -->
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Student Application Files</h1>
                </div>
            </div>
       <div id="studentInfoDiv" class="row">
<div class="row"> 
	<div class="form-group" style="margin-left: 25px;margin-bottom: 10px;"> 
		<form enctype="multipart/form-data">
			<label>Select Application_ApplicantNumber.pdf File</label>
			<input type="file" name="files[]" id="fileToUpload"  multiple="multiple" accept="application/pdf"/><br/>
			<div id="uploadMsgDiv" class="form-group has-success">
				<label class="control-label" id="fileUploadMsg" for="inputError">Please upload the file</label>
			</div>
			<input  id="uploadbtn" name="uploadbtn" value="Upload pdf" class="btn btn-primary" type="button">
		</form>
	</div>
<div> 
       		<!-- #Student Application Information -->
					<?php
include($root.'/osms.dbconfig.inc');

$error_message = "";
$counter = 0;

$mysqli = new mysqli($hostname,$username, $password,$dbname);

/* check connection */
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}

function filesize_formatted($path)
{
	$size = filesize($path);
	$units = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
	$power = $size > 0 ? floor(log($size, 1024)) : 0;
	return number_format($size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];
}

function camelCase($str, array $noStrip = [])
{
	// non-alpha and non-numeric characters become spaces
	$str = preg_replace('/[^a-z0-9' . implode("", $noStrip) . ']+/i', ' ', $str);
	$str = trim($str);
	// uppercase the first character of each word
	$str = ucwords($str);
	$str = str_replace(" ", "", $str);
// 	$str = lcfirst($str);

	return $str;
}

// $sqlF = "SELECT * FROM tbl_excel_info  where ApplicantID is not null";
$sqlF = "SELECT * FROM tbl_excel_info ";

// $sql2 = "SELECT StudentId FROM tbl_student_evaluation WHERE Status='Pending'";
// $result1 = $mysqli->query($sql1);
// $result2 = $mysqli->query($sql2);
//$sql = "SELECT * FROM tbl_faculty_info LEFT OUTER JOIN tbl_student_evaluation ON tbl_student_evaluation.FacultyId = tbl_faculty_info.email WHERE tbl_faculty_info.email='".$user_email."'";
$resultF = $mysqli->query($sqlF);

$html="";
$html.='<span id="response"></span>';
$html.='<a href="#" style="margin-right: 25px;float: right;margin-bottom: 10px;" class="btn btn-primary" id="reNameBtn">Rename</a>';
$html.='<div class="row">';
$html.='<div class="col-lg-12">';
$html.='<div class="panel panel-default">';
$html.='<div class="panel-heading">';
$html.='All Applications';
$html.='</div>';
$html.='<div class="panel-body"  style="overflow:auto">';
$html.='<table width="100%" class="table table-striped table-bordered table-hover" id="studentTables-Applications">';
$html.="<thead>";
$html.="<tr>";
$html .= '<th>ApplicantID/PantherID</th>';
$html .= '<th>Applicant Name</th>';
//$html .= '<th>LastName</th>';
$html .= '<th>Files Name</th>';
$html .= '<th>Files Size</th>';
$html .= '<th>File Access</th>';
$html.="</tr>";
$html.="</thead>";
$html.="<tbody>";

$dir = $root."/Applications/";
if($resultF-> num_rows > 0)
{
	while($rowF = $resultF->fetch_assoc())
	{
		$PantherId = $rowF["PantherId"];
		if($PantherId==0)
			continue;
		$FirstName=$rowF["FirstName"];
		$LastName=$rowF["LastName"];
		
		$ApplicationID=$rowF["ApplicantID"];
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
				$fileSize = filesize_formatted($filenamelocation);
			}
			else 
			{ 
				$filenamelocation = null;
				$FullFileName ='./../../Applications/'.$FileName;
				$fileSize = filesize_formatted($FullFileName);
				$filename = basename($FullFileName);
			}
		}
		else
		{ 
			$filenamelocation = null;
			$FullFileName ='./../../Applications/'.$FileName;
			$fileSize = filesize_formatted($FullFileName);
			$filename = basename($FullFileName);
		} 
		
	
	$html.="<tr class='odd gradeX'>";
	$html .= '<td>'.((is_numeric($ApplicationID))?$ApplicationID:$PantherId).'</td>';

	if(file_exists($filenamelocation)){	
		
		$html .= '<td><a href="'.$filenamelocation.'" target="_blank">'.$FirstName.' '.$LastName.'</td>';
	}else if(file_exists($FullFileName)){
		$html .= '<td><a href="'.$FullFileName.'" target="_blank">'.$FirstName.' '.$LastName.'</a></td>';
	}
	else {
		$html .= '<td>'.$FirstName.' '.$LastName.'</td>';
		$filename = null;
		$fileSize = null;
		$filenamelocation = null;
		$FullFileName = null;
	}
	
	
	$html .= '<td>'.$filename.'</td>';
	$html .= '<td>'.$fileSize.'</td>';
	if (file_exists($FullFileName)) {
		$html .= '<td>Accessible</a></td>';
		}
		else if (file_exists($filenamelocation)) {
		$html .= '<td>Accessible</a></td>';
		}
	else {	
		$html .= '<td>Not Accessible</td>';
	}
	
	$html .= "</tr>";
	}
}

$html.='</tbody>';
$html.='</table>';
$html.='</div>';
$html.='</div>';
$html.='</div>';
$html.='</div>';


echo $html;

?>   		
       		<!-- /#Master Student Information -->
       </div>
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

<!-- FooterLinks -->
<?php 
include $root.'/links/footerLinks.php';
?>
<!-- /#FooterLinks -->
    

 <script>
    $(document).ready(function() {
        $('#studentTables-Applications').DataTable({
            responsive: true
        });
    });


    $(function () {
        $('#reNameBtn').on('click', function (e) {
        $.ajax({
            type: 'post',
            url: './RenameApplications.php',
//             data: { StudentId:checkbox_value, FacultyId: selvalue,Batch:true},
            success: function (php_script_response) {
              var responseText = php_script_response;
    		  if (/Successfully/i.test(responseText))
              {	  
    			  $("#facselect").attr("style", "visibility: hidden");
    			$("#response").attr("Style", "color: green;");
    			$('#response').html(responseText);
              }
              else
              {
    			$("#response").attr("class", "color: red;");
    			$('#response').html(responseText);
              }
            }    
          });
        });
      });

    
    $('#uploadbtn').on('click', function() {   
     	 var form_data = new FormData();
         var ins = document.getElementById('fileToUpload').files.length;
         for (var x = 0; x < ins; x++) {
             form_data.append("files[]", document.getElementById('fileToUpload').files[x]);
         }
    	if(form_data != null)
    	{
//         var form_data = new FormData();                  
//         form_data.append('file', file_data);
//        alert(file_data);                            
        $.ajax({
                    url: './uploadApplicaitons.php', // point to server-side PHP script 
                    dataType: 'text',  // what to expect back from the PHP script, if anything
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: form_data,                         
                    type: 'post',
                    success: function(php_script_response){
                        var responcetext = php_script_response;
                        if (/has been uploaded/i.test(responcetext))
                        {
                        	$("#fileUploadMsg").html(responcetext); 
                        	 $("#uploadMsgDiv").attr("class", "form-group has-success");
                        	$('#fileToUpload').val("");   
//                         	 var $container = $("#studentInfoDiv");
//                              $container.load("FacultyExcel.php");
                     						
                        }
                        else
                        {
                        $("#uploadMsgDiv").attr("class", "form-group has-error");
                        $("#fileUploadMsg").html(responcetext);
                        $('#fileToUpload').val("");   
                        }
//                         alert(php_script_response); // display response from the PHP script, if any
                    }
         });
    	}
    	else
    	{
    		$("#uploadMsgDiv").attr("class", "form-group has-error");
    		$("#fileUploadMsg").html("Please Browse Application_ApplicantNumber.pdf files");
    		}
    });
    
    </script>
    <script src="../../vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="../../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
    <script src="../../vendor/datatables-responsive/dataTables.responsive.js"></script>
</body>

</html>