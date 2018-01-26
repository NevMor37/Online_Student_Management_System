<?php

// Connection variables 
// $host = "localhost"; // MySQL host name eg. localhost
// $user = "root"; // MySQL user. eg. root ( if your on localserver)
// $password = ""; // MySQL user password  (if password is not set for your root user then keep it empty )
// $database = "test"; // MySQL Database name
$host='localhost';
$user='ogmstest';
$password='ogms123';
$database='ogmstest';
// Connect to MySQL Database 
$mysqli = new mysqli($host,$user, $password,$database);
// Select MySQL Database 
//mysql_select_db($database, $db);

?>