<?php

	include "db.php";
	$password = 'sdf';
	if (isset($_COOKIE['password']))
		$password = $_COOKIE['password'];

	if($_COOKIE['loggedperms'] == "player") {

		$sql = "select player from login";
		$result = $con->query($sql);

	    while ($row = $result->fetch_assoc()) {
	        $pass = $row["player"];
	        if ($pass == $password) {
				setcookie('loggedperms', "player", time()+60*60*24*30, "/");
	        	return;
	        }
	    }

	} else if ($_COOKIE['loggedperms'] == "dm") {

		$sql = "select dm from login";
		$result = $con->query($sql);

	    while ($row = $result->fetch_assoc()) {
	        $pass = $row["dm"];
	        if ($pass == $password) {
				setcookie('loggedperms', "dm", time()+60*60*24*30, "/");
	        	return;
	        }
	    }
	}

	echo "<script>window.location.href = 'http://'+window.location.hostname + '/login'</script>";