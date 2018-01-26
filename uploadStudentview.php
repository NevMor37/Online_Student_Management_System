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
    //echo '$target_path:'.$target_path .'$FileType:'.$FileType .'$tmpfile' .$tmpfile;
   // if ($FileType == "application/xls" or $FileType == "application/vnd.ms-excel" or $FileType == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
    if($FileType =='xls' or $FileType =='csv'){
        if (move_uploaded_file($_FILES['studentfile']['tmp_name'], $target_path))
        {
            //echo 'file correct';
            $inputFileName = $target_path;
            $inputFileType = $FileType;
            /**********************PHPExcel Script to Read Excel File**********************/

            //  Read your Excel workbook
            try {
                $inputFileType = PHPExcel_IOFactory::identify($inputFileName); //Identify the file
                $objReader = PHPExcel_IOFactory::createReader($inputFileType); //Creating the reader
                //$objPHPExcel = $objReader->load($inputFileName); //Loading the file
                $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
            } catch (Exception $e) {
                die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME)
                    . '": ' . $e->getMessage());
            }

            //echo '$inputFileName:'.$inputFileName;
            $connect = $mysqli;
            //  Get worksheet dimensions
            //$sheet = $objPHPExcel->getSheet(0);     //Selecting sheet 0
            foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
                $highestRow = $worksheet->getHighestRow();     //Getting number of rows
                $highestColumn = $worksheet->getHighestColumn();     //Getting number of columns

                echo '<br>';
                //  Loop through each row of the worksheet in turn
                for ($row = 2; $row <= $highestRow; $row++) {
                    //echo '$Name:';
                    $rownumber=0;
                    $Name = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow($rownumber, $row)->getValue());
                    $rownumber=$rownumber+1;//1
                    //echo  $Name;
                    $_Name = explode(',', $Name);
                    $FirstName = '';
                    $MiddleName = '';
                    $LastName = '';
                    $NameLength = sizeof($_Name);
                    if ($NameLength > 0) {
                        if ($NameLength >= 2) {
                            $FirstName = $_Name[0];
                            $LastName = $_Name[1];
                        } else {
                            $FirstName = $_Name[0];
                        }
                    }
                    $AlternativeLastName = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow($rownumber, $row)->getValue());
                    $rownumber=$rownumber+1;//2
                    $PantherId = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow($rownumber, $row)->getValue());
                    $rownumber=$rownumber+1;//3
                    $PantherId = (string)((int)$PantherId);
                    $ApplicantId = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow($rownumber, $row)->getValue());
                    $rownumber=$rownumber+1;//4
                    $Program = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow($rownumber, $row)->getValue());
                    $rownumber=$rownumber+1;//5
                    $Term = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow($rownumber, $row)->getValue());
                    $rownumber=$rownumber+1;//6
                    $Concentration = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow($rownumber, $row)->getValue());
                    $rownumber=$rownumber+1;//7
                    $EMail = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow($rownumber, $row)->getValue());
                    $rownumber=$rownumber+1;//8
                    $CitizenshipStatus = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow($rownumber, $row)->getValue());
                    $rownumber=$rownumber+1;//9
                    $GREDate = PHPExcel_Style_NumberFormat::toFormattedString(mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow($rownumber, $row)->getValue()), "MM/DD/YYYY");
                    $rownumber=$rownumber+1;//10
                    $GREVerbalScore = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow($rownumber, $row)->getValue());
                    $rownumber=$rownumber+1;//11
                    $GREVerbalPercent = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow($rownumber, $row)->getValue());
                    $rownumber=$rownumber+1;//12
                    $GREQuantScore = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow($rownumber, $row)->getValue());
                    $rownumber=$rownumber+1;//13
                    $GREQuantPercent = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow($rownumber, $row)->getValue());
                    $rownumber=$rownumber+1;//14
                    $GREAnalyticalScore = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow($rownumber, $row)->getValue());
                    $rownumber=$rownumber+1;//15
                    $GREAnalyticalPercent = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow($rownumber, $row)->getValue());
                    $rownumber=$rownumber+1;//16
                    $TOEFLDateofTest = PHPExcel_Style_NumberFormat::toFormattedString(mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow($rownumber, $row)->getValue()), "MM/DD/YYYY");
                    $rownumber=$rownumber+1;//17
                    $TOEFLTestType = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow($rownumber, $row)->getValue());
                    $rownumber=$rownumber+1;//18
                    $TOEFLTotal = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow($rownumber, $row)->getValue());
                    $rownumber=$rownumber+1;//19
                    $TOEFLListening = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow($rownumber, $row)->getValue());
                    $rownumber=$rownumber+1;//20
                    $TOEFLReading = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow($rownumber, $row)->getValue());
                    $rownumber=$rownumber+1;//21
                    $TOEFLWriting = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow($rownumber, $row)->getValue());
                    $rownumber=$rownumber+1;//22
                    $TOEFLSpeaking = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow($rownumber, $row)->getValue());
                    $rownumber=$rownumber+1;//23
                    $IELTSDate = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow($rownumber, $row)->getValue());
                    $rownumber=$rownumber+1;//24
                    $IELTSOverallScore = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow($rownumber, $row)->getValue());
                    $rownumber=$rownumber+1;//25
                    $UgGPAOverall = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow($rownumber, $row)->getValue());
                    $rownumber=$rownumber+1;//26
                    $Race = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow($rownumber, $row)->getValue());
                    $rownumber=$rownumber+1;//27
                    $Ethnicity = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow($rownumber, $row)->getValue());//Hispanic
                    $rownumber=$rownumber+1;//28
                    $CitizenshipCountry = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow($rownumber, $row)->getValue());
                    $rownumber=$rownumber+1;//29
                    $Gender = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow($rownumber, $row)->getValue());
                    $rownumber=$rownumber+1;//30
                    $CollegeName1 = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow($rownumber, $row)->getValue());
                    $rownumber=$rownumber+1;//31
                    $DateAttendedFrom1 = PHPExcel_Style_NumberFormat::toFormattedString(mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow($rownumber, $row)->getValue()), "MM/DD/YYYY");
                    $rownumber=$rownumber+1;//32
                    $DateAttendedTo1 = PHPExcel_Style_NumberFormat::toFormattedString(mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow($rownumber, $row)->getValue()), "MM/DD/YYYY");
                    $rownumber=$rownumber+1;//33
                    $Major1 = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow($rownumber, $row)->getValue());
                    $rownumber=$rownumber+1;//34
                    $Degree1 = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow($rownumber, $row)->getValue());
                    $rownumber=$rownumber+1;//35
                    $CollegeName2 = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow($rownumber, $row)->getValue());
                    $rownumber=$rownumber+1;//36
                    $DateAttendedFrom2 = PHPExcel_Style_NumberFormat::toFormattedString(mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow($rownumber, $row)->getValue()), "MM/DD/YYYY");
                    $rownumber=$rownumber+1;//37
                    $DateAttendedTo2 = PHPExcel_Style_NumberFormat::toFormattedString(mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow($rownumber, $row)->getValue()), "MM/DD/YYYY");
                    $rownumber=$rownumber+1;//38
                    $Major2 = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow($rownumber, $row)->getValue());
                    $rownumber=$rownumber+1;//39
                    $Degree2 = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow($rownumber, $row)->getValue());
                    $rownumber=$rownumber+1;//40
                    $CollegeName3 = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow($rownumber, $row)->getValue());
                    $rownumber=$rownumber+1;//41
                    $DateAttendedFrom3 = PHPExcel_Style_NumberFormat::toFormattedString(mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow($rownumber, $row)->getValue()), "MM/DD/YYYY");
                    $rownumber=$rownumber+1;//42
                    $DateAttendedTo3 = PHPExcel_Style_NumberFormat::toFormattedString(mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow($rownumber, $row)->getValue()), "MM/DD/YYYY");
                    $rownumber=$rownumber+1;//43
                    $Major3 = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow($rownumber, $row)->getValue());
                    $rownumber=$rownumber+1;//44
                    $Degree3 = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow($rownumber, $row)->getValue());
                    $rownumber=$rownumber+1;//45
                    $LinkAddress = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow($rownumber, $row)->getValue());
                    $rownumber=$rownumber+1;//46
                    $UgGPAMajor = '';
                    $GraduateGPA = '';
                    $Faculty1 = '';
                    $Faculty2 = '';
                    $Faculty3 = '';
                    //$IELTSDate $IELTSOverallScore
                    if(empty($TOEFLTotal))
                    {
                        if(!empty($IELTSOverallScore))
                        {
                            $TOEFLTotal='IELTS '.$IELTSOverallScore;
                        }
                    }
                    if(empty($TOEFLDateofTest))
                    {
                        if(!empty($IELTSDate))
                        {
                            $TOEFLDateofTest='IELTS '.$IELTSOverallScore;
                        }
                    }

                    if($PantherId==0)
                    {
                        echo "<font color='red'>".'The PantherID of '.$Name . ' is 0!!!'."</font>";
                        echo '<br>';
                    }
                    else
                    {
                        $checksql = "
                        select PantherId
                        from tbl_excel_info 
                        where PantherId =" . $PantherId;
                        //echo $checksql;
                        //echo '<br>';
                        $result = mysqli_query($db, $checksql);

                        if ($result->num_rows > 0) {
                            // output data of each row
                            while ($m_row = $result->fetch_assoc()) {

                                $sql = "update tbl_excel_info 
                                  set FirstName='$FirstName',MiddleName='$MiddleName',LastName='$LastName',
                                      AlternativeLastName='$AlternativeLastName',
                                      Program='$Program',Term='$Term',Concentration='$Concentration',EMail='$EMail',GREDate='$GREDate',
                                      GREVerbalScore='$GREVerbalScore',GREVerbalPercent='$GREVerbalPercent',
                                      GREQuantScore='$GREQuantScore',GREQuantPercent='$GREQuantPercent',
                                      GREAnalyticalScore='$GREAnalyticalScore',
                                      GREAnalyticalPercent='$GREAnalyticalPercent',TOEFLDateofTest='$TOEFLDateofTest',
                                      TOEFLTestType='$TOEFLTestType',TOEFLTotal='$TOEFLTotal',TOEFLReading='$TOEFLReading',
                                      TOEFLListening='$TOEFLListening',TOEFLSpeaking='$TOEFLSpeaking',
                                      TOEFLWriting='$TOEFLWriting',UgGPAOverall='$UgGPAOverall',UgGPAMajor='$UgGPAMajor',
                                      GraduateGPA='$GraduateGPA',Faculty1='$Faculty1',Faculty2='$Faculty2',
                                      Faculty3='$Faculty3',
                                      Race='$Race',Ethnicity='$Ethnicity',Gender='$Gender',CitizenshipCountry='$CitizenshipCountry',CitizenshipStatus='$CitizenshipStatus',
                                      CollegeName1='$CollegeName1',DateAttendedFrom1='$DateAttendedFrom1',DateAttendedTo1='$DateAttendedTo1',Major1='$Major1',Degree1='$Degree1',
                                      CollegeName2='$CollegeName2',DateAttendedFrom2='$DateAttendedFrom2',DateAttendedTo2='$DateAttendedTo2',Major2='$Major2',Degree2='$Degree2',
                                      CollegeName3='$CollegeName3',DateAttendedFrom3='$DateAttendedFrom3',DateAttendedTo3='$DateAttendedTo3',Major3='$Major3',Degree3='$Degree3',
                                      ApplicantID='$ApplicantId',linkaddress='$LinkAddress'
                                    where PantherId = 
                                  " . $PantherId;
                               //echo $sql.'<br>';
                                $p_result = mysqli_query($connect, $sql);
                                if ($p_result) {
                                    //echo 'update successful';
                                } else {
                                    echo "<font color='red'>"."PantherId: " . $PantherId .' update error!!!'."</font>" ;
                                    echo $sql;
                                    echo '<br>';
                                }
                            }
                        } else {
                            // echo "insert sql:";
                            $sql = "INSERT INTO tbl_excel_info (PantherId,FirstName,MiddleName,LastName,AlternativeLastName,
                                              Program,Term,Concentration,EMail,GREDate,
                                              GREVerbalScore,GREVerbalPercent,GREQuantScore,GREQuantPercent,GREAnalyticalScore,
                                              GREAnalyticalPercent,TOEFLDateofTest,TOEFLTestType,TOEFLTotal,TOEFLReading,
                                              TOEFLListening,TOEFLSpeaking,TOEFLWriting,UgGPAOverall,UgGPAMajor,
                                              GraduateGPA,Faculty1,Faculty2,Faculty3,
                                              Race,Ethnicity,Gender,CitizenshipCountry,CitizenshipStatus,
                                              CollegeName1,DateAttendedFrom1,DateAttendedTo1,Major1,Degree1,
                                              CollegeName2,DateAttendedFrom2,DateAttendedTo2,Major2,Degree2,
                                              CollegeName3,DateAttendedFrom3,DateAttendedTo3,Major3,Degree3,
                                              ApplicantID,linkaddress) 
                                              VALUES ('" . $PantherId . "','" . $FirstName . "','" . $MiddleName . "','" . $LastName . "','" . $AlternativeLastName . "',
                                                        '" . $Program . "','" . $Term . "','" . $Concentration . "','" . $EMail . "','" . $GREDate . "',
                                                        '" . $GREVerbalScore . "','" . $GREVerbalPercent . "','" . $GREQuantScore . "','" . $GREQuantPercent . "','" . $GREAnalyticalScore . "',
                                                        '" . $GREAnalyticalPercent . "','" . $TOEFLDateofTest . "','" . $TOEFLTestType . "','" . $TOEFLTotal . "','" . $TOEFLReading . "',
                                                        '" . $TOEFLListening . "','" . $TOEFLSpeaking . "','" . $TOEFLWriting . "','" . $UgGPAOverall . "','" . $UgGPAMajor . "',
                                                        '" . $GraduateGPA . "','" . $Faculty1 . "','" . $Faculty2 . "','" . $Faculty3 . "',
                                                        '" . $Race . "','" . $Ethnicity . "','" . $Gender . "','" . $CitizenshipCountry . "','" . $CitizenshipStatus . "',
                                                        '" . $CollegeName1 . "','" . $DateAttendedFrom1 . "','" . $DateAttendedTo1 . "','" . $Major1 . "','" . $Degree1 . "',
                                                        '" . $CollegeName2 . "','" . $DateAttendedFrom2 . "','" . $DateAttendedTo2 . "','" . $Major2 . "','" . $Degree2 . "',
                                                        '" . $CollegeName3 . "','" . $DateAttendedFrom3 . "','" . $DateAttendedTo3 . "','" . $Major3 . "','" . $Degree3 . "',
                                                        '" . $ApplicantId . "','" . $LinkAddress . "')";
                            //echo $sql;
                            $p_result = mysqli_query($connect, $sql);
                            if ($p_result) {
                                //echo 'insert successful';
                            } else {
                                echo "<font color='red'>"."PantherId: " . $PantherId .' insert error!!!'."</font>";
                                echo $sql;
                                echo "<font color='red'>".'error:'. mysqli_error($connect)."</font>";
                                echo '<br>';
                            }

                        }


                    }
                }

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

