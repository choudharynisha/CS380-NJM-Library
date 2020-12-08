<?php
    require("db.php");

    if(isset($_POST['submit'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $getuser = "SELECT password FROM njm_users WHERE username = '$username' AND role = 'borrower'";

        $results = $connection->query($getuser);
        
        if($results->num_rows == 0) {
            echo "Invalid user";
        } else {
            $hashedpassword = $results->fetch_assoc()['password'];
            if(password_verify($password, $hashedpassword)) {
                echo "Valid user";
            } else {
                echo "Not valid";
            }
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
    </head>
    <body>
        <form action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method = "post">
            Username
            <input id = "username" type = "text" name = "username" required /><br /><br />

            Password
            <input type = "password" name = "password" required /><br /><br />

            <input type = "submit" value = "submit" name = "submit" />
        </form>

        <a href = "javascript:reset_password()">Forgot Password</a>

        <script>
            function reset_password() {
                var username = document.getElementById("username").value.trim();

                if(username.length > 0) {
                    window.location = `reset_password.php?username=${username}`;
                } else {
                    alert("Please enter your username before requesting to reset password.");
                    return false;
                }
            }
        </script>
    </body>
</html>