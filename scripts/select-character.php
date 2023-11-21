<?php

	if ($_SERVER["REQUEST_METHOD"] == "POST" && $_COOKIE['loggedperms'] !== "dm") {

		$char = $_POST["selected"];

		if (isset($_COOKIE['selected']) && $char == $_COOKIE['selected'])
			return;


		if ($char == "" && isset($_COOKIE['selected'])) {
		    unset($_COOKIE['selected']); 
		    setcookie('selected', '', -1, '/'); 
		}

		setcookie('selected', $char, time()+60*60*24*30, "/");
	}
	echo "<script>
		function getHost() {
		    let hostname = window.location.hostname;
		    if (hostname == \"localhost\")
		        hostname += \"/dndinittracker\";
		    return hostname;
		}
		window.location.href = 'http://'+ getHost() + '/characters';
	</script>";