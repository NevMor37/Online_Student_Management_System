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
//echo '$root:'.$root;
$mysqli = new mysqli($hostname,$username, $password,$dbname);

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

?>
<?php
$db=$mysqli;

//echo $_SESSION["status"];
$termarray = array();
$sql = "
                select Termid,Term,Startday,Endday
                from tbl_term
                where Termid in(
								select termid 
								from 
								(
								select Termid,Term,Startday,Endday,DATEDIFF(DATE_ADD(Startday,INTERVAL 30 DAY),Now()) 
								from tbl_term
								where DATEDIFF(DATE_ADD(Startday,INTERVAL 30 DAY),Now()) >0 
								order by DATEDIFF(DATE_ADD(Startday,INTERVAL 30 DAY),Now())  ASC 
								LIMIT 1
								) as te
                              )
                union all 
                
                select Termid,Term,Startday,Endday
                from tbl_term
                where Termid not in(
								select termid 
								from 
								(
								select Termid,Term,Startday,Endday,DATEDIFF(DATE_ADD(Startday,INTERVAL 30 DAY),Now()) 
								from tbl_term
								where DATEDIFF(DATE_ADD(Startday,INTERVAL 30 DAY),Now()) >0 
								order by DATEDIFF(DATE_ADD(Startday,INTERVAL 30 DAY),Now())  ASC 
								LIMIT 1
								) as te
                              )

            ";
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


//echo $_SESSION["status"];
$studentarray = array();
$sql = "select PantherID,FirstName,MiddleName,LastName,Email,College,Department,Degree
        from tbl_student_info
        where Status ='active'
            ";
//echo $sql . '<br>';
$result = mysqli_query($db, $sql);

if ($result->num_rows > 0) {
    $i = 0;
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        $studentarray[$i] = array();
        $studentarray[$i]['PantherID'] = $row["PantherID"];
        $studentarray[$i]['FirstName'] = $row["FirstName"];
        $studentarray[$i]['MiddleName'] = $row["MiddleName"];
        $studentarray[$i]['LastName'] = $row["LastName"];
        $studentarray[$i]['Email'] = $row["Email"];
        $studentarray[$i]['College'] = $row["College"];
        $studentarray[$i]['Department'] = $row["Department"];
        $studentarray[$i]['Degree'] = $row["Degree"];
        $i = $i + 1;
    }
}

$sql = "select PantherID,FirstName,MiddleName,LastName,Email,'AS' as College,'CSC', as Department
         CASE
            WHEN Program LIKE '%Master%' THEN 'MS'
                WHEN Program LIKE '%MS%' THEN 'MS'
                WHEN Program LIKE '%PHD%' THEN 'PHD'
            WHEN Program LIKE '%Doctor%' THEN 'PHD'
            
          END as Degree
                from tbl_excel_info as info
                inner JOIN tbl_student_evaluation as ev on ev.StudentId = info.PantherId
                        where PantherId not in(select PantherID
                from tbl_student_info
                where Status ='active') 
            ";
//echo $sql . '<br>';
$result = mysqli_query($db, $sql);

if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        $studentarray[$i] = array();
        $studentarray[$i]['PantherID'] = $row["PantherID"];
        $studentarray[$i]['FirstName'] = $row["FirstName"];
        $studentarray[$i]['MiddleName'] = $row["MiddleName"];
        $studentarray[$i]['LastName'] = $row["LastName"];
        $studentarray[$i]['Email'] = $row["Email"];
        $studentarray[$i]['College'] = $row["College"];
        $studentarray[$i]['Department'] = $row["Department"];
        $studentarray[$i]['Degree'] = $row["Degree"];
        $i = $i + 1;
    }
}

include ($root.'/PHPExcel/IOFactory.php');

