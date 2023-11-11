<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="../styles.css">
    <title>Hearts of Iron Login</title>
</head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<body class="container">
    <div class="login-container">
        <div class="login-box">
            <h2>Log In</h2>
            <form action="../scripts/login.php" method="post">
                <input type="password" name="password" placeholder="Password" required> <br>
                <button type="submit" name="playersubmit">I am a Player</button>
                <button type="submit" name="dmsubmit">I am the DM</button>
            </form>
        </div>
    </div>
</body>
</html>
