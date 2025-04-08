<?php

// Database credentials
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "district_management_database"; 

// Create and check connection
try {
  $conn = mysqli_connect($servername, $username, $password, $dbname);
}
catch (mysqli_sql_exception){
  echo "could not connect! <br>";
}


