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
		 $dbname, $port, $socket);
if ($con->connect_error) {
  die("Connection failed: " . $con->connect_error);
}

*/
