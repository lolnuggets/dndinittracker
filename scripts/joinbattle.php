<?php

	if ($_SERVER["REQUEST_METHOD"] == "POST") {

		include "db.php";
		if (isset($_COOKIE['selected']) && isset($_POST["join"])) {
			$sql1 = "insert into tempinfo values ('". $_COOKIE['selected'] ."',". $_POST["hp"] .",". $_POST["initiative"] .", 0, 0, 1);";
			$sql2 = "insert into conditions values ('". $_COOKIE['selected'] ."', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);";
		} else if (isset($_COOKIE['selected']) && isset($_POST["leave"])) {
			$sql1 = "delete from tempinfo where id='". $_COOKIE['selected'] ."';";
			$sql2 = "delete from conditions where id='". $_COOKIE['selected'] ."';";
			$con->query($sql1);
			$con->query($sql2);
			$con->close();
			echo "<script>window.location.href=\"../playerview\";</script>";
		}
		$con->query($sql1);
		$con->query($sql2);

		$sql = "select currentturn from tempinfo";
		$result = $con->query($sql);
		$turnsum = 0;

		while ($row = $result->fetch_assoc()) {
			$turnsum += (int)$row["currentturn"];
		}
		
		if ($turnsum !== 0 && isset($_POST["join"])) {
			$sql1 = "select * from tempinfo order by initiative desc, initprio asc";
			$result = $con->query($sql1);

			if (mysqli_num_rows($result) !== 0) {
				$counter = 0;
				$prev = null;
				while($row = $result->fetch_assoc()) {
					if ($row["id"] == $_COOKIE['selected'])
						if ($prev == null) {
							$turn = (int)($result->fetch_assoc()["currentturn"])+1;
						} else {
							$turn = (int)$prev["currentturn"];
						}

					$prev = $row;
				}

				$sql1 = "update tempinfo set currentturn=". $turn ." where id='". $_COOKIE['selected'] ."';";
				$con->query($sql1);
			}
		}

		$con->close();
		echo "<script>window.location.href=\"../playerview\";</script>";
	}