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

echo '$hostname:'.$hostname .'$username:'.$username .'$password:'.$password .'$dbname:'.$dbname;
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

function getcurpantherid($db,$extraid)
{
    $returnid = -1;
    $sql = "select PantherID from  tbl_taassignment_extra 
           where TAAssignmentExtraID = '$extraid'";
    echo $sql .'<br>';
    $result = mysqli_query($db,$sql);
    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            $returnid = $row["PantherID"];
        }
    }

    return $returnid;
}

function updateapplicationstatus($db,$termid,$pantherID,$status)
{
    $sql = "update tbl_gaapplication 
                        set Status='$status'
                        where PantherID ='$pantherID' and TermID ='$termid' ";

    echo $sql .'<br>';

    $result = mysqli_query($db,$sql);
    if (!$result)
    {
        echo("Error description: " . mysqli_error($db));
    }
    //echo "PantherID".$pantherID." status  update: ";
}

if(isset($_POST['register_btn']))
{
    //$username=($_POST['firstName']);
    echo $_SESSION["status"];
    $termid=($_POST['termid']);
    //echo '$termid:'.$termid;
    $course=($_POST['course']);
    $tanumber=($_POST['tanumber']);
    $ganumber=($_POST['ganumber']);
    $lanumber=($_POST['lanumber']);

//    $taassignment1=($_POST['taassignment1']);
//    $taassignment2=($_POST['taassignment2']);
//    $taassignment3=($_POST['taassignment3']);
//    $gaassignment1=($_POST['gaassignment1']);
//    $gaassignment2=($_POST['gaassignment2']);
//    $gaassignment3=($_POST['gaassignment3']);
//    $laassignment1=($_POST['laassignment1']);
//    $laassignment2=($_POST['laassignment2']);
//    $laassignment3=($_POST['laassignment3']);
    //echo 'tanametext1:'.$_POST['tanametext1'];
    $taassignment1=($_POST['tanametext1']);
    $taassignment2=($_POST['tanametext2']);
    $taassignment3=($_POST['tanametext3']);
    $gaassignment1=($_POST['ganametext1']);
    $gaassignment2=($_POST['ganametext2']);
    $gaassignment3=($_POST['ganametext3']);
    $laassignment1=($_POST['lanametext1']);
    $laassignment2=($_POST['lanametext2']);
    $laassignment3=($_POST['lanametext3']);


    //set all old student Waitinglist
    $m_id = $_SESSION["id"];
    echo '$m_id:'.$m_id;
    $sql = "
                              SELECT  ti.TAAssignmentID as id,
                                ti.CourseID as course,
                                ti.TANumber as tanumber,
                                ti.GANumber as ganumber,
                                ti.LANumber as lanumber,
                                te.TAAssignmentExtraID as taassignmentextraid,
                                te.Assignment as assignment,
                                te.Instance as instance,
                                te.PantherID as PantherID
                                FROM tbl_taassignment_info as ti
                                left join tbl_taassignment_extra as te on te.TAAssignmentID = ti.TAAssignmentID
      
                                where ti.TAAssignmentID=
                        " . $m_id;
    $result = mysqli_query($db,$sql);
    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            $p_PantherID = $row["PantherID"];
            if(!empty($p_PantherID)) {
                $pantherID = $p_PantherID;
                $status = 'Waitinglist';
                updateapplicationstatus($db, $termid, $pantherID, $status);
            }
        }
    }


    if($_SESSION["status"]=="create") {
        echo 'null';
        $sql = "INSERT INTO tbl_taassignment_info(CourseID,TANumber,GANumber,LANumber) 
                  VALUES('$course','$tanumber','$ganumber','$lanumber')";
        echo $sql .'<br>';
        $result = mysqli_query($db,$sql);
        $taassignmentid = mysqli_insert_id($db);
        echo "TAAssignment ID of last inserted record is: " . $taassignmentid;

        $i=1;
        if($i<= $tanumber )
        {
            $sql = "INSERT INTO tbl_taassignment_extra(TAAssignmentID,Assignment,Instance,PantherID) 
                      VALUES('$taassignmentid','TA',$i,'$taassignment1')";
            echo $sql .'<br>';
            $result = mysqli_query($db,$sql);
            $taassignmentextraid = mysqli_insert_id($db);
            echo "TAAssignmentExtra ID of last inserted TA1 record is: " . $taassignmentextraid;

            $pantherID = $taassignment1;
            $status = 'Approved';
            updateapplicationstatus($db,$termid,$pantherID,$status);

            $i=$i+1;
        }
        if($i<= $tanumber )
        {
            $sql = "INSERT INTO tbl_taassignment_extra(TAAssignmentID,Assignment,Instance,PantherID) 
                      VALUES('$taassignmentid','TA',$i,'$taassignment2')";
            echo $sql .'<br>';
            $result = mysqli_query($db,$sql);
            $taassignmentextraid = mysqli_insert_id($db);
            echo "TAAssignmentExtra ID of last inserted TA2 record is: " . $taassignmentextraid;

            $pantherID = $taassignment2;
            $status = 'Approved';
            updateapplicationstatus($db,$termid,$pantherID,$status);

            $i=$i+1;
        }
        if($i<= $tanumber )
        {
            $sql = "INSERT INTO tbl_taassignment_extra(TAAssignmentID,Assignment,Instance,PantherID) 
                      VALUES('$taassignmentid','TA',$i,'$taassignment3')";
            echo $sql .'<br>';
            $result = mysqli_query($db,$sql);
            $taassignmentextraid = mysqli_insert_id($db);
            echo "TAAssignmentExtra ID of last inserted TA3 record is: " . $taassignmentextraid;

            $pantherID = $taassignment3;
            $status = 'Approved';
            updateapplicationstatus($db,$termid,$pantherID,$status);

            $i=$i+1;
        }
        $i=1;
        if($i<= $ganumber )
        {
            $sql = "INSERT INTO tbl_taassignment_extra(TAAssignmentID,Assignment,Instance,PantherID) 
                      VALUES('$taassignmentid','GA',$i,'$gaassignment1')";
            echo $sql .'<br>';
            $result = mysqli_query($db,$sql);
            $taassignmentextraid = mysqli_insert_id($db);
            echo "TAAssignmentExtra ID of last inserted GA1 record is: " . $taassignmentextraid;

            $pantherID = $gaassignment1;
            $status = 'Approved';
            updateapplicationstatus($db,$termid,$pantherID,$status);

            $i=$i+1;
        }
        if($i<= $ganumber )
        {
            $sql = "INSERT INTO tbl_taassignment_extra(TAAssignmentID,Assignment,Instance,PantherID) 
                      VALUES('$taassignmentid','GA',$i,'$gaassignment2')";
            echo $sql .'<br>';
            $result = mysqli_query($db,$sql);
            $taassignmentextraid = mysqli_insert_id($db);
            echo "TAAssignmentExtra ID of last inserted GA2 record is: " . $taassignmentextraid;

            $pantherID = $gaassignment2;
            $status = 'Approved';
            updateapplicationstatus($db,$termid,$pantherID,$status);
            $i=$i+1;
        }
        if($i<= $ganumber )
        {
            $sql = "INSERT INTO tbl_taassignment_extra(TAAssignmentID,Assignment,Instance,PantherID) 
                      VALUES('$taassignmentid','GA',$i,'$gaassignment3')";
            echo $sql .'<br>';
            $result = mysqli_query($db,$sql);
            $taassignmentextraid = mysqli_insert_id($db);
            echo "TAAssignmentExtra ID of last inserted GA3 record is: " . $taassignmentextraid;

            $pantherID = $gaassignment3;
            $status = 'Approved';
            updateapplicationstatus($db,$termid,$pantherID,$status);
        }
        $i=1;
        if($i<= $lanumber )
        {
            $sql = "INSERT INTO tbl_taassignment_extra(TAAssignmentID,Assignment,Instance,PantherID) 
                      VALUES('$taassignmentid','LA',$i,'$laassignment1')";
            echo $sql .'<br>';
            $result = mysqli_query($db,$sql);
            $taassignmentextraid = mysqli_insert_id($db);
            echo "TAAssignmentExtra ID of last inserted LA1 record is: " . $taassignmentextraid;

            $pantherID = $laassignment1;
            $status = 'Approved';
            updateapplicationstatus($db,$termid,$pantherID,$status);

            $i=$i+1;
        }
        if($i<= $lanumber )
        {
            $sql = "INSERT INTO tbl_taassignment_extra(TAAssignmentID,Assignment,Instance,PantherID) 
                      VALUES('$taassignmentid','LA',$i,'$laassignment2')";
            echo $sql .'<br>';
            $result = mysqli_query($db,$sql);
            $taassignmentextraid = mysqli_insert_id($db);
            echo "TAAssignmentExtra ID of last inserted LA2 record is: " . $taassignmentextraid;

            $pantherID = $laassignment2;
            $status = 'Approved';
            updateapplicationstatus($db,$termid,$pantherID,$status);
            $i=$i+1;
        }
        if($i<= $lanumber )
        {
            $sql = "INSERT INTO tbl_taassignment_extra(TAAssignmentID,Assignment,Instance,PantherID) 
                      VALUES('$taassignmentid','LA',$i,'$laassignment3')";
            echo $sql .'<br>';
            $result = mysqli_query($db,$sql);
            $taassignmentextraid = mysqli_insert_id($db);
            echo "TAAssignmentExtra ID of last inserted LA3 record is: " . $taassignmentextraid;

            $pantherID = $laassignment3;
            $status = 'Approved';
            updateapplicationstatus($db,$termid,$pantherID,$status);
        }
    }
    else
    {
        $id = $_SESSION["id"];
        $sql = "UPDATE tbl_taassignment_info 
                set CourseID='$course',TANumber = '$tanumber',GANumber='$ganumber',LANumber='$lanumber'
                where TAAssignmentID = $id";
        echo $sql .'<br>';
        $result = mysqli_query($db,$sql);
        echo "TAAssignmentExtra ID data update: " . $id;

        $m_tanumber=$_SESSION["tanumber"];
        $assignment="TA";
        if($m_tanumber<$tanumber)
        {
            //update and insert
            echo "update and insert <br>";
            $i=1;
            if($i<=$m_tanumber && $i<=$tanumber)
            {
                $extraid=$_SESSION["taassignmentextraidta1"];
                $currpantherid = getcurpantherid($db,$extraid);
                $sql = "UPDATE tbl_taassignment_extra 
                        set TAAssignmentID='$id',Assignment = '$assignment',Instance='$i',PantherID='$taassignment1'
                        where TAAssignmentExtraID = $extraid";
                echo $sql .'<br>';
                $result = mysqli_query($db,$sql);
                if (!$result)
                {
                    echo("Error description: " . mysqli_error($db));
                }
                echo "TAAssignmentExtra ID data TA 1 update: " . $extraid;

//                 $pantherID = $currpantherid;
//                 $status = 'Waitinglist';
//                 updateapplicationstatus($db,$termid,$pantherID,$status);

                $pantherID = $taassignment1;
                $status = 'Approved';
                updateapplicationstatus($db,$termid,$pantherID,$status);

                $i=$i+1;
            }
            else if ($i>$m_tanumber && $i<=$tanumber)
            {
                $sql = "INSERT INTO tbl_taassignment_extra(TAAssignmentID,Assignment,Instance,PantherID) 
                      VALUES('$id','$assignment',$i,'$taassignment1')";
                echo $sql .'<br>';
                $result = mysqli_query($db,$sql);
                $taassignmentextraid = mysqli_insert_id($db);
                echo "TAAssignmentExtra ID of last inserted TA1 record is: " . $taassignmentextraid;

                $pantherID = $taassignment1;
                $status = 'Approved';
                updateapplicationstatus($db,$termid,$pantherID,$status);

                $i=$i+1;
            }
            if($i<=$m_tanumber && $i<=$tanumber)
            {
                $extraid=$_SESSION["taassignmentextraidta2"];
                $currpantherid = getcurpantherid($db,$extraid);
                $sql = "UPDATE tbl_taassignment_extra 
                        set TAAssignmentID='$id',Assignment = '$assignment',Instance='$i',PantherID='$taassignment2'
                        where TAAssignmentExtraID = $extraid";
                echo $sql .'<br>';
                $result = mysqli_query($db,$sql);
                if (!$result)
                {
                    echo("Error description: " . mysqli_error($db));
                }
                echo "TAAssignmentExtra ID data TA 2 update: " . $extraid;

//                 $pantherID = $currpantherid;
//                 $status = 'Waitinglist';
//                 updateapplicationstatus($db,$termid,$pantherID,$status);

                $pantherID = $taassignment2;
                $status = 'Approved';
                updateapplicationstatus($db,$termid,$pantherID,$status);

                $i=$i+1;
            }
            else if ($i>$m_tanumber && $i<=$tanumber)
            {
                $sql = "INSERT INTO tbl_taassignment_extra(TAAssignmentID,Assignment,Instance,PantherID) 
                      VALUES('$id','$assignment',$i,'$taassignment2')";
                echo $sql .'<br>';
                $result = mysqli_query($db,$sql);
                $taassignmentextraid = mysqli_insert_id($db);
                echo "TAAssignmentExtra ID of last inserted TA2 record is: " . $taassignmentextraid;

                $pantherID = $taassignment2;
                $status = 'Approved';
                updateapplicationstatus($db,$termid,$pantherID,$status);

                $i=$i+1;
            }
            if($i<=$m_tanumber && $i<=$tanumber)
            {
                $extraid=$_SESSION["taassignmentextraidta3"];
                $currpantherid = getcurpantherid($db,$extraid);
                $sql = "UPDATE tbl_taassignment_extra 
                        set TAAssignmentID='$id',Assignment = '$assignment',Instance='$i',PantherID='$taassignment3'
                        where TAAssignmentExtraID = $extraid";
                echo $sql .'<br>';
                $result = mysqli_query($db,$sql);
                if (!$result)
                {
                    echo("Error description: " . mysqli_error($db));
                }
                echo "TAAssignmentExtra ID data TA 3 update: " . $extraid;

//                 $pantherID = $currpantherid;
//                 $status = 'Waitinglist';
//                 updateapplicationstatus($db,$termid,$pantherID,$status);

                $pantherID = $taassignment3;
                $status = 'Approved';
                updateapplicationstatus($db,$termid,$pantherID,$status);

                $i=$i+1;
            }
            else if ($i>$m_tanumber && $i<=$tanumber)
            {
                $sql = "INSERT INTO tbl_taassignment_extra(TAAssignmentID,Assignment,Instance,PantherID) 
                      VALUES('$id','$assignment',$i,'$taassignment3')";
                echo $sql .'<br>';
                $result = mysqli_query($db,$sql);
                $taassignmentextraid = mysqli_insert_id($db);
                echo "TAAssignmentExtra ID of last inserted TA3 record is: " . $taassignmentextraid;

                $pantherID = $taassignment3;
                $status = 'Approved';
                updateapplicationstatus($db,$termid,$pantherID,$status);

                $i=$i+1;
            }
        }
        else if ($m_tanumber===$tanumber)
        {
            //update
            echo "update <br>";
            $i=1;
            if($i<=$m_tanumber)
            {
                $extraid=$_SESSION["taassignmentextraidta1"];
                $currpantherid = getcurpantherid($db,$extraid);
                $sql = "UPDATE tbl_taassignment_extra 
                        set TAAssignmentID='$id',Assignment = '$assignment',Instance='$i',PantherID='$taassignment1'
                        where TAAssignmentExtraID = $extraid";
                echo $sql .'<br>';
                $result = mysqli_query($db,$sql);
                if (!$result)
                {
                    echo("Error description: " . mysqli_error($db));
                }
                echo "TAAssignmentExtra ID data TA 1 update: " . $extraid;

//                 $pantherID = $currpantherid;
//                 $status = 'Waitinglist';
//                 updateapplicationstatus($db,$termid,$pantherID,$status);

                $pantherID = $taassignment1;
                $status = 'Approved';
                updateapplicationstatus($db,$termid,$pantherID,$status);

                $i=$i+1;
            }
            if($i<=$m_tanumber)
            {
                $extraid=$_SESSION["taassignmentextraidta2"];
                $currpantherid = getcurpantherid($db,$extraid);
                $sql = "UPDATE tbl_taassignment_extra 
                        set TAAssignmentID='$id',Assignment = '$assignment',Instance='$i',PantherID='$taassignment2'
                        where TAAssignmentExtraID = $extraid";
                echo $sql .'<br>';
                $result = mysqli_query($db,$sql);
                if (!$result)
                {
                    echo("Error description: " . mysqli_error($db));
                }
                echo "TAAssignmentExtra ID data TA 2 update: " . $extraid;

//                 $pantherID = $currpantherid;
//                 $status = 'Waitinglist';
//                 updateapplicationstatus($db,$termid,$pantherID,$status);

                $pantherID = $taassignment2;
                $status = 'Approved';
                updateapplicationstatus($db,$termid,$pantherID,$status);

                $i=$i+1;
            }
            if($i<=$m_tanumber)
            {
                $extraid=$_SESSION["taassignmentextraidta3"];
                $currpantherid = getcurpantherid($db,$extraid);
                $sql = "UPDATE tbl_taassignment_extra 
                        set TAAssignmentID='$id',Assignment = '$assignment',Instance='$i',PantherID='$taassignment3'
                        where TAAssignmentExtraID = $extraid";
                echo $sql .'<br>';
                $result = mysqli_query($db,$sql);
                if (!$result)
                {
                    echo("Error description: " . mysqli_error($db));
                }
                echo "TAAssignmentExtra ID data TA 3 update: " . $extraid;
//
//                 $pantherID = $currpantherid;
//                 $status = 'Waitinglist';
//                 updateapplicationstatus($db,$termid,$pantherID,$status);

                $pantherID = $taassignment3;
                $status = 'Approved';
                updateapplicationstatus($db,$termid,$pantherID,$status);

                $i=$i+1;
            }
        }
        else if($m_tanumber > $tanumber)
        {
            //update and delete
            echo "update and delete <br>";
            $i=3;
            if($i<=$m_tanumber && $i<=$tanumber)
            {
                $extraid=$_SESSION["taassignmentextraidta3"];
                $currpantherid = getcurpantherid($db,$extraid);
                $sql = "UPDATE tbl_taassignment_extra 
                        set TAAssignmentID='$id',Assignment = '$assignment',Instance='$i',PantherID='$taassignment3'
                        where TAAssignmentExtraID = $extraid";
                echo $sql .'<br>';
                $result = mysqli_query($db,$sql);
                if (!$result)
                {
                    echo("Error description: " . mysqli_error($db));
                }
                echo "TAAssignmentExtra ID data TA 3 update: " . $extraid;

//                 $pantherID = $currpantherid;
//                 $status = 'Waitinglist';
//                 updateapplicationstatus($db,$termid,$pantherID,$status);

                $pantherID = $taassignment3;
                $status = 'Approved';
                updateapplicationstatus($db,$termid,$pantherID,$status);

            }
            else if ($i<=$m_tanumber && $i>$tanumber)
            {
                $extraid=$_SESSION["taassignmentextraidta3"];
                $currpantherid = getcurpantherid($db,$extraid);
                $sql = "delete from tbl_taassignment_extra where TAAssignmentExtraID = $extraid";
                echo $sql .'<br>';
                $result = mysqli_query($db,$sql);
                if (!$result)
                {
                    echo("Error description: " . mysqli_error($db));
                }
                echo "TAAssignmentExtra ID of last delete TA3 record is: " . $extraid;

//                 $pantherID = $currpantherid;
//                 $status = 'Waitinglist';
//                 updateapplicationstatus($db,$termid,$pantherID,$status);

            }
            //2
            $i=$i-1;
            if($i<=$m_tanumber && $i<=$tanumber)
            {
                $extraid=$_SESSION["taassignmentextraidta2"];
                $currpantherid = getcurpantherid($db,$extraid);
                $sql = "UPDATE tbl_taassignment_extra 
                        set TAAssignmentID='$id',Assignment = '$assignment',Instance='$i',PantherID='$taassignment2'
                        where TAAssignmentExtraID = $extraid";
                echo $sql .'<br>';
                $result = mysqli_query($db,$sql);
                if (!$result)
                {
                    echo("Error description: " . mysqli_error($db));
                }
                echo "TAAssignmentExtra ID data TA 2 update: " . $extraid;

//                 $pantherID = $currpantherid;
//                 $status = 'Waitinglist';
//                 updateapplicationstatus($db,$termid,$pantherID,$status);

                $pantherID = $taassignment2;
                $status = 'Approved';
                updateapplicationstatus($db,$termid,$pantherID,$status);

            }
            else if ($i<=$m_tanumber && $i>$tanumber)
            {
                $extraid=$_SESSION["taassignmentextraidta2"];
                $currpantherid = getcurpantherid($db,$extraid);
                $sql = "delete from tbl_taassignment_extra where TAAssignmentExtraID = $extraid";
                echo $sql .'<br>';
                $result = mysqli_query($db,$sql);
                echo "TAAssignmentExtra ID of last delete TA2 record is: " . $extraid;

//                 $pantherID = $currpantherid;
//                 $status = 'Waitinglist';
//                 updateapplicationstatus($db,$termid,$pantherID,$status);

            }
            //1
            $i=$i-1;
            if($i<=$m_tanumber && $i<=$tanumber)
            {
                $extraid=$_SESSION["taassignmentextraidta1"];
                $currpantherid = getcurpantherid($db,$extraid);
                $sql = "UPDATE tbl_taassignment_extra 
                        set TAAssignmentID='$id',Assignment = '$assignment',Instance='$i',PantherID='$taassignment1'
                        where TAAssignmentExtraID = $extraid";
                echo $sql .'<br>';
                $result = mysqli_query($db,$sql);
                if (!$result)
                {
                    echo("Error description: " . mysqli_error($db));
                }
                echo "TAAssignmentExtra ID data TA 1 update: " . $extraid;

//                 $pantherID = $currpantherid;
//                 $status = 'Waitinglist';
//                 updateapplicationstatus($db,$termid,$pantherID,$status);

                $pantherID = $taassignment1;
                $status = 'Approved';
                updateapplicationstatus($db,$termid,$pantherID,$status);

                $i=$i+1;
            }
            else if ($i<=$m_tanumber && $i > $tanumber)
            {
                $extraid=$_SESSION["taassignmentextraidta1"];
                $currpantherid = getcurpantherid($db,$extraid);
                $sql = "delete from tbl_taassignment_extra where TAAssignmentExtraID = $extraid";
                echo $sql .'<br>';
                $result = mysqli_query($db,$sql);
                echo "TAAssignmentExtra ID of last delete TA1 record is: " . $extraid;

//                 $pantherID = $currpantherid;
//                 $status = 'Waitinglist';
//                 updateapplicationstatus($db,$termid,$pantherID,$status);

                $i=$i+1;
            }
        }

        $m_ganumber=$_SESSION["ganumber"];
        $assignment="GA";
        if($m_ganumber<$ganumber)
        {
            //update and insert
            echo "update and insert <br>";
            $i=1;
            if($i<=$m_ganumber && $i<=$ganumber)
            {
                $extraid=$_SESSION["taassignmentextraidga1"];
                $currpantherid = getcurpantherid($db,$extraid);
                $sql = "UPDATE tbl_taassignment_extra
                        set TAAssignmentID='$id',Assignment = '$assignment',Instance='$i',PantherID='$gaassignment1'
                        where TAAssignmentExtraID = $extraid";
                echo $sql .'<br>';
                $result = mysqli_query($db,$sql);
                if (!$result)
                {
                    echo("Error description: " . mysqli_error($db));
                }
                echo "TAAssignmentExtra ID data GA 1 update: " . $extraid;

//                 $pantherID = $currpantherid;
//                 $status = 'Waitinglist';
//                 updateapplicationstatus($db,$termid,$pantherID,$status);

                $pantherID = $gaassignment1;
                $status = 'Approved';
                updateapplicationstatus($db,$termid,$pantherID,$status);

                $i=$i+1;
            }
            else if ($i>$m_ganumber && $i<=$ganumber)
            {
                $sql = "INSERT INTO tbl_taassignment_extra(TAAssignmentID,Assignment,Instance,PantherID)
                      VALUES('$id','$assignment',$i,'$gaassignment1')";
                echo $sql .'<br>';
                $result = mysqli_query($db,$sql);
                $taassignmentextraid = mysqli_insert_id($db);

                $pantherID = $gaassignment1;
                $status = 'Approved';
                updateapplicationstatus($db,$termid,$pantherID,$status);

                $i=$i+1;
            }
            if($i<=$m_ganumber && $i<=$ganumber)
            {
                $extraid=$_SESSION["taassignmentextraidga2"];
                $currpantherid = getcurpantherid($db,$extraid);
                $sql = "UPDATE tbl_taassignment_extra
                        set TAAssignmentID='$id',Assignment = '$assignment',Instance='$i',PantherID='$gaassignment2'
                        where TAAssignmentExtraID = $extraid";
                echo $sql .'<br>';
                $result = mysqli_query($db,$sql);
                if (!$result)
                {
                    echo("Error description: " . mysqli_error($db));
                }
                echo "TAAssignmentExtra ID data GA 2 update: " . $extraid;

//                 $pantherID = $currpantherid;
//                 $status = 'Waitinglist';
//                 updateapplicationstatus($db,$termid,$pantherID,$status);

                $pantherID = $gaassignment2;
                $status = 'Approved';
                updateapplicationstatus($db,$termid,$pantherID,$status);

                $i=$i+1;
            }
            else if ($i>$m_ganumber && $i<=$ganumber)
            {
                $sql = "INSERT INTO tbl_taassignment_extra(TAAssignmentID,Assignment,Instance,PantherID)
                      VALUES('$id','$assignment',$i,'$gaassignment2')";
                echo $sql .'<br>';
                $result = mysqli_query($db,$sql);
                $taassignmentextraid = mysqli_insert_id($db);
                echo "TAAssignmentExtra ID of last inserted GA2 record is: " . $taassignmentextraid;

                $pantherID = $gaassignment2;
                $status = 'Approved';
                updateapplicationstatus($db,$termid,$pantherID,$status);

                $i=$i+1;
            }
            if($i<=$m_ganumber && $i<=$ganumber)
            {
                $extraid=$_SESSION["taassignmentextraidga3"];
                $currpantherid = getcurpantherid($db,$extraid);
                $sql = "UPDATE tbl_taassignment_extra
                        set TAAssignmentID='$id',Assignment = '$assignment',Instance='$i',PantherID='$gaassignment3'
                        where TAAssignmentExtraID = $extraid";
                echo $sql .'<br>';
                $result = mysqli_query($db,$sql);
                if (!$result)
                {
                    echo("Error description: " . mysqli_error($db));
                }
                echo "TAAssignmentExtra ID data GA 3 update: " . $extraid;

//                 $pantherID = $currpantherid;
//                 $status = 'Waitinglist';
//                 updateapplicationstatus($db,$termid,$pantherID,$status);

                $pantherID = $gaassignment3;
                $status = 'Approved';
                updateapplicationstatus($db,$termid,$pantherID,$status);

                $i=$i+1;
            }
            else if ($i>$m_ganumber && $i<=$ganumber)
            {
                $sql = "INSERT INTO tbl_taassignment_extra(TAAssignmentID,Assignment,Instance,PantherID)
                      VALUES('$id','$assignment',$i,'$gaassignment3')";
                echo $sql .'<br>';
                $result = mysqli_query($db,$sql);
                $taassignmentextraid = mysqli_insert_id($db);
                echo "TAAssignmentExtra ID of last inserted GA3 record is: " . $taassignmentextraid;

                $pantherID = $gaassignment3;
                $status = 'Approved';
                updateapplicationstatus($db,$termid,$pantherID,$status);

                $i=$i+1;
            }
        }
        else if ($m_ganumber===$ganumber)
        {
            //update
            echo "update <br>";
            $i=1;
            if($i<=$m_ganumber)
            {
                $extraid=$_SESSION["taassignmentextraidga1"];
                $currpantherid = getcurpantherid($db,$extraid);
                $sql = "UPDATE tbl_taassignment_extra
                        set TAAssignmentID='$id',Assignment = '$assignment',Instance='$i',PantherID='$gaassignment1'
                        where TAAssignmentExtraID = $extraid";
                echo $sql .'<br>';
                $result = mysqli_query($db,$sql);
                if (!$result)
                {
                    echo("Error description: " . mysqli_error($db));
                }
                echo "TAAssignmentExtra ID data GA 1 update: " . $extraid;

//                 $pantherID = $currpantherid;
//                 $status = 'Waitinglist';
//                 updateapplicationstatus($db,$termid,$pantherID,$status);

                $pantherID = $gaassignment1;
                $status = 'Approved';
                updateapplicationstatus($db,$termid,$pantherID,$status);

                $i=$i+1;
            }
            if($i<=$m_ganumber)
            {
                $extraid=$_SESSION["taassignmentextraidga2"];
                $currpantherid = getcurpantherid($db,$extraid);
                $sql = "UPDATE tbl_taassignment_extra
                        set TAAssignmentID='$id',Assignment = '$assignment',Instance='$i',PantherID='$gaassignment2'
                        where TAAssignmentExtraID = $extraid";
                echo $sql .'<br>';
                $result = mysqli_query($db,$sql);
                if (!$result)
                {
                    echo("Error description: " . mysqli_error($db));
                }
                echo "TAAssignmentExtra ID data GA 2 update: " . $extraid;

//                 $pantherID = $currpantherid;
//                 $status = 'Waitinglist';
//                 updateapplicationstatus($db,$termid,$pantherID,$status);

                $pantherID = $gaassignment2;
                $status = 'Approved';
                updateapplicationstatus($db,$termid,$pantherID,$status);

                $i=$i+1;
            }
            if($i<=$m_ganumber)
            {
                $extraid=$_SESSION["taassignmentextraidga3"];
                $currpantherid = getcurpantherid($db,$extraid);
                $sql = "UPDATE tbl_taassignment_extra
                        set TAAssignmentID='$id',Assignment = '$assignment',Instance='$i',PantherID='$gaassignment3'
                        where TAAssignmentExtraID = $extraid";
                echo $sql .'<br>';
                $result = mysqli_query($db,$sql);
                if (!$result)
                {
                    echo("Error description: " . mysqli_error($db));
                }
                echo "TAAssignmentExtra ID data GA 3 update: " . $extraid;

//                 $pantherID = $currpantherid;
//                 $status = 'Waitinglist';
//                 updateapplicationstatus($db,$termid,$pantherID,$status);

                $pantherID = $gaassignment3;
                $status = 'Approved';
                updateapplicationstatus($db,$termid,$pantherID,$status);

                $i=$i+1;
            }
        }
        else if($m_ganumber > $ganumber)
        {
            //update and delete
            echo "update and delete <br>";
            $i=3;
            if($i<=$m_tanumber && $i<=$tanumber)
            {
                $extraid=$_SESSION["taassignmentextraidga3"];
                $currpantherid = getcurpantherid($db,$extraid);
                $sql = "UPDATE tbl_taassignment_extra
                        set TAAssignmentID='$id',Assignment = '$assignment',Instance='$i',PantherID='$gaassignment3'
                        where TAAssignmentExtraID = $extraid";
                echo $sql .'<br>';
                $result = mysqli_query($db,$sql);
                if (!$result)
                {
                    echo("Error description: " . mysqli_error($db));
                }
                echo "TAAssignmentExtra ID data GA 3 update: " . $extraid;

//                 $pantherID = $currpantherid;
//                 $status = 'Waitinglist';
//                 updateapplicationstatus($db,$termid,$pantherID,$status);

                $pantherID = $gaassignment3;
                $status = 'Approved';
                updateapplicationstatus($db,$termid,$pantherID,$status);

            }
            else if ($i<=$m_ganumber && $i>$ganumber)
            {
                $extraid=$_SESSION["taassignmentextraidga3"];
                $currpantherid = getcurpantherid($db,$extraid);
                $sql = "delete from tbl_taassignment_extra where TAAssignmentExtraID = $extraid";
                echo $sql .'<br>';
                $result = mysqli_query($db,$sql);
                if (!$result)
                {
                    echo("Error description: " . mysqli_error($db));
                }
                echo "TAAssignmentExtra ID of last delete GA3 record is: " . $extraid;

//                 $pantherID = $currpantherid;
//                 $status = 'Waitinglist';
//                 updateapplicationstatus($db,$termid,$pantherID,$status);

            }
            //2
            $i=$i-1;
            if($i<=$m_ganumber && $i<=$ganumber)
            {
                $extraid=$_SESSION["taassignmentextraidga2"];
                $currpantherid = getcurpantherid($db,$extraid);
                $sql = "UPDATE tbl_taassignment_extra
                        set TAAssignmentID='$id',Assignment = '$assignment',Instance='$i',PantherID='$gaassignment2'
                        where TAAssignmentExtraID = $extraid";
                echo $sql .'<br>';
                $result = mysqli_query($db,$sql);
                if (!$result)
                {
                    echo("Error description: " . mysqli_error($db));
                }
                echo "TAAssignmentExtra ID data GA 2 update: " . $extraid;

//                 $pantherID = $currpantherid;
//                 $status = 'Waitinglist';
//                 updateapplicationstatus($db,$termid,$pantherID,$status);

                $pantherID = $gaassignment2;
                $status = 'Approved';
                updateapplicationstatus($db,$termid,$pantherID,$status);


            }
            else if ($i<=$m_ganumber && $i>$ganumber)
            {
                $extraid=$_SESSION["taassignmentextraidga2"];
                $currpantherid = getcurpantherid($db,$extraid);
                $sql = "delete from tbl_taassignment_extra where TAAssignmentExtraID = $extraid";
                echo $sql .'<br>';
                $result = mysqli_query($db,$sql);
                echo "TAAssignmentExtra ID of last delete GA2 record is: " . $extraid;


//                 $pantherID = $currpantherid;
//                 $status = 'Waitinglist';
//                 updateapplicationstatus($db,$termid,$pantherID,$status);

            }
            //1
            $i=$i-1;
            if($i<=$m_ganumber && $i<=$ganumber)
            {
                $extraid=$_SESSION["taassignmentextraidga1"];
                $currpantherid = getcurpantherid($db,$extraid);
                $sql = "UPDATE tbl_taassignment_extra
                        set TAAssignmentID='$id',Assignment = '$assignment',Instance='$i',PantherID='$gaassignment1'
                        where TAAssignmentExtraID = $extraid";
                echo $sql .'<br>';
                $result = mysqli_query($db,$sql);
                if (!$result)
                {
                    echo("Error description: " . mysqli_error($db));
                }
                echo "TAAssignmentExtra ID data GA 1 update: " . $extraid;

//                 $pantherID = $currpantherid;
//                 $status = 'Waitinglist';
//                 updateapplicationstatus($db,$termid,$pantherID,$status);

                $pantherID = $gaassignment1;
                $status = 'Approved';
                updateapplicationstatus($db,$termid,$pantherID,$status);

                $i=$i+1;
            }
            else if ($i<=$m_ganumber && $i > $ganumber)
            {
                $extraid=$_SESSION["taassignmentextraidga1"];
                $currpantherid = getcurpantherid($db,$extraid);
                $sql = "delete from tbl_taassignment_extra where TAAssignmentExtraID = $extraid";
                echo $sql .'<br>';
                $result = mysqli_query($db,$sql);
                echo "TAAssignmentExtra ID of last delete GA1 record is: " . $extraid;


                $pantherID = $currpantherid;
                $status = 'Waitinglist';
                updateapplicationstatus($db,$termid,$pantherID,$status);

                $i=$i+1;
            }
        }

        $m_lanumber=$_SESSION["lanumber"];
        $assignment="LA";
        if($m_lanumber<$lanumber)
        {
            //update and insert
            echo "update and insert <br>";
            $i=1;
            if($i<=$m_lanumber && $i<=$lanumber)
            {
                $extraid=$_SESSION["taassignmentextraidla1"];
                $currpantherid = getcurpantherid($db,$extraid);
                $sql = "UPDATE tbl_taassignment_extra
                        set TAAssignmentID='$id',Assignment = '$assignment',Instance='$i',PantherID='$laassignment1'
                        where TAAssignmentExtraID = $extraid";
                echo $sql .'<br>';
                $result = mysqli_query($db,$sql);
                if (!$result)
                {
                    echo("Error description: " . mysqli_error($db));
                }
                echo "TAAssignmentExtra ID data GA 1 update: " . $extraid;

//                 $pantherID = $currpantherid;
//                 $status = 'Waitinglist';
//                 updateapplicationstatus($db,$termid,$pantherID,$status);

                $pantherID = $laassignment1;
                $status = 'Approved';
                updateapplicationstatus($db,$termid,$pantherID,$status);

                $i=$i+1;
            }
            else if ($i>$m_lanumber && $i<=$lanumber)
            {
                $sql = "INSERT INTO tbl_taassignment_extra(TAAssignmentID,Assignment,Instance,PantherID)
                      VALUES('$id','$assignment',$i,'$laassignment1')";
                echo $sql .'<br>';
                $result = mysqli_query($db,$sql);
                $taassignmentextraid = mysqli_insert_id($db);
                echo "TAAssignmentExtra ID of last inserted GA1 record is: " . $taassignmentextraid;

                $pantherID = $laassignment1;
                $status = 'Approved';
                updateapplicationstatus($db,$termid,$pantherID,$status);

                $i=$i+1;
            }
            if($i<=$m_lanumber && $i<=$lanumber)
            {
                $extraid=$_SESSION["taassignmentextraidla2"];
                $currpantherid = getcurpantherid($db,$extraid);
                $sql = "UPDATE tbl_taassignment_extra
                        set TAAssignmentID='$id',Assignment = '$assignment',Instance='$i',PantherID='$laassignment2'
                        where TAAssignmentExtraID = $extraid";
                echo $sql .'<br>';
                $result = mysqli_query($db,$sql);
                if (!$result)
                {
                    echo("Error description: " . mysqli_error($db));
                }
                echo "TAAssignmentExtra ID data GA 2 update: " . $extraid;

//                 $pantherID = $currpantherid;
//                 $status = 'Waitinglist';
//                 updateapplicationstatus($db,$termid,$pantherID,$status);

                $pantherID = $laassignment2;
                $status = 'Approved';
                updateapplicationstatus($db,$termid,$pantherID,$status);

                $i=$i+1;
            }
            else if ($i>$m_lanumber && $i<=$lanumber)
            {
                $sql = "INSERT INTO tbl_taassignment_extra(TAAssignmentID,Assignment,Instance,PantherID)
                      VALUES('$id','$assignment',$i,'$laassignment2')";
                echo $sql .'<br>';
                $result = mysqli_query($db,$sql);
                $taassignmentextraid = mysqli_insert_id($db);
                echo "TAAssignmentExtra ID of last inserted GA2 record is: " . $taassignmentextraid;

                $pantherID = $laassignment2;
                $status = 'Approved';
                updateapplicationstatus($db,$termid,$pantherID,$status);

                $i=$i+1;
            }
            if($i<=$m_lanumber && $i<=$lanumber)
            {
                $extraid=$_SESSION["taassignmentextraidla3"];
                $currpantherid = getcurpantherid($db,$extraid);
                $sql = "UPDATE tbl_taassignment_extra
                        set TAAssignmentID='$id',Assignment = '$assignment',Instance='$i',PantherID='$laassignment3'
                        where TAAssignmentExtraID = $extraid";
                echo $sql .'<br>';
                $result = mysqli_query($db,$sql);
                if (!$result)
                {
                    echo("Error description: " . mysqli_error($db));
                }
                echo "TAAssignmentExtra ID data GA 3 update: " . $extraid;


//                 $pantherID = $currpantherid;
//                 $status = 'Waitinglist';
//                 updateapplicationstatus($db,$termid,$pantherID,$status);

                $pantherID = $laassignment3;
                $status = 'Approved';
                updateapplicationstatus($db,$termid,$pantherID,$status);

                $i=$i+1;
            }
            else if ($i>$m_lanumber && $i<=$lanumber)
            {
                $sql = "INSERT INTO tbl_taassignment_extra(TAAssignmentID,Assignment,Instance,PantherID)
                      VALUES('$id','$assignment',$i,'$laassignment3')";
                echo $sql .'<br>';
                $result = mysqli_query($db,$sql);
                $taassignmentextraid = mysqli_insert_id($db);
                echo "TAAssignmentExtra ID of last inserted GA3 record is: " . $taassignmentextraid;

                $pantherID = $laassignment3;
                $status = 'Approved';
                updateapplicationstatus($db,$termid,$pantherID,$status);

                $i=$i+1;
            }
        }
        else if ($m_lanumber===$lanumber)
        {
            //update
            echo "update <br>";
            $i=1;
            if($i<=$m_lanumber)
            {
                $extraid=$_SESSION["taassignmentextraidla1"];
                $currpantherid = getcurpantherid($db,$extraid);
                $sql = "UPDATE tbl_taassignment_extra
                        set TAAssignmentID='$id',Assignment = '$assignment',Instance='$i',PantherID='$laassignment1'
                        where TAAssignmentExtraID = $extraid";
                echo $sql .'<br>';
                $result = mysqli_query($db,$sql);
                if (!$result)
                {
                    echo("Error description: " . mysqli_error($db));
                }
                echo "TAAssignmentExtra ID data GA 1 update: " . $extraid;

//                 $pantherID = $currpantherid;
//                 $status = 'Waitinglist';
//                 updateapplicationstatus($db,$termid,$pantherID,$status);

                $pantherID = $laassignment1;
                $status = 'Approved';
                updateapplicationstatus($db,$termid,$pantherID,$status);

                $i=$i+1;
            }
            if($i<=$m_lanumber)
            {
                $extraid=$_SESSION["taassignmentextraidla2"];
                $currpantherid = getcurpantherid($db,$extraid);
                $sql = "UPDATE tbl_taassignment_extra
                        set TAAssignmentID='$id',Assignment = '$assignment',Instance='$i',PantherID='$laassignment2'
                        where TAAssignmentExtraID = $extraid";
                echo $sql .'<br>';
                $result = mysqli_query($db,$sql);
                if (!$result)
                {
                    echo("Error description: " . mysqli_error($db));
                }
                echo "TAAssignmentExtra ID data GA 2 update: " . $extraid;

//                 $pantherID = $currpantherid;
//                 $status = 'Waitinglist';
//                 updateapplicationstatus($db,$termid,$pantherID,$status);

                $pantherID = $laassignment2;
                $status = 'Approved';
                updateapplicationstatus($db,$termid,$pantherID,$status);

                $i=$i+1;
            }
            if($i<=$m_lanumber)
            {
                $extraid=$_SESSION["taassignmentextraidla3"];
                $currpantherid = getcurpantherid($db,$extraid);
                $sql = "UPDATE tbl_taassignment_extra
                        set TAAssignmentID='$id',Assignment = '$assignment',Instance='$i',PantherID='$laassignment3'
                        where TAAssignmentExtraID = $extraid";
                echo $sql .'<br>';
                $result = mysqli_query($db,$sql);
                if (!$result)
                {
                    echo("Error description: " . mysqli_error($db));
                }
                echo "TAAssignmentExtra ID data GA 3 update: " . $extraid;


//                 $pantherID = $currpantherid;
//                 $status = 'Waitinglist';
//                 updateapplicationstatus($db,$termid,$pantherID,$status);

                $pantherID = $laassignment3;
                $status = 'Approved';
                updateapplicationstatus($db,$termid,$pantherID,$status);

                $i=$i+1;
            }
        }
        else if($m_lanumber > $lanumber)
        {
            //update and delete
            echo "update and delete <br>";
            $i=3;
            if($i<=$m_tanumber && $i<=$tanumber)
            {
                $extraid=$_SESSION["taassignmentextraidla3"];
                $currpantherid = getcurpantherid($db,$extraid);
                $sql = "UPDATE tbl_taassignment_extra
                        set TAAssignmentID='$id',Assignment = '$assignment',Instance='$i',PantherID='$laassignment3'
                        where TAAssignmentExtraID = $extraid";
                echo $sql .'<br>';
                $result = mysqli_query($db,$sql);
                if (!$result)
                {
                    echo("Error description: " . mysqli_error($db));
                }
                echo "TAAssignmentExtra ID data GA 3 update: " . $extraid;

//                 $pantherID = $currpantherid;
//                 $status = 'Waitinglist';
//                 updateapplicationstatus($db,$termid,$pantherID,$status);

                $pantherID = $laassignment3;
                $status = 'Approved';
                updateapplicationstatus($db,$termid,$pantherID,$status);

            }
            else if ($i<=$m_lanumber && $i>$lanumber)
            {
                $extraid=$_SESSION["taassignmentextraidla3"];
                $currpantherid = getcurpantherid($db,$extraid);
                $sql = "delete from tbl_taassignment_extra where TAAssignmentExtraID = $extraid";
                echo $sql .'<br>';
                $result = mysqli_query($db,$sql);
                if (!$result)
                {
                    echo("Error description: " . mysqli_error($db));
                }
                echo "TAAssignmentExtra ID of last delete GA3 record is: " . $extraid;

//                 $pantherID = $currpantherid;
//                 $status = 'Waitinglist';
//                 updateapplicationstatus($db,$termid,$pantherID,$status);

            }
            //2
            $i=$i-1;
            if($i<=$m_lanumber && $i<=$lanumber)
            {
                $extraid=$_SESSION["taassignmentextraidla2"];
                $currpantherid = getcurpantherid($db,$extraid);
                $sql = "UPDATE tbl_taassignment_extra
                        set TAAssignmentID='$id',Assignment = '$assignment',Instance='$i',PantherID='$laassignment2'
                        where TAAssignmentExtraID = $extraid";
                echo $sql .'<br>';
                $result = mysqli_query($db,$sql);
                if (!$result)
                {
                    echo("Error description: " . mysqli_error($db));
                }
                echo "TAAssignmentExtra ID data GA 2 update: " . $extraid;

//                 $pantherID = $currpantherid;
//                 $status = 'Waitinglist';
//                 updateapplicationstatus($db,$termid,$pantherID,$status);

                $pantherID = $laassignment2;
                $status = 'Approved';
                updateapplicationstatus($db,$termid,$pantherID,$status);

            }
            else if ($i<=$m_lanumber && $i>$lanumber)
            {
                $extraid=$_SESSION["taassignmentextraidla2"];
                $currpantherid = getcurpantherid($db,$extraid);
                $sql = "delete from tbl_taassignment_extra where TAAssignmentExtraID = $extraid";
                echo $sql .'<br>';
                $result = mysqli_query($db,$sql);
                echo "TAAssignmentExtra ID of last delete GA2 record is: " . $extraid;


//                 $pantherID = $currpantherid;
//                 $status = 'Waitinglist';
//                 updateapplicationstatus($db,$termid,$pantherID,$status);
            }
            //1
            $i=$i-1;
            if($i<=$m_lanumber && $i<=$lanumber)
            {
                $extraid=$_SESSION["taassignmentextraidla1"];
                $currpantherid = getcurpantherid($db,$extraid);
                $sql = "UPDATE tbl_taassignment_extra
                        set TAAssignmentID='$id',Assignment = '$assignment',Instance='$i',PantherID='$laassignment1'
                        where TAAssignmentExtraID = $extraid";
                echo $sql .'<br>';
                $result = mysqli_query($db,$sql);
                if (!$result)
                {
                    echo("Error description: " . mysqli_error($db));
                }
                echo "TAAssignmentExtra ID data GA 1 update: " . $extraid;

//                 $pantherID = $currpantherid;
//                 $status = 'Waitinglist';
//                 updateapplicationstatus($db,$termid,$pantherID,$status);

                $pantherID = $laassignment1;
                $status = 'Approved';
                updateapplicationstatus($db,$termid,$pantherID,$status);

                $i=$i+1;
            }
            else if ($i<=$m_lanumber && $i > $lanumber)
            {
                $extraid=$_SESSION["taassignmentextraidla1"];
                $currpantherid = getcurpantherid($db,$extraid);
                $sql = "delete from tbl_taassignment_extra where TAAssignmentExtraID = $extraid";
                echo $sql .'<br>';
                $result = mysqli_query($db,$sql);
                echo "TAAssignmentExtra ID of last delete GA1 record is: " . $extraid;

//                 $pantherID = $currpantherid;
//                 $status = 'Waitinglist';
//                 updateapplicationstatus($db,$termid,$pantherID,$status);

                $i=$i+1;
            }
        }
    }

    header("location:taassignmentviewdashboard.php");  //redirect home page
}
else
{
    if(empty($_GET['id'])==false) {
        if (is_numeric($_GET['id'])) {

            $id = (int)$_GET['id'];

            $sql = "
                              SELECT  ti.TAAssignmentID as id,
                                ti.CourseID as course,
                                ti.TANumber as tanumber,
                                ti.GANumber as ganumber,
                                ti.LANumber as lanumber,
                                te.TAAssignmentExtraID as taassignmentextraidta1,
                                te.PantherID as taassignment1,
                                te2.TAAssignmentExtraID as taassignmentextraidta2,
                                te2.PantherID as taassignment2,
                                te3.TAAssignmentExtraID as taassignmentextraidta3,
                                te3.PantherID as taassignment3,
                                te4.TAAssignmentExtraID as taassignmentextraidga1,
                                te4.PantherID as gaassignment1,
                                te5.TAAssignmentExtraID as taassignmentextraidga2,
                                te5.PantherID as gaassignment2,
                                te6.TAAssignmentExtraID as taassignmentextraidga3,
                                te6.PantherID as gaassignment3,
                                te7.TAAssignmentExtraID as taassignmentextraidla1,
                                te7.PantherID as laassignment1,
                                te8.TAAssignmentExtraID as taassignmentextraidla2,
                                te8.PantherID as laassignment2,
                                te9.TAAssignmentExtraID as taassignmentextraidla3,
                                te9.PantherID as laassignment3
                                FROM tbl_taassignment_info as ti
                                left join tbl_taassignment_extra as te on te.TAAssignmentID = ti.TAAssignmentID and te.Instance='1' and te.Assignment='TA'
                                left join tbl_taassignment_extra as te2 on te2.TAAssignmentID = ti.TAAssignmentID and te2.Instance='2' and te2.Assignment='TA'
                                left join tbl_taassignment_extra as te3 on te3.TAAssignmentID = ti.TAAssignmentID and te3.Instance='3' and te3.Assignment='TA'
                                left join tbl_taassignment_extra as te4 on te4.TAAssignmentID = ti.TAAssignmentID and te4.Instance='1' and te4.Assignment='GA'
                                left join tbl_taassignment_extra as te5 on te5.TAAssignmentID = ti.TAAssignmentID and te5.Instance='2' and te5.Assignment='GA'
                                left join tbl_taassignment_extra as te6 on te6.TAAssignmentID = ti.TAAssignmentID and te6.Instance='3' and te6.Assignment='GA'
                                left join tbl_taassignment_extra as te7 on te7.TAAssignmentID = ti.TAAssignmentID and te7.Instance='1' and te7.Assignment='LA'
                                left join tbl_taassignment_extra as te8 on te8.TAAssignmentID = ti.TAAssignmentID and te8.Instance='2' and te8.Assignment='LA'
                                left join tbl_taassignment_extra as te9 on te9.TAAssignmentID = ti.TAAssignmentID and te9.Instance='3' and te9.Assignment='LA'
                                where ti.TAAssignmentID=
                        " . $id;
            //echo $sql;
            $result = mysqli_query($db, $sql);

            if ($result->num_rows > 0) {
                // output data of each row
                while ($row = $result->fetch_assoc()) {
                    //echo "id: " . $row["id"] .  "<br>";
                    $id = $row["id"];
                    $course = $row["course"];
                    $tanumber = $row["tanumber"];
                    $ganumber = $row["ganumber"];
                    $lanumber = $row["lanumber"];
                    $taassignmentextraidta1 = $row["taassignmentextraidta1"];
                    $taassignmentextraidta2 = $row["taassignmentextraidta2"];
                    $taassignmentextraidta3 = $row["taassignmentextraidta3"];
                    $taassignmentextraidga1 = $row["taassignmentextraidga1"];
                    $taassignmentextraidga2 = $row["taassignmentextraidga2"];
                    $taassignmentextraidga3 = $row["taassignmentextraidga3"];
                    $taassignmentextraidla1 = $row["taassignmentextraidla1"];
                    $taassignmentextraidla2 = $row["taassignmentextraidla2"];
                    $taassignmentextraidla3 = $row["taassignmentextraidla3"];
                    $taassignment1 = $row["taassignment1"];
                    $taassignment2 = $row["taassignment2"];
                    $taassignment3 = $row["taassignment3"];
                    $gaassignment1 = $row["gaassignment1"];
                    $gaassignment2 = $row["gaassignment2"];
                    $gaassignment3 = $row["gaassignment3"];
                    $laassignment1 = $row["laassignment1"];
                    $laassignment2 = $row["laassignment2"];
                    $laassignment3 = $row["laassignment3"];
                    $_SESSION["id"] = $id;

                    $_SESSION["tanumber"] = $tanumber;
                    $_SESSION["ganumber"] = $ganumber;
                    $_SESSION["lanumber"] = $lanumber;
                    //TA
                    $i=1;
                    if($i<=$tanumber)
                    {
                        $_SESSION["taassignmentextraidta1"] = $taassignmentextraidta1;
                        $i=$i+1;
                    }
                    if($i<=$tanumber)
                    {
                        $_SESSION["taassignmentextraidta2"] = $taassignmentextraidta2;
                        $i=$i+1;
                    }
                    if($i<=$tanumber)
                    {
                        $_SESSION["taassignmentextraidta3"] = $taassignmentextraidta3;
                        $i=$i+1;
                    }
                    //GA
                    $i=1;
                    if($i<=$ganumber)
                    {
                        $_SESSION["taassignmentextraidga1"] = $taassignmentextraidga1;
                        $i=$i+1;
                    }
                    if($i<=$ganumber)
                    {
                        $_SESSION["taassignmentextraidga2"] = $taassignmentextraidga2;
                        $i=$i+1;
                    }
                    if($i<=$ganumber)
                    {
                        $_SESSION["taassignmentextraidga3"] = $taassignmentextraidga3;
                        $i=$i+1;
                    }
                    //LA
                    $i=1;
                    if($i<=$lanumber)
                    {
                        $_SESSION["taassignmentextraidla1"] = $taassignmentextraidla1;
                        $i=$i+1;
                    }
                    if($i<=$lanumber)
                    {
                        $_SESSION["taassignmentextraidla2"] = $taassignmentextraidla2;
                        $i=$i+1;
                    }
                    if($i<=$lanumber)
                    {
                        $_SESSION["taassignmentextraidla3"] = $taassignmentextraidla3;
                        $i=$i+1;
                    }

                }
            } else {
                //echo "0 results";
            }

            $_SESSION["status"] = "update";
            //echo $_SESSION["status"];
        }
    }
    else
    {
        $_SESSION["status"] = "create";
        //echo $_SESSION["status"];
    }
}

