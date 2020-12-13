<?php
    require("db.php");

    // use sessions system to keep track of logins.
    session_start();
    $_SESSION['login_time'] = time(); //session time 
    
    // check for a login attempt: 
    if(isset($_POST['submit'])) {
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
        
        if($user->num_rows < 1) {
            echo "Invalid user";
        } else {
            $hashedpassword = $user->fetch_assoc()['password'];
            if(password_verify($password, $hashedpassword)) {
                echo "Valid user";
                header("Location: librarian_index.php");
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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
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
<body data-new-gr-c-s-check-loaded="14.984.0" data-gr-ext-installed="">
<div class="hero-image">

    <div id="navbar">
         <a href="index.php"> Home</a>
         <a href="navBookTable.php"> Books</a>
         <a href="#"> Contact Us</a>

      
        <div class="logo">
          <!--<img src="lanternz.gif">-->
          <h1 style="color: yellow; font-size: 25px;text-align: center;">NJM Online Library</h1>
        </div>
  </div>
</div>
    <div class="row">
        <div class="leftcolumn">
            <div class="card2">
                <div class="fakeimg">
                    <div class = "box">
                    <h1 style = "text-align: center; font-size: 35px;">Librarian Login</h1>
                        <div id = "login-error-msg-holder">
                            <p id = "login-error-msg">Invalid username<span id="error-msg-second-line">and/or password</span></p>
                        </div>

                        <form action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method = "post">
                            Username<input id = "username" type = "text" name = "username" required /><br /><br />
                            Password<input type = "password" name = "password" required /><br /><br />
                            <input  type = "submit" value = "submit" name = "submit" />
                        </form>
                            <br>
                        <a href = "javascript:reset_password()" style = "margin:auto; padding-bottom: 15px;">Forgot Password</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="rightcolumn">
        
                <h3>Monthly Book Club Reads</h3>
                <div class="fakeimg"><img src="images/persuasion_ja.jpg"><br>Persuasion by Jane Austen</div>
                <div class="fakeimg"><img src="images/anxious_people.jpeg"> <br>Anxious People by Fredrick Backman</div>
        </div>
    </div>

    <div class="footer">
        <p style="color:white;  text-align: center; ">
            <br><br>
            Contact us @
            Email: ouremail@brynmawr.edu <br>
            Mobile: +1 610 526 5000
        </p>
    </div>

        <script>window.onscroll = function() {myFunction()};
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
                    window.location = `reset_password.php?username=${username}`;
                } else {
                    alert("Please enter your username before requesting to reset password.");
                    return false;
                }
            }
        </script>

    </body>
</html>
