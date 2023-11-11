<?php

	include "db.php";

	$sql = "select * from tempinfo order by initiative desc, initprio desc;";
	$lowestq = $con->query($sql);

	$get_lowest = 9999;
	while($temp_result = $lowestq->fetch_assoc())
		if ((int)$temp_result["currentturn"] < $get_lowest)
			$get_lowest = (int)$temp_result["currentturn"];

	$sql = "select * from tempinfo order by initiative desc, initprio desc;";
	$lowestq = $con->query($sql);

	$lastplayer;
	$index = 0;
	while($temp_result = $lowestq->fetch_assoc()) {
		if ((int)$temp_result["currentturn"] == $get_lowest)
			break;
		$lastplayer = $temp_result["id"];
		$index++;
	}
	if ($index == 0) {
		$sql = "select * from tempinfo order by initiative desc, initprio desc;";
		$lowestq = $con->query($sql);
		while($row = $lowestq->fetch_assoc()) {
			$lastplayer = $row["id"];
		}
	}