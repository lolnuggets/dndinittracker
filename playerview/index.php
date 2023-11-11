  <?php include "../scripts/verifylogged.php"; ?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Hearts of Iron</title>
	<link rel="stylesheet" type="text/css" href="../styles.css">
</head>
<body class="container">
	<header id="header">
		<nav>
			<div class="logo">
				<img src="../resources/logo.png" alt="Logo">
			</div>
			<div class="title">
				<a>Hearts of Iron</a>
			</div>
			<?php
				if (isset($_COOKIE['selected']) && $_COOKIE['loggedperms'] !== "dm") {
					$player = $_COOKIE['selected'];
				} else
					$player = "No player";

				echo "<div><a>". $player ."</a> </div>"
			?>
			<ul>
				<li><a href="../">Home</a></li>
				<li><a href="../characters">Characters</a></li>
				<li><a href="../info/">Info</a></li>
				<?php
					if ($_COOKIE['loggedperms'] == "player")
						$perms = "player";
					if ($_COOKIE['loggedperms'] == "dm")
						$perms = "dm";
					echo "<li><a>Currently signed in as: " . $perms . "</a></li>";
				?>
				<li><a href="../scripts/signout.php">Sign Out</a></li>
			</ul>
		</nav>
	</header>

		<section id="initiative-list">

        	<script src="../scripts/drawlist.js"></script>

		</section>
		<section id="join-battle">
			<?php include "../scripts/loadjoinbattle.php"; ?>
		</section>
	
	<footer id="footer">
		&copy; 2023 Lol_Nuggets
	</footer>

</body>
</html>
