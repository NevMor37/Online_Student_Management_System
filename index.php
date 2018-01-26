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

if($_SESSION['user']['role'] == "Student"){
	header('Location: ./../student/index.php');
}
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
                <h4>Welcome <?php echo $user_name?></h4>
                    <h1 class="page-header">Dashboard</h1>
					<span id="response"></span>
<?php 
include 'AdminDashboard.php';
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
	            responsive: true,
                iDisplayLength : 50,
	        });
	        $('#studentTables-info').DataTable({
	            responsive: true,
                iDisplayLength : 50,
	        });
	        $('#studentTables-AdvisorPending').DataTable({
	            responsive: true,
                iDisplayLength : 50,
	        });

             $('#studentTables-pendingadmitreject').DataTable({
                 responsive: true,
                 iDisplayLength : 50,
             });
             $('#studentTables-PendingatFaculty').DataTable({
                 responsive: true,
                 iDisplayLength : 50,
             });
	    });


    $('#option_submit').click(function(){
        //var status=$('option:selected',this.termid).text();
        var termid= $('#termid').find('option:selected').attr('value');
        var name ='';
        var value='';
        name = 'admintermid';
        value = termid;
        var termidreturn = changesession(name,value);
        window.location.reload();

    });

        function  changesession(name,value)
        {
            var returnvalue =0;
            $.ajax({
                url:"changesession.php", //the page containing php script
                type: "POST", //request type
                async: false,
                data:{name:name,value:value},
                //data:{id:m_id},
                success:function(data)
                {

                    if(data =='success')
                    {
                        returnvalue=1;
                    }
                    else
                    {
                        returnvalue= 0;
                    }
                    //alert(data);
                }
            });
            //alert('name:'+name+'value:'+value+'returnvalue:'+returnvalue);
            return returnvalue;
        }
	    
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

function remindFaculty(id) {
	 $.ajax({
         type: 'post',
         url: './RemindFaculty.php',
         data: { StudentId:id},
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
    
    }
</script>



</html>
