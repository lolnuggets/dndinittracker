<?php

	include "../scripts/db.php";

	if ($_COOKIE['loggedperms'] == "player") {
		// if you are dm
		
		$selected = $_POST["name"];

        $sql = "select * from characterinfo where id = '". $selected ."';";
		$result = $con->query($sql);
		$row = $result->fetch_assoc();
	
		$name = $row["id"];
		$lvl = $row["level"];
		$maxhp = $row["maxhp"];
		$ac = $row["ac"];
		$passper = $row["passper"];
		$passinv = $row["passivs"];
		$passins = $row["passins"];
		$dv = $row["darkvision"];

		$sql = "select * from tempinfo where id = '". $selected ."';";
		$result = $con->query($sql);
		$row = $result->fetch_assoc();

		if (!empty($row)) {
			// see if currently in battle
			$hp = $row["hp"];
			$init = $row["initiative"];
			$conditions = "none";

			$sql = "select * from conditions where id = '". $selected ."';";
			$result = $con->query($sql);
			$row = $result->fetch_assoc();

			if (!empty($row)) {
				// do something
				$counter = 0;
				$conditions = "";
				foreach($row as $condition=>$stt) {
					if ($condition !== "id" && $stt) {
						if ($counter !== 0)
							$conditions = $conditions."/";
						if ($condition == "exhaustion")
							$conditions= $conditions.$stt.$condition;

						$counter++;
					}
				}
			}


		} else {
			$conditions = "none";
			$hp = 0;
			$init = 0;
		}

		$sql = "select * from characterinfo";
		$result = $con->query($sql);

		$totalhp = 0;
		$totallvl = 0;
		$totalmembers = 0;
		$counter = 0;

		$members = "none";
		while($row = $result->fetch_assoc()) {
			$playername = $row["id"];
			$level = $row["level"];
			$totalhp += $row["maxhp"];
			$totallvl += $level;
			$totalmembers++;

			if ($members == "none")
				$members = "";
			if ($counter !== 0)
				$members= $members."/";
			$members= $members.$level.$playername;
			$counter++;
		}

        echo $name ."\\". $lvl ."\\". $maxhp ."\\". $ac ."\\". $passper ."\\". $passinv ."\\". $passins ."\\". $dv ."\\". $hp ."\\". $init ."\\". $conditions."\\". $members ."\\". $totalmembers ."\\". $totalhp ."\\". $totallvl / $totalmembers;


	} else if ($_COOKIE['loggedperms'] == "dm" && isset($_POST["name"])) {
		// if you are dm
		
		$selected = $_POST["name"];

        $sql = "select * from characterinfo where id = '". $selected ."';";
		$result = $con->query($sql);
		$row = $result->fetch_assoc();
	
		$name = $row["id"];
		$lvl = $row["level"];
		$maxhp = $row["maxhp"];
		$ac = $row["ac"];
		$passper = $row["passper"];
		$passinv = $row["passivs"];
		$passins = $row["passins"];
		$dv = $row["darkvision"];

		$sql = "select * from tempinfo where id = '". $selected ."';";
		$result = $con->query($sql);
		$row = $result->fetch_assoc();

		if (!empty($row)) {
			// see if currently in battle
			$hp = $row["hp"];
			$init = $row["initiative"];
			$conditions = "none";

			$sql = "select * from conditions where id = '". $selected ."';";
			$result = $con->query($sql);
			$row = $result->fetch_assoc();

			if (!empty($row)) {
				// do something
				$counter = 0;
				$conditions = "";
				foreach($row as $condition=>$stt) {
					if ($condition !== "id" && $stt) {
						if ($counter !== 0)
							$conditions = $conditions."/";
						if ($condition == "exhaustion")
							$conditions= $conditions.$stt.$condition;

						$counter++;
					}
				}
			}


		} else {
			$conditions = "none";
			$hp = 0;
			$init = 0;
		}

		$sql = "select * from characterinfo";
		$result = $con->query($sql);

		$totalhp = 0;
		$totallvl = 0;
		$totalmembers = 0;
		$counter = 0;

		$members = "none";
		while($row = $result->fetch_assoc()) {
			$playername = $row["id"];
			$level = $row["level"];
			$totalhp += $row["maxhp"];
			$totallvl += $level;
			$totalmembers++;

			if ($members == "none")
				$members = "";
			if ($counter !== 0)
				$members= $members."/";
			$members= $members.$level.$playername;
			$counter++;
		}

        echo $name ."\\". $lvl ."\\". $maxhp ."\\". $ac ."\\". $passper ."\\". $passinv ."\\". $passins ."\\". $dv ."\\". $hp ."\\". $init ."\\". $conditions."\\". $members ."\\". $totalmembers ."\\". $totalhp ."\\". $totallvl / $totalmembers;

	} else {
		// edge case guard
		if ($_COOKIE['loggedperms'] !== "dm")
			echo "<div class=\"msg-box\"><h1>Failed to find selected character</h1></div>";
	}


	$con->close();
?>