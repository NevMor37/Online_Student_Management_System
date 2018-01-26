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


                //echoing every cell in the selected row for simplicity. You can save the data in database too.
//                foreach($rowData[0] as $k=>$v) {
//                    echo $v.' ';
//                }
                echo 'Term:'.$rowData[0][0]. 'Pantherid:'.$rowData[0][1].'Last Name:'.$rowData[0][2].
                    'First Name:'.$rowData[0][3].'College:'.$rowData[0][4].'Department:'.$rowData[0][5].
                    'Degree:'.$rowData[0][6].'Major:'.$rowData[0][7].'Concentration:'. $rowData[0][8].
                    'Degree Level:'.$rowData[0][9].'Hours Enrolled:'.$rowData[0][10].'Email:'.$rowData[0][11].
                    'Class Standing:'.$rowData[0][12];
                $id = $rowData[0][1];
                $firstname = $rowData[0][3];
                $lastname = $rowData[0][2];
                $email = $rowData[0][11];
                $college = $rowData[0][4];
                $department = $rowData[0][5];
                $degree = $rowData[0][6];
                $major = $rowData[0][7];
                $concentration = $rowData[0][8];
//
                $concentration = str_replace('-','',$concentration);
                    $sql = "select  PantherID as id,
                                FirstName as firstname,
                                MiddleName as middlename,
                                LastName as lastname,
                                Email as email,
                                MobileNumber as mobilenumber,
                                College as college,
                                Department as department,
                                Location as location,
                                Degree as degree,
                                Major as major,
                                Concentration as concentration,
                                Position as position,
                                Status as status
                        from tbl_student_info
                        where PantherID = " . $id;

                    $result = mysqli_query($db, $sql);

                    if ($result->num_rows > 0)
                    {
                        // output data of each row
                        while ($m_row = $result->fetch_assoc())
                        {
                            echo "id: " . $m_row["id"] .  "<br>";
                            $p_id = $m_row["id"];
                            $p_firstname = $m_row["firstname"];
                            $p_middlename = $m_row["middlename"];
                            $p_lastname = $m_row["lastname"];
                            $p_email = $m_row["email"];
                            $p_mobilenumber = $m_row["mobilenumber"];
                            $p_college = $m_row["college"];
                            $p_department = $m_row["department"];
                            $p_location = $m_row["location"];
                            $p_degree = $m_row["degree"];
                            $p_major = $m_row["major"];
                            $p_concentration = $m_row["concentration"];
                            $p_position = $m_row["position"];
                            $p_status = $m_row["status"];
                            //echo $position;
                            //echo $status;
                            echo 'update sql:';
                            $updatesql = "   update tbl_student_info
                                    set FirstName='$firstname',LastName='$lastname',
                                        Email='$email',College='$college',
                                        Department='$department',Degree='$degree',
                                        Major='$major',Concentration='$concentration'
                                    where PantherID = $id";
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
                        $middlename = '';
                        $mobilenumber = '';
                        $location = '';
                        $position = 'notinstructor';
                        $status = 'active';
                        $insertsql = "insert into tbl_student_info(PantherID,FirstName,MiddleName,LastName,
                                              Email,MobileNumber,College,Department,Location,Degree,Major,
                                              Concentration,Position,Status)
                                  values('$id','$firstname','$middlename','$lastname',
                                  '$email','$mobilenumber','$college','$department','$location','$degree','$major',
                                            '$concentration','$position','$status')
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
                        <td>PantherID</td><td>FirstName</td><td>LastName</td><td>Email</td>
                       <td>Degree</td><td>Position</td><td>Status</td><td>Opeartion</td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                        $sql = "select  PantherID as id,
                                        FirstName as firstname,
                                        MiddleName as middlename,
                                        LastName as lastname,
                                        Email as email,
                                        MobileNumber as mobilenumber,
                                        Department as department,
                                        Location as location,
                                        Degree as degree,
                                        Position as position,
                                        Status as status
                                from tbl_student_info
                                order by LastName
                                 ";

                        $result = mysqli_query($db, $sql);

                        while($row=mysqli_fetch_assoc($result))
                        {
                            echo '<tr><td>' . $row["id"] .
                                '</td><td>' . $row["firstname"] .
                            //    '</td><td>' . $row["middlename"] .
                                '</td><td>' . $row["lastname"] .
                                '</td><td>' . $row["email"] .
                             //   '</td><td>' . $row["mobilenumber"] .
                             //   '</td><td>' . $row["department"] .
                             //   '</td><td>' . $row["location"] .
                                '</td><td>' . $row["degree"] .
                                '</td><td>' . $row["position"] .
                                '</td><td>' . $row["status"] .
                                '</td><td>'.
                                '<a href="studentregister.php?id= ' . $row["id"] . '" >Update</a><br>'.
                                '<a href="studentremove.php?id=' . $row["id"] . ' "\">Remove</a>'.
                                '</td></tr>';
                        }

                        ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

