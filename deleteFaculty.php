<?php
// check request
if(isset($_POST['id']) && isset($_POST['id']) != "")
{
    // include Database connection file
    include("db_connection.php");

    // get user id
    $user_id = $_POST['id'];

    // delete User
    $query = "DELETE FROM tbl_faculty_info WHERE PantherID = '$user_id'";
    if (!($result = $mysqli->query($query))) {
        exit(mysql_error());
    }
}
?>
