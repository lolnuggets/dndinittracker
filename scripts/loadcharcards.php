<?php
    // script to load character cards on relevatn pages
	include "db.php";

	$sql = "select * from characterinfo";
	$result = $con->query($sql);

    while ($row = $result->fetch_assoc()) {

    	$name = $row["id"];
    	$lvl = $row["level"];
    	$maxhp = $row["maxhp"];
    	$ac = $row["ac"];
    	$passper = $row["passper"];
    	$passinv = $row["passivs"];
    	$passins = $row["passins"];
    	$dv = $row["darkvision"];

        // load here
        echo "<div class=\"player\"><h1>". $name ."</h1> <h1>level ". $lvl ."</h1> <h2>Max HP ". $maxhp ."</h2><h2>". $ac
        ." AC</h2><h3> Perc. <br> ". $passper ."</h3><h3> Invs. <br> ". $passinv ." </h3><h3> Insi. <br> ". $passins
        ." </h3><h3> DV. <br> ". $dv ."ft</h3>";

        if (strpos($_SERVER['REQUEST_URI'], '/characters') !== false) {

            echo "<a id=\"". $name ."\"><div class=\"custom-button\" style=\"width:200px;height:50px;padding:10px\"><h2>Select ". $name
                ."</h2></div></a><script>document.getElementById(\"". $name ."\").onclick = function(){\n\t\$.post(\"../scripts/selectchar.php\", { selected:\"".
                $name ."\" })\n\twindow.location.href = '../characters';\n} </script>";
        }
        echo "</div>";
    }

	$con->close();