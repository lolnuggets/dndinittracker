<?php

if (isset($_COOKIE['loggedperms'])) {
    unset($_COOKIE['loggedperms']); 
    setcookie('loggedperms', '', -1, '/'); 
}

if (isset($_COOKIE['password'])) {
    unset($_COOKIE['password']); 
    setcookie('password', '', -1, '/'); 
}

echo "<script>window.location.href = \"www.dndhoi.com/login\"</script>";
