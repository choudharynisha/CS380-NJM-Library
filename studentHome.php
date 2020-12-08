<?php
session_start();
?>

<html>
<head>
</head>

<body>
<h1>Student Page after Logging in as Student</h1>
<?php
    echo "User Name entered is " . $_SESSION["userName"] . ".<br>";
?>

<div id="center_button">
    Student Activity to View Book Table
    <button onclick="location.href='bookTable.php'">Book Table</button>
</div>
</body>
</html>