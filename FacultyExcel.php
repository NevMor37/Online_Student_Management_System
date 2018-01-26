<?php

$html="";

$html.='<div class="row">';
$html.='<div class="col-lg-12">';
$html.='<div class="panel panel-default">';
$html.='<div class="panel-heading">';
$html.='Faculty Information';
$html.='</div>';
$html.='<div class="panel-body"  style="overflow:auto">';
$html.='<table width="100%" class="table table-striped table-bordered table-hover" id="facultyTables-info">';

include('../../osms.dbconfig.inc');
$error_message = "";
$counter = 0;

$mysqli = new mysqli($hostname, $username, $password, $dbname);

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}


$sql = "SELECT  * FROM tbl_faculty_info order by PantherID";
$result = $mysqli->query($sql);
$row_cnt = $result->num_rows;


if ($result->num_rows > 0) {
    // output data of each row
    $count = 0;
    while ($row = $result->fetch_assoc()) {
        $count++;
        //if($count)
        #echo "username: " . $row["userid"]. " - password : " . $row["password"]. " -email: " . $row["email"]. "<br>";
        $PantherId=$row["PantherID"];
        $FirstName=$row["FirstName"];
        //$MiddleName=$row["MiddleName"];
        $LastName=$row["LastName"];
        $EMail=$row["email"];
        $MobileNumber=$row["MobileNumber"];
        $Location=$row["Location"];
        $Department=$row["Department"];




        if ($count ==1) {
            $html.="<thead>";
            $html.="<tr>";

            $html .= '<th>'.'Id'.'</th>';
            $html .= '<th>First Name</th>';
            //$html .= '<th>Middle Name</th>';
            $html .= '<th>Last Name</th>';
            $html .= '<th>Email</th>';
            $html .= '<th>'.'MobileNumber'.'</th>';
            $html .= '<th>'.'Location'.'</th>';
            $html .= '<th>'.'Department'.'</th>';
            $html .= '<th>'.'Update'.'</th>';
            $html .= '<th>'.'Delete'.'</th>';

            $html.="</tr>";
            $html.="</thead>";
            $html.="<tbody>";
        } elseif ($PantherId!=0) {
            $html.="<tr class='odd gradeX'>";

            $html .= "<td><input type='checkbox' value=".$PantherId.'> '.$PantherId.'</td>';
            $html .= '<td>'.$FirstName.'</td>';
            //$html .= '<td>'.$MiddleName.'</td>';
            $html .= '<td>'.$LastName.'</td>';
            $html .= '<td>'.$EMail.'</td>';
            $html .= '<td>'.$MobileNumber.'</td>';
            $html .= '<td>'.$Location.'</td>';
            $html .= '<td>'.$Department.'</td>';
            $html .= '<td><button onclick="GetUserDetails('.$PantherId.')" class="btn btn-warning">Update</button></td>';
            $html .= '<td><button onclick="DeleteUser('.$PantherId.')" class="btn btn-danger">Delete</button></td>';
            $html .= "</tr>";
        }
    }
} else {
    echo "0 results";
}


$mysqli->close();



$html.='</tbody>';
$html.='</table>';
// $html.='<div class="well">';
// $html.='<h4>DataTables Usage Information</h4>';
// $html.='<p>DataTables is a very flexible, advanced tables plugin for jQuery. In SB Admin, we are using a specialized version of DataTables built for Bootstrap 3. We have also customized the table headings to use Font Awesome icons in place of images. For complete documentation on DataTables, visit their website at <a target="_blank" href="https://datatables.net/">https://datatables.net/</a>.</p>';
// $html.='<a class="btn btn-default btn-lg btn-block" target="_blank" href="https://datatables.net/">View DataTables Documentation</a>';
// $html.='</div>';
$html.='</div>';
$html.='</div>';
$html.='</div>';
$html.='</div>';

echo $html;
