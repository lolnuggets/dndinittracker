<?php
	
	// post for verifying login
	include "db.php";

	if ($_SERVER["REQUEST_METHOD"] == "POST") {

		$password = $_POST["password"];

		if(isset($_POST["playersubmit"])) {

			$sql = "select player from login";
			$result = $con->query($sql);

		    while ($row = $result->fetch_assoc()) {
		        $pass = $row["player"];
		        if ($pass == $password) {
		        	$value = "player";
					setcookie('loggedperms', $value, time()+60*60*24*30, "/");
					setcookie('password', $password, time()+60*60*24*30, "/");
		        }
		    }

		} else if (isset($_POST["dmsubmit"])) {

			$sql = "select dm from login";
			$result = $con->query($sql);

		    while ($row = $result->fetch_assoc()) {
		        $pass = $row["dm"];
		        if ($pass == $password) {
		        	$value = "dm";
					setcookie('loggedperms', $value, time()+60*60*24*30, "/");
					setcookie('password', $password, time()+60*60*24*30, "/");
					if (isset($_COOKIE['selected'])) {
					    unset($_COOKIE['selected']); 
					    setcookie('selected', '', -1, '/'); 
					}
		        }
		    }


		} else {
			echo "Error fail submit";
		}
	} else {
		echo "fail post";
	}

	$con->close();
	echo "<script>window.location.href = '../'</script>";