<?php
    session_start();
    session_destroy();
    header('Location: patron_login.php');
    exit;
?>