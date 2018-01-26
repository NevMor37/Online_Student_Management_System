<?php
//session_start();
//
////connect to database
//$db=mysqli_connect("localhost","root","hu1015","authentication");

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
$db=$mysqli;
include ($root.'/PHPExcel/IOFactory.php');
        $instructorarray = array();
        $sql = "select PantherID,email, 
                CONCAT(coalesce(FirstName,' ') , IF(MiddleName = '', ' ', IFNULL(MiddleName,' ')),coalesce(LastName,' ')) as Name
                from tbl_faculty_info  ";
        //echo $sql . '<br>';
        $result = mysqli_query($db, $sql);

        if ($result->num_rows > 0) {
            $i = 0;
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                $instructorarray[$i] = array();
                $instructorarray[$i]['PantherID'] = $row["PantherID"];
                $instructorarray[$i]['email'] = $row["email"];
                $instructorarray[$i]['Name'] = $row["Name"];
                $i = $i + 1;
            }
        }

        $sql = "select PantherID,email, CONCAT(coalesce(FirstName,' ') , IF(MiddleName = '', ' ', IFNULL(MiddleName,' ')),coalesce(LastName,' ')) as Name
                                        from tbl_student_info
                                        where Position = 'instructor'
                                        order by LastName
                            ";
        $result = mysqli_query($db, $sql);

        if ($result->num_rows > 0) {
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                $instructorarray[$i] = array();
                $instructorarray[$i]['PantherID'] = $row["PantherID"];
                $instructorarray[$i]['email'] = $row["email"];
                $instructorarray[$i]['Name'] = $row["Name"];
                $i = $i + 1;
            }
        }

        $cur_termid = '3';
        $cur_degree = 'all';
        $cur_faculty = 'all';
        $sql = "
              select DATE_FORMAT(te.Startday,'%Y%m') as Startday,ga.PantherID,ga.FirstName,ga.LastName,
                      ga.Degree,ga.Email,cou.Course,cou.CRN,sc.Facultyid,ga.Status
                from tbl_gaapplication as ga
                LEFT JOIN tbl_term as te on te.Termid=ga.TermID
                LEFT JOIN tbl_taassignment_extra as ex on ex.PantherID = ga.PantherID
                LEFT JOIN tbl_taassignment_info as inf on inf.TAAssignmentID = ex.TAAssignmentID
                LEFT JOIN tbl_course as cou on cou.Courseid = inf.CourseID
                LEFT JOIN tbl_schedule as sc on sc.Courseid = cou.Courseid and sc.Termid = te.Termid 
                and sc.Instance=1";
        $sql=$sql ."  where ";
        if(empty($cur_termid) || $cur_termid=='all')
        {
            $sql=$sql ."  ga.TermID <> -1 ";
        }
        else
        {
            $sql=$sql ."  ga.TermID =$cur_termid ";
        }
        $sql=$sql ." and ";
        if(empty($cur_degree) || $cur_degree=='all')
        {
            $sql=$sql ."  ga.Degree <> -1 ";
        }
        else
        {
            $sql=$sql ."  ga.Degree =$cur_degree ";
        }
        $sql=$sql ." and ";
        if(empty($cur_faculty) || $cur_faculty=='all')
        {
            $sql=$sql ."  sc.Facultyid <> -1 ";
        }
        else
        {
            $sql=$sql ."  sc.Facultyid ='$cur_faculty' ";
        }
        $sql=$sql ."  order by te.Startday,ga.Degree,ga.LastName,ga.FirstName
                                         ";
        //echo $sql;
        $result = mysqli_query($db, $sql);

        // Instantiate a new PHPExcel object
        $objPHPExcel = new PHPExcel();
        // Set the active Excel worksheet to sheet 0
        $objPHPExcel->setActiveSheetIndex(0);
        // Initialise the Excel row number
        $rowCount = 1;
        // Iterate through each result from the SQL query in turn
        // We fetch each database result row into $row in turn
        if ($result->num_rows > 0) {
            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, 'Term');
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, 'Pantherid');
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, 'Last Name');
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, 'First Name');
            $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, 'Degree');
            $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, 'Email');
            $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, 'Class');
            $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, 'Faculty');
            $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, 'CRN');
            $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, 'Status');
            // Increment the Excel row counter
            $rowCount++;
            while ($row = mysqli_fetch_assoc($result)) {
                $currentinstructor = $row["Facultyid"];
                $m_currentinstructor = '';
                foreach ($instructorarray as $p_arr) {
                    $email = $p_arr["email"];
                    $Name = $p_arr["Name"];
                    //echo '$email:' .$email;
                    //echo '$Name:' .$Name;
                    if ($currentinstructor == $email) {
                        $m_currentinstructor = $Name;
                    }
                }
                //            select DATE_FORMAT(te.Startday,'%Y%m') as Startday,ga.PantherID,ga.FirstName,ga.LastName,
                //                      ga.Degree,ga.Email,cou.Course,cou.CRN,sc.Facultyid,ga.Status
                // Set cell An to the "name" column from the database (assuming you have a column called name)
                //    where n is the Excel row number (ie cell A1 in the first row)
                $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $row['Startday']);
                $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $row['PantherID']);
                $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $row['FirstName']);
                $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $row['LastName']);
                $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $row['Degree']);
                $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $row['Email']);
                $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $row['Course']);
                $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $m_currentinstructor);
                $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $row['CRN']);
                $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, $row['Status']);
                // Increment the Excel row counter
                $rowCount++;
            }
        }
        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Report.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');

        echo 'success';

?>
