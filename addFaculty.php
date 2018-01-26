<?php
	
	if(isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']))
	{
		// include Database connection file
		include("db_connection.php");

		// get values
		$first_name = $_POST['first_name'];
		$last_name = $_POST['last_name'];
		$email = $_POST['email'];
		$mobile = $_POST['mobile'];
		$location = $_POST['address'];
		$department = $_POST['department'];

		$query = "select max(PantherID) as maxid from tbl_faculty_info;";
        
                $result = $mysqli->query($query);
	
	       $row = $result->fetch_assoc();
		$maxid=$row["maxid"]+1;

		$query = "INSERT INTO tbl_faculty_info(PantherID, FirstName, LastName, email, MobileNumber, Department, Location) VALUES('$maxid','$first_name', '$last_name', '$email','$mobile','$department','$location')";

		if (!($result = $mysqli->query($query))) {
	        exit(mysql_error());
	    }
	    echo "1 Record Added!";
	}
	else{
            echo "something is wrong";
	}
         
?>
