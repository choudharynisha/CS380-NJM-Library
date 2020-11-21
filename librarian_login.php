<?php
    require("db.php");

    // use sessions system to keep track of logins.
    session_start();
    
    // check for a login attempt: 
    if(isset($_POST['submit'])) {
        echo "Attempting login";
        $username = $_POST['username'];
        $password = $_POST['password'];
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        
        // make sure the username and password are valid, check length, etc
        if((strlen($username) > 20) || (strlen($username) < 5)) {
            // invalid
            echo "Invalid username";
        }
        
        if((strlen($password) > 20) || (strlen($password) < 6)) {
            // invalid
            echo "Invalid password";
        }
        
        // search for a match to username and password.
        $getuser = "SELECT password FROM njm_users WHERE username = '$username' AND role = 'librarian'";
        $user = $connection->query($getuser);
        
        if($users->num_rows == 0) {
            echo "Invalid user";
        } else {
            $hashedpassword = $user->fetch_assoc()['password'];
            if(password_verify($password, $hashedpassword)) {
                echo "Valid user";
            } else {
                echo "Not valid";
            }
        }
        
        // if no match, then alert the user and allow re-entry of username and password.
        
        // if there is a match, note the person as logged in in th sessions system, and redirect to the librarian admin page
    }
?>


<!DOCTYPE html>
    <html>
        <head>
            <title>NMJ Online Library Management System</title>
            <link rel = "stylesheet" type = "text/css" href = "style.css">
            <meta charset = "utf-8">
            <meta name = "viewport" content = "width=device-width, initial-scale=1">
            <link rel = "stylesheet" href = "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
            <script defer src = "login-page.js"></script>
            
        <style type = "text/css">
            nav {
                float: right;
                word-spacing: 30px;
                padding: 20px;
            }
            
            nav li {
                display: inline-block;
                line-height: 80px;
            }
        </style>
    </head>
    
    <body>
        <div class = "wrapper">
            <header>
                <div class = "logo">
                    <img src = "images/lanternz.gif" style = "border-radius: 45%;">
                    <h1 style = "color: black; font-size: 25px;text-align: center;">NMJ Online Library</h1>
                </div>
                
                <nav>
                    <ul>
                        <li><a href = "index.html">HOME</a></li>
                        <li><a href = "resources.html">RESOURCES</a></li>
                        <li><a href = "login.html">LOGIN</a></li>
                        <li><a href = "feedback.html">FEEDBACK</a></li>
                    </ul>
                </nav>
            </header>
            <section>
                <div class = "sec_img">
                    <br><br><br>
                    <div class = "box">
                        <br><br><br><br>
                        <h1 style = "text-align: center; font-size: 35px;">Librarian Login</h1><br><br>
                        
                        <div id = "login-error-msg-holder">
                            <p id = "login-error-msg">Invalid username<span id="error-msg-second-line">and/or password</span></p>
                        </div>
                        
                        <form id = "login-form" method = "post" action = "librarian_login.php">
                            <input type = "text" name = "username" id = "username-field" class = "login-form-field" placeholder = "Username">
                            <input type = "password" name = "password" id = "password-field" class = "login-form-field" placeholder = "Password">
                            <input type = "submit" name = "submit" value = "Login" id = "login-form-submit">
                        </form>
                    </div>
                </div>
            </section>
            <footer>
                <p style = "color:white;  text-align: center; ">
                    <br><br>
                    Contact us @
                    Email: ouremail.brynmawr.edu <br>
                    Mobile: +1 610 526 5000
                </p>
            </footer>
        </div>
    </body>
</html>