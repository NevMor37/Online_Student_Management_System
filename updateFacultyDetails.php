<?php
// include Database connection file
include("db_connection.php");

// check request
if(isset($_POST))
{
    // get values
    $id = $_POST['id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $address = $_POST['address'];
    $department = $_POST['department'];

    // Updaste User details
    $query = "UPDATE tbl_faculty_info SET FirstName = '$first_name', LastName = '$last_name', email = '$email', MobileNumber = '$mobile', Location = '$address', Department = '$department' WHERE PantherID = '$id'";
    if (!($result = $mysqli->query($query))) {
        exit(mysql_error());
    }
}
