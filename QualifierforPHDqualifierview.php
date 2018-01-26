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
    //include $root.'/authenticate.php';
    include($root.'/osms.dbconfig.inc');
    $error_message = "";
    $counter = 0;

    $mysqli = new mysqli($hostname,$username, $password,$dbname);

    /* check connection */
    if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }
?>
<?php
//connect to database
$db=$mysqli;

?>
<?php
    if(isset($_SESSION['message']))
    {
         echo "<div id='error_msg'>".$_SESSION['message']."</div>";
         unset($_SESSION['message']);
    }
?>

<div>
    <td><a href="QualifierforPHDInsert.php?" >Insert</a></td>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body"  style="overflow:auto">
                <table width="100%" class="table table-striped table-bordered table-hover" id="qualifier-view">
                    <thead>
                    <!-- Head -->
                        <tr>
                            <th>QualifierID</th><th>TermID</th><th>Committeeemember1</th><th>Committeemember2</th><th>Committeemember3</th><th>Update</th><th>Remove</th>
                        </tr>
                    </thead>
                    <tbody>
                    	 <?php

                        $sql = "SELECT QualifierID as qid,
                        		TermID as tid,
                        		Committeemember1 as c1,
                        		Committeemember2 as c2,
                        		Committeemember3 as c3,
                        		Committeemember4 as c4,
                        		Committeemember5 as c5                               
                                FROM tbl_qualifier
                                 ";

                        $result = mysqli_query($db, $sql);

                        while($row=mysqli_fetch_assoc($result))
                        {
                            echo '<tr><td>' . $row["qid"] .
                            	'</td><td>' . $row["tid"] .
                            	'</td><td>' . $row["c1"] .
                            	'</td><td>' . $row["c2"] .
                            	'</td><td>' . $row["c3"] .
                                '</td><td><a href="QualifierforPHDUpdate.php?id= ' . $row["id"] . '" >Update</a></td><td><a href="QualifierforPHDdelete.php?id=' . $row["id"] . ' "\">Remove</a></td></tr>';
                        }

                        ?>