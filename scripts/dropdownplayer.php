<?php

	if ($_SERVER["REQUEST_METHOD"] == "POST") {

		include "db.php";

		$player = $_POST["id"];

		$sql = "select * from characterinfo where id='". $player ."'";
		$row = ($con->query($sql))->fetch_assoc();

    	$lvl = $row["level"];
    	$maxhp = $row["maxhp"];
    	$passper = $row["passper"];
    	$passinv = $row["passivs"];
    	$passins = $row["passins"];
    	$dv = $row["darkvision"];

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
		echo $lvl ."-". $maxhp ."-". $passper ."-". $passinv ."-". $passins ."-". $dv ."-". $rcon ."-". $exhaustion;
	}