<?php
// include Database connection file
include("db_connection.php");

// check request
if(isset($_POST['id']) && isset($_POST['id']) != "")
{
    // get User ID
    $user_id = $_POST['id'];

    // Get User Details
    $query = "SELECT * FROM tbl_faculty_info WHERE PantherID = '$user_id'";
    if (!($result = $mysqli->query($query))) {
        exit(mysql_error());
    }
    $response = array();
     if($result->num_rows > 0){
       while($row = $result->fetch_row()){
            $response['FirstName'] = $row[1];
            $response['LastName'] = $row[3];
            $response['email'] = $row[4];
            $response['mobile'] = $row[5];
            $response['department'] = $row[6];	
            $response['location'] = $row[7];	
        }
    }
    else
    {
        $response['status'] = 200;
        $response['message'] = "Data not found!";
    }
    // display JSON data
    echo json_encode($response);
}
else
{
    $response['status'] = 200;
    $response['message'] = "Invalid Request!";
}
