<?php

	include "db.php";

	$sql = "select id from characterinfo";
	$result = $con->query($sql);

	$rcon = "";
	$first = true;
    while ($row = $result->fetch_assoc()) {
		if (!$first) {
			$rcon = $rcon."/";
		} else {
			$first = false;
		}
    	$rcon=$rcon.$row["id"];
    }

    echo $rcon;