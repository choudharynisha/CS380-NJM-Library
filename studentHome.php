<?php
session_start();
    $expiry = 5400; //90 min is 5400 sec
        if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time'] > $expiry)) {
            session_unset();
            session_destroy();
            echo "<script>
                alert('Please login again. Your session expired'); 
                window.location.href = 'main_login.php';
                </script>";
        }
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
