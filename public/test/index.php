<?php
	define("HOST", "10.200.254.74");
	define("USER", "vldash");
	define("PASS", "$$vldash123$$");
	define("DB", "vl_production");
	
	$con=mysqli_connect(HOST,USER,PASS,DB); 
	if (mysqli_connect_errno())
	  {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	  }
?>