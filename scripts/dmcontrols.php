<?php


	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		
		include "db.php";
		if(isset($_POST["insert-custom"])) {
			// adding custom creature to battle
			$id = $_POST["name"];
			$relationship = $_POST["relationship"];
			$hp = $_POST["hp"];
			$maxhp = $_POST["maxhp"];
			$hidden = isset($_POST["hidden"]) ? "1" : "0";
			$init = $_POST["init"];

			$ac = "null";
			if ($_POST["ac"] !== "")
				$ac = $_POST["ac"];
			$level = "null";
			if ($_POST["level"] !== "")
				$level = $_POST["level"];
			$passpc = "null";
			if ($_POST["passperception"] !== "")
				$passpc = $_POST["passperception"];
			$passiv = "null";
			if ($_POST["passinvestigation"] !== "")
				$passiv = $_POST["passinvestigation"];
			$passis = "null";
			if ($_POST["passinsight"] !== "")
				$passis = $_POST["passinsight"];
			$dv = "null";
			if ($_POST["darkvision"] !== "")
				$dv = $_POST["darkvision"];

			$notes = "No notes added.";
			$sql = "insert into npcinfo values ('".$id."',". $level .",". $maxhp .",". $ac .",". $passpc .",". $passiv .",". $passis .",". $dv .",'". $relationship ."',". $hidden .",\"".$notes."\", \"\")";
			$con->query($sql);

			$notes = "";
			$sql = "insert into tempinfo values ('".$id."', ". $hp .",". $init. ",0,0,0)";
			$con->query($sql);
			$sql = "insert into conditions values ('". $id ."', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);";
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
					$con->query($sql1);
				}
			}

		} else {
			// using game controls
			if (isset($_POST["exit"])) {
				// exit battle
				$sql = "delete from tempinfo where id='".$_POST["selected"]."'";
				$con->query($sql);
				$sql = "delete from conditions where id='".$_POST["selected"]."'";
				$con->query($sql);
				$sql = "delete from npcinfo where id='".$_POST["selected"]."'";
				$con->query($sql);
			} else if (isset($_POST["hide"])) {
				$sql = "select player from tempinfo where id='". $_POST["selected"] ."'";
				$result = $con->query($sql)->fetch_assoc()["player"];
				if ($result == "0") {
					$val;
					$sql = "select hidden from npcinfo where id='". $_POST["selected"] ."'";
					$result = $con->query($sql)->fetch_assoc()["hidden"];
					if ($result == "1")
						$val = "0";
					else
						$val = "1";
					$sql = "update npcinfo set hidden=".$val." where id='".$_POST["selected"]."';";
					$con->query($sql);
				}
			} else if (isset($_POST["set-relationship"])) {
				$sql = "select player from tempinfo where id='". $_POST["selected"] ."'";
				$result = $con->query($sql)->fetch_assoc()["player"];
				if ($result == "0") {
					$sql = "update npcinfo set hostility='".$_POST["relationship"]."' where id='". $_POST["selected"] ."'";
					$con->query($sql);
				}
				
			} else if (isset($_POST["prev"])) {
				
				$sql = "select battlestate from dmstates where id=0";
				$result = $con->query($sql);
				$state = $result->fetch_assoc()["battlestate"];
				if ($state !== "idle") {
					include "getlastplayer.php";
					$sql = "select * from tempinfo where id='".$lastplayer."';";
					$result = $con->query($sql);
					$row = $result->fetch_assoc();
					$val = (int)$row["currentturn"];
					$val--;

					$sql = "update tempinfo set currentturn=".$val." where id='".$lastplayer."'";
					$con->query($sql);
					
					while(true) {

						include "getcurrentturn.php";
						include "getlastplayer.php";

						$sql = "select * from tempinfo where id='". $currentplayer ."';";
						$result = $con->query($sql);
						$row = $result->fetch_assoc();
						if ($row["player"] == "1")
							break;

						$sql = "select hidden from npcinfo where id='". $currentplayer ."';";
						$result = $con->query($sql);
						$row = $result->fetch_assoc();
						if ($row["hidden"] == "0")
							break;

						$sql = "select * from tempinfo where id='".$lastplayer."';";
						$result = $con->query($sql);
						$row = $result->fetch_assoc();
						$val = (int)$row["currentturn"];
						$val--;

						$sql = "update tempinfo set currentturn=".$val." where id ='". $lastplayer ."';";
						$con->query($sql);
					}
				}
			} else if (isset($_POST["next"])) {
				
				$sql = "select battlestate from dmstates where id=0";
				$result = $con->query($sql);
				$state = $result->fetch_assoc()["battlestate"];
				if($state !== "idle") {
					include "getcurrentturn.php";
					$sql = "select * from tempinfo where id='".$currentplayer."';";
					$result = $con->query($sql);
					$row = $result->fetch_assoc();
					$val = (int)$row["currentturn"];


					include "getcurrentturn.php";
					$val++;

					$sql = "update tempinfo set currentturn=".$val." where id ='".$currentplayer ."';";
					$con->query($sql);
					
					while(true) {

						include "getcurrentturn.php";

						$sql = "select * from tempinfo where id='". $currentplayer ."';";
						$result = $con->query($sql);
						$row = $result->fetch_assoc();
						if ($row["player"] == "1")
							break;
						$val = (int)$row["currentturn"];
						$val++;

						$sql = "select hidden from npcinfo where id='". $currentplayer ."';";
						$result = $con->query($sql);
						$row = $result->fetch_assoc();
						if ($row["hidden"] == "0")
							break;

						$sql = "update tempinfo set currentturn=".$val." where id ='". $currentplayer ."';";
						$con->query($sql);
					}
				}
			} else if (isset($_POST["start"])) {
				$sql = "update dmstates set battlestate='ongoing' where id = 0;";
				$con->query($sql);
			} else if (isset($_POST["kill"])) {
				$sql = "update dmstates set battlestate='idle' where id = 0;";
				$con->query($sql);
			} else if (isset($_POST["clear"])) {
				include "resetnpc.php";
				include "resettemp.php";
				include "resetconds.php";
			} else if (isset($_POST["force"])) {
				
				$found = false;
				$sql = "select id from tempinfo";
				$result = $con->query($sql);
				while($row = $result->fetch_assoc()) {
					if ($row["id"] == $_POST["global-player"]) {
						$found = true;
						break;
					}
				}
				if (!$found) {
					$sql1 = "insert into tempinfo values ('". $_POST["global-player"] ."',". $_POST["hp"] .",". $_POST["init"] .", 0, 0, 1);";
					$sql2 = "insert into conditions values ('". $_POST["global-player"] ."', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);";
					$con->query($sql1);
					$con->query($sql2);

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
							$counter = 0;
							$prev = null;
							while($row = $result->fetch_assoc()) {
								if ($row["id"] == $_POST["global-player"])
									if ($prev == null) {
										$turn = (int)($result->fetch_assoc()["currentturn"])+1;
									} else {
										$turn = (int)$prev["currentturn"];
									}

								$prev = $row;
							}

							$sql1 = "update tempinfo set currentturn=". $turn ." where id='". $_POST["global-player"] ."';";
							$con->query($sql1);
						}
					}
				}
			}
		}
	}


	$con->close();
	echo "<script>window.location.href = '../dmview'</script>";