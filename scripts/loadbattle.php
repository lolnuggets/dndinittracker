<?php
	
	include "db.php";
	
	$sql = "select * from tempinfo order by initiative desc, initprio desc;";
	$result = $con->query($sql);
	$first = true;
	if (mysqli_num_rows($result) == 0) { 
		//results are empty, do something here 
		echo "<div class=\"msg-box\">no current battle</div>";
		return;
	}

	$get_lowest = 9999;
	while($temp_result = $result->fetch_assoc())
		if ((int)$temp_result["currentturn"] < $get_lowest)
			$get_lowest = (int)$temp_result["currentturn"];

	$result = $con->query($sql);
	$firstorder = true;
	while($temp_result = $result->fetch_assoc()) {
		$sql = "select * from characterinfo where id='" . $temp_result["id"] . "';";
		$result2 = $con->query($sql);
		$player_result = $result2->fetch_assoc();

		$id = $temp_result["id"];
		$hp = $temp_result["hp"];
		$ac = $player_result["ac"];
		$init = $temp_result["initiative"];
		$hostility = $player_result["hostility"]; // swap out with diff stat

		$mhp = $player_result["maxhp"];
		if ($hp / $mhp >= 1)
			$status = "healthy";
		else if ($hp / $mhp >= 0.85)
			$status = "ok";
		else if ($hp / $mhp >= 0.75)
			$status = "light injuries";
		else if ($hp / $mhp >= 0.5)
			$status = "moderatly injured";
		else if ($hp / $mhp >= 0.25)
			$status = "severe injuries";
		else if ($hp / $mhp >= 0.1)
			$status = "critical damage";
		else if ($hp / $mhp > 0.0)
			$status = "fatal damage";
		else if ($hp / $mhp <= 0.0)
			$status = "dead";

		if (isset($_COOKIE['selected']) && $id !== $_COOKIE['selected']) {
			$hp = "?";
		}

		if ((int)$temp_result["currentturn"] == $get_lowest && $firstorder) {
			echo "<div class=\"initiative-item\" style=\"background-color:#1b3834\" ";
			$firstorder = false;
		} else
			echo "<div class=\"initiative-item\" ";

		if ($first) {
			echo
	    	" id=\"player-". $id ."\">
	    		<div class=\"break\" style=\"left: 512px;\"></div>
	    		<div class=\"break\" style=\"left: 737px;\"></div>
	    		<div class=\"break\" style=\"left: 845px;\"></div>
	    		<div class=\"break\" style=\"left: 955px;\"></div>
	    		<div class=\"break\" style=\"left: 1065px;\"></div>
	    		<div class=\"name hint\">name</div>
	    		<div class=\"status hint\">status</div>
	    		<div class=\"hp hint\">hp</div>
	    		<div class=\"ac hint\">ac</div>
	    		<div class=\"initiative hint\">init</div>
	    		<div class=\"party hint\">relationship</div>
	    		<div class=\"name\" style=\"top: -10px;\">".$id."</div>
	    		<div class=\"status\" style=\"top: -10px;\">".$status."</div>
	    		<div class=\"hp\" style=\"top: -10px;\">".$hp."</div>
	    		<div class=\"ac\" style=\"top: -10px;\">".$ac."</div>
	    		<div class=\"initiative\" style=\"top: -10px;\">".$init."</div>
				<div class=\"party\" style=\"top: -10px;\">".$hostility."</div>
	    	</div>";
	    	$first = false;
	    } else {
	    	echo 
	    	" id=\"player-". $id ."\">
	    		    		<div class=\"break\" style=\"left: 512px;\"></div>
	    		    		<div class=\"break\" style=\"left: 737px;\"></div>
	    		    		<div class=\"break\" style=\"left: 845px;\"></div>
	    		    		<div class=\"break\" style=\"left: 955px;\"></div>
	    		    		<div class=\"break\" style=\"left: 1065px;\"></div>
	    		    		<div class=\"name\">".$id."</div>
	    		    		<div class=\"status\">".$status."</div>
	    		    		<div class=\"hp\">".$hp."</div>
	    		    		<div class=\"ac\">".$ac."</div>
	    		    		<div class=\"initiative\">".$init."</div>
							<div class=\"party\">".$hostility."</div>
	    		    	</div>";
	    }
	}
	$con->close();