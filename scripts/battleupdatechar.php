<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>Hearts of Iron</title>
    <link rel="stylesheet" type="text/css" href="../styles.css">
</head>
<body class="container">

<?php
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		include "db.php";

		if (isset($_POST["edit"])) {

			$database = "character";
			if ($_POST["npc"] == "true")
				$database = "npc";

			$sql = "select * from ".$database."info where id='". $_POST["id"] ."';";
			$result = $con->query($sql);
			$row = $result->fetch_assoc();

			$ac = "null";
			if (isset($row["ac"]))
				$ac = $row["ac"];

			if ($_POST["ac"] !== "")
				$ac = $_POST["ac"];

			$sql = "update ".$database."info set ac=". $ac ." where id='". $_POST["id"] ."';";
			$con->query($sql);
			$sql = "select * from tempinfo where id='". $_POST["id"] ."'";
			$result = $con->query($sql);
			$row = $result->fetch_assoc();

			$hp = $row["hp"];
			$init = $row["initiative"];

			if ($_POST["hp"] !== "")
				$hp = $_POST["hp"];
			if ($_POST["init"] !== "")
				$init = $_POST["init"];

			$sql = "update tempinfo set initiative=". $init .",hp=". $hp ." where id='". $_POST["id"] ."';";
			$con->query($sql);

		} else if (isset($_POST["itr"])) {

			$sql = "select initprio from tempinfo where id='". $_POST["id"] ."';";
			$result = $con->query($sql);
			$row = (int)$result->fetch_assoc()["initprio"];

			$row++;
			$sql = "update tempinfo set initprio=".$row." where id ='". $_POST["id"] ."';";
			$con->query($sql);

		} else if (isset($_POST["deit"])) {

			$sql = "select initprio from tempinfo where id='". $_POST["id"] ."';";
			$result = $con->query($sql);
			$row = (int)$result->fetch_assoc()["initprio"];

			$row--;
			$sql = "update tempinfo set initprio=".$row." where id ='". $_POST["id"] ."';";
			$con->query($sql);

		} else if ((isset($_POST["deal"]) || isset($_POST["heal"])) && isset($_POST["dmg"])) {
	
			$sql = "select hp from tempinfo where id='". $_POST["id"] ."';";
			$result = $con->query($sql);
			$row = (int)$result->fetch_assoc()["hp"];

			if (isset($_POST["deal"])) {
				$row-= (int)$_POST["dmg"];
			} else {
				$row+= (int)$_POST["dmg"];
			}
			$sql = "update tempinfo set hp=".$row." where id ='". $_POST["id"] ."';";
			$con->query($sql);

		} else if (isset($_POST["con"])) {

			$sql = "select * from conditions where id='". $_POST["id"] ."';";
			$result = $con->query($sql)->fetch_assoc();

			echo "
			<div class=\"msg-box\" style=\"position:absolute; top: 30vh; left:40vw\">
				<form action=\"../scripts/updatecondition.php\" method=\"post\">
					<h1>Conditions</h1>

					<input type=\"hidden\" id=\"name\" name=\"name\" value=\"". $_POST["id"] ."\">

					<label for=\"blinded\">(Blinded:</label>
					<input type=\"checkbox\" id=\"blinded\" name=\"blinded\" value=\"set\"". ($result["blinded"] == 0 ? "" : "Checked") .">)

					<label for=\"charmed\">(Charmed:</label>
					<input type=\"checkbox\" id=\"charmed\" name=\"charmed\" value=\"set\"". ($result["charmed"] == 0  ? "" : "Checked") .">)<br>

					<label for=\"deafened\">(Deafened:</label>
					<input type=\"checkbox\" id=\"deafened\" name=\"deafened\" value=\"set\"". ($result["deafened"] == 0  ? "" : "Checked") .">)

					<label for=\"frightened\">(Frightened</label>
					<input type=\"checkbox\" id=\"frightened\" name=\"frightened\" value=\"set\"". ($result["frightened"] == 0 ? "" : "Checked") .">)

					<label for=\"grappled\">(Grappled:</label>
					<input type=\"checkbox\" id=\"grappled\" name=\"grappled\" value=\"set\"". ($result["grappled"] == 0 ? "" : "Checked") .">)

					<label for=\"incapacitated\">(Incap:</label>
					<input type=\"checkbox\" id=\"incapacitated\" name=\"incapacitated\" value=\"set\"". ($result["incapacitated"] == 0 ? "" : "Checked") .">)

					<label for=\"invisible\">(Invisible:</label>
					<input type=\"checkbox\" id=\"invisible\" name=\"invisible\" value=\"set\"". ($result["invisible"] == 0 ? "" : "Checked") .">)

					<label for=\"paralyzed\">(Paralyzed:</label>
					<input type=\"checkbox\" id=\"paralyzed\" name=\"paralyzed\" value=\"set\"". ($result["paralyzed"] == 0 ? "" : "Checked") .">)

					<label for=\"petrified\">(Petrified:</label>
					<input type=\"checkbox\" id=\"petrified\" name=\"petrified\" value=\"set\"". ($result["petrified"] == 0 ? "" : "Checked") .">)<br>

					<label for=\"poisoned\">(Poisoned:</label>
					<input type=\"checkbox\" id=\"poisoned\" name=\"poisoned\" value=\"set\"". ($result["poisoned"] == 0 ? "" : "Checked") .">)

					<label for=\"prone\">(Prone:</label>
					<input type=\"checkbox\" id=\"prone\" name=\"prone\" value=\"set\"". ($result["prone"] == 0 ? "" : "Checked") .">)<br>

					<label for=\"restrained\">(Restrained:</label>
					<input type=\"checkbox\" id=\"restrained\" name=\"restrained\" value=\"set\"". ($result["restrained"] == 0 ? "" : "Checked") .">)

					<label for=\"stunned\">(Stunned:</label>
					<input type=\"checkbox\" id=\"stunned\" name=\"stunned\" value=\"set\"". ($result["stunned"] == 0 ? "" : "Checked") .">)

					<label for=\"unconscious\">(Unconscious:</label>
					<input type=\"checkbox\" id=\"unconscious\" name=\"unconscious\" value=\"set\"". ($result["unconscious"] == 0 ? "" : "Checked") .">)

					<label for=\"exhaustion\">(Exhaustion:</label>
					<input type=\"number\" style=\"width:40px;\" min=\"0\" step=\"1\" id=\"exhaustion\" name=\"exhaustion\" value=\"".  $result["exhaustion"] ."\" required>)<br>
            		<button type=\"submit\" name=\"submit\">Submit</button>
            	</form>
			</div>";
			$con->close();
			return;
		} else if (isset($_POST["next"])){
	
			$sql = "select * from tempinfo where id='". $_POST["id"] ."';";
			$result = $con->query($sql);
			$row = $result->fetch_assoc();
			$val = (int)$row["currentturn"];

			$sql = "select battlestate from dmstates where id=0";
			$result = $con->query($sql);
			$state = $result->fetch_assoc()["battlestate"];

			include "getcurrentturn.php";
			if ($row["id"] == $currentplayer && $state !== "idle")
				$val++;

			$sql = "update tempinfo set currentturn=".$val." where id ='". $_POST["id"] ."';";
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
		echo "<script>window.location.href = '../". $_COOKIE['loggedperms'] ."view';</script>";
		$con->close();
	}
?>
</body>