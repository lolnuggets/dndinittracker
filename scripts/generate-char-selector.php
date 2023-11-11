<?php

	include "db.php";

	$sql = "select * from characterinfo";
	$result = $con->query($sql);

    while ($row = $result->fetch_assoc()) {
    	echo "<option value=\"". $row["id"] ."\">". $row["id"] ."</option>";
	}

	$con->close();