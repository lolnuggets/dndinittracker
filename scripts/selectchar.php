<?php

	if ($_SERVER["REQUEST_METHOD"] == "POST") {

		if ($_COOKIE['loggedperms'] == "dm") {
	        return;
	    }

		$char = $_POST["selected"];

		if (isset($_COOKIE['selected']) && $char == $_COOKIE['selected'])
			return;


		if ($char == "" && isset($_COOKIE['selected'])) {
		    unset($_COOKIE['selected']); 
		    setcookie('selected', '', -1, '/'); 
		}

		setcookie('selected', $char, time()+60*60*24*30, "/");
		echo "<script>window.location.href = '../characters'</script>";
	}