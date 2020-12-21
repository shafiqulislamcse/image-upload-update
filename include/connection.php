<?php 
	$srvername ="localhost";
	$username  ="root";
	$password  ="";
	$db        ="upload";

	$conn = new mysqli($srvername,$username,$password,$db);

	if ($conn -> connect_error) {
		die("Connection Failes". $conn -> connect_error);
	}
 ?>