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
            <div class="row">
                <div class="col-lg-12">
              
                    <h1 class="page-header">Check-In</h1>
					<span id="response"></span>
<?php 
// include 'AdminDashboard.php';
include 'CheckInTable.php';
?>
                </div>
                <!-- /.col-lg-12 -->
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
</body>
 <script src="../../vendor/datatables/js/jquery.dataTables.min.js"></script>
  <script src="../../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
    <script src="../../vendor/datatables-responsive/dataTables.responsive.js"></script>
<script type="text/javascript">
 
$( document ).ready(function() {
	 $(document).ready(function() {
	        $('#studentTables-Completed').DataTable({
	            responsive: true
	        });
	        $('#studentTables-info').DataTable({
	            responsive: true
	        });
	        $('#studentTables-AdvisorPending').DataTable({
	            responsive: true
	        });
         $('#studentTables-AdvisorCheckIn').DataTable({
             responsive: true
         });
	        
	    });
	    
//         $.ajax({
//         type: 'post',
        
//         url: './AdminDashboard.php',
//         success: function (php_script_response) {	
//           var responsetext = php_script_response;
//           if (/Dashboard Shown/i.test(responsetext))
//           {
//            $("#response").attr("Style", "color: green;");
//            $('#response').html(responsetext);

//            $('#studentTables-info').DataTable({
//    	        responsive: true
//    	    });
//            $('#studentTables-info1').DataTable({
//       	        responsive: true
//       	    });
      	    
//           }
//           else
//           {
//           $("#response").attr("class", "color: red;");
//           $('#response').html(responsetext);
//           }
//         } 
//       });
});

function addRecord(id,sName) {
	 var conf = confirm("Are you sure, do you really want to Check-In "+sName+"?");
	    if (conf == true) {
	 $.ajax({
         type: 'post',
         url: './CheckInStudent.php',
         data: { StudentId:id , StudentName:sName},
         success: function (php_script_response) {
           var responseText = php_script_response;
 		  if (/Successfully/i.test(responseText))
 		  {
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
            window.location.reload();
	    }
    }

function deleteRecord(id,sName) {
	 var conf = confirm("Are you sure, do you really want to delete "+sName+"?");
	    if (conf == true) {
	        alert('true');
	 $.ajax({
        type: 'post',
        url: './CheckInDelete.php',
        data: { StudentId:id , StudentName:sName},
        success: function (php_script_response) {
          var responseText = php_script_response;
		  if (/Successfully/i.test(responseText))
		  {
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
            window.location.reload();
	    }
   }

</script>
   
</html>
