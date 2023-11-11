<?php

	if ($_SERVER["REQUEST_METHOD"] == "POST") {

		include "db.php";
		$id = $_POST["name"];
		$hp = $_POST["hp"];
		$maxhp = $_POST["maxhp"];
		$hidden = $_POST["hidden"]=="true" ? "1" : "0";
		$init = $_POST["init"];
		$relationship = $_POST["relationship"];
		$notes = $_POST["notes"];
		$api = $_POST["api"];

		/*
		parse dnd5e api here, handling the following: $ac, $passpc, $passis, $dv, $notes
		*/

		$level = "null";
		$ac = $_POST["ac"];
		$passpc = $_POST["passpc"];
		$passis = $_POST["passis"];
		$passiv = $_POST["passiv"];
		$dv = $_POST["dv"];

		$sql = "insert into npcinfo values ('".$id."',". $level .",". $maxhp .",". $ac .",". $passpc .",". $passiv .",". $passis .",". $dv .",'". $relationship ."',". $hidden .",\"".$notes."\",\"". $api ."\");";
		echo $sql;
		$con->query($sql);

		$sql = "insert into conditions values ('".$id."', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);";
		echo $sql;
		$con->query($sql);

		$notes = "";
		$sql = "insert into tempinfo values ('".$id."', ". $hp .",". $init. ",0,0,0)";
		$con->query($sql);
		
		$sql = "select currentturn from tempinfo";
		$result = $con->query($sql);
		$turnsum = 0;

		while ($row = $result->fetch_assoc()) {
			$turnsum += (int)$row["currentturn"];
		}
		
		if ($turnsum !== 0) {
			$sql1 = "select * from tempinfo order by initiative desc, initprio asc";
			$result = $con->query($sql1);

			if (mysqli_num_rows($result) !== 0) {
				$prev = null;
				while($row = $result->fetch_assoc()) {
					if ($row["id"] == $id)
						if ($prev == null) {
							$turn = (int)($result->fetch_assoc()["currentturn"])+1;
						} else {
							$turn = (int)$prev["currentturn"];
						}

					$prev = $row;
				}

				$sql1 = "update tempinfo set currentturn=". $turn ." where id='". $id ."';";
				echo $sql1;
				$con->query($sql1);
			}
		}
		$con->close();
	}