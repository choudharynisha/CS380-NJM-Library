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
<body>
<div class="hero-image">

    <div id="navbar">
        <  <a href="index.html"> Home</a>
        <a href="main_login.php"> Log In</a>
        <a href="bookTable.php"> Books</a>
        <a href="index.html"> Ask Librarian</a>
      
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
            <form action = "index.html" method = "post">
                Username
                <input id = "username" type = "text" name = "username" required /><br /><br />
                Password
                <input type = "password" name = "password" required /><br /><br />
                <input  type = "submit" value = "submit" name = "submit" />
        </form>
        </div>
    </div>
    </div>
  </div>
  <div class="rightcolumn">
    <div class="card">
        <h4><a href="main_login.php">Log in</a></h4>
        <h4><a href="#">Request Librarian Help</a></h4>
        <h4><a href="#">Feedback</a></h4>
    </div>
    <div class="card">
      <h3>Monthly Book Club Reads</h3>
      <div class="fakeimg">
        <img src="persuasion_ja.jpg"> 
      </div>
      <div class="fakeimg"><img src="anxious_people.jpeg"> </div>
    </div>
  </div>
</div>

<div class="footer">
    <p style="color:white;  text-align: center; ">
        <br><br>
        Contact us @
        Email: ouremail.brynmawr.edu <br>
        Mobile: +1 610 526 5000
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
    </script>


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
