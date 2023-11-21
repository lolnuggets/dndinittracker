<?php

	include "db.php";
	$password = '';
	if (isset($_COOKIE['password']))
		$password = $_COOKIE['password'];

	$loggedperms = "";
	if (isset($_COOKIE['loggedperms']))
		$loggedperms = $_COOKIE['loggedperms'];

	if($loggedperms == "player") {

		$sql = "select player from login";
		$result = $con->query($sql);

	    while ($row = $result->fetch_assoc()) {
	        $pass = $row["player"];
	        if ($pass == $password) {
				setcookie('loggedperms', "player", time()+60*60*24*30, "/");
	        	return;
	        }
	    }

	} else if ($loggedperms == "dm") {

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

	echo "<script>
			function getHost() {
			    let hostname = window.location.hostname;
			    if (hostname == \"localhost\")
			        hostname += \"/dndinittracker\";
			    return hostname;
			}
			window.location.href = 'http://'+ getHost() + '/login';
		</script>";