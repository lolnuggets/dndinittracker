<?php

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		// script for adding a character through a posted form on page CHARACTERS
		include "db.php";
		
		$id = $_POST["name"];
		$lvl = $_POST["level"];
		$mhp = $_POST["maxhp"];
		$ac = $_POST["ac"];
		$pper = $_POST["passperception"];
		$pinv = $_POST["passinvestigation"];
		$pins = $_POST["passinsight"];
		$dv = $_POST["darkvision"];

		$sql = "insert into characterinfo values ('" . $id . "'," . $lvl . "," . $mhp . "," . $ac . "," . $pper . "," . $pinv . "," . $pins . "," . $dv . ")";
		$result = $con->query($sql);
		$con->close();
	}

echo "<script>
			function getHost() {
			    let hostname = window.location.hostname;
			    if (hostname == \"localhost\")
			        hostname += \"/dndinittracker\";
			    return hostname;
			}
			window.location.href = 'http://'+ getHost() + '/characters';
		</script>";