<?php


	if ($_SERVER["REQUEST_METHOD"] == "GET") {

		include "db.php";

		$sql= "select battlestate from dmstates where id=0;";
		$result = $con->query($sql)->fetch_assoc()["battlestate"];
		echo $result."/";

		$sql = "select * from tempinfo order by initiative desc, initprio desc";
		$result = $con->query($sql);
		if (mysqli_num_rows($result) == 0) {
			$sql = "update dmstates set battlestate='idle' where id=0";
			$con->query($sql);
			echo "EMPTY";
			return;
		}

		$return = "";

		while($row = $result->fetch_assoc()) {

			include "getcurrentturn.php";

			if (isset($current))
				echo "|";

			$current = "false";
			if ($row["id"] == $currentplayer)
				$current = "true";

			$isplayer = "true";
			if ($row["player"] == "0")
				$isplayer = "false";

			echo  $row["id"]."\\".$row["hp"]."\\".$row["initiative"]."\\".$current;

			if ($isplayer == "true") {
				$sql = "select * from characterinfo where id='".$row["id"]."';";
				$charrow = $con->query($sql)->fetch_assoc();

				echo "\\". $charrow["maxhp"]."\\".$charrow["ac"]."\\PLAYER";
			} else {
				$sql = "select * from npcinfo where id='".$row["id"]."';";
				$charrow = $con->query($sql)->fetch_assoc();

				$hidden = "true";
				if ($charrow["hidden"] == "0")
					$hidden = "false";

				$ac = "none";
				if (isset($charrow["ac"]))
					$ac = $charrow["ac"];

				echo "\\". $charrow["maxhp"]."\\".$ac."\\".$charrow["hostility"]."\\".$hidden;
			}
			//echo $return;
		}
	}