<?php
	// script for adding a character through a posted form on page CHARACTERS
	include "db.php";

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		
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
	}

$con->close();
echo "<script>window.location = '../characters'</script>";