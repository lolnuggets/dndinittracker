<?php

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		include "db.php";
		$sql = "select notes from npcinfo where id='".$_POST["id"]."';";
		echo $con->query($sql)->fetch_assoc()["notes"];
		$con->close();
	}