$status = $_SESSION["status"];

echo $status;
$coursearray = array();
$sql = "select co.Courseid as id, 
                te.Term as term,
                te.Termid as termid,
                co.CRN as crn,
                co.Subject as subject,
                co.Course as course,
                co.Title as title
                from tbl_course  as co
                LEFT JOIN tbl_schedule as sc on sc.Courseid = co.Courseid and sc.instance=1
                LEFT JOIN tbl_term as te on te.Termid = sc.Termid
                order by te.Startday desc,co.Course,co.CRN
                                 ";

$result = mysqli_query($db, $sql);

if ($result->num_rows > 0) {
    $i = 0;
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        $coursearray[$i] = array();
        $coursearray[$i]['id'] = $row["id"];
        $coursearray[$i]['term'] = $row["term"];
        $coursearray[$i]['termid'] = $row["termid"];
        $coursearray[$i]['crn'] = $row["crn"];
        $coursearray[$i]['subject'] = $row["subject"];
        $coursearray[$i]['course'] = $row["course"];
        $coursearray[$i]['title'] = $row["title"];
        $i = $i + 1;
    }
}

$termarray = array();
$sql = "select Termid,Term,Startday,Endday
            from tbl_term";
//echo $sql . '<br>';
$result = mysqli_query($db, $sql);