<form action="" method="post" enctype="multipart/form-data">
<div class="form-group">
    <label for="studentInputFile">Select Student Information in Excel File Format</label>
    <input type="file" name="studentfile" id="studentfile" size="1500" accept="application/vnd.ms-excel">
    <p class="help-block"></p>
    <input type="submit"  name="studentimport" value="Upload xls" class="btn btn-primary" ></input>
</div>

</form>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body"  style="overflow:auto">
                <table width="100%" class="table table-striped table-bordered table-hover" id="studentTables-info">
                    <thead>
                    <!-- Head -->
                    <tr>
                        <td>PantherID</td><td>FirstName</td><td>LastName</td>
                        <td>Program</td><td>Term</td><td>Concentration</td>
                    </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT  * FROM tbl_excel_info where PantherId <>'' and CONVERT(PantherId ,unsigned)  != 0";
                        $result = $mysqli->query($sql);
                        $row_cnt = $result->num_rows;


                        if ($result->num_rows > 0) {
                            // output data of each row
                            $count = 0;
                            while ($row = $result->fetch_assoc()) {

                                $Status = "";
                                $count++;
                                $PantherId = $row["PantherId"];
                                $FirstName = $row["FirstName"];
                                $MiddleName = $row["MiddleName"];
                                $LastName = $row["LastName"];
                                $AlternativeLastName = $row["AlternativeLastName"];
                                $Program = $row["Program"];
                                $Term = $row["Term"];
                                $Concentration = $row["Concentration"];
                                $EMail = $row["EMail"];
                                $GREDate = $row["GREDate"];
                                $GREVerbalScore = $row["GREVerbalScore"];
                                $GREVerbalPercent = $row["GREVerbalPercent"];
                                $GREQuantScore = $row["GREQuantScore"];
                                $GREQuantPercent = $row["GREQuantPercent"];
                                $GREAnalyticalScore = $row["GREAnalyticalScore"];
                                $GREAnalyticalPercent = $row["GREAnalyticalPercent"];
                                $TOEFLDateofTest = $row["TOEFLDateofTest"];
                                $TOEFLTestType = $row["TOEFLTestType"];
                                $TOEFLTotal = $row["TOEFLTotal"];
                                $TOEFLReading = $row["TOEFLReading"];
                                $TOEFLListening = $row["TOEFLListening"];
                                $TOEFLSpeaking = $row["TOEFLSpeaking"];
                                $TOEFLWriting = $row["TOEFLWriting"];
                                $UgGPAOverall = $row["UgGPAOverall"];
                                $UgGPAMajor = $row["UgGPAMajor"];
                                $GraduateGPA = $row["GraduateGPA"];
                                $Faculty1 = $row["Faculty1"];
                                $Faculty2 = $row["Faculty2"];
                                $Faculty3 = $row["Faculty3"];
                                $Race = $row["Race"];
                                $Ethnicity = $row["Ethnicity"];
                                $Gender = $row["Gender"];
                                $CitizenshipCountry = $row["CitizenshipCountry"];
                                $CitizenshipStatus = $row["CitizenshipStatus"];
                                $CollegeName1 = $row["CollegeName1"];
                                $DateAttendedFrom1 = $row["DateAttendedFrom1"];
                                $DateAttendedTo1 = $row["DateAttendedTo1"];
                                $Major1 = $row["Major1"];
                                $Degree1 = $row["Degree1"];
                                $CollegeName2 = $row["CollegeName2"];
                                $DateAttendedFrom2 = $row["DateAttendedFrom2"];
                                $DateAttendedTo2 = $row["DateAttendedTo2"];
                                $Major2 = $row["Major2"];
                                $Degree2 = $row["Degree2"];
                                $CollegeName3 = $row["CollegeName3"];
                                $DateAttendedFrom3 = $row["DateAttendedFrom3"];
                                $DateAttendedTo3 = $row["DateAttendedTo3"];
                                $Major3 = $row["Major3"];
                                $Degree3 = $row["Degree3"];
                                $linkaddress = $row["linkaddress"];

                                $sql1 = "SELECT  * FROM tbl_student_evaluation where StudentId='{$PantherId}'";
                                //echo $sql1;
                                $result1 = $mysqli->query($sql1);

                                if ($result1->num_rows > 0) {
                                    $Status = "Assigned";
                                }
                                $html="";
                                $html.="<tr class='odd gradeX'>";

                                $html .= '<td><a href="./forms.php?PantherId='.$PantherId.'">00'.$PantherId.'</a></td>';
                                $html .= '<td>'.$FirstName.'</td>';
                                // $html .= '<td>'.$MiddleName.'</td>';
                                if(!empty($linkaddress))
                                {
                                    $html .= '<td><a href="'.$linkaddress.'" target="_blank">'.$LastName.'</td>';
                                }
                                else
                                {
                                    $html .= '<td>'.$LastName.'</td>';
                                }
                                $html .= '<td>'.$Program.'</td>';
                                $html .= '<td>'.$Term.'</td>';
                                $html .= '<td>'.$Concentration.'</td>';
                                echo $html;
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

