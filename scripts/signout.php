<?php

if (isset($_COOKIE['loggedperms'])) {
    unset($_COOKIE['loggedperms']); 
    setcookie('loggedperms', '', -1, '/'); 
}

if (isset($_COOKIE['password'])) {
    unset($_COOKIE['password']); 
    setcookie('password', '', -1, '/'); 
}

echo "<script>location.replace(\"www.dndhoi.com/login\")</script>";
