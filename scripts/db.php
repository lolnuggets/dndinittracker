<?php
		
$host="db-dnd.cd6nxpmmcv4n.us-east-2.rds.amazonaws.com";
$port=3306;
$socket="";
$user="admin";
$password="cNdsxmcWwyxfK#!V4F4EyT7q&L";
$dbname="dnddb";

$con = new mysqli($host, $user, $password, $dbname, $port, $socket);
if ($con->connect_error) {
  die("Connection failed: " . $con->connect_error);
}

/*

LOCAL
<?php
		
$host="localhost";
$port=3306;
$socket="";
$user="root";
$password="";
$dbname="dbdndinit";

$con = new mysqli($host, $user, $password, $dbname, $port, $socket);
if ($con->connect_error) {
  die("Connection failed: " . $con->connect_error);
}

PUBLIC
<?php
		
$host="db-dnd.cd6nxpmmcv4n.us-east-2.rds.amazonaws.com";
$port=3306;
$socket="";
$user="admin";
$password="cNdsxmcWwyxfK#!V4F4EyT7q&L";
$dbname="dnddb";

$con = new mysqli($host, $user, $password, $dbname, $port, $socket);
if ($con->connect_error) {
  die("Connection failed: " . $con->connect_error);
}

*/