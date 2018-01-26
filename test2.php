<?php

        // include Database connection file
        include("db_connection.php");

        $query = "select max(PantherID) from tbl_faculty_info";
        echo $query;
        $result = $mysqli->query($query);
				if (!$result){
					echo "dead";
				}
				$row = mysql_fetch_array($result);
				echo $row["PantherID"];

?>
