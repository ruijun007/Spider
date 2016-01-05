<?php
function sql()
{
	// $con = mysqli_connect("localhost","root","spider"); 
	$con = mysql_connect("localhost","root","spider");
	if (!$con)
  	{
  		die('Could not connect: ' . mysql_error());
  	}else {
  		echo "aaa";
  	}
}
sql();
?>