<?php
    include "../scripts/verifylogged.php";
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
            <ul>
                <li><a href="../">Home</a></li>
                <li><a href="../characters">Characters</a></li>
                <li><a href="#">Info</a></li>
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
    <section id="view-current-player">
        <script src="../scripts/editorajax.js"></script>
        <!-- view current card-->
        <?php include "../scripts/loadcharview.php"; ?>
    </section>
    
    <section id="edit-player-card">
        <!-- position to add new cards -->


        <?php
        if (isset($_COOKIE["selected"]))
        echo "
        <div class=msg-box style=\"justify-content: center; display: block;margin-left: auto; margin-right: auto;width:600px\">
        <h2>Edit character...</h2>
        <form action=\"../scripts/updatechar.php\" method=\"post\">
            <input id=\"selected\" value=\"". $_COOKIE["selected"] ."\" name=\"selected\"type=\"hidden\">

            <label for=\"name\">Name: </label>
            <input id=\"name\" type=\"text\" name=\"name\" placeholder=\"Name\"> <br>

            <label for=\"level\">Level: </label>
            <input id=\"level\"type=\"number\" name=\"level\" style=\"width:40px;\" min=\"0\" step=\"1\">

            <label for=\"maxhp\">Max HP: </label>
            <input id=\"maxhp\" type=\"number\" name=\"maxhp\" style=\"width:40px;\" min=\"0\" step=\"1\">

            <label for=\"ac\">Armor Class: </label>
            <input id=\"ac\" type=\"number\" name=\"ac\" style=\"width:40px;\" min=\"0\" step=\"1\"> <br>

            <label for=\"passperception\">Passive Perception: </label>
            <input id=\"passperception\" type=\"number\" name=\"passperception\" style=\"width:40px;\" min=\"0\" step=\"1\">

            <label for=\"passinvestigation\">Passive Investigation: </label>
            <input id=\"passinvestigation\" type=\"number\" name=\"passinvestigation\" style=\"width:40px;\" min=\"0\" step=\"1\"> <br>

            <label for=\"passinsight\">Passive Insight: </label>
            <input id=\"passinsight\" type=\"number\" name=\"passinsight\" style=\"width:40px;\" min=\"0\" step=\"1\">

            <label for=\"darkvision\">Darkvision: </label>
            <input id=\"darkvision\" type=\"number\" name=\"darkvision\" style=\"width:40px;\" min=\"0\" step=\"1\"> <br>

            <button type=\"submit\" name=\"submit\">Submit</button><br><br><br>
            <button type=\"submit\" name=\"delete\">DELETE CHARACTER</button>
        </form>
        </div>";
        ?>

    </section>
    <footer id="footer">
        &copy; 2023 Lol_Nuggets
    </footer>
</body>
</html>