if(isset($_POST["studentimport"])) {
    //$destination_path = getcwd().DIRECTORY_SEPARATOR;
    $destination_path= $root;
    $destination_path= $destination_path.'/';
    $target_path = "uploads/";
    //$target_path = "";
    $target_path = $destination_path .$target_path. basename( $_FILES["studentfile"]["name"]);
    //$target_path = "uploads/";
    //$target_path = $target_path . basename($_FILES['studentfile']['name']);
    //$FileType=$_FILES["studentfile"]["type"];
    $FileType = pathinfo($target_path,PATHINFO_EXTENSION);
    $tmpfile = $_FILES['studentfile']['tmp_name'];
    echo '$target_path:'.$target_path .'$FileType:'.$FileType .'$tmpfile' .$tmpfile;
   // if ($FileType == "application/xls" or $FileType == "application/vnd.ms-excel" or $FileType == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
    if($FileType =='xls' or $FileType =='csv'){
        if (move_uploaded_file($_FILES['studentfile']['tmp_name'], $target_path))
        {
            $inputFileName = $target_path;
            $inputFileType = $FileType;
            /**********************PHPExcel Script to Read Excel File**********************/

            //  Read your Excel workbook
            try {
                $inputFileType = PHPExcel_IOFactory::identify($inputFileName); //Identify the file
                $objReader = PHPExcel_IOFactory::createReader($inputFileType); //Creating the reader
                $objPHPExcel = $objReader->load($inputFileName); //Loading the file
            } catch (Exception $e) {
                die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME)
                    . '": ' . $e->getMessage());
            }


            //  Get worksheet dimensions
            $sheet = $objPHPExcel->getSheet(0);     //Selecting sheet 0
            $highestRow = $sheet->getHighestRow();     //Getting number of rows
            $highestColumn = $sheet->getHighestColumn();     //Getting number of columns

            echo '<br>';
            //  Loop through each row of the worksheet in turn
            for ($row = 2; $row <= $highestRow; $row++)
            {

                //  Read a row of data into an array

                $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                    NULL, TRUE, FALSE);
                // This line works as $sheet->rangeToArray('A1:E1') that is selecting all the cells in that row from cell A to highest column cell

//                echo 'Term:'.$rowData[0][0]. 'Pantherid:'.$rowData[0][1].'Last Name:'.$rowData[0][2].
//                    'First Name:'.$rowData[0][3].'College:'.$rowData[0][4].'Department:'.$rowData[0][5].
//                    'Degree:'.$rowData[0][6].'Major:'.$rowData[0][7].'Concentration:'. $rowData[0][8].
//                    'Degree Level:'.$rowData[0][9].'Hours Enrolled:'.$rowData[0][10].'Email:'.$rowData[0][11].
//                    'Class Standing:'.$rowData[0][12];
                $term = $rowData[0][0];
                $pantherid = $rowData[0][1];
                $firstname = $rowData[0][3];
                $lastname = $rowData[0][2];
                $email = $rowData[0][7];
                $college = 'AS';
                $supportdepartment = 'CSC';
                $degree = $rowData[0][5];
                $curr_TermID='';
                echo 'termarray start';
                foreach ($termarray as $arr)
                {
                    $p_Termid = $arr["Termid"];
                    $p_Term = $arr["Term"];
                    $p_Startday = $arr["Startday"];
                    //echo '$p_Startday:'.$p_Startday;
                    $p_termstr = str_replace('-','',substr($p_Startday,0,7));
                    //echo '$p_termstr:'.$p_termstr;
                    if($p_termstr==$term)
                    {
                        $curr_TermID=$p_Termid;
                        break;
                    }
                }

                $sql = "select FundedID as fundedid,
                            PantherID as pantherid,
                            TermID as termid
                    from tbl_fundedstudent
                    where PantherID =  '$pantherid' and TermID='$curr_TermID'" ;

                $result = mysqli_query($db, $sql);

                if ($result->num_rows > 0)
                {
                    // output data of each row
                    while ($m_row = $result->fetch_assoc())
                    {
                        echo "fundedid: " . $m_row["fundedid"] .  "<br>";
                        $p_fundedid = $m_row["fundedid"];
                        $p_supportdepartment = $supportdepartment;
                        $p_degree = $degree;
                        //echo $position;
                        //echo $status;
                        echo 'update sql:';
                        $updatesql = "   update tbl_fundedstudent
                                set Position='$p_degree',SupportDepartment='$p_supportdepartment'                               
                                where FundedID = '$p_fundedid'";
                        echo $updatesql;
                        $p_result =mysqli_query($db,$updatesql);
                        if($p_result)
                        {
                            echo 'update successful';
                        }
                        else
                        {
                            echo 'update error';
                        }
                    }
                }
                else
                {
                    echo "insert sql:";
//                    $p_position = $degree;
//                    echo '$degree'.$degree;
//                    echo '$p_position'.$p_position;
                    $p_supportdepartment = $supportdepartment;
                    $p_position = $m_Degree;
                    $p_supportdepartment = $supportdepartment;
                    $insertsql = "insert into tbl_fundedstudent(TermID,PantherID,Position,SupportDepartment)
                              values('$curr_TermID','$pantherid','$degree','$p_supportdepartment')
                                  ";
                        echo $insertsql;
                    $p_result =mysqli_query($db,$insertsql);
                    if($p_result)
                    {
                        echo 'insert successful';
                    }
                    else
                    {
                        echo 'insert error';
                    }

                    }

                echo '<br>';

            }


            echo "The file " . basename($_FILES['studentfile']['name']) .
                " has been uploaded";
        }
        else
        {
            echo "There was an error uploading the file, please try again!";
        }
    }
    else
    {
        echo "This is not an excel file, please try again!";
    }
}



