<?php
	// script for updating a character through a posted form on page info

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		
		include "db.php";
		
		$selected = $_POST['selected'];

		if (isset($_POST["delete"])) {
			$sql = "delete from characterinfo where id='".$selected."'";
			$con->query($sql);
			$sql = "delete from tempinfo where id='".$selected."'";
			$con->query($sql);
			$sql = "delete from conditions where id='".$selected."'";
			$con->query($sql);

			if (isset($_COOKIE['selected'])) {
			    unset($_COOKIE['selected']); 
			    setcookie('selected', '', -1, '/'); 
			}

		} else {
			$sql = "select * from characterinfo where id = '". $selected ."'";
			$result = $con->query($sql);
			$row = $result->fetch_assoc();

			$id = $row["id"];
			$lvl = $row["level"];
			$mhp = $row["maxhp"];
			$ac = $row["ac"];
			$pper = $row["passper"];
			$pinv = $row["passivs"];
			$pins = $row["passins"];
			$dv = $row["darkvision"];

			if ($_POST["name"] !== "")
				$id = $_POST["name"];
			if ($_POST["level"] !== "")
				$lvl = $_POST["level"];
			if ($_POST["maxhp"] !== "")
				$mhp = $_POST["maxhp"];
			if ($_POST["ac"] !== "")
				$ac = $_POST["ac"];
			if ($_POST["passperception"] !== "")
				$pper = $_POST["passperception"];
			if ($_POST["passinvestigation"] !== "")
				$pinv = $_POST["passinvestigation"];
			if ($_POST["passinsight"] !== "")
				$pins = $_POST["passinsight"];
			if ($_POST["darkvision"] !== "")
				$dv = $_POST["darkvision"];

			$sql = "update characterinfo set id ='" . $id . "', level=" . $lvl . ",maxhp=" . $mhp . ",ac=" . $ac . ",passper=" . $pper . ",passivs=" . $pinv . ",passins=" . $pins . ",darkvision=" . $dv . " where id='". $selected ."'";
			$con->query($sql);

			if ($_COOKIE['loggedperms'] == "dm") {
		        return;
		    }

			$char = $_POST["selected"];

			if (isset($_COOKIE['selected']) && $char == $_COOKIE['selected'])
				return;

			setcookie('selected', $id, time()+60*60*24*30, "/");

		}
		$con->close();
		echo "<script>window.location = '../info'</script>";
	}
