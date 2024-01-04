<?php
		
$host="localhost";
$port=3306;
$socket="";
$user="admin";
$password="";
$dbname="dnddb";

$con = new mysqli($host, $user, $password, $dbname, $port, $socket);
if ($con->connect_error) {
  die("Connection failed: " . $con->connect_error);
}
