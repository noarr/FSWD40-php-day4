<?php 

define('DBHOST', 'localhost');
define('DBUSER', 'cf');
define('DBPASS', '');
define('DBNAME', 'carrentalDB');

$con = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);

if(!$con) {
	die("Connection failed: " . mysqli_error());
} else {
	echo "Connection established!";
}

 ?>