<?php
if (isset($_SERVER['HTTP_HOST'])) {
    if ($_SERVER['HTTP_HOST'] == "localhost") {
        $root =  realpath($_SERVER["CONTEXT_DOCUMENT_ROOT"]);
        define("ROOT", $root."/student/ogms/public_html");
        $root = ROOT;
    } else {
        $root =  realpath($_SERVER["CONTEXT_DOCUMENT_ROOT"]);
    }
} else {
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
                    <h1 class="page-header">Add/Remove/Update Faculty Information</h1>
                </div>
								<div class="row">
        <div class="col-md-12 ">
            <div class=" btn-toolbar  pull-right">

		<div id ="facselect" class= "btn-group" style="visibility: hidden;">
			<button id ="DeleteMultipleFaculty" class="btn btn-danger">Delete them</button>
		</div>

                <button class="btn btn-success" data-toggle="modal" data-target="#add_new_record_modal">Add New Faculty</button>

            </div>
        </div>
    </div>
		<br />
                <div>

                </div>
            </div>
       <div id="studentInfoDiv" class="row">
<?php
include "FacultyExcel.php";
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
            responsive: true,
	     searching: true,
             paging: true
        });
    });
    </script>
    <script src="../../vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="../../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
    <script src="../../vendor/datatables-responsive/dataTables.responsive.js"></script>
		<script src="faculty_modal.js"></script>

		<?php
		include "faculty_modal.html";
		 ?>


   <script>
    $(":checkbox").on('click', function () {
        var checkbox_value = "";
        var count = 0;
        $(":checkbox").each(function () {
            var ischecked = $(this).is(":checked");
            if (ischecked) {
                count += 1;

                checkbox_value += $(this).val() + "|";
            }
        });
        if(count)
        {$("#facselect").attr("style", "visibility: visible");}
        else
        {$("#facselect").attr("style", "visibility: hidden");}
        console.log(count);

    });



    $(function () {
      $('#DeleteMultipleFaculty').on('click', function (e) {
        var count = 0;
        var checkbox_value = "";
        $(":checkbox").each(function () {
              var ischecked = $(this).is(":checked");
              if (ischecked) {
                  count += 1;

                  checkbox_value += $(this).val() + ",";
              }
          });
        console.log(checkbox_value);

        var conf = confirm("Are you sure, do you really want to delete faculties? ");
        if (conf == true) {

        $.ajax({
          type: 'post',
          url: './DeleteMultipleFaculty.php',
          data: { StudentId:checkbox_value},
          success: function (php_script_response) {
            var responseText = php_script_response;
            console.log(responseText);
          $("#facselect").attr("style", "visibility: hidden");
          readRecords();
              window.location.reload();
            }

          }
        );
 }




      });
    });

</script>



</body>

</html>
