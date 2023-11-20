<?php

	if ($_SERVER["REQUEST_METHOD"] == "GET") {
		include "db.php";

		$sql = "select * from characterinfo";
		$result = $con->query($sql);
		$players = "";

	    while ($row = $result->fetch_assoc()) {

	    	$name = $row["id"];
	    	$lvl = $row["level"];
	    	$maxhp = $row["maxhp"];
	    	$ac = $row["ac"];
	    	$passper = $row["passper"];
	    	$passinv = $row["passivs"];
	    	$passins = $row["passins"];
	    	$dv = $row["darkvision"];

	    	if ($players !== "")
	    		$players = $players.", ";

	    	$players = $players. "{ \"name\": \"".$name."\", \"level\": ". $lvl .", \"maxhp\": ". $maxhp .", \"armorclass\": ". $ac .", \"passives\": { \"perception\": ". $passper .", \"investigation\": ". $passinv .", \"insight\": ". $passins .", \"darkvision\": ". $dv ." } }";
	    }
	    echo "{ \"results\": [ ". $players ." ] }";
	}