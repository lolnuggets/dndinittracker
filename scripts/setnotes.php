<?php

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		include "db.php";
		$sql = "update npcinfo set notes=\"".$_POST["notes"]."\" where id='".$_POST["id"]."';";
		$con->query($sql);
		$con->close();
	}