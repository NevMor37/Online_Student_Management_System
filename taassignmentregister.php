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


function Inserttaassignment_info()
{

}
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

if(isset($_POST['register_btn']) || isset($_POST['registerunsafe_btn']) )
{
    $Isunsafe = false;
    if(isset($_POST['registerunsafe_btn']))
    {
        $Isunsafe = true;
    }
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
    $taassignment4=($_POST['tanametext4']);
    $taassignment5=($_POST['tanametext5']);
    $gaassignment1=($_POST['ganametext1']);
    $gaassignment2=($_POST['ganametext2']);
    $gaassignment3=($_POST['ganametext3']);
    $gaassignment4=($_POST['ganametext4']);
    $gaassignment5=($_POST['ganametext5']);
    $laassignment1=($_POST['lanametext1']);
    $laassignment2=($_POST['lanametext2']);
    $laassignment3=($_POST['lanametext3']);
    $laassignment4=($_POST['lanametext4']);
    $laassignment5=($_POST['lanametext5']);

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
            if(!empty($p_PantherID) && $Isunsafe ==false) {
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
         if($i<= $tanumber )
         {
             $sql = "INSERT INTO tbl_taassignment_extra(TAAssignmentID,Assignment,Instance,PantherID) 
                      VALUES('$taassignmentid','TA',$i,'$taassignment4')";
             echo $sql .'<br>';
             $result = mysqli_query($db,$sql);
             $taassignmentextraid = mysqli_insert_id($db);
             echo "TAAssignmentExtra ID of last inserted TA4 record is: " . $taassignmentextraid;

             $pantherID = $taassignment4;
             $status = 'Approved';
             updateapplicationstatus($db,$termid,$pantherID,$status);

             $i=$i+1;
         }
         if($i<= $tanumber )
         {
             $sql = "INSERT INTO tbl_taassignment_extra(TAAssignmentID,Assignment,Instance,PantherID) 
                      VALUES('$taassignmentid','TA',$i,'$taassignment5')";
             echo $sql .'<br>';
             $result = mysqli_query($db,$sql);
             $taassignmentextraid = mysqli_insert_id($db);
             echo "TAAssignmentExtra ID of last inserted TA5 record is: " . $taassignmentextraid;

             $pantherID = $taassignment5;
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
         if($i<= $ganumber )
         {
             $sql = "INSERT INTO tbl_taassignment_extra(TAAssignmentID,Assignment,Instance,PantherID) 
                      VALUES('$taassignmentid','GA',$i,'$gaassignment4')";
             echo $sql .'<br>';
             $result = mysqli_query($db,$sql);
             $taassignmentextraid = mysqli_insert_id($db);
             echo "TAAssignmentExtra ID of last inserted GA4 record is: " . $taassignmentextraid;

             $pantherID = $gaassignment4;
             $status = 'Approved';
             updateapplicationstatus($db,$termid,$pantherID,$status);
         }
         if($i<= $ganumber )
         {
             $sql = "INSERT INTO tbl_taassignment_extra(TAAssignmentID,Assignment,Instance,PantherID) 
                      VALUES('$taassignmentid','GA',$i,'$gaassignment4')";
             echo $sql .'<br>';
             $result = mysqli_query($db,$sql);
             $taassignmentextraid = mysqli_insert_id($db);
             echo "TAAssignmentExtra ID of last inserted GA4 record is: " . $taassignmentextraid;

             $pantherID = $gaassignment4;
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
         if($i<= $lanumber )
         {
             $sql = "INSERT INTO tbl_taassignment_extra(TAAssignmentID,Assignment,Instance,PantherID) 
                      VALUES('$taassignmentid','LA',$i,'$laassignment4')";
             echo $sql .'<br>';
             $result = mysqli_query($db,$sql);
             $taassignmentextraid = mysqli_insert_id($db);
             echo "TAAssignmentExtra ID of last inserted LA4 record is: " . $taassignmentextraid;

             $pantherID = $laassignment4;
             $status = 'Approved';
             updateapplicationstatus($db,$termid,$pantherID,$status);
         }
         if($i<= $lanumber )
         {
             $sql = "INSERT INTO tbl_taassignment_extra(TAAssignmentID,Assignment,Instance,PantherID) 
                      VALUES('$taassignmentid','LA',$i,'$laassignment5')";
             echo $sql .'<br>';
             $result = mysqli_query($db,$sql);
             $taassignmentextraid = mysqli_insert_id($db);
             echo "TAAssignmentExtra ID of last inserted LA5 record is: " . $taassignmentextraid;

             $pantherID = $laassignment5;
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
             if($i<=$m_tanumber && $i<=$tanumber)
             {
                 $extraid=$_SESSION["taassignmentextraidta4"];
                 $currpantherid = getcurpantherid($db,$extraid);
                 $sql = "UPDATE tbl_taassignment_extra 
                        set TAAssignmentID='$id',Assignment = '$assignment',Instance='$i',PantherID='$taassignment4'
                        where TAAssignmentExtraID = $extraid";
                 echo $sql .'<br>';
                 $result = mysqli_query($db,$sql);
                 if (!$result)
                 {
                     echo("Error description: " . mysqli_error($db));
                 }
                 echo "TAAssignmentExtra ID data TA 4 update: " . $extraid;

                 $pantherID = $taassignment4;
                 $status = 'Approved';
                 updateapplicationstatus($db,$termid,$pantherID,$status);

                 $i=$i+1;
             }
             else if ($i>$m_tanumber && $i<=$tanumber)
             {
                 $sql = "INSERT INTO tbl_taassignment_extra(TAAssignmentID,Assignment,Instance,PantherID) 
                      VALUES('$id','$assignment',$i,'$taassignment4')";
                 echo $sql .'<br>';
                 $result = mysqli_query($db,$sql);
                 $taassignmentextraid = mysqli_insert_id($db);
                 echo "TAAssignmentExtra ID of last inserted TA4 record is: " . $taassignmentextraid;

                 $pantherID = $taassignment4;
                 $status = 'Approved';
                 updateapplicationstatus($db,$termid,$pantherID,$status);

                 $i=$i+1;
             }
             if($i<=$m_tanumber && $i<=$tanumber)
             {
                 $extraid=$_SESSION["taassignmentextraidta5"];
                 $currpantherid = getcurpantherid($db,$extraid);
                 $sql = "UPDATE tbl_taassignment_extra 
                        set TAAssignmentID='$id',Assignment = '$assignment',Instance='$i',PantherID='$taassignment5'
                        where TAAssignmentExtraID = $extraid";
                 echo $sql .'<br>';
                 $result = mysqli_query($db,$sql);
                 if (!$result)
                 {
                     echo("Error description: " . mysqli_error($db));
                 }
                 echo "TAAssignmentExtra ID data TA 5 update: " . $extraid;

                 $pantherID = $taassignment5;
                 $status = 'Approved';
                 updateapplicationstatus($db,$termid,$pantherID,$status);

                 $i=$i+1;
             }
             else if ($i>$m_tanumber && $i<=$tanumber)
             {
                 $sql = "INSERT INTO tbl_taassignment_extra(TAAssignmentID,Assignment,Instance,PantherID) 
                      VALUES('$id','$assignment',$i,'$taassignment5')";
                 echo $sql .'<br>';
                 $result = mysqli_query($db,$sql);
                 $taassignmentextraid = mysqli_insert_id($db);
                 echo "TAAssignmentExtra ID of last inserted TA5 record is: " . $taassignmentextraid;

                 $pantherID = $taassignment5;
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

                 $pantherID = $taassignment3;
                 $status = 'Approved';
                 updateapplicationstatus($db,$termid,$pantherID,$status);

                 $i=$i+1;
             }

             if($i<=$m_tanumber)
             {
                 $extraid=$_SESSION["taassignmentextraidta4"];
                 $currpantherid = getcurpantherid($db,$extraid);
                 $sql = "UPDATE tbl_taassignment_extra 
                        set TAAssignmentID='$id',Assignment = '$assignment',Instance='$i',PantherID='$taassignment4'
                        where TAAssignmentExtraID = $extraid";
                 echo $sql .'<br>';
                 $result = mysqli_query($db,$sql);
                 if (!$result)
                 {
                     echo("Error description: " . mysqli_error($db));
                 }
                 echo "TAAssignmentExtra ID data TA 4 update: " . $extraid;

                 $pantherID = $taassignment4;
                 $status = 'Approved';
                 updateapplicationstatus($db,$termid,$pantherID,$status);

                 $i=$i+1;
             }
             if($i<=$m_tanumber)
             {
                 $extraid=$_SESSION["taassignmentextraidta5"];
                 $currpantherid = getcurpantherid($db,$extraid);
                 $sql = "UPDATE tbl_taassignment_extra 
                        set TAAssignmentID='$id',Assignment = '$assignment',Instance='$i',PantherID='$taassignment5'
                        where TAAssignmentExtraID = $extraid";
                 echo $sql .'<br>';
                 $result = mysqli_query($db,$sql);
                 if (!$result)
                 {
                     echo("Error description: " . mysqli_error($db));
                 }
                 echo "TAAssignmentExtra ID data TA 5 update: " . $extraid;

                 $pantherID = $taassignment5;
                 $status = 'Approved';
                 updateapplicationstatus($db,$termid,$pantherID,$status);

                 $i=$i+1;
             }
         }
         else if($m_tanumber > $tanumber)
         {
             //update and delete
             echo "update and delete <br>";
             $i=5;
             //5
             if($i<=$m_tanumber && $i<=$tanumber)
             {
                 $extraid=$_SESSION["taassignmentextraidta5"];
                 $currpantherid = getcurpantherid($db,$extraid);
                 $sql = "UPDATE tbl_taassignment_extra 
                        set TAAssignmentID='$id',Assignment = '$assignment',Instance='$i',PantherID='$taassignment5'
                        where TAAssignmentExtraID = $extraid";
                 echo $sql .'<br>';
                 $result = mysqli_query($db,$sql);
                 if (!$result)
                 {
                     echo("Error description: " . mysqli_error($db));
                 }
                 echo "TAAssignmentExtra ID data TA 5 update: " . $extraid;

                 $pantherID = $taassignment5;
                 $status = 'Approved';
                 updateapplicationstatus($db,$termid,$pantherID,$status);

             }
             else if ($i<=$m_tanumber && $i>$tanumber)
             {
                 $extraid=$_SESSION["taassignmentextraidta5"];
                 $currpantherid = getcurpantherid($db,$extraid);
                 $sql = "delete from tbl_taassignment_extra where TAAssignmentExtraID = $extraid";
                 echo $sql .'<br>';
                 $result = mysqli_query($db,$sql);
                 if (!$result)
                 {
                     echo("Error description: " . mysqli_error($db));
                 }
                 echo "TAAssignmentExtra ID of last delete TA5 record is: " . $extraid;
             }
             $i=$i-1;
             //4
             if($i<=$m_tanumber && $i<=$tanumber)
             {
                 $extraid=$_SESSION["taassignmentextraidta4"];
                 $currpantherid = getcurpantherid($db,$extraid);
                 $sql = "UPDATE tbl_taassignment_extra 
                        set TAAssignmentID='$id',Assignment = '$assignment',Instance='$i',PantherID='$taassignment4'
                        where TAAssignmentExtraID = $extraid";
                 echo $sql .'<br>';
                 $result = mysqli_query($db,$sql);
                 if (!$result)
                 {
                     echo("Error description: " . mysqli_error($db));
                 }
                 echo "TAAssignmentExtra ID data TA 4 update: " . $extraid;

                 $pantherID = $taassignment4;
                 $status = 'Approved';
                 updateapplicationstatus($db,$termid,$pantherID,$status);

             }
             else if ($i<=$m_tanumber && $i>$tanumber)
             {
                 $extraid=$_SESSION["taassignmentextraidta4"];
                 $currpantherid = getcurpantherid($db,$extraid);
                 $sql = "delete from tbl_taassignment_extra where TAAssignmentExtraID = $extraid";
                 echo $sql .'<br>';
                 $result = mysqli_query($db,$sql);
                 if (!$result)
                 {
                     echo("Error description: " . mysqli_error($db));
                 }
                 echo "TAAssignmentExtra ID of last delete TA4 record is: " . $extraid;
             }
             $i=$i-1;
             //3
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
             }
             $i=$i-1;
             //2
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

             }
             $i=$i-1;
             //1
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

                 $pantherID = $taassignment1;
                 $status = 'Approved';
                 updateapplicationstatus($db,$termid,$pantherID,$status);

             }
             else if ($i<=$m_tanumber && $i > $tanumber)
             {
                 $extraid=$_SESSION["taassignmentextraidta1"];
                 $currpantherid = getcurpantherid($db,$extraid);
                 $sql = "delete from tbl_taassignment_extra where TAAssignmentExtraID = $extraid";
                 echo $sql .'<br>';
                 $result = mysqli_query($db,$sql);
                 echo "TAAssignmentExtra ID of last delete TA1 record is: " . $extraid;

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
             if($i<=$m_ganumber && $i<=$ganumber)
             {
                 $extraid=$_SESSION["taassignmentextraidga4"];
                 $currpantherid = getcurpantherid($db,$extraid);
                 $sql = "UPDATE tbl_taassignment_extra
                        set TAAssignmentID='$id',Assignment = '$assignment',Instance='$i',PantherID='$gaassignment4'
                        where TAAssignmentExtraID = $extraid";
                 echo $sql .'<br>';
                 $result = mysqli_query($db,$sql);
                 if (!$result)
                 {
                     echo("Error description: " . mysqli_error($db));
                 }
                 echo "TAAssignmentExtra ID data GA 4 update: " . $extraid;

                 $pantherID = $gaassignment4;
                 $status = 'Approved';
                 updateapplicationstatus($db,$termid,$pantherID,$status);

                 $i=$i+1;
             }
             else if ($i>$m_ganumber && $i<=$ganumber)
             {
                 $sql = "INSERT INTO tbl_taassignment_extra(TAAssignmentID,Assignment,Instance,PantherID)
                      VALUES('$id','$assignment',$i,'$gaassignment4')";
                 echo $sql .'<br>';
                 $result = mysqli_query($db,$sql);
                 $taassignmentextraid = mysqli_insert_id($db);
                 echo "TAAssignmentExtra ID of last inserted GA4 record is: " . $taassignmentextraid;

                 $pantherID = $gaassignment4;
                 $status = 'Approved';
                 updateapplicationstatus($db,$termid,$pantherID,$status);

                 $i=$i+1;
             }
             if($i<=$m_ganumber && $i<=$ganumber)
             {
                 $extraid=$_SESSION["taassignmentextraidga5"];
                 $currpantherid = getcurpantherid($db,$extraid);
                 $sql = "UPDATE tbl_taassignment_extra
                        set TAAssignmentID='$id',Assignment = '$assignment',Instance='$i',PantherID='$gaassignment5'
                        where TAAssignmentExtraID = $extraid";
                 echo $sql .'<br>';
                 $result = mysqli_query($db,$sql);
                 if (!$result)
                 {
                     echo("Error description: " . mysqli_error($db));
                 }
                 echo "TAAssignmentExtra ID data GA 5 update: " . $extraid;

                 $pantherID = $gaassignment5;
                 $status = 'Approved';
                 updateapplicationstatus($db,$termid,$pantherID,$status);

                 $i=$i+1;
             }
             else if ($i>$m_ganumber && $i<=$ganumber)
             {
                 $sql = "INSERT INTO tbl_taassignment_extra(TAAssignmentID,Assignment,Instance,PantherID)
                      VALUES('$id','$assignment',$i,'$gaassignment5')";
                 echo $sql .'<br>';
                 $result = mysqli_query($db,$sql);
                 $taassignmentextraid = mysqli_insert_id($db);
                 echo "TAAssignmentExtra ID of last inserted GA5 record is: " . $taassignmentextraid;

                 $pantherID = $gaassignment5;
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

                 $pantherID = $gaassignment3;
                 $status = 'Approved';
                 updateapplicationstatus($db,$termid,$pantherID,$status);

                 $i=$i+1;
             }
             if($i<=$m_ganumber)
             {
                 $extraid=$_SESSION["taassignmentextraidga4"];
                 $currpantherid = getcurpantherid($db,$extraid);
                 $sql = "UPDATE tbl_taassignment_extra
                        set TAAssignmentID='$id',Assignment = '$assignment',Instance='$i',PantherID='$gaassignment4'
                        where TAAssignmentExtraID = $extraid";
                 echo $sql .'<br>';
                 $result = mysqli_query($db,$sql);
                 if (!$result)
                 {
                     echo("Error description: " . mysqli_error($db));
                 }
                 echo "TAAssignmentExtra ID data GA 4 update: " . $extraid;

                 $pantherID = $gaassignment4;
                 $status = 'Approved';
                 updateapplicationstatus($db,$termid,$pantherID,$status);

                 $i=$i+1;
             }
             if($i<=$m_ganumber)
             {
                 $extraid=$_SESSION["taassignmentextraidga5"];
                 $currpantherid = getcurpantherid($db,$extraid);
                 $sql = "UPDATE tbl_taassignment_extra
                        set TAAssignmentID='$id',Assignment = '$assignment',Instance='$i',PantherID='$gaassignment5'
                        where TAAssignmentExtraID = $extraid";
                 echo $sql .'<br>';
                 $result = mysqli_query($db,$sql);
                 if (!$result)
                 {
                     echo("Error description: " . mysqli_error($db));
                 }
                 echo "TAAssignmentExtra ID data GA 5 update: " . $extraid;

                 $pantherID = $gaassignment5;
                 $status = 'Approved';
                 updateapplicationstatus($db,$termid,$pantherID,$status);

                 $i=$i+1;
             }
         }
         else if($m_ganumber > $ganumber)
         {
             //update and delete
             echo "update and delete <br>";
             $i=5;
             //5
             if($i<=$m_ganumber && $i<=$ganumber)
             {
                 $extraid=$_SESSION["taassignmentextraidga5"];
                 $currpantherid = getcurpantherid($db,$extraid);
                 $sql = "UPDATE tbl_taassignment_extra
                        set TAAssignmentID='$id',Assignment = '$assignment',Instance='$i',PantherID='$gaassignment5'
                        where TAAssignmentExtraID = $extraid";
                 echo $sql .'<br>';
                 $result = mysqli_query($db,$sql);
                 if (!$result)
                 {
                     echo("Error description: " . mysqli_error($db));
                 }
                 echo "TAAssignmentExtra ID data GA 5 update: " . $extraid;

                 $pantherID = $gaassignment5;
                 $status = 'Approved';
                 updateapplicationstatus($db,$termid,$pantherID,$status);

             }
             else if ($i<=$m_ganumber && $i>$ganumber)
             {
                 $extraid=$_SESSION["taassignmentextraidga5"];
                 $currpantherid = getcurpantherid($db,$extraid);
                 $sql = "delete from tbl_taassignment_extra where TAAssignmentExtraID = $extraid";
                 echo $sql .'<br>';
                 $result = mysqli_query($db,$sql);
                 if (!$result)
                 {
                     echo("Error description: " . mysqli_error($db));
                 }
                 echo "TAAssignmentExtra ID of last delete GA5 record is: " . $extraid;

             }
             //4
             $i=$i-1;
             if($i<=$m_ganumber && $i<=$ganumber)
             {
                 $extraid=$_SESSION["taassignmentextraidga4"];
                 $currpantherid = getcurpantherid($db,$extraid);
                 $sql = "UPDATE tbl_taassignment_extra
                        set TAAssignmentID='$id',Assignment = '$assignment',Instance='$i',PantherID='$gaassignment4'
                        where TAAssignmentExtraID = $extraid";
                 echo $sql .'<br>';
                 $result = mysqli_query($db,$sql);
                 if (!$result)
                 {
                     echo("Error description: " . mysqli_error($db));
                 }
                 echo "TAAssignmentExtra ID data GA 4 update: " . $extraid;

                 $pantherID = $gaassignment4;
                 $status = 'Approved';
                 updateapplicationstatus($db,$termid,$pantherID,$status);
             }
             else if ($i<=$m_ganumber && $i>$ganumber)
             {
                 $extraid=$_SESSION["taassignmentextraidga4"];
                 $currpantherid = getcurpantherid($db,$extraid);
                 $sql = "delete from tbl_taassignment_extra where TAAssignmentExtraID = $extraid";
                 echo $sql .'<br>';
                 $result = mysqli_query($db,$sql);
                 echo "TAAssignmentExtra ID of last delete GA4 record is: " . $extraid;
             }
             //3
             $i=$i-1;
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
                 echo "TAAssignmentExtra ID data LA 1 update: " . $extraid;

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
                 echo "TAAssignmentExtra ID of last inserted LA1 record is: " . $taassignmentextraid;

                 $pantherID = $laassignment1;
                 $status = 'Approved';
                 updateapplicationstatus($db,$termid,$pantherID,$status);

                 $i=$i+1;
             }
             if($i<=$m_lanumber && $i<=$lanumber)
             {
                 $extraid=$_SESSION["taassignmentextraidla2"];
                 echo 'taassignmentextraidla2:'.$extraid;
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
                 echo "TAAssignmentExtra ID data LA 2 update: " . $extraid;

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
                 echo "TAAssignmentExtra ID of last inserted LA2 record is: " . $taassignmentextraid;

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
                 echo "TAAssignmentExtra ID data LA 3 update: " . $extraid;

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
                 echo "TAAssignmentExtra ID of last inserted LA3 record is: " . $taassignmentextraid;

                 $pantherID = $laassignment3;
                 $status = 'Approved';
                 updateapplicationstatus($db,$termid,$pantherID,$status);

                 $i=$i+1;
             }
             if($i<=$m_lanumber && $i<=$lanumber)
             {
                 $extraid=$_SESSION["taassignmentextraidla4"];
                 $currpantherid = getcurpantherid($db,$extraid);
                 $sql = "UPDATE tbl_taassignment_extra
                        set TAAssignmentID='$id',Assignment = '$assignment',Instance='$i',PantherID='$laassignment4'
                        where TAAssignmentExtraID = $extraid";
                 echo $sql .'<br>';
                 $result = mysqli_query($db,$sql);
                 if (!$result)
                 {
                     echo("Error description: " . mysqli_error($db));
                 }
                 echo "TAAssignmentExtra ID data LA 4 update: " . $extraid;

                 $pantherID = $laassignment4;
                 $status = 'Approved';
                 updateapplicationstatus($db,$termid,$pantherID,$status);

                 $i=$i+1;
             }
             else if ($i>$m_lanumber && $i<=$lanumber)
             {
                 $sql = "INSERT INTO tbl_taassignment_extra(TAAssignmentID,Assignment,Instance,PantherID)
                      VALUES('$id','$assignment',$i,'$laassignment4')";
                 echo $sql .'<br>';
                 $result = mysqli_query($db,$sql);
                 $taassignmentextraid = mysqli_insert_id($db);
                 echo "TAAssignmentExtra ID of last inserted LA4 record is: " . $taassignmentextraid;

                 $pantherID = $laassignment4;
                 $status = 'Approved';
                 updateapplicationstatus($db,$termid,$pantherID,$status);

                 $i=$i+1;
             }
             if($i<=$m_lanumber && $i<=$lanumber)
             {
                 $extraid=$_SESSION["taassignmentextraidla5"];
                 $currpantherid = getcurpantherid($db,$extraid);
                 $sql = "UPDATE tbl_taassignment_extra
                        set TAAssignmentID='$id',Assignment = '$assignment',Instance='$i',PantherID='$laassignment5'
                        where TAAssignmentExtraID = $extraid";
                 echo $sql .'<br>';
                 $result = mysqli_query($db,$sql);
                 if (!$result)
                 {
                     echo("Error description: " . mysqli_error($db));
                 }
                 echo "TAAssignmentExtra ID data LA 5 update: " . $extraid;

                 $pantherID = $laassignment5;
                 $status = 'Approved';
                 updateapplicationstatus($db,$termid,$pantherID,$status);

                 $i=$i+1;
             }
             else if ($i>$m_lanumber && $i<=$lanumber)
             {
                 $sql = "INSERT INTO tbl_taassignment_extra(TAAssignmentID,Assignment,Instance,PantherID)
                      VALUES('$id','$assignment',$i,'$laassignment5')";
                 echo $sql .'<br>';
                 $result = mysqli_query($db,$sql);
                 $taassignmentextraid = mysqli_insert_id($db);
                 echo "TAAssignmentExtra ID of last inserted LA5 record is: " . $taassignmentextraid;

                 $pantherID = $laassignment5;
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
                 echo "TAAssignmentExtra ID data LA 1 update: " . $extraid;

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
                 echo "TAAssignmentExtra ID data LA 2 update: " . $extraid;

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
                 echo "TAAssignmentExtra ID data LA 3 update: " . $extraid;

                 $pantherID = $laassignment3;
                 $status = 'Approved';
                 updateapplicationstatus($db,$termid,$pantherID,$status);

                 $i=$i+1;
             }
             if($i<=$m_lanumber)
             {
                 $extraid=$_SESSION["taassignmentextraidla4"];
                 $currpantherid = getcurpantherid($db,$extraid);
                 $sql = "UPDATE tbl_taassignment_extra
                        set TAAssignmentID='$id',Assignment = '$assignment',Instance='$i',PantherID='$laassignment4'
                        where TAAssignmentExtraID = $extraid";
                 echo $sql .'<br>';
                 $result = mysqli_query($db,$sql);
                 if (!$result)
                 {
                     echo("Error description: " . mysqli_error($db));
                 }
                 echo "TAAssignmentExtra ID data LA 4 update: " . $extraid;

                 $pantherID = $laassignment4;
                 $status = 'Approved';
                 updateapplicationstatus($db,$termid,$pantherID,$status);

                 $i=$i+1;
             }
             if($i<=$m_lanumber)
             {
                 $extraid=$_SESSION["taassignmentextraidla5"];
                 $currpantherid = getcurpantherid($db,$extraid);
                 $sql = "UPDATE tbl_taassignment_extra
                        set TAAssignmentID='$id',Assignment = '$assignment',Instance='$i',PantherID='$laassignment5'
                        where TAAssignmentExtraID = $extraid";
                 echo $sql .'<br>';
                 $result = mysqli_query($db,$sql);
                 if (!$result)
                 {
                     echo("Error description: " . mysqli_error($db));
                 }
                 echo "TAAssignmentExtra ID data LA 5 update: " . $extraid;

                 $pantherID = $laassignment5;
                 $status = 'Approved';
                 updateapplicationstatus($db,$termid,$pantherID,$status);

                 $i=$i+1;
             }
         }
         else if($m_lanumber > $lanumber)
         {
             //update and delete
             echo "update and delete <br>";
             $i=5;
             //5
             if($i<=$m_tanumber && $i<=$tanumber)
             {
                 $extraid=$_SESSION["taassignmentextraidla5"];
                 $currpantherid = getcurpantherid($db,$extraid);
                 $sql = "UPDATE tbl_taassignment_extra
                        set TAAssignmentID='$id',Assignment = '$assignment',Instance='$i',PantherID='$laassignment5'
                        where TAAssignmentExtraID = $extraid";
                 echo $sql .'<br>';
                 $result = mysqli_query($db,$sql);
                 if (!$result)
                 {
                     echo("Error description: " . mysqli_error($db));
                 }
                 echo "TAAssignmentExtra ID data LA 5 update: " . $extraid;

                 $pantherID = $laassignment5;
                 $status = 'Approved';
                 updateapplicationstatus($db,$termid,$pantherID,$status);

             }
             else if ($i<=$m_lanumber && $i>$lanumber)
             {
                 $extraid=$_SESSION["taassignmentextraidla5"];
                 $currpantherid = getcurpantherid($db,$extraid);
                 $sql = "delete from tbl_taassignment_extra where TAAssignmentExtraID = $extraid";
                 echo $sql .'<br>';
                 $result = mysqli_query($db,$sql);
                 if (!$result)
                 {
                     echo("Error description: " . mysqli_error($db));
                 }
                 echo "TAAssignmentExtra ID of last delete LA5 record is: " . $extraid;
             }
             //4
             $i=$i-1;
             if($i<=$m_lanumber && $i<=$lanumber)
             {
                 $extraid=$_SESSION["taassignmentextraidla4"];
                 $currpantherid = getcurpantherid($db,$extraid);
                 $sql = "UPDATE tbl_taassignment_extra
                        set TAAssignmentID='$id',Assignment = '$assignment',Instance='$i',PantherID='$laassignment4'
                        where TAAssignmentExtraID = $extraid";
                 echo $sql .'<br>';
                 $result = mysqli_query($db,$sql);
                 if (!$result)
                 {
                     echo("Error description: " . mysqli_error($db));
                 }
                 echo "TAAssignmentExtra ID data LA 4 update: " . $extraid;

                 $pantherID = $laassignment4;
                 $status = 'Approved';
                 updateapplicationstatus($db,$termid,$pantherID,$status);

             }
             else if ($i<=$m_lanumber && $i>$lanumber)
             {
                 $extraid=$_SESSION["taassignmentextraidla4"];
                 $currpantherid = getcurpantherid($db,$extraid);
                 $sql = "delete from tbl_taassignment_extra where TAAssignmentExtraID = $extraid";
                 echo $sql .'<br>';
                 $result = mysqli_query($db,$sql);
                 echo "TAAssignmentExtra ID of last delete LA4 record is: " . $extraid;
             }
             //3
             $i=$i-1;
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
                 echo "TAAssignmentExtra ID data LA 3 update: " . $extraid;

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
                 echo "TAAssignmentExtra ID of last delete LA3 record is: " . $extraid;
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
                 echo "TAAssignmentExtra ID data LA 2 update: " . $extraid;

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
                 echo "TAAssignmentExtra ID of last delete LA2 record is: " . $extraid;
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
                 echo "TAAssignmentExtra ID data LA 1 update: " . $extraid;

                 $pantherID = $laassignment1;
                 $status = 'Approved';
                 updateapplicationstatus($db,$termid,$pantherID,$status);
             }
             else if ($i<=$m_lanumber && $i > $lanumber)
             {
                 $extraid=$_SESSION["taassignmentextraidla1"];
                 $currpantherid = getcurpantherid($db,$extraid);
                 $sql = "delete from tbl_taassignment_extra where TAAssignmentExtraID = $extraid";
                 echo $sql .'<br>';
                 $result = mysqli_query($db,$sql);
                 echo "TAAssignmentExtra ID of last delete LA1 record is: " . $extraid;
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
                                te.TAAssignmentExtraID as taassignmentextraid,
                                te.Assignment as assignment,
                                te.Instance as instance,
                                te.PantherID as pantherid,
                                sc.Termid as Termid
                                FROM tbl_taassignment_info as ti
                                left join tbl_taassignment_extra as te on te.TAAssignmentID = ti.TAAssignmentID
                                left join tbl_schedule as sc on sc.Courseid = ti.CourseID and sc.Instance =1
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
                        $taassignmentextraid = $row["taassignmentextraid"];
                        $assignment = $row["assignment"];
                        $instance = $row["instance"];
                        $pantherid = $row["pantherid"];
                        $termid = $row["Termid"];
                        switch ($assignment)
                        {
                            case "TA":
                                switch ($instance)
                                {
                                    case "1":
                                        $taassignmentextraidta1 = $taassignmentextraid;
                                        $taassignment1 = $pantherid;
                                        break;
                                    case "2":
                                        $taassignmentextraidta2 = $taassignmentextraid;
                                        $taassignment2 = $pantherid;
                                        break;
                                    case "3":
                                        $taassignmentextraidta3 = $taassignmentextraid;
                                        $taassignment3 = $pantherid;
                                        break;
                                    case "4":
                                        $taassignmentextraidta4 = $taassignmentextraid;
                                        $taassignment4 = $pantherid;
                                        break;
                                    case "5":
                                        $taassignmentextraidta5 = $taassignmentextraid;
                                        $taassignment5 = $pantherid;
                                        break;
                                    default:
                                        break;
                                }
                                break;
                            case "GA":
                                switch ($instance)
                                {
                                    case "1":
                                        $taassignmentextraidga1 = $taassignmentextraid;
                                        $gaassignment1 = $pantherid;
                                        break;
                                    case "2":
                                        $taassignmentextraidga2 = $taassignmentextraid;
                                        $gaassignment2 = $pantherid;
                                        break;
                                    case "3":
                                        $taassignmentextraidga3 = $taassignmentextraid;
                                        $gaassignment3 = $pantherid;
                                        break;
                                    case "4":
                                        $taassignmentextraidga4 = $taassignmentextraid;
                                        $gaassignment4 = $pantherid;
                                        break;
                                    case "5":
                                        $taassignmentextraidga5 = $taassignmentextraid;
                                        $gaassignment5 = $pantherid;
                                        break;
                                    default:
                                        break;
                                }
                                break;
                            case "LA":
                                switch ($instance)
                                {
                                    case "1":
                                        $taassignmentextraidla1 = $taassignmentextraid;
                                        $laassignment1 = $pantherid;
                                        break;
                                    case "2":
                                        $taassignmentextraidla2 = $taassignmentextraid;
                                        $laassignment2 = $pantherid;
                                        break;
                                    case "3":
                                        $taassignmentextraidla3 = $taassignmentextraid;
                                        $laassignment3 = $pantherid;
                                        break;
                                    case "4":
                                        $taassignmentextraidla4 = $taassignmentextraid;
                                        $laassignment4 = $pantherid;
                                        break;
                                    case "5":
                                        $taassignmentextraidla5 = $taassignmentextraid;
                                        $laassignment5 = $pantherid;
                                        break;
                                    default:
                                        break;
                                }
                                break;
                            default:
                                break;
                        }
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
                        if($i<=$tanumber)
                        {
                            $_SESSION["taassignmentextraidta4"] = $taassignmentextraidta4;
                            $i=$i+1;
                        }
                        if($i<=$tanumber)
                        {
                            $_SESSION["taassignmentextraidta5"] = $taassignmentextraidta5;
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
                        if($i<=$ganumber)
                        {
                            $_SESSION["taassignmentextraidga4"] = $taassignmentextraidga4;
                            $i=$i+1;
                        }
                        if($i<=$ganumber)
                        {
                            $_SESSION["taassignmentextraidga5"] = $taassignmentextraidga5;
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
                        if($i<=$lanumber)
                        {
                            $_SESSION["taassignmentextraidla4"] = $taassignmentextraidla4;
                            $i=$i+1;
                        }
                        if($i<=$lanumber)
                        {
                            $_SESSION["taassignmentextraidla5"] = $taassignmentextraidla5;
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
<html>
<!-- Header -->
<head>
    <?php
    include $root.'/links/header.php';
    include $root.'/links/footerLinks.php';
    ?>
    <script type="text/javascript" src="carjava.js"></script>
</head>
<!-- /#Header -->
<body onload="init();">
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
                                if ($p_Termid == $termid) {
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
                    <label><input type="radio" name="tanumber" class="radio" value="0" onChange="ChangeTANumber(this)"  <?php if(empty($id)==false){ if ($tanumber=='0'){echo  'checked';}}else{{echo  'checked';}} ?> >0</label>
                    <label><input type="radio" name="tanumber" class="radio" value="1" onChange="ChangeTANumber(this)" <?php if(empty($id)==false){ if ($tanumber=='1'){echo  'checked';}} ?> >1</label>
                    <label><input type="radio" name="tanumber" class="radio" value="2" onChange="ChangeTANumber(this)" <?php if(empty($id)==false){ if ($tanumber=='2'){echo  'checked';}} ?> >2</label>
                    <label><input type="radio" name="tanumber" class="radio" value="3" onChange="ChangeTANumber(this)" <?php if(empty($id)==false){ if ($tanumber=='3'){echo  'checked';}} ?> >3</label>
                    <label><input type="radio" name="tanumber" class="radio" value="4" onChange="ChangeTANumber(this)" <?php if(empty($id)==false){ if ($tanumber=='4'){echo  'checked';}} ?> >4</label>
                    <label><input type="radio" name="tanumber" class="radio" value="5" onChange="ChangeTANumber(this)" <?php if(empty($id)==false){ if ($tanumber=='5'){echo  'checked';}} ?> >5</label>
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
            <tr name ="taall4">
                <td name ="talabel4" id="talabel4" >Fourth Tutor:</td>
                <td>
                    <a href="#" class="hover" data-id = "" id="tanamelabel4" name="tanamelabel4"></a>
                </td>
                <td ALIGN="center">
                    <input type="text"  data-id="" name="tanametext4" id="tanametext4" value="" >
                </td>
            </tr>
            <tr name ="taall4_2">
                <td name ="" id="" ></td>
                <td ALIGN="center"  name ="tanameorderlabel4_1" id="tanameorderlabel4_1" >Order by Course,GPA</td>
                <td ALIGN="center"  name ="tanameorderlabel4_2" id="tanameorderlabel4_2" >Order by Course,GPA</td>
            </tr>
            <tr name ="taall4_3">
                <td name ="" id="" ></td>
                <td ALIGN="center">
                    <select name="taassignment4" id="taassignment4" style="display: inherit"  size="10" >
                    </select>
                </td>
                <td ALIGN="center">
                    <select name="taassignment4_order2" id="taassignment4_order2"  style="display: inherit" size="10" >
                    </select>
                </td>
            </tr>
            <tr name ="taall5">
                <td name ="talabel5" id="talabel5" >Fifth Tutor:</td>
                <td>
                    <a href="#" class="hover" data-id = "" id="tanamelabel5" name="tanamelabel5"></a>
                </td>
                <td ALIGN="center">
                    <input type="text"  data-id="" name="tanametext5" id="tanametext5" value="" >
                </td>
            </tr>
            <tr name ="taall5_2">
                <td name ="" id="" ></td>
                <td ALIGN="center"  name ="tanameorderlabel5_1" id="tanameorderlabel5_1" >Order by Course,GPA</td>
                <td ALIGN="center"  name ="tanameorderlabel5_2" id="tanameorderlabel5_2" >Order by Course,GPA</td>
            </tr>
            <tr name ="taall5_5">
                <td name ="" id="" ></td>
                <td ALIGN="center">
                    <select name="taassignment5" id="taassignment5" style="display: inherit"  size="10" >
                    </select>
                </td>
                <td ALIGN="center">
                    <select name="taassignment5_order2" id="taassignment5_order2"  style="display: inherit" size="10" >
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
                    <label><input type="radio" name="ganumber" class="radio" value="4" onChange="ChangeGANumber(this)" <?php if(empty($id)==false){ if ($ganumber=='4'){echo  'checked';}} ?> >4</label>
                    <label><input type="radio" name="ganumber" class="radio" value="5" onChange="ChangeGANumber(this)" <?php if(empty($id)==false){ if ($ganumber=='5'){echo  'checked';}} ?> >5</label>
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
            <tr name ="gaall4">
                <td name ="galabel4" id="galabel4" >Fourth Grader:</td>
                <td>
                    <a href="#" class="hover" data-id = "" id="ganamelabel4" name="ganamelabel4"></a>
                </td>
                <td ALIGN="center">
                    <input type="text"  data-id="" name="ganametext4" id="ganametext4" value="" >
                </td>
            </tr>
            <tr name ="gaall4_2">
                <td name ="" id="" ></td>
                <td ALIGN="center"  name ="ganameorderlabel4_1" id="ganameorderlabel4_1" >Order by Course,GPA</td>
                <td ALIGN="center"  name ="ganameorderlabel4_2" id="ganameorderlabel4_2" >Order by Course,GPA</td>
            </tr>
            <tr name ="gaall4_4">
                <td name ="" id="" ></td>
                <td ALIGN="center">
                    <select name="gaassignment4" id="gaassignment4" style="display: inherit"  size="10" >
                    </select>
                </td>
                <td ALIGN="center">
                    <select name="gaassignment4_order2" id="gaassignment4_order2"  style="display: inherit" size="10" >
                    </select>
                </td>
            </tr>
            <tr name ="gaall5">
                <td name ="galabel5" id="galabel5" >Fifth Grader:</td>
                <td>
                    <a href="#" class="hover" data-id = "" id="ganamelabel5" name="ganamelabel5"></a>
                </td>
                <td ALIGN="center">
                    <input type="text"  data-id="" name="ganametext5" id="ganametext5" value="" >
                </td>
            </tr>
            <tr name ="gaall5_2">
                <td name ="" id="" ></td>
                <td ALIGN="center"  name ="ganameorderlabel5_1" id="ganameorderlabel5_1" >Order by Course,GPA</td>
                <td ALIGN="center"  name ="ganameorderlabel5_2" id="ganameorderlabel5_2" >Order by Course,GPA</td>
            </tr>
            <tr name ="gaall5_5">
                <td name ="" id="" ></td>
                <td ALIGN="center">
                    <select name="gaassignment5" id="gaassignment5" style="display: inherit"  size="10" >
                    </select>
                </td>
                <td ALIGN="center">
                    <select name="gaassignment5_order2" id="gaassignment5_order2"  style="display: inherit" size="10" >
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
                    <label><input type="radio" name="lanumber" class="radio" value="4" onChange="ChangeLANumber(this)" <?php if(empty($id)==false){ if ($lanumber=='4'){echo  'checked';}} ?> >4</label>
                    <label><input type="radio" name="lanumber" class="radio" value="5" onChange="ChangeLANumber(this)" <?php if(empty($id)==false){ if ($lanumber=='5'){echo  'checked';}} ?> >5</label>
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
            <tr name ="laall4">
                <td name ="lalabel4" id="lalabel4"  >Fourth Lab Instructor:</td>
                <td>
                    <a href="#" class="hover" data-id = "" id="lanamelabel4" name="lanamelabel4"></a>
                </td>
                <td ALIGN="center">
                    <input type="text"  data-id="" name="lanametext4" id="lanametext4" value="" >
                </td>
            </tr>
            <tr name ="laall4_2">
                <td name ="" id="" ></td>
                <td ALIGN="center"  name ="lanameorderlabel4_1" id="lanameorderlabel4_1" >Order by Course,GPA</td>
                <td ALIGN="center"  name ="lanameorderlabel4_2" id="lanameorderlabel4_2" >Order by Course,GPA</td>
            </tr>
            <tr name ="laall4_4">
                <td name ="" id="" ></td>
                <td ALIGN="center">
                    <select name="laassignment4" id="laassignment4" style="display: inherit"  size="10" >
                </td>
                <td ALIGN="center">
                    <select name="laassignment4_order2" id="laassignment4_order2"  style="display: inherit" size="10" >
                    </select>
                </td>
            </tr>
            <tr name ="laall5">
                <td name ="lalabel5" id="lalabel5"  >Fifth Lab Instructor:</td>
                <td>
                    <a href="#" class="hover" data-id = "" id="lanamelabel5" name="lanamelabel5"></a>
                </td>
                <td ALIGN="center">
                    <input type="text"  data-id="" name="lanametext5" id="lanametext5" value="" >
                </td>
            </tr>
            <tr name ="laall5_2">
                <td name ="" id="" ></td>
                <td ALIGN="center"  name ="lanameorderlabel5_1" id="lanameorderlabel5_1" >Order by Course,GPA</td>
                <td ALIGN="center"  name ="lanameorderlabel5_2" id="lanameorderlabel5_2" >Order by Course,GPA</td>
            </tr>
            <tr name ="laall5_5">
                <td name ="" id="" ></td>
                <td ALIGN="center">
                    <select name="laassignment5" id="laassignment5" style="display: inherit"  size="10" >
                </td>
                <td ALIGN="center">
                    <select name="laassignment5_order2" id="laassignment5_order2"  style="display: inherit" size="10" >
                    </select>
                </td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" name="register_btn" class="Register" value="Submit"></td>
                <td><input type="submit" name="registerunsafe_btn" class="Register" value="Submit" style=" color: red; "  title="It's an unsafe operation!"></td>
            </tr>
 
        </table>
        </form>
    </div>
</body>
</html>

<script  type="text/javascript">

    function ChangeTANumber(selectObject){
        var pvalue =document.querySelector('input[name="tanumber"]:checked').value;
        //var pvalue=document.GTAregister.assignment.options[document.GTAregister.assignment.selectedIndex].label;
        //window.alert(pvalue.toString());
        var statusarray = new Array(5);
        if(pvalue.toString() == "0")
        {
            statusarray = ['none', 'none', 'none','none','none'];
            TASeriousStatus(statusarray);
            //window.alert("hidden");
            // window.alert(document.GTAregister.course.style.display);
        }
        else if(pvalue.toString() == "1")
        {
            //window.alert("visible");
            statusarray = ['', 'none', 'none','none','none'];
            TASeriousStatus(statusarray);
        }
        else if(pvalue.toString() == "2")
        {
            //window.alert("visible");
            statusarray = ['', '', 'none','none','none'];
            TASeriousStatus(statusarray);
        }
        else if(pvalue.toString() == "3")
        {
            //window.alert("visible");
            statusarray = ['', '', '','none','none'];
            TASeriousStatus(statusarray);
        }
        else if(pvalue.toString() == "4") {
            //window.alert("visible");
            statusarray = ['', '', '', '', 'none'];
            TASeriousStatus(statusarray);
        }
        else if(pvalue.toString() == "5") {
            //window.alert("visible");
            statusarray = ['', '', '', '', ''];
            TASeriousStatus(statusarray);
        }
    }

    function TASeriousStatus(statusarray) {

        document.taassignmentregister.taassignment1.style.display  = statusarray[0];
        document.taassignmentregister.taassignment2.style.display  = statusarray[1];
        document.taassignmentregister.taassignment3.style.display  = statusarray[2];
        document.taassignmentregister.taassignment4.style.display  = statusarray[3];
        document.taassignmentregister.taassignment5.style.display  = statusarray[4];

        document.taassignmentregister.taassignment1_order2.style.display  = statusarray[0];
        document.taassignmentregister.taassignment2_order2.style.display  = statusarray[1];
        document.taassignmentregister.taassignment3_order2.style.display  = statusarray[2];
        document.taassignmentregister.taassignment4_order2.style.display  = statusarray[3];
        document.taassignmentregister.taassignment5_order2.style.display  = statusarray[4];

        document.getElementById("talabel1").style.display  = statusarray[0];
        document.getElementById("talabel2").style.display  = statusarray[1];
        document.getElementById("talabel3").style.display  = statusarray[2];
        document.getElementById("talabel4").style.display  = statusarray[3];
        document.getElementById("talabel5").style.display  = statusarray[4];

        document.getElementById("tanamelabel1").style.display  = statusarray[0];
        document.getElementById("tanamelabel2").style.display  = statusarray[1];
        document.getElementById("tanamelabel3").style.display  = statusarray[2];
        document.getElementById("tanamelabel4").style.display  = statusarray[3];
        document.getElementById("tanamelabel5").style.display  = statusarray[4];

        document.getElementById("tanametext1").style.display  = statusarray[0];
        document.getElementById("tanametext2").style.display  = statusarray[1];
        document.getElementById("tanametext3").style.display  = statusarray[2];
        document.getElementById("tanametext4").style.display  = statusarray[3];
        document.getElementById("tanametext5").style.display  = statusarray[4];

        document.getElementById("tanameorderlabel1_1").style.display  = statusarray[0];
        document.getElementById("tanameorderlabel2_1").style.display  = statusarray[1];
        document.getElementById("tanameorderlabel3_1").style.display  = statusarray[2];
        document.getElementById("tanameorderlabel4_1").style.display  = statusarray[3];
        document.getElementById("tanameorderlabel5_1").style.display  = statusarray[4];

        document.getElementById("tanameorderlabel1_2").style.display  = statusarray[0];
        document.getElementById("tanameorderlabel2_2").style.display  = statusarray[1];
        document.getElementById("tanameorderlabel3_2").style.display  = statusarray[2];
        document.getElementById("tanameorderlabel4_2").style.display  = statusarray[3];
        document.getElementById("tanameorderlabel5_2").style.display  = statusarray[4];
    }

    function ChangeGANumber(selectObject){
        //var pvalue =document.taassignmentregister.ganumber.value;
        var pvalue =document.querySelector('input[name="ganumber"]:checked').value;
        //var pvalue=document.GTAregister.assignment.options[document.GTAregister.assignment.selectedIndex].label;
        //window.alert(pvalue.toString());
        var statusarray = new Array(5);
        if(pvalue.toString() == "0")
        {
            //window.alert("hidden");
            // window.alert(document.GTAregister.course.style.display);
            statusarray = ['none', 'none', 'none', 'none', 'none'];
            GASeriousStatus(statusarray);
        }
        else if(pvalue.toString() == "1")
        {
            //window.alert("visible");
            statusarray = ['', 'none', 'none', 'none', 'none'];
            GASeriousStatus(statusarray);

        }
        else if(pvalue.toString() == "2")
        {
            //window.alert("visible");
            statusarray = ['', '', 'none', 'none', 'none'];
            GASeriousStatus(statusarray);
        }
        else if(pvalue.toString() == "3")
        {
            //window.alert("visible");
            statusarray = ['', '', '', 'none', 'none'];
            GASeriousStatus(statusarray);
        }
        else if(pvalue.toString() == "4")
        {
            //window.alert("visible");
            statusarray = ['', '', '', '', 'none'];
            GASeriousStatus(statusarray);
        }
        else if(pvalue.toString() == "5")
        {
            //window.alert("visible");
            statusarray = ['', '', '', '', ''];
            GASeriousStatus(statusarray);
        }
    }

    function GASeriousStatus(statusarray) {

        document.taassignmentregister.gaassignment1.style.display  = statusarray[0];
        document.taassignmentregister.gaassignment2.style.display  = statusarray[1];
        document.taassignmentregister.gaassignment3.style.display  = statusarray[2];
        document.taassignmentregister.gaassignment4.style.display  = statusarray[3];
        document.taassignmentregister.gaassignment5.style.display  = statusarray[4];

        document.taassignmentregister.gaassignment1_order2.style.display  = statusarray[0];
        document.taassignmentregister.gaassignment2_order2.style.display  = statusarray[1];
        document.taassignmentregister.gaassignment3_order2.style.display  = statusarray[2];
        document.taassignmentregister.gaassignment4_order2.style.display  = statusarray[3];
        document.taassignmentregister.gaassignment5_order2.style.display  = statusarray[4];

        document.getElementById("galabel1").style.display  = statusarray[0];
        document.getElementById("galabel2").style.display  = statusarray[1];
        document.getElementById("galabel3").style.display  = statusarray[2];
        document.getElementById("galabel4").style.display  = statusarray[3];
        document.getElementById("galabel5").style.display  = statusarray[4];

        document.getElementById("ganamelabel1").style.display  = statusarray[0];
        document.getElementById("ganamelabel2").style.display  = statusarray[1];
        document.getElementById("ganamelabel3").style.display  = statusarray[2];
        document.getElementById("ganamelabel4").style.display  = statusarray[3];
        document.getElementById("ganamelabel5").style.display  = statusarray[4];

        document.getElementById("ganametext1").style.display  = statusarray[0];
        document.getElementById("ganametext2").style.display  = statusarray[1];
        document.getElementById("ganametext3").style.display  = statusarray[2];
        document.getElementById("ganametext4").style.display  = statusarray[3];
        document.getElementById("ganametext5").style.display  = statusarray[4];

        document.getElementById("ganameorderlabel1_1").style.display  = statusarray[0];
        document.getElementById("ganameorderlabel2_1").style.display  = statusarray[1];
        document.getElementById("ganameorderlabel3_1").style.display  = statusarray[2];
        document.getElementById("ganameorderlabel4_1").style.display  = statusarray[3];
        document.getElementById("ganameorderlabel5_1").style.display  = statusarray[4];

        document.getElementById("ganameorderlabel1_2").style.display  = statusarray[0];
        document.getElementById("ganameorderlabel2_2").style.display  = statusarray[1];
        document.getElementById("ganameorderlabel3_2").style.display  = statusarray[2];
        document.getElementById("ganameorderlabel4_2").style.display  = statusarray[3];
        document.getElementById("ganameorderlabel5_2").style.display  = statusarray[4];
    }

    function ChangeLANumber(selectObject){
        //var pvalue =document.taassignmentregister.lanumber.value;
        var pvalue =document.querySelector('input[name="lanumber"]:checked').value;
        //var pvalue=document.GTAregister.assignment.options[document.GTAregister.assignment.selectedIndex].label;
        //window.alert(pvalue.toString());
        var statusarray = new Array(5);
        if(pvalue.toString() == "0")
        {
            //window.alert("hidden");
            // window.alert(document.GTAregister.course.style.display);
            statusarray = ['none', 'none', 'none', 'none', 'none'];
            LASeriousStatus(statusarray);
        }
        else if(pvalue.toString() == "1")
        {
            //window.alert("visible");
            statusarray = ['', 'none', 'none', 'none', 'none'];
            LASeriousStatus(statusarray);
        }
        else if(pvalue.toString() == "2")
        {
            //window.alert("visible");
            statusarray = ['', '', 'none', 'none', 'none'];
            LASeriousStatus(statusarray);
        }
        else if(pvalue.toString() == "3")
        {
            //window.alert("visible");
            statusarray = ['', '', '', 'none', 'none'];
            LASeriousStatus(statusarray);
        }
        else if(pvalue.toString() == "4")
        {
            //window.alert("visible");
            statusarray = ['', '', '', '', 'none'];
            LASeriousStatus(statusarray);
        }
        else if(pvalue.toString() == "5")
        {
            //window.alert("visible");
            statusarray = ['', '', '', '', ''];
            LASeriousStatus(statusarray);
        }
    }

    function LASeriousStatus(statusarray) {

        document.taassignmentregister.laassignment1.style.display  = statusarray[0];
        document.taassignmentregister.laassignment2.style.display  = statusarray[1];
        document.taassignmentregister.laassignment3.style.display  = statusarray[2];
        document.taassignmentregister.laassignment4.style.display  = statusarray[3];
        document.taassignmentregister.laassignment5.style.display  = statusarray[4];

        document.taassignmentregister.laassignment1_order2.style.display  = statusarray[0];
        document.taassignmentregister.laassignment2_order2.style.display  = statusarray[1];
        document.taassignmentregister.laassignment3_order2.style.display  = statusarray[2];
        document.taassignmentregister.laassignment4_order2.style.display  = statusarray[3];
        document.taassignmentregister.laassignment5_order2.style.display  = statusarray[4];

        document.getElementById("lalabel1").style.display  = statusarray[0];
        document.getElementById("lalabel2").style.display  = statusarray[1];
        document.getElementById("lalabel3").style.display  = statusarray[2];
        document.getElementById("lalabel4").style.display  = statusarray[3];
        document.getElementById("lalabel5").style.display  = statusarray[4];

        document.getElementById("lanamelabel1").style.display  = statusarray[0];
        document.getElementById("lanamelabel2").style.display  = statusarray[1];
        document.getElementById("lanamelabel3").style.display  = statusarray[2];
        document.getElementById("lanamelabel4").style.display  = statusarray[3];
        document.getElementById("lanamelabel5").style.display  = statusarray[4];

        document.getElementById("lanametext1").style.display  = statusarray[0];
        document.getElementById("lanametext2").style.display  = statusarray[1];
        document.getElementById("lanametext3").style.display  = statusarray[2];
        document.getElementById("lanametext4").style.display  = statusarray[3];
        document.getElementById("lanametext5").style.display  = statusarray[4];

        document.getElementById("lanameorderlabel1_1").style.display  = statusarray[0];
        document.getElementById("lanameorderlabel2_1").style.display  = statusarray[1];
        document.getElementById("lanameorderlabel3_1").style.display  = statusarray[2];
        document.getElementById("lanameorderlabel4_1").style.display  = statusarray[3];
        document.getElementById("lanameorderlabel5_1").style.display  = statusarray[4];

        document.getElementById("lanameorderlabel1_2").style.display  = statusarray[0];
        document.getElementById("lanameorderlabel2_2").style.display  = statusarray[1];
        document.getElementById("lanameorderlabel3_2").style.display  = statusarray[2];
        document.getElementById("lanameorderlabel4_2").style.display  = statusarray[3];
        document.getElementById("lanameorderlabel5_2").style.display  = statusarray[4];
    }

        $('.hovercourse').tooltip({
            title: fetchcourseData,
            html: true,
            placement: 'right'
        });

        function fetchcourseData()
        {
            var fetch_data = '';
            var element = $(this);
            var id = element.attr("data-id");
            $.ajax({
                url:"taassignmentregisterfetchcourse.php",
                method:"POST",
                async: false,
                data:{id:id},
                success:function(data)
                {
                    fetch_data = data;
                }
            });
            return fetch_data;
        }

        $('.hover').tooltip({
            title: fetchData,
            html: true,
            placement: 'right'
        });

        function fetchData()
        {
            var fetch_data = '';
            var element = $(this);
            var term =document.getElementById('termid');
            var selecttermid = term.options[term.selectedIndex].value;
            //alert(selecttermid);
            var id = element.attr("data-id");
            $.ajax({
                url:"taassignmentregisterfetchapplicant.php",
                method:"POST",
                async: false,
                data:{id:id,termid:selecttermid},
                success:function(data)
                {
                    fetch_data = data;
                }
            });
            return fetch_data;
        }

        function getoutput(assistantarray,proritytarray,assistantapprovedarray,m_id,selectedvalue)
        {
            var output = '';
//alert(selectedvalue);
            output=output+"<optgroup label='Prority Applicant list'></optgroup>";
            for (var key in proritytarray)
            {
                var GAApplicationID = proritytarray[key].GAApplicationID;
                var PantherID = proritytarray[key].PantherID;
                var Name = proritytarray[key].Name;
                var email = proritytarray[key].email;
                var prority = proritytarray[key].prority;
                var CurrentGPA = proritytarray[key].CurrentGPA;
                var degree = proritytarray[key].degree;
                output=output+"<option value='"+PantherID+"'";
                if(!(isEmpty(m_id)))
                {
                    if(PantherID==selectedvalue)
                    {
                        output=output+" selected ";
                    }
                }
                output=output+" >"+Name+"("+degree+")"+"</option>";
            }

            //alert(output);
            output=output+"<optgroup label='Other Applicant list'></optgroup>";
            for (var key in assistantarray)
            {
                var GAApplicationID = assistantarray[key].GAApplicationID;
                var PantherID = assistantarray[key].PantherID;
                var Name = assistantarray[key].Name;
                var email = assistantarray[key].email;
                var prority = assistantarray[key].prority;
                var CurrentGPA = assistantarray[key].CurrentGPA;
                var degree = assistantarray[key].degree;
                var Issame = false;
                for (var m_key in proritytarray)
                {
                    var m_PantherID = proritytarray[m_key].PantherID;
                    if(m_PantherID==PantherID)
                    {
                        Issame=true;
                        //alert()
                    }
                }
                for (var m_key in assistantapprovedarray)
                {
                    var m_PantherID = assistantapprovedarray[m_key].PantherID;
                    if(m_PantherID==PantherID)
                    {
                        Issame=true;
                        //alert()
                    }
                }
                if(Issame==false)
                {
                    output = output + "<option value='" + PantherID + "'";
                    if(!(isEmpty(m_id)))
                    {
                        if(PantherID==selectedvalue)
                        {
                            output=output+" selected ";
                        }
                    }
                    output = output + " >" + Name + "(" + degree + ")" + "</option>";
                }
            }

            output=output+"<optgroup label='Approved Applicant list'></optgroup>";
            for (var key in assistantapprovedarray)
            {
                var GAApplicationID = assistantapprovedarray[key].GAApplicationID;
                var PantherID = assistantapprovedarray[key].PantherID;
                var Name = assistantapprovedarray[key].Name;
                var email = assistantapprovedarray[key].email;
                var prority = assistantapprovedarray[key].prority;
                var CurrentGPA = assistantapprovedarray[key].CurrentGPA;
                var degree = assistantapprovedarray[key].degree;
                output=output+"<option value='"+PantherID+"'";
                if(!(isEmpty(m_id)))
                {
                    if(PantherID==selectedvalue)
                    {
                        output=output+" selected ";
                    }
                }
                output=output+" >"+Name+"("+degree+")"+"</option>";
            }
            output=output+"<optgroup label='NULL list'></optgroup>";
            output=output+"<option value='' >NULL</option>";
            //alert(output);
            return output;
        }

        $("#course").change(function() {
            //$('#help-text').text($('option:selected').attr('data-content'));
            //$('#test2').text($('option:selected').attr('data-content'));
            //$('#test2').innerText = $('option:selected').attr('data-content');
            $selectvalue =$('option:selected',this).attr('value');
            $selectname=$('option:selected',this).text();
            //alert($selectvalue);
            //alert($selectname);
            //$('#test2').text($selectdata);
            document.getElementById('courselabel').innerText = $selectname;
            document.getElementById('courselabel').setAttribute('data-id',$selectvalue);
            //$('#test2').('data-id')=$('option:selected').attr('data-content');

            var courseid = $selectvalue;
            var term =document.getElementById('termid');
            var selecttermid = term.options[term.selectedIndex].value;
            //alert(courseid);
            //alert(selecttermid);
            //var fetch_data = new Array();
            var output ='';
            var m_id = "<?php if(empty($id)==false){echo $id;}else{echo '';} ; ?>";
            //var m_taassignment1 = "<?php if(empty($id)==false){echo $taassignment1;}else{echo '';} ; ?>";
            var proritytarray=null;
            //alert(m_taassignment1);
            $.ajax({
                url:"taassignmentfetchassistant.php", //the page containing php script
                type: "POST", //request type
                async: false,
                data:{courseid:courseid,termid:selecttermid},
                //data:{id:m_id},
                success:function(data)
                {
                    proritytarray = jQuery.parseJSON(data);


                }
            });

            var assistantapprovedarray=null;
            $.ajax({
                url:"taassignmentfetchtermassistantapproved.php", //the page containing php script
                type: "POST", //request type
                async: false,
                data:{termid:selecttermid},
                success:function(data)
                {
                    assistantapprovedarray = jQuery.parseJSON(data);

                }
            });

            var assistantarray=null;
            $.ajax({
                url:"taassignmentfetchtermassistant.php", //the page containing php script
                type: "POST", //request type
                async: false,
                data:{termid:selecttermid},
                success:function(data)
                {
                    assistantarray = jQuery.parseJSON(data);
                    //alert(data);
                }
            });

            var output ='';
            var seelctvalue ='';

            selectvalue="<?php if(empty($id)==false){echo $taassignment1;}else{echo '';} ; ?>";
            output = getoutput(assistantarray,proritytarray,assistantapprovedarray,m_id,selectvalue);
            $("#taassignment1").html(output);
            $("#taassignment1").change();

            selectvalue="<?php if(empty($id)==false){echo $taassignment2;}else{echo '';} ; ?>";
            output = getoutput(assistantarray,proritytarray,assistantapprovedarray,m_id,selectvalue);
            $("#taassignment2").html(output);
            $("#taassignment2").change();

            selectvalue="<?php if(empty($id)==false){echo $taassignment3;}else{echo '';} ; ?>";
            output = getoutput(assistantarray,proritytarray,assistantapprovedarray,m_id,selectvalue);
            //alert(output);
            $("#taassignment3").html(output);
            $("#taassignment3").change();

            selectvalue="<?php if(empty($id)==false){echo $taassignment4;}else{echo '';} ; ?>";
            output = getoutput(assistantarray,proritytarray,assistantapprovedarray,m_id,selectvalue);
            //alert(output);
            $("#taassignment4").html(output);
            $("#taassignment4").change();

            selectvalue="<?php if(empty($id)==false){echo $taassignment5;}else{echo '';} ; ?>";
            output = getoutput(assistantarray,proritytarray,assistantapprovedarray,m_id,selectvalue);
            //alert(output);
            $("#taassignment5").html(output);
            $("#taassignment5").change();

            selectvalue="<?php if(empty($id)==false){echo $gaassignment1;}else{echo '';} ; ?>";
            output = getoutput(assistantarray,proritytarray,assistantapprovedarray,m_id,selectvalue);
            $("#gaassignment1").html(output);
            $("#gaassignment1").change();

            selectvalue="<?php if(empty($id)==false){echo $gaassignment2;}else{echo '';} ; ?>";
            output = getoutput(assistantarray,proritytarray,assistantapprovedarray,m_id,selectvalue);
            $("#gaassignment2").html(output);
            $("#gaassignment2").change();

            selectvalue="<?php if(empty($id)==false){echo $gaassignment3;}else{echo '';} ; ?>";
            output = getoutput(assistantarray,proritytarray,assistantapprovedarray,m_id,selectvalue);
            $("#gaassignment3").html(output);
            $("#gaassignment3").change();

            selectvalue="<?php if(empty($id)==false){echo $gaassignment4;}else{echo '';} ; ?>";
            output = getoutput(assistantarray,proritytarray,assistantapprovedarray,m_id,selectvalue);
            $("#gaassignment4").html(output);
            $("#gaassignment4").change();

            selectvalue="<?php if(empty($id)==false){echo $gaassignment5;}else{echo '';} ; ?>";
            output = getoutput(assistantarray,proritytarray,assistantapprovedarray,m_id,selectvalue);
            $("#gaassignment5").html(output);
            $("#gaassignment5").change();

            selectvalue="<?php if(empty($id)==false){echo $laassignment1;}else{echo '';} ; ?>";
            output = getoutput(assistantarray,proritytarray,assistantapprovedarray,m_id,selectvalue);
            $("#laassignment1").html(output);
            $("#laassignment1").change();

            selectvalue="<?php if(empty($id)==false){echo $laassignment2;}else{echo '';} ; ?>";
            output = getoutput(assistantarray,proritytarray,assistantapprovedarray,m_id,selectvalue);
            $("#laassignment2").html(output);
            $("#laassignment2").change();

            selectvalue="<?php if(empty($id)==false){echo $laassignment3;}else{echo '';} ; ?>";
            output = getoutput(assistantarray,proritytarray,assistantapprovedarray,m_id,selectvalue);
            $("#laassignment3").html(output);
            $("#laassignment3").change();

            selectvalue="<?php if(empty($id)==false){echo $laassignment4;}else{echo '';} ; ?>";
            output = getoutput(assistantarray,proritytarray,assistantapprovedarray,m_id,selectvalue);
            $("#laassignment4").html(output);
            $("#laassignment4").change();

            selectvalue="<?php if(empty($id)==false){echo $laassignment5;}else{echo '';} ; ?>";
            output = getoutput(assistantarray,proritytarray,assistantapprovedarray,m_id,selectvalue);
            $("#laassignment5").html(output);
            $("#laassignment5").change();

            selectvalue="<?php if(empty($id)==false){echo $taassignment1;}else{echo '';} ; ?>";
            output = getoutput(assistantarray,proritytarray,assistantapprovedarray,m_id,selectvalue);
            $("#taassignment1_order2").html(output);
            $("#taassignment1_order2").change();

            selectvalue="<?php if(empty($id)==false){echo $taassignment2;}else{echo '';} ; ?>";
            output = getoutput(assistantarray,proritytarray,assistantapprovedarray,m_id,selectvalue);
            $("#taassignment2_order2").html(output);
            $("#taassignment2_order2").change();

            selectvalue="<?php if(empty($id)==false){echo $taassignment3;}else{echo '';} ; ?>";
            output = getoutput(assistantarray,proritytarray,assistantapprovedarray,m_id,selectvalue);
            $("#taassignment3_order2").html(output);
            $("#taassignment3_order2").change();

            selectvalue="<?php if(empty($id)==false){echo $taassignment4;}else{echo '';} ; ?>";
            output = getoutput(assistantarray,proritytarray,assistantapprovedarray,m_id,selectvalue);
            $("#taassignment4_order2").html(output);
            $("#taassignment4_order2").change();

            selectvalue="<?php if(empty($id)==false){echo $taassignment5;}else{echo '';} ; ?>";
            output = getoutput(assistantarray,proritytarray,assistantapprovedarray,m_id,selectvalue);
            $("#taassignment5_order2").html(output);
            $("#taassignment5_order2").change();

            selectvalue="<?php if(empty($id)==false){echo $gaassignment1;}else{echo '';} ; ?>";
            output = getoutput(assistantarray,proritytarray,assistantapprovedarray,m_id,selectvalue);
            $("#gaassignment1_order2").html(output);
            $("#gaassignment1_order2").change();

            selectvalue="<?php if(empty($id)==false){echo $gaassignment2;}else{echo '';} ; ?>";
            output = getoutput(assistantarray,proritytarray,assistantapprovedarray,m_id,selectvalue);
            $("#gaassignment2_order2").html(output);
            $("#gaassignment2_order2").change();

            selectvalue="<?php if(empty($id)==false){echo $gaassignment3;}else{echo '';} ; ?>";
            output = getoutput(assistantarray,proritytarray,assistantapprovedarray,m_id,selectvalue);
            $("#gaassignment3_order2").html(output);
            $("#gaassignment3_order2").change();

            selectvalue="<?php if(empty($id)==false){echo $gaassignment4;}else{echo '';} ; ?>";
            output = getoutput(assistantarray,proritytarray,assistantapprovedarray,m_id,selectvalue);
            $("#gaassignment4_order2").html(output);
            $("#gaassignment4_order2").change();

            selectvalue="<?php if(empty($id)==false){echo $gaassignment5;}else{echo '';} ; ?>";
            output = getoutput(assistantarray,proritytarray,assistantapprovedarray,m_id,selectvalue);
            $("#gaassignment5_order2").html(output);
            $("#gaassignment5_order2").change();

            selectvalue="<?php if(empty($id)==false){echo $laassignment1;}else{echo '';} ; ?>";
            output = getoutput(assistantarray,proritytarray,assistantapprovedarray,m_id,selectvalue);
            $("#laassignment1_order2").html(output);
            $("#laassignment1_order2").change();

            selectvalue="<?php if(empty($id)==false){echo $laassignment2;}else{echo '';} ; ?>";
            output = getoutput(assistantarray,proritytarray,assistantapprovedarray,m_id,selectvalue);
            $("#laassignment2_order2").html(output);
            $("#laassignment2_order2").change();

            selectvalue="<?php if(empty($id)==false){echo $laassignment3;}else{echo '';} ; ?>";
            output = getoutput(assistantarray,proritytarray,assistantapprovedarray,m_id,selectvalue);
            $("#laassignment3_order2").html(output);
            $("#laassignment3_order2").change();

            selectvalue="<?php if(empty($id)==false){echo $laassignment4;}else{echo '';} ; ?>";
            output = getoutput(assistantarray,proritytarray,assistantapprovedarray,m_id,selectvalue);
            $("#laassignment4_order2").html(output);
            $("#laassignment4_order2").change();

            selectvalue="<?php if(empty($id)==false){echo $laassignment5;}else{echo '';} ; ?>";
            output = getoutput(assistantarray,proritytarray,assistantapprovedarray,m_id,selectvalue);
            $("#laassignment5_order2").html(output);
            $("#laassignment5_order2").change();

        }).change();

        function isEmpty(str) {
            return (!str || 0 === str.length);
        }




    $("#termid").change(function () {

        var val = $(this).val();
        alert(val);
        var coursearray= <?php echo json_encode($coursearray); ?>;
        //var status= "<?php echo $status;?>";
        //alert(status);
        //var m_course= "<?php echo $course; ?>";
        var m_course= "<?php if(empty($course)==false){echo $course;}else{echo '';} ; ?>";
        var m_id = "<?php if(empty($id)==false){echo $id;}else{echo '';} ; ?>";
        //alert(m_id);
        var output ='';
        for(var i=0;i<coursearray.length;i++){
            //alert(jArray[i]);
            //output=output+jArray[i]['id']+',';
            var id = coursearray[i]['id'];
            var term = coursearray[i]['term'];
            var termid = coursearray[i]['termid'];
            var crn = coursearray[i]['crn'];
            var subject = coursearray[i]['subject'];
            var course = coursearray[i]['course'];
            var title = coursearray[i]['title'];
            var Name = term+'-'+crn+'-'+subject+'-'+course;
            if(termid==val)
            {
                output=output+"<option value='"+id+"'";
                if(!(isEmpty(m_id)))
                {
                    //output=output+"  disabled='disabled' ";
                    if (id == m_course)
                    {
                        output=output+" selected ";
                    }
                }
                else
                {
                    if(i==0)
                    {
                        //output=output+" selected ";
                    }
                }
                output=output+" >"+Name+"</option>";
            }
        }
        //alert(output);
        $("#course").html(output);
        $("#course").change();

    });

    $("#taassignment1").change(function() {
        //alert('taassignment1');
        $selectvalue =$('option:selected',this).attr('value');
        $selectname=$('option:selected',this).text();
        //alert($selectvalue);
        //alert($selectname);
        document.getElementById('tanamelabel1').innerText = $selectname;
        document.getElementById('tanamelabel1').setAttribute('data-id',$selectvalue);
        //alert('set tanamelabel1 finish');
        //alert(document.getElementById('tanametext1').toString());
        document.getElementById('tanametext1').text  = $selectname;
        //alert('set tanametext1 step2');
        document.getElementById('tanametext1').setAttribute('value',$selectvalue);
        //alert('set tanametext1 finish');
        //alert(document.getElementById('tanamelabel1').innerText);
        //alert(document.getElementById('tanametext1').getAttribute('value'));

    }).change();

    $("#taassignment2").change(function() {
        $selectvalue =$('option:selected',this).attr('value');
        $selectname=$('option:selected',this).text();
        document.getElementById('tanamelabel2').innerText = $selectname;
        document.getElementById('tanamelabel2').setAttribute('data-id',$selectvalue);
        document.getElementById('tanametext2').text = $selectname;
        document.getElementById('tanametext2').setAttribute('value',$selectvalue);
    }).change();

    $("#taassignment3").change(function() {
        $selectvalue =$('option:selected',this).attr('value');
        $selectname=$('option:selected',this).text();
        document.getElementById('tanamelabel3').innerText = $selectname;
        document.getElementById('tanamelabel3').setAttribute('data-id',$selectvalue);
        document.getElementById('tanametext3').text = $selectname;
        document.getElementById('tanametext3').setAttribute('value',$selectvalue);
    }).change();

    $("#taassignment4").change(function() {
        $selectvalue =$('option:selected',this).attr('value');
        $selectname=$('option:selected',this).text();
        document.getElementById('tanamelabel4').innerText = $selectname;
        document.getElementById('tanamelabel4').setAttribute('data-id',$selectvalue);
        document.getElementById('tanametext4').text = $selectname;
        document.getElementById('tanametext4').setAttribute('value',$selectvalue);
    }).change();

    $("#taassignment5").change(function() {
        $selectvalue =$('option:selected',this).attr('value');
        $selectname=$('option:selected',this).text();
        document.getElementById('tanamelabel5').innerText = $selectname;
        document.getElementById('tanamelabel5').setAttribute('data-id',$selectvalue);
        document.getElementById('tanametext5').text = $selectname;
        document.getElementById('tanametext5').setAttribute('value',$selectvalue);
    }).change();

    $("#gaassignment1").change(function() {
        $selectvalue =$('option:selected',this).attr('value');
        $selectname=$('option:selected',this).text();
        document.getElementById('ganamelabel1').innerText = $selectname;
        document.getElementById('ganamelabel1').setAttribute('data-id',$selectvalue);
        document.getElementById('ganametext1').text = $selectname;
        document.getElementById('ganametext1').setAttribute('value',$selectvalue);
    }).change();

    $("#gaassignment2").change(function() {
        $selectvalue =$('option:selected',this).attr('value');
        $selectname=$('option:selected',this).text();
        document.getElementById('ganamelabel2').innerText = $selectname;
        document.getElementById('ganamelabel2').setAttribute('data-id',$selectvalue);
        document.getElementById('ganametext2').text = $selectname;
        document.getElementById('ganametext2').setAttribute('value',$selectvalue);
    }).change();

    $("#gaassignment3").change(function() {
        $selectvalue =$('option:selected',this).attr('value');
        $selectname=$('option:selected',this).text();
        document.getElementById('ganamelabel3').innerText = $selectname;
        document.getElementById('ganamelabel3').setAttribute('data-id',$selectvalue);
        document.getElementById('ganametext3').text = $selectname;
        document.getElementById('ganametext3').setAttribute('value',$selectvalue);
    }).change();

    $("#gaassignment4").change(function() {
        $selectvalue =$('option:selected',this).attr('value');
        $selectname=$('option:selected',this).text();
        document.getElementById('ganamelabel4').innerText = $selectname;
        document.getElementById('ganamelabel4').setAttribute('data-id',$selectvalue);
        document.getElementById('ganametext4').text = $selectname;
        document.getElementById('ganametext4').setAttribute('value',$selectvalue);
    }).change();

    $("#gaassignment5").change(function() {
        $selectvalue =$('option:selected',this).attr('value');
        $selectname=$('option:selected',this).text();
        document.getElementById('ganamelabel5').innerText = $selectname;
        document.getElementById('ganamelabel5').setAttribute('data-id',$selectvalue);
        document.getElementById('ganametext5').text = $selectname;
        document.getElementById('ganametext5').setAttribute('value',$selectvalue);
    }).change();

    $("#laassignment1").change(function() {
        //alert('laassignment1');
        $selectvalue =$('option:selected',this).attr('value');
        $selectname=$('option:selected',this).text();
        //alert($selectvalue);
        document.getElementById('lanamelabel1').innerText = $selectname;
        document.getElementById('lanamelabel1').setAttribute('data-id',$selectvalue);
        document.getElementById('lanametext1').text = $selectname;
        document.getElementById('lanametext1').setAttribute('value',$selectvalue);
    }).change();

    $("#laassignment2").change(function() {
        $selectvalue =$('option:selected',this).attr('value');
        $selectname=$('option:selected',this).text();
        document.getElementById('lanamelabel2').innerText = $selectname;
        document.getElementById('lanamelabel2').setAttribute('data-id',$selectvalue);
        document.getElementById('lanametext2').text = $selectname;
        document.getElementById('lanametext2').setAttribute('value',$selectvalue);
    }).change();

    $("#laassignment3").change(function() {
        $selectvalue =$('option:selected',this).attr('value');
        $selectname=$('option:selected',this).text();
        document.getElementById('lanamelabel3').innerText = $selectname;
        document.getElementById('lanamelabel3').setAttribute('data-id',$selectvalue);
        document.getElementById('lanametext3').text = $selectname;
        document.getElementById('lanametext3').setAttribute('value',$selectvalue);
    }).change();

    $("#laassignment4").change(function() {
        $selectvalue =$('option:selected',this).attr('value');
        $selectname=$('option:selected',this).text();
        document.getElementById('lanamelabel4').innerText = $selectname;
        document.getElementById('lanamelabel4').setAttribute('data-id',$selectvalue);
        document.getElementById('lanametext4').text = $selectname;
        document.getElementById('lanametext4').setAttribute('value',$selectvalue);
    }).change();

    $("#laassignment5").change(function() {
        $selectvalue =$('option:selected',this).attr('value');
        $selectname=$('option:selected',this).text();
        document.getElementById('lanamelabel5').innerText = $selectname;
        document.getElementById('lanamelabel5').setAttribute('data-id',$selectvalue);
        document.getElementById('lanametext5').text = $selectname;
        document.getElementById('lanametext5').setAttribute('value',$selectvalue);
    }).change();

    $("#taassignment1_order2").change(function() {
        $selectvalue =$('option:selected',this).attr('value');
        $selectname=$('option:selected',this).text();
        document.getElementById('tanamelabel1').innerText = $selectname;
        document.getElementById('tanamelabel1').setAttribute('data-id',$selectvalue);
        document.getElementById('tanametext1').text = $selectname;
        document.getElementById('tanametext1').setAttribute('value',$selectvalue);

    }).change();

    $("#taassignment2_order2").change(function() {
        $selectvalue =$('option:selected',this).attr('value');
        $selectname=$('option:selected',this).text();
        document.getElementById('tanamelabel2').innerText = $selectname;
        document.getElementById('tanamelabel2').setAttribute('data-id',$selectvalue);
        document.getElementById('tanametext2').text = $selectname;
        document.getElementById('tanametext2').setAttribute('value',$selectvalue);
    }).change();

    $("#taassignment3_order2").change(function() {
        $selectvalue =$('option:selected',this).attr('value');
        $selectname=$('option:selected',this).text();
        document.getElementById('tanamelabel3').innerText = $selectname;
        document.getElementById('tanamelabel3').setAttribute('data-id',$selectvalue);
        document.getElementById('tanametext3').text = $selectname;
        document.getElementById('tanametext3').setAttribute('value',$selectvalue);
    }).change();

    $("#taassignment4_order2").change(function() {
        $selectvalue =$('option:selected',this).attr('value');
        $selectname=$('option:selected',this).text();
        document.getElementById('tanamelabel4').innerText = $selectname;
        document.getElementById('tanamelabel4').setAttribute('data-id',$selectvalue);
        document.getElementById('tanametext4').text = $selectname;
        document.getElementById('tanametext4').setAttribute('value',$selectvalue);
    }).change();

    $("#taassignment5_order2").change(function() {
        $selectvalue =$('option:selected',this).attr('value');
        $selectname=$('option:selected',this).text();
        document.getElementById('tanamelabel5').innerText = $selectname;
        document.getElementById('tanamelabel5').setAttribute('data-id',$selectvalue);
        document.getElementById('tanametext5').text = $selectname;
        document.getElementById('tanametext5').setAttribute('value',$selectvalue);
    }).change();

    $("#gaassignment1_order2").change(function() {
        $selectvalue =$('option:selected',this).attr('value');
        $selectname=$('option:selected',this).text();
        document.getElementById('ganamelabel1').innerText = $selectname;
        document.getElementById('ganamelabel1').setAttribute('data-id',$selectvalue);
        document.getElementById('ganametext1').text = $selectname;
        document.getElementById('ganametext1').setAttribute('value',$selectvalue);
    }).change();

    $("#gaassignment2_order2").change(function() {
        $selectvalue =$('option:selected',this).attr('value');
        $selectname=$('option:selected',this).text();
        document.getElementById('ganamelabel2').innerText = $selectname;
        document.getElementById('ganamelabel2').setAttribute('data-id',$selectvalue);
        document.getElementById('ganametext2').text = $selectname;
        document.getElementById('ganametext2').setAttribute('value',$selectvalue);
    }).change();

    $("#gaassignment3_order2").change(function() {
        $selectvalue =$('option:selected',this).attr('value');
        $selectname=$('option:selected',this).text();
        document.getElementById('ganamelabel3').innerText = $selectname;
        document.getElementById('ganamelabel3').setAttribute('data-id',$selectvalue);
        document.getElementById('ganametext3').text = $selectname;
        document.getElementById('ganametext3').setAttribute('value',$selectvalue);
    }).change();

    $("#gaassignment4_order2").change(function() {
        $selectvalue =$('option:selected',this).attr('value');
        $selectname=$('option:selected',this).text();
        document.getElementById('ganamelabel4').innerText = $selectname;
        document.getElementById('ganamelabel4').setAttribute('data-id',$selectvalue);
        document.getElementById('ganametext4').text = $selectname;
        document.getElementById('ganametext4').setAttribute('value',$selectvalue);
    }).change();

    $("#gaassignment5_order2").change(function() {
        $selectvalue =$('option:selected',this).attr('value');
        $selectname=$('option:selected',this).text();
        document.getElementById('ganamelabel5').innerText = $selectname;
        document.getElementById('ganamelabel5').setAttribute('data-id',$selectvalue);
        document.getElementById('ganametext5').text = $selectname;
        document.getElementById('ganametext5').setAttribute('value',$selectvalue);
    }).change();

    $("#laassignment1_order2").change(function() {
        $selectvalue =$('option:selected',this).attr('value');
        $selectname=$('option:selected',this).text();
        document.getElementById('lanamelabel1').innerText = $selectname;
        document.getElementById('lanamelabel1').setAttribute('data-id',$selectvalue);
        document.getElementById('lanametext1').text = $selectname;
        document.getElementById('lanametext1').setAttribute('value',$selectvalue);
    }).change();

    $("#laassignment2_order2").change(function() {
        $selectvalue =$('option:selected',this).attr('value');
        $selectname=$('option:selected',this).text();
        document.getElementById('lanamelabel2').innerText = $selectname;
        document.getElementById('lanamelabel2').setAttribute('data-id',$selectvalue);
        document.getElementById('lanametext2').text = $selectname;
        document.getElementById('lanametext2').setAttribute('value',$selectvalue);
    }).change();

    $("#laassignment3_order2").change(function() {
        $selectvalue =$('option:selected',this).attr('value');
        $selectname=$('option:selected',this).text();
        document.getElementById('lanamelabel3').innerText = $selectname;
        document.getElementById('lanamelabel3').setAttribute('data-id',$selectvalue);
        document.getElementById('lanametext3').text = $selectname;
        document.getElementById('lanametext3').setAttribute('value',$selectvalue);
    }).change();

    $("#laassignment4_order2").change(function() {
        $selectvalue =$('option:selected',this).attr('value');
        $selectname=$('option:selected',this).text();
        document.getElementById('lanamelabel4').innerText = $selectname;
        document.getElementById('lanamelabel4').setAttribute('data-id',$selectvalue);
        document.getElementById('lanametext4').text = $selectname;
        document.getElementById('lanametext4').setAttribute('value',$selectvalue);
    }).change();

    $("#laassignment5_order2").change(function() {
        $selectvalue =$('option:selected',this).attr('value');
        $selectname=$('option:selected',this).text();
        document.getElementById('lanamelabel5').innerText = $selectname;
        document.getElementById('lanamelabel5').setAttribute('data-id',$selectvalue);
        document.getElementById('lanametext5').text = $selectname;
        document.getElementById('lanametext5').setAttribute('value',$selectvalue);
    }).change();


    function init()
    {
        ChangeTANumber(this);
        ChangeGANumber(this);
        ChangeLANumber(this);
        //alert('beinterm');
        $("#termid").change();
    }

    window.onload =init;
</script>

<style>
    label {
        display: inline-block;
    }
</style>