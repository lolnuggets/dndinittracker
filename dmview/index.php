<?php
    include "../scripts/verifylogged.php";

    if ($_COOKIE['loggedperms'] !== "dm") {
        echo "<script>alert(\"You are currently signed in as a player\");</script>";
        echo "<script>window.location.href = '../'</script>";
    }

?>

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

    <section id="dm-controls">
        <div class="msg-box" style="width:500px;display:inline-block;vertical-align:top;">
            <h2>Insert a character...</h2>
            <form action="../scripts/dmcontrols.php" method="post">
                <label for="name1">Name: </label>
                <input id="name1" type="text" name="name" placeholder="Name" required> <br>

                <label for="level1">Level: </label>
                <input id="level1"type="number" name="level" style="width:40px;" min="0" step="1">

                <label for="relationship1">Relationship: </label>
                <select id="relationship1" name="relationship">
                    <option value="unknown">Unknown</option>
                    <option value="hostile">Hostile</option>
                    <option value="friendly">Friendly</option>
                    <option value="neutral">Neutral</option>
                </select><br>

                <label for="hp1">HP: </label>
                <input id="hp1" type="number" name="hp" style="width:40px;" min="0" step="1" required>

                <label for="maxhp1">Max HP: </label>
                <input id="maxhp1" type="number" name="maxhp" style="width:40px;" min="0" step="1" required> 
                <label for="hidden1"> Hidden:</label>
                <input type="checkbox" id="hidden1" name="hidden" value="set"><br>

                <label for="ac1">Armor Class: </label>
                <input id="ac1" type="number" name="ac" style="width:40px;" min="0" step="1">

                <label for="init1">Initiative: </label>
                <input id="init1" type="number" name="init" style="width:40px;" min="0" step="1" required> <br>

                <label for="passperception1">Passive Perception: </label>
                <input id="passperception1" type="number" name="passperception" style="width:40px;" min="0" step="1">

                <label for="passinvestigation1">Passive Investigation: </label>
                <input id="passinvestigation1" type="number" name="passinvestigation" style="width:40px;" min="0" step="1"> <br>

                <label for="passinsight1">Passive Insight: </label>
                <input id="passinsight1" type="number" name="passinsight" style="width:40px;" min="0" step="1">

                <label for="darkvision1">Darkvision: </label>
                <input id="darkvision1" type="number" name="darkvision" style="width:40px;" min="0" step="1"> <br><br>

                <button type="submit" name="insert-custom">Submit</button>
            </form>
        </div>
        <div class="msg-box" style="width:500px;display:inline-block;vertical-align:top;">
            <h2 id="api-header">Insert from api</h2>
            <form id="api-form">
                <script src="../scripts/apifillin.js"></script>

                <input id="api-name" type="hidden" name="api-name" value="">

                <label for="name2">Name: </label>
                <input id="name2" type="text" name="name" placeholder="Name" required> <br>

                <label for="hp2">HP: </label>
                <input id="hp2" type="number" name="hp" style="width:40px;" min="0" step="1" required>

                <label for="maxhp2">Max HP: </label>
                <input id="maxhp2" type="number" name="maxhp" style="width:40px;" min="0" step="1" required> 
                <label for="hidden2"> Hidden:</label>
                <input type="checkbox" id="hidden2" name="hidden" value="set"><br>

                <label for="init2">Initiative: </label>
                <input id="init2" type="number" name="init" style="width:40px;" min="0" step="1" required>


                <label for="relationship2">Relationship: </label>
                <select id="relationship2" name="relationship">
                    <option value="unknown">Unknown</option>
                    <option value="hostile">Hostile</option>
                    <option value="friendly">Friendly</option>
                    <option value="neutral">Neutral</option>
                </select><br><br>

                <button type="submit" id="api-submit" name="insert-api">Submit</button>
            </form>
        </div>
        <div class="msg-box" style="width:500px;display:inline-block;vertical-align:top;">
            <h2>Game controls</h2>
            <form action="../scripts/dmcontrols.php" method="post">
                <h3 style="margin-bottom:2px"> battle controls </h3>
                <label for="local-player">Choose a player:</label>
                <select id="local-player" name="selected">
                    <option value="">None</option>
                    <?php include "../scripts/genchar.php" ?>
                </select> <br>
                <button type="submit" name="exit">Leave Combat</button>
                <button type="submit" name="hide">Toggle hidden</button><br>
                <button type="submit" name="set-relationship">Set relationship</button>
                <select id="relationship" name="relationship">
                    <option value="unknown">Unknown</option>
                    <option value="hostile">Hostile</option>
                    <option value="friendly">Friendly</option>
                    <option value="neutral">Neutral</option>
                </select><br>

                <button type="submit" name="prev">Prev Turn</button>
                <button type="submit" name="next">Next Turn</button> <br>
                <button type="submit" name="start">Start Battle</button>
                <button type="submit" name="kill">Suspend Battle</button>
                <button type="submit" name="clear">Clear Battle</button>

                <h3 style="margin-bottom:2px"> global controls </h3>
                <label for="global-player">Choose a player:</label>
                <select id="global-player" name="global-player">
                    <option value="">None</option>
                    <?php include "../scripts/generate-char-selector.php" ?>
                </select> <br>
                <label for="hp3">HP: </label>
                <input id="hp3" type="number" name="hp" style="width:40px;" min="0" step="1">
                <label for="init3">Init: </label>
                <input id="init3" type="number" name="init" style="width:40px;" min="0" step="1"> <br>
                <button type="submit" name="force">Force into battle</button>
            </form>
        </div>
    </section>

    <section id="initiative-list">

        <script src="../scripts/drawlist.js"></script>

    </section>
    
    <footer id="footer">
        &copy; 2023 Lol_Nuggets
    </footer>

</body>
</html>
