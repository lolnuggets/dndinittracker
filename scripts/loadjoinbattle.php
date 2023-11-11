<?php

	if (isset($_COOKIE['selected'])) {
		include "db.php";

		$sql = "select id from tempinfo";
		$result = $con->query($sql);

		$in_battle = false;

		while($row = $result->fetch_assoc()) {
			if ($row["id"] == $_COOKIE['selected']) {
				$in_battle = true;
				break;
			}
		}

		if ($in_battle) {
			echo
	    	"<div class=\"msg-box\">
	        	<form action=\"../scripts/joinbattle.php\" method=\"post\">
		    		Leave battle?<br>
					<button name=\"leave\">Leave</button>
				</form>
			</div>";

		} else {
			echo
	    	"<div class=\"msg-box\">
        		<form action=\"../scripts/joinbattle.php\" method=\"post\">
		    		Join battle?<br>
			    	<label for=\"initiative\">Initiative: </label>
					<input type=\"number\" id=\"initiative\" name=\"initiative\" style=\"width:40px;\" min=\"0\" step=\"1\" required>
			    	<label for=\"hp\">HPs: </label>
					<input type=\"number\" id=\"hp\" name=\"hp\" style=\"width:40px;\" min=\"0\" step=\"1\" required>
					<button name=\"join\">Join</button>
				</form>
			</div>";
		}
		$con->close();
	} else {
		// what to do when no player selected
			echo
	    	"<div class=\"msg-box\">no player selected</div>";
	}

?>