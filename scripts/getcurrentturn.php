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

	$currentplayer;
	while($temp_result = $lowestq->fetch_assoc()) {
		if ((int)$temp_result["currentturn"] == $get_lowest) {
			$currentplayer = $temp_result["id"];
			break;
		}
	}