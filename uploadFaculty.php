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
include $root.'/authenticate.php';
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
                    <h1 class="page-header">Faculty Info Upload</h1>
                </div>
                <div>
                	<div class="form-group">
<!--                 		<form action="./../../fileupload.php" method="post" enctype="multipart/form-data"> -->
                                            <form enctype="multipart/form-data">
                                            <label>Select Faculty_data.xls File</label>
                                            <input type="file" name="fileToUpload" id="fileToUpload"></br>
                                            <div id="uploadMsgDiv" class="form-group has-success">
                                            <label class="control-label" id="fileUploadMsg" for="inputError">Please upload the file</label>
                                        </div>
                                            <input  id="uploadbtn" name="uploadbtn" value="Upload xls" class="btn btn-primary" type="button">
                		</form>
                	
                	</div>
                </div>
            </div>
       <div id="studentInfoDiv" class="row">
            <?php
       include 'FacultyExcel_noButton.php';
       ?>
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
    
<script type="text/javascript">
  
$('#uploadbtn').on('click', function() {   
    var file_data = $('#fileToUpload').prop('files')[0];   
	if(file_data != null)
	{
    var form_data = new FormData();                  
    form_data.append('file', file_data);
   // alert(file_data);                            
    $.ajax({
                url: './../../fileupload.php?q=faculty', // point to server-side PHP script 
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
                    	 var $container = $("#studentInfoDiv");
                         $container.load("FacultyExcel.php");
                 						
                    }
                    else
                    {
                        $("#uploadMsgDiv").attr("class", "form-group has-error");
                    $("#fileUploadMsg").html(php_script_response);
                    }
                    // alert(php_script_response); // display response from the PHP script, if any
                }
     });
	}
	else
	{
		$("#uploadMsgDiv").attr("class", "form-group has-error");
		$("#fileUploadMsg").html("Please Browse Faculty_data.xls file");
		}
});

</script>
<script>
    $(document).ready(function() {
        $('#facultyTables-info').DataTable({
            responsive: true
        });
    });
    </script>
    <script src="../../vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="../../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
    <script src="../../vendor/datatables-responsive/dataTables.responsive.js"></script>
</body>

</html>
