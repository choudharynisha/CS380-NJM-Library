<?php
    $servername = "localhost";
    $username = "jwang3";
    $password = "";
    $dbname = "test";

    $connection = new mysqli($servername, $username, $password, $dbname);

    if($connection->connect_error) {
        die("Connection failed: " . $connnection->connect_error);
    }
?>