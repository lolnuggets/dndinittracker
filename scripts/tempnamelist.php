<?php
	include "db.php";

	$sql = "select id from tempinfo where player=0";
	$result = $con->query($sql);

	$rcon = "";
	while($row = $result->fetch_assoc()) {
		if ($rcon !== "")
			$rcon = $rcon."/";
		$rcon = $rcon . $row["id"];
	}

	echo $rcon;
	$con->close();