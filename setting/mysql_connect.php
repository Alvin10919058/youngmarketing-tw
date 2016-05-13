<?php

		



$host = "localhost";

$user = "youngfbc";

$password = "29SC_Lab49 SISU";

$database = "youngfbc_rcotc";



$domain = "http://youngmarketing-tw.youngfb.com/test";





$con = mysqli_connect($host, $user, $password, $database);

//$con = new mysqli($host, $user, $password, $database);

if (mysqli_connect_errno()) {

  print "Failed to connect to MySQL: " . mysqli_connect_error();

}

