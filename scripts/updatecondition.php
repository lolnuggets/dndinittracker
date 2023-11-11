<?php


	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		
		include "db.php";

		$selected = $_POST["name"];//str_replace("-"," ",$_POST["name"]);
		$blinded = (isset($_POST["blinded"]) ? "1" : "0");
		$charmed = (isset($_POST["charmed"]) ? "1" : "0");
		$deafened = (isset($_POST["deafened"]) ? "1" : "0");
		$frightened = (isset($_POST["frightened"]) ? "1" : "0");
		$grappled = (isset($_POST["grappled"]) ? "1" : "0");
		$incapacitated = (isset($_POST["incapacitated"]) ? "1" : "0");
		$invisible = (isset($_POST["invisible"]) ? "1" : "0");
		$paralyzed = (isset($_POST["paralyzed"]) ? "1" : "0");
		$petrified = (isset($_POST["petrified"]) ? "1" : "0");
		$poisoned = (isset($_POST["poisoned"]) ? "1" : "0");
		$prone = (isset($_POST["prone"]) ? "1" : "0");
		$restrained = (isset($_POST["restrained"]) ? "1" : "0");
		$stunned = (isset($_POST["stunned"]) ? "1" : "0");
		$unconscious = (isset($_POST["unconscious"]) ? "1" : "0");
		$exhaustion = $_POST["exhaustion"];

		$sql = "update conditions set blinded=".$blinded.",charmed=".$charmed.",deafened=".$deafened.",frightened=".$frightened.",grappled=".$grappled.",incapacitated=".$incapacitated.",invisible=".$invisible.",paralyzed=".$paralyzed.",petrified=".$petrified.",poisoned=".$poisoned.",prone=".$prone.",restrained=".$restrained.",stunned=".$stunned.",unconscious=".$unconscious.",exhaustion=".$exhaustion." where id='".$selected."';";
		$con->query($sql);
		echo $sql;
	}


	echo "<script>window.location.href = '../". $_COOKIE['loggedperms'] ."view';</script>";