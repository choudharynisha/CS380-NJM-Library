<?php
    $servername = "localhost";
    $username = "jwang3";
    $password = "";
    $dbname = "test";

    $connnection = new mysqli($servername, $username, $password, $dbname);

    if($connnection->connect_error) {
        die("Connection failed: " . $connnection->connect_error);
    }
?>