if ($result->num_rows > 0) {
    $i = 0;
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        $termarray[$i] = array();
        $termarray[$i]['Termid'] = $row["Termid"];
        $termarray[$i]['Term'] = $row["Term"];
        $termarray[$i]['Startday'] = $row["Startday"];
        $termarray[$i]['Endday'] = $row["Endday"];
        $i = $i + 1;
    }
}

$applicantarray = array();
$sql = " select ga.GAApplicationID as id, 
            CONCAT(coalesce(ga.FirstName,' ') , IF(ga.MiddleName = '', ' ', IFNULL(ga.MiddleName,' ')),coalesce(ga.LastName,' ')) as name,
            ga.PantherID as pantherid,
            ga.Email as email,
			ga.Termid as termid,
            ga.Courses as course,
            ga.Degree as degree
            from tbl_gaapplication as ga 
            where ga.Status='Waitinglist'
         ";

$result = mysqli_query($db, $sql);

if ($result->num_rows > 0)
{
    $i = 0;
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        $applicantarray[$i] = array();
        $applicantarray[$i]['id'] = $row["id"];
        $applicantarray[$i]['name'] = $row["name"];
        $applicantarray[$i]['pantherid'] = $row["pantherid"];
        $applicantarray[$i]['email'] = $row["email"];
        $applicantarray[$i]['termid'] = $row["termid"];
        $applicantarray[$i]['course'] = $row["course"];
        $applicantarray[$i]['degree'] = $row["degree"];
        $i = $i + 1;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    include $root.'/links/header.php';
    include $root.'/links/footerLinks.php';
    ?>
    <script type="text/javascript" src="carjava.js"></script>
    <script type="text/javascript">
        function ChangeTANumber(selectObject){
            //alert(document.);
           // var pvalue =document.getElementById("tanumber").value;
            var pvalue =document.querySelector('input[name="tanumber"]:checked').value;;
            //var pvalue=document.GTAregister.assignment.options[document.GTAregister.assignment.selectedIndex].label;
            alert(pvalue.toString());
            if(pvalue.toString() == "0")
            {
                //window.alert("hidden");
                // window.alert(document.GTAregister.course.style.display);
                document.taassignmentregister.taassignment1.style.display  = 'none';
                document.taassignmentregister.taassignment2.style.display  = 'none';
                document.taassignmentregister.taassignment3.style.display  = 'none';

                document.taassignmentregister.taassignment1_order2.style.display  = 'none';
                document.taassignmentregister.taassignment2_order2.style.display  = 'none';
                document.taassignmentregister.taassignment3_order2.style.display  = 'none';

                document.getElementById("talabel1").style.display  = 'none';
                document.getElementById("talabel2").style.display  = 'none';
                document.getElementById("talabel3").style.display  = 'none';

                document.getElementById("tanamelabel1").style.display  = 'none';
                document.getElementById("tanamelabel2").style.display  = 'none';
                document.getElementById("tanamelabel3").style.display  = 'none';

                document.getElementById("tanametext1").style.display  = 'none';
                document.getElementById("tanametext2").style.display  = 'none';
                document.getElementById("tanametext3").style.display  = 'none';

                document.getElementById("tanameorderlabel1_1").style.display  = 'none';
                document.getElementById("tanameorderlabel2_1").style.display  = 'none';
                document.getElementById("tanameorderlabel3_1").style.display  = 'none';
                document.getElementById("tanameorderlabel1_2").style.display  = 'none';
                document.getElementById("tanameorderlabel2_2").style.display  = 'none';
                document.getElementById("tanameorderlabel3_2").style.display  = 'none';
            }
            else if(pvalue.toString() == "1")
            {
                //window.alert("visible");
                document.taassignmentregister.taassignment1.style.display  = 'inherit';
                document.taassignmentregister.taassignment2.style.display  = 'none';
                document.taassignmentregister.taassignment3.style.display  = 'none';

                document.taassignmentregister.taassignment1_order2.style.display  = 'inherit';
                document.taassignmentregister.taassignment2_order2.style.display  = 'none';
                document.taassignmentregister.taassignment3_order2.style.display  = 'none';

                document.getElementById("talabel1").style.display  = 'inherit';
                document.getElementById("talabel2").style.display  = 'none';
                document.getElementById("talabel3").style.display  = 'none';

                document.getElementById("tanamelabel1").style.display  = 'inherit';
                document.getElementById("tanamelabel2").style.display  = 'none';
                document.getElementById("tanamelabel3").style.display  = 'none';

                document.getElementById("tanametext1").style.display  = 'inherit';
                document.getElementById("tanametext2").style.display  = 'none';
                document.getElementById("tanametext3").style.display  = 'none';

                document.getElementById("tanameorderlabel1_1").style.display  = '';
                document.getElementById("tanameorderlabel2_1").style.display  = 'none';
                document.getElementById("tanameorderlabel3_1").style.display  = 'none';
                document.getElementById("tanameorderlabel1_2").style.display  = '';
                document.getElementById("tanameorderlabel2_2").style.display  = 'none';
                document.getElementById("tanameorderlabel3_2").style.display  = 'none';

            }
            else if(pvalue.toString() == "2")
            {
                //window.alert("visible");
                document.taassignmentregister.taassignment1.style.display  = 'inherit';
                document.taassignmentregister.taassignment2.style.display  = 'inherit';
                document.taassignmentregister.taassignment3.style.display  = 'none';

                document.taassignmentregister.taassignment1_order2.style.display  = 'inherit';
                document.taassignmentregister.taassignment2_order2.style.display  = 'inherit';
                document.taassignmentregister.taassignment3_order2.style.display  = 'none';

                document.getElementById("talabel1").style.display  = 'inherit';
                document.getElementById("talabel2").style.display  = 'inherit';
                document.getElementById("talabel3").style.display  = 'none';

                document.getElementById("tanamelabel1").style.display  = 'inherit';
                document.getElementById("tanamelabel2").style.display  = 'inherit';
                document.getElementById("tanamelabel3").style.display  = 'none';

                document.getElementById("tanametext1").style.display  = 'inherit';
                document.getElementById("tanametext2").style.display  = 'inherit';
                document.getElementById("tanametext3").style.display  = 'none';

                document.getElementById("tanameorderlabel1_1").style.display  = '';
                document.getElementById("tanameorderlabel2_1").style.display  = '';
                document.getElementById("tanameorderlabel3_1").style.display  = 'none';
                document.getElementById("tanameorderlabel1_2").style.display  = '';
                document.getElementById("tanameorderlabel2_2").style.display  = '';
                document.getElementById("tanameorderlabel3_2").style.display  = 'none';
            }
            else if(pvalue.toString() == "3")
            {
                //window.alert("visible");
                document.taassignmentregister.taassignment1.style.display  = 'inherit';
                document.taassignmentregister.taassignment2.style.display  = 'inherit';
                document.taassignmentregister.taassignment3.style.display  = 'inherit';

                document.taassignmentregister.taassignment1_order2.style.display  = 'inherit';
                document.taassignmentregister.taassignment2_order2.style.display  = 'inherit';
                document.taassignmentregister.taassignment3_order2.style.display  = 'inherit';

                document.getElementById("talabel1").style.display  = 'inherit';
                document.getElementById("talabel2").style.display  = 'inherit';
                document.getElementById("talabel3").style.display  = 'inherit';

                document.getElementById("tanamelabel1").style.display  = 'inherit';
                document.getElementById("tanamelabel2").style.display  = 'inherit';
                document.getElementById("tanamelabel3").style.display  = 'inherit';

                document.getElementById("tanametext1").style.display  = 'inherit';
                document.getElementById("tanametext2").style.display  = 'inherit';
                document.getElementById("tanametext3").style.display  = 'inherit';

                document.getElementById("tanameorderlabel1_1").style.display  = '';
                document.getElementById("tanameorderlabel2_1").style.display  = '';
                document.getElementById("tanameorderlabel3_1").style.display  = '';
                document.getElementById("tanameorderlabel1_2").style.display  = '';
                document.getElementById("tanameorderlabel2_2").style.display  = '';
                document.getElementById("tanameorderlabel3_2").style.display  = '';
            }
        }
        window.onload =(init());
        function init()
        {
            alert('hi');
            ChangeTANumber(this);

        }
    </script>
</head>
<body onload="init()">
<?php
if(isset($_SESSION['message']))
{
    echo "<div id='error_msg'>".$_SESSION['message']."</div>";
    unset($_SESSION['message']);
}
?>

<!-- Navigation -->
<?php
include $root.'/UI/staff/staffmenu.php';
?>
<!-- /#Navigation -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<!-- page-wrapper -->
<div id="page-wrapper">
    <form method="post" action="taassignmentregister.php" name ="taassignmentregister" >
        <table>
            <tr>
                <td>Term:</td>
            </tr>
            <tr>
                <td>
                    <select name="termid" id="termid"  >
                        <?php
                        foreach ($termarray as $arr)
                        {
                            $p_Termid = $arr["Termid"];
                            $p_Term = $arr["Term"];
                            echo "<option value='$p_Termid'";
                            if(empty($id)==false)
                            {
                                if ($p_Termid == $TermID) {
                                    echo "selected";
                                }
                            }
                            echo  "    >
                            $p_Term
                            </option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Course:</td>
            </tr>
            <tr>
                <td>
                    <select name="course" id="course" <?php if(empty($id)==false){ echo "readonly='readonly'";} ?> >

                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    <a href="#" class="hovercourse" data-id = "" id="courselabel"></a>
                </td>
            </tr>
            <tr>
                <td>Tutor Number:</td>
                <td>
                    <label><input type="radio" name="tanumber" id ="tanumber" class="radio" value="0" onChange="ChangeTANumber(this)"  <?php if(empty($id)==false){ if ($tanumber=='0'){echo  'checked';}}else{{echo  'checked';}} ?> >0</label>
                    <label><input type="radio" name="tanumber" id ="tanumber"  class="radio" value="1" onChange="ChangeTANumber(this)" <?php if(empty($id)==false){ if ($tanumber=='1'){echo  'checked';}} ?> >1</label>
                    <label><input type="radio" name="tanumber" id ="tanumber"  class="radio" value="2" onChange="ChangeTANumber(this)" <?php if(empty($id)==false){ if ($tanumber=='2'){echo  'checked';}} ?> >2</label>
                    <label><input type="radio" name="tanumber" id ="tanumber"  class="radio" value="3" onChange="ChangeTANumber(this)" <?php if(empty($id)==false){ if ($tanumber=='3'){echo  'checked';}} ?> >3</label>
                </td>
            </tr>
            <tr name ="taall1">
                <td name ="talabel1" id="talabel1" >First Tutor:</td>
                <td ALIGN="center">
                    <a href="#" class="hover" data-id = "" id="tanamelabel1" name="tanamelabel1"></a>
                </td>
                <td ALIGN="center">
                    <input type="text"  data-id="" name="tanametext1" id="tanametext1" value="" >
                </td>
            </tr>
            <tr name ="taall1_2">
                <td name ="" id="" ></td>
                <td ALIGN="center" name ="tanameorderlabel1_1" id="tanameorderlabel1_1" >Order by Course,GPA</td>
                <td ALIGN="center" name ="tanameorderlabel1_2" id="tanameorderlabel1_2" >Order by Course,GPA</td>
            </tr>
            <tr name ="taall1_3">
                <td name ="" id="" ></td>
                <td ALIGN="center">
                    <select name="taassignment1" id="taassignment1"  style="display: inherit" size="10" >
                    </select>
                </td>
                <td ALIGN="center">
                    <select name="taassignment1_order2" id="taassignment1_order2"  style="display: inherit" size="10" >
                    </select>
                </td>
            </tr>
            <tr name ="taall2">
                <td name ="talabel2" id="talabel2">Second Tutor:</td>
                <td>
                    <a href="#" class="hover" data-id = "" id="tanamelabel2" name="tanamelabel2"></a>
                </td>
                <td ALIGN="center">
                    <input type="text"  data-id="" name="tanametext2" id="tanametext2" value="" >
                </td>
            </tr>
            <tr name ="taall2_2">
                <td name ="" id="" ></td>
                <td ALIGN="center"  name ="tanameorderlabel2_1" id="tanameorderlabel2_1" >Order by Course,GPA</td>
                <td ALIGN="center"  name ="tanameorderlabel2_2" id="tanameorderlabel2_2" >Order by Course,GPA</td>
            </tr>
            <tr name ="taall2_3">
                <td name ="" id="" ></td>
                <td ALIGN="center">
                    <select name="taassignment2" id="taassignment2"  style="display: inherit"  size="10" >
                    </select>
                </td>
                <td ALIGN="center">
                    <select name="taassignment2_order2" id="taassignment2_order2"  style="display: inherit" size="10" >
                    </select>
                </td>
            </tr>
            <tr name ="taall3">
                <td name ="talabel3" id="talabel3" >Third Tutor:</td>
                <td>
                    <a href="#" class="hover" data-id = "" id="tanamelabel3" name="tanamelabel3"></a>
                </td>
                <td ALIGN="center">
                    <input type="text"  data-id="" name="tanametext3" id="tanametext3" value="" >
                </td>
            </tr>
            <tr name ="taall3_2">
                <td name ="" id="" ></td>
                <td ALIGN="center"  name ="tanameorderlabel3_1" id="tanameorderlabel3_1" >Order by Course,GPA</td>
                <td ALIGN="center"  name ="tanameorderlabel3_2" id="tanameorderlabel3_2" >Order by Course,GPA</td>
            </tr>
            <tr name ="taall3_3">
                <td name ="" id="" ></td>
                <td ALIGN="center">
                    <select name="taassignment3" id="taassignment3" style="display: inherit"  size="10" >
                    </select>
                </td>
                <td ALIGN="center">
                    <select name="taassignment3_order2" id="taassignment3_order2"  style="display: inherit" size="10" >
                    </select>
                </td>
            </tr>
            <tr>
                <td>Grader Number:</td>
                <td>
                    <label><input type="radio" name="ganumber" class="radio" value="0" onChange="ChangeGANumber(this)"  <?php if(empty($id)==false){ if ($ganumber=='0'){echo  'checked';}}else{{echo  'checked';}} ?> >0</label>
                    <label><input type="radio" name="ganumber" class="radio" value="1" onChange="ChangeGANumber(this)" <?php if(empty($id)==false){ if ($ganumber=='1'){echo  'checked';}} ?> >1</label>
                    <label><input type="radio" name="ganumber" class="radio" value="2" onChange="ChangeGANumber(this)" <?php if(empty($id)==false){ if ($ganumber=='2'){echo  'checked';}} ?> >2</label>
                    <label><input type="radio" name="ganumber" class="radio" value="3" onChange="ChangeGANumber(this)" <?php if(empty($id)==false){ if ($ganumber=='3'){echo  'checked';}} ?> >3</label>
                </td>
            </tr>
            <tr name ="gaall1">
                <td name ="galabel1" id="galabel1" >First Grader:</td>
                <td>
                    <a href="#" class="hover" data-id = "" id="ganamelabel1" name="ganamelabel1"></a>
                </td>
                <td ALIGN="center">
                    <input type="text"  data-id="" name="ganametext1" id="ganametext1" value="" >
                </td>
            </tr>
            <tr name ="gaall1_2">
                <td name ="" id="" ></td>
                <td ALIGN="center"  name ="ganameorderlabel1_1" id="ganameorderlabel1_1" >Order by Course,GPA</td>
                <td ALIGN="center"  name ="ganameorderlabel1_2" id="ganameorderlabel1_2" >Order by Course,GPA</td>
            </tr>
            <tr name ="gaall1_3">
                <td name ="" id="" ></td>
                <td ALIGN="center">
                    <select name="gaassignment1" id="gaassignment1" style="display: inherit"  size="10" >
                    </select>
                </td>
                <td ALIGN="center">
                    <select name="gaassignment1_order2" id="gaassignment1_order2"  style="display: inherit" size="10" >
                    </select>
                </td>
            </tr>
            <tr name ="gaall2">
                <td name ="galabel2" id="galabel2">Second Grader:</td>
                <td>
                    <a href="#" class="hover" data-id = "" id="ganamelabel2" name="ganamelabel2"></a>
                </td>
                <td ALIGN="center">
                    <input type="text"  data-id="" name="ganametext2" id="ganametext2" value="" >
                </td>
            </tr>
            <tr name ="gaall2_2">
                <td name ="" id="" ></td>
                <td ALIGN="center"  name ="ganameorderlabel2_1" id="ganameorderlabel2_1" >Order by Course,GPA</td>
                <td ALIGN="center"  name ="ganameorderlabel2_2" id="ganameorderlabel2_2" >Order by Course,GPA</td>
            </tr>
            <tr name ="gaall2_3">
                <td name ="" id="" ></td>
                <td ALIGN="center">
                    <select name="gaassignment2" id="gaassignment2" style="display: inherit"  size="10" >
                    </select>
                </td>
                <td ALIGN="center">
                    <select name="gaassignment2_order2" id="gaassignment2_order2"  style="display: inherit" size="10" >
                    </select>
                </td>
            </tr>
            <tr name ="gaall3">
                <td name ="galabel3" id="galabel3" >Third Grader:</td>
                <td>
                    <a href="#" class="hover" data-id = "" id="ganamelabel3" name="ganamelabel3"></a>
                </td>
                <td ALIGN="center">
                    <input type="text"  data-id="" name="ganametext3" id="ganametext3" value="" >
                </td>
            </tr>
            <tr name ="gaall3_2">
                <td name ="" id="" ></td>
                <td ALIGN="center"  name ="ganameorderlabel3_1" id="ganameorderlabel3_1" >Order by Course,GPA</td>
                <td ALIGN="center"  name ="ganameorderlabel3_2" id="ganameorderlabel3_2" >Order by Course,GPA</td>
            </tr>
            <tr name ="gaall3_3">
                <td name ="" id="" ></td>
                <td ALIGN="center">
                    <select name="gaassignment3" id="gaassignment3" style="display: inherit"  size="10" >
                    </select>
                </td>
                <td ALIGN="center">
                    <select name="gaassignment3_order2" id="gaassignment3_order2"  style="display: inherit" size="10" >
                    </select>
                </td>
            </tr>
            <tr>
                <td>Lab Instructor Number:</td>
                <td>
                    <label><input type="radio" name="lanumber" class="radio" value="0" onChange="ChangeLANumber(this)"  <?php if(empty($id)==false){ if ($lanumber=='0'){echo  'checked';}} else{{echo  'checked';}} ?> >0</label>
                    <label><input type="radio" name="lanumber" class="radio" value="1" onChange="ChangeLANumber(this)" <?php if(empty($id)==false){ if ($lanumber=='1'){echo  'checked';}} ?> >1</label>
                    <label><input type="radio" name="lanumber" class="radio" value="2" onChange="ChangeLANumber(this)" <?php if(empty($id)==false){ if ($lanumber=='2'){echo  'checked';}} ?> >2</label>
                    <label><input type="radio" name="lanumber" class="radio" value="3" onChange="ChangeLANumber(this)" <?php if(empty($id)==false){ if ($lanumber=='3'){echo  'checked';}} ?> >3</label>
                </td>
            </tr>
            <tr name ="laall1">
                <td name ="lalabel1" id="lalabel1" >First Lab Instructor:</td>
                <td>
                    <a href="#" class="hover" data-id = "" id="lanamelabel1" name="lanamelabel1"></a>
                </td>
                <td ALIGN="center">
                    <input type="text"  data-id="" name="lanametext1" id="lanametext1" value="" >
                </td>
            </tr>
            <tr name ="laall1_2">
                <td name ="" id="" ></td>
                <td ALIGN="center"  name ="lanameorderlabel1_1" id="lanameorderlabel1_1" >Order by Course,GPA</td>
                <td ALIGN="center"  name ="lanameorderlabel1_2" id="lanameorderlabel1_2" >Order by Course,GPA</td>
            </tr>
            <tr name ="laall1_3">
                <td name ="" id="" ></td>
                <td ALIGN="center">
                    <select name="laassignment1" id="laassignment1" style="display: inherit"  size="10" >
                    </select>
                </td>
                <td ALIGN="center">
                    <select name="laassignment1_order2" id="laassignment1_order2"  style="display: inherit" size="10" >
                    </select>
                </td>
            </tr>
            <tr name ="laall2">
                <td name ="lalabel2" id="lalabel2"  >Second Lab Instructor:</td>
                <td>
                    <a href="#" class="hover" data-id = "" id="lanamelabel2" name="lanamelabel2"></a>
                </td>
                <td ALIGN="center">
                    <input type="text"  data-id="" name="lanametext2" id="lanametext2" value="" >
                </td>
            </tr>
            <tr name ="laall2_2">
                <td name ="" id="" ></td>
                <td ALIGN="center"  name ="lanameorderlabel2_1" id="lanameorderlabel2_1" >Order by Course,GPA</td>
                <td ALIGN="center"  name ="lanameorderlabel2_2" id="lanameorderlabel2_2" >Order by Course,GPA</td>
            </tr>
            <tr name ="laall2_3">
                <td name ="" id="" ></td>
                <td ALIGN="center">
                    <select name="laassignment2" id="laassignment2" style="display: inherit"  size="10" >
                    </select>
                </td>
                <td ALIGN="center">
                    <select name="laassignment2_order2" id="laassignment2_order2"  style="display: inherit" size="10" >
                    </select>
                </td>
            </tr>
            <tr name ="laall3">
                <td name ="lalabel3" id="lalabel3"  >Third Lab Instructor:</td>
                <td>
                    <a href="#" class="hover" data-id = "" id="lanamelabel3" name="lanamelabel3"></a>
                </td>
                <td ALIGN="center">
                    <input type="text"  data-id="" name="lanametext3" id="lanametext3" value="" >
                </td>
            </tr>
            <tr name ="laall3_2">
                <td name ="" id="" ></td>
                <td ALIGN="center"  name ="lanameorderlabel3_1" id="lanameorderlabel3_1" >Order by Course,GPA</td>
                <td ALIGN="center"  name ="lanameorderlabel3_2" id="lanameorderlabel3_2" >Order by Course,GPA</td>
            </tr>
            <tr name ="laall3_3">
                <td name ="" id="" ></td>
                <td ALIGN="center">
                    <select name="laassignment3" id="laassignment3" style="display: inherit"  size="10" >
                </td>
                <td ALIGN="center">
                    <select name="laassignment3_order2" id="laassignment3_order2"  style="display: inherit" size="10" >
                    </select>
                </td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" name="register_btn" class="Register" value="Submit"></td>
            </tr>

        </table>
    </form>
</div>

</body>

</html>

<style>
    label {
        display: inline-block;
    }
</style>


