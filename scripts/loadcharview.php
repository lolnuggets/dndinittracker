<?php

	include "../scripts/db.php";

	if ($_COOKIE['loggedperms'] == "player") {
		if (isset($_COOKIE['selected'])) {

			$selected = $_COOKIE['selected'];

			$sql = "select * from characterinfo where id = '". $selected ."'";
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

			// load here
      		echo "<div class=\"msg-box\" style=\"display:inline-block;vertical-align:top;\">
                <h1>". $name ."</h1>
                <p>level ". $lvl ."<br>". $maxhp ." max hp&emsp;". $ac ." AC</p>
                <p><br>passives:<br>". $passper ." perception&emsp;". $passinv ." investigation</p>
                <p>". $passins ." insight&emsp;Darkvision ". $dv ."ft</p>
            </div>";


			$sql = "select * from tempinfo where id = '". $selected ."'";
			$result = $con->query($sql);
			$row = $result->fetch_assoc();
			if (!empty($row)) {
				// see if currently in battle
				$hp = $row["hp"];
				$init = $row["initiative"];

	      		echo "<div class=\"msg-box\" style=\"display:inline-block;vertical-align:top;\">
	                <h1>Party: HEARTS OF IRON</h1>
	                <p>". $hp ."/". $maxhp ."hp</p>
	                <p>Currently in battle?<br>TRUE, initiative:". $init ."</p><p>Conditions:<br>";

				$sql = "select * from conditions where id = '". $selected ."'";
				$result = $con->query($sql);
				$row = $result->fetch_assoc();
				if (!empty($row)) {
					// do something
					$counter = 0;
					foreach($row as $condition=>$stt) {
						if ($condition !== "id" && $stt) {

							if ($counter !== 0)
								echo ", ";
							if ($counter !== 0 && $counter % 3 == 0)
								echo "\n";

							echo $condition;
							if ($condition == "exhaustion")
								echo "(". $stt .")";

							$counter++;
						}
					}
				} else {
					echo "None";
				}
				echo "</p>
            			</div>";


			} else {
	      		echo "<div class=\"msg-box\" style=\"display:inline-block;vertical-align:top;\">
	                <h1>Party: HEARTS OF IRON</h1>
	                <p>". $maxhp ."/". $maxhp ."hp</p>
	                <p>Currently in battle?<br>FALSE</p>
	                <p>Conditions:<br>None</p>
            	</div>";

			}

            echo "<div class=\"msg-box\" style=\"display:inline-block;vertical-align:top;\">
                <h1>Hearts of Iron</h1><p>Members (5):<br><div style=\"text-align:left;position:relative;left:40px\">";

			$sql = "select * from characterinfo";
			$result = $con->query($sql);

			$totalhp = 0;
			$totallvl = 0;
			$totalmembers = 0;
			$counter = 0;

			while($row = $result->fetch_assoc()) {
				$playername = $row["id"];
				$level = $row["level"];
				$totalhp += $row["maxhp"];
				$totallvl += $level;
				$totalmembers++;

				if ($counter !== 0)
					echo ", ";
				if ($counter !== 0 && $counter % 2 == 0)
					echo "<br>";
				echo $playername . "(lv.". $level .")";
				$counter++;
			}

            echo 	"</div></p>
            		<p>Total HP<br>". $totalhp ."</p>
            		<p><br>Average Level (". $totallvl / $totalmembers .")</p>
        		</div>
        		<img src=\"../resources/hoi128.png\" alt=\"Party Emblem\" style=\"position:relative;left:-100px;top:70px\">";

		} else {
			echo "no character selected";
		}

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