?>

<div class="header">
    <h1></h1>
</div>
<?php
    if(isset($_SESSION['message']))
    {
         echo "<div id='error_msg'>".$_SESSION['message']."</div>";
         unset($_SESSION['message']);
    }
?>
<h1>View</h1>

<form action="" method="post" enctype="multipart/form-data">
<div class="form-group">
    <label for="studentInputFile">Select Excel/CSV to upload:</label>
    <input type="file" name="studentfile" id="studentfile" size="1500">
    <p class="help-block"></p>
    <input type="submit"  name="studentimport" value="Upload"></input>
</div>

</form>
<div>
    <a href="studentregister.php">Add New student</a><br>
    <h4></h4>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body"  style="overflow:auto">
                <table width="100%" class="table table-striped table-bordered table-hover" id="student-view">
                    <thead>
                    <!-- Head -->
                    <tr>
                        <td>Term</td><td>PantherID</td><td>Name</td><td>Email</td>
                       <td>Degree</td><td>Opeartion</td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                        $sql = "
                            SELECT FundedID,TermID,PantherID,Position,SupportDepartment
                            FROM tbl_fundedstudent;
                                 ";

                        $result = mysqli_query($db, $sql);

                        while($row=mysqli_fetch_assoc($result))
                        {
                            $FundedID=$row["FundedID"] ;
                            $TermID=$row["TermID"] ;
                            $PantherID=$row["PantherID"] ;
                            $Position=$row["Position"] ;
                            $SupportDepartment=$row["SupportDepartment"] ;
                            $m_Term='';
                            foreach ($termarray as $arr)
                            {
                                $p_Termid = $arr["Termid"];
                                $p_Term = $arr["Term"];
                                if($p_Termid==$TermID)
                                {
                                    $m_Term=$p_Term;
                                    break;
                                }
                            }
                            $m_name='';
                            $m_Email='';
                            $m_College='';
                            $m_Department='';
                            $m_Degree='';
                            foreach ($studentarray as $arr)
                            {
                                $studentarray[$i]['PantherID'] = $row["PantherID"];
                                $studentarray[$i]['FirstName'] = $row["FirstName"];
                                $studentarray[$i]['MiddleName'] = $row["MiddleName"];
                                $studentarray[$i]['LastName'] = $row["LastName"];
                                $studentarray[$i]['Email'] = $row["Email"];
                                $studentarray[$i]['College'] = $row["College"];
                                $studentarray[$i]['Department'] = $row["Department"];
                                $studentarray[$i]['Degree'] = $row["Degree"];
                                $p_PantherID = $arr["PantherID"];
                                $p_FirstName = $arr["FirstName"];
                                $p_MiddleName = $arr["MiddleName"];
                                $p_LastName = $arr["LastName"];
                                $p_Email = $arr["Email"];
                                $p_College = $arr["College"];
                                $p_Department = $arr["Department"];
                                $p_Degree = $arr["Degree"];
                                if($p_PantherID==$PantherID)
                                {
                                    $m_name=$p_FirstName.' '.$p_MiddleName.' '.$p_LastName;
                                    $m_Email=$p_Email;
                                    $m_College=$p_College;
                                    $m_Department=$p_Department;
                                    $m_Degree=$p_Degree;
                                }
                            }
                            echo '<tr><td>' .$m_Term .
                                '</td><td>' . $PantherID .
                                '</td><td>' . $m_name .
                                '</td><td>' . $m_Email .
                                '</td><td>' . $m_Degree .
                                '</td><td>'.
                                '<a href="fundedstudentregister.php?id= ' . $row["id"] . '" >Update</a><br>'.
                                '<a href="fundedstudentremove.php?id=' . $row["id"] . ' "\">Remove</a>'.
                                '</td></tr>';
                        }

                        ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

