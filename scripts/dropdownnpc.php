<?php

	if ($_SERVER["REQUEST_METHOD"] == "POST") {

		include "db.php";

		$player = $_POST["id"];

		$sql = "select * from npcinfo where id='". $player ."'";
		$row = ($con->query($sql))->fetch_assoc();

    	$maxhp = $row["maxhp"];

    	$lvl = "unset";
    	if (isset($row["level"]))
    		$lvl = $row["level"];

    	$passper = "unset";
    	if (isset($row["passper"]))
    	$passper = $row["passper"];

    	$passinv = "unset";
    	if (isset($row["passivs"]))
    	$passinv = $row["passivs"];

    	$passins = "unset";
    	if (isset($row["passins"]))
    	$passins = $row["passins"];

    	$dv = "unset";
    	if (isset($row["darkvision"]))
    	$dv = $row["darkvision"];

    	$api = "unset";
    	if (isset($row["statcard"]))
    		$api = str_replace('-',' ',$row["statcard"]);

		$sql = "select * from conditions where id='". $player ."'";
		$row = ($con->query($sql))->fetch_assoc();
		$exhaustion = $row["exhaustion"];

		$rcon = "none";
		$first = true;
		foreach($row as $condition=>$stt)
			if (($condition !== "id" && $condition !== "exhaustion")&& $stt) {
				if ($rcon == "none")
					$rcon = "";
				if (!$first) {
					$rcon = $rcon."/";
				} else {
					$first = false;
				}

				$rcon= $rcon.$condition;
			}
		echo $lvl ."-". $maxhp ."-". $passper ."-". $passinv ."-". $passins ."-". $dv ."-".$rcon ."-". $exhaustion ."-". $api;
	}