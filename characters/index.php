<?php
    include "../scripts/verify-logged.php";
?>


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
            <ul id="links">
                <li><a href="../">Home</a></li>
                <li><a href="#">Characters</a></li>
                <li><a href="../info">Info</a></li>
                <?php
                    if ($_COOKIE['loggedperms'] == "player")
                        $perms = "player";
                    if ($_COOKIE['loggedperms'] == "dm")
                        $perms = "dm";
                    echo "<li><a>Currently signed in as: " . $perms . "</a></li>";
                ?>
                <script src="../scripts/signout-button.js"></script>
            </ul>
        </nav>
    </header>
    <section id="player-cards">
      <!-- Player cards for the game -->
      <script src="../scripts/create-cards.js"></script>
    </section>

    <section id="select-player-card">
        <!-- form to select a player card -->
        <form action="../scripts/select-character.php" method="post">
            <label for="char-selector">Choose a player:</label>
            <select id="char-selector" name="selected">
                <option value="">None</option>
                <?php include "../scripts/generate-char-selector.php" ?>
            </select>
            <input type="submit">
        </form>
    </section>

    <section id="add-player-card">
        <!-- position to add new cards -->

        <h2>Create a character</h2>
        <form action="../scripts/add-character.php" method="post">
            <label for="name">Name: </label>
            <input id="name" type="text" name="name" placeholder="Name" required> <br>

            <label for="level">Level: </label>
            <input id="level"type="number" name="level" style="width:40px;" min="0" step="1" required>

            <label for="maxhp">Max HP: </label>
            <input id="maxhp" type="number" name="maxhp" style="width:40px;" min="0" step="1" required>

            <label for="ac">Armor Class: </label>
            <input id="ac" type="number" name="ac" style="width:40px;" min="0" step="1" required> <br>

            <label for="passperception">Passive Perception: </label>
            <input id="passperception" type="number" name="passperception" style="width:40px;" min="0" step="1" required>

            <label for="passinvestigation">Passive Investigation: </label>
            <input id="passinvestigation" type="number" name="passinvestigation" style="width:40px;" min="0" step="1" required>

            <label for="passinsight">Passive Insight: </label>
            <input id="passinsight" type="number" name="passinsight" style="width:40px;" min="0" step="1" required>

            <label for="darkvision">Darkvision: </label>
            <input id="darkvision" type="number" name="darkvision" style="width:40px;" min="0" step="1" required> <br>

            <button type="submit" name="submit">Submit</button>
        </form>
    </section>


    <footer id="footer">
        &copy; 2023 Lol_Nuggets
    </footer>
</body>
</html>
