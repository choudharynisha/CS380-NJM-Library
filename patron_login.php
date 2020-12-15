<?php
    require("db.php");
    session_start();

    if(isset($_POST['submit'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        $_SESSION["userName"] = $username;
        $_SESSION['login_time'] = time(); //session time 
        
        $getuser = "SELECT password FROM njm_users WHERE username = '$username' AND role = 'borrower'";

        $results = $connection->query($getuser);
        
        if($results->num_rows == 0) {
            echo "<script>
                    alert('Invalid username and/or password');
                    window.location.href = 'http://comet.cs.brynmawr.edu/~nchoudhary/CS380-Library-System/patron_login.php';
                </script>";
        } else {
            $hashedpassword = $results->fetch_assoc()['password'];
            if(password_verify($password, $hashedpassword)) {
                echo "Valid user";
                header("Location: http://comet.cs.brynmawr.edu/~nchoudhary/CS380-Library-System/patron_index.php");
            } else {
                echo "<script>
                    alert('Invalid username and/or password');
                    window.location.href = 'http://comet.cs.brynmawr.edu/~nchoudhary/CS380-Library-System/patron_login.php';
                </script>";
            }
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta name = "viewport" content = "width=device-width, initial-scale=1">
        <link rel = "stylesheet" href = "http://comet.cs.brynmawr.edu/~nchoudhary/CS380-Library-System/style.css">
        <meta charset = "utf-8">
        <link rel = "stylesheet" href = "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script defer src = "login-page.js"></script>

        <style>
            * {
                box-sizing: border-box;
            }

            body, html {
                height: 100%;
                margin: 0;
                font-family: Arial, Helvetica, sans-serif;
                background: rgb(245, 243, 243);
            }    
        </style>
    </head>
    <body data-new-gr-c-s-check-loaded = "14.984.0" data-gr-ext-installed = "">
    <div class = "hero-image">
        <div id = "navbar">
            <a href = "http://comet.cs.brynmawr.edu/~nchoudhary/CS380-Library-System">Home</a>
            <a href = "http://comet.cs.brynmawr.edu/~nchoudhary/CS380-Library-System/navBookTable.php">Books</a>
            
            <div class = "logo">
                <h1 style = "color: yellow; font-size: 25px;text-align: center;">NJM Online Library</h1>
            </div>
        </div>
    </div>
    <div class = "row">
            <div class = "leftcolumn">
            <div class = "card2">
                <div class = "fakeimg">
                <div class = "box">
                
                <h1 style = "text-align: center; font-size: 35px;">Patron Login</h1>
                    <div id = "login-error-msg-holder">
                        <p id = "login-error-msg">Invalid username<span id="error-msg-second-line">and/or password</span></p>
                    </div>

                    <form action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method = "post">
                    Username
                    <input id = "username" type = "text" name = "username" required /><br /><br />
                    
                    Password
                    <input type = "password" name = "password" required /><br /><br />
                    <input  type = "submit" value = "submit" name = "submit" />
                </form>
                <br>
                <a href = "javascript:reset_password()" style = "margin:auto; padding-bottom: 15px;">Forgot Password</a>
            </div>
            </div>
            </div>
            </div>
            <div class = "rightcolumn">
                <h3>Monthly Book Club Reads</h3>
                <div class = "fakeimg"><img src = "http://comet.cs.brynmawr.edu/~nchoudhary/CS380-Library-System/images/persuasion_ja.jpg"><br>Persuasion by Jane Austen</div>
                <div class = "fakeimg"><img src = "http://comet.cs.brynmawr.edu/~nchoudhary/CS380-Library-System/images/anxious_people.jpeg"><br>Anxious People by Fredrick Backman</div>
            </div>
        </div>

        <div class="footer">
            <p style="color:white;  text-align: center; ">
                <br><br>
                Contact us @
                Email: ouremail@brynmawr.edu <br>
                Phone: +1 610 526 5000
            </p>
        </div>
        <script>
            window.onscroll = function() {myFunction()};
        
            var navbar = document.getElementById("navbar");
            var sticky = navbar.offsetTop;
        
            function myFunction() {
                if (window.pageYOffset >= sticky) {
                    navbar.classList.add("sticky")
                } else {
                    navbar.classList.remove("sticky");
                }
            }

            function reset_password() {
                var username = document.getElementById("username").value.trim();

                if(username.length > 0) {
                    window.location = `http://comet.cs.brynmawr.edu/~nchoudhary/CS380-Library-System/reset_password.php?username=${username}&role=borrower`;
                } else {
                    alert("Please enter your username before requesting to reset password.");
                    return false;
                }
            }
        </script>
    </body>
</html>