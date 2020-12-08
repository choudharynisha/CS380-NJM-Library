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
<<<<<<< HEAD
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
        <a href="index.html"> Home</a>
        <a href="main_login.php"> Log In</a>
        <a href="bookTable.php"> Books</a>
        <a href="index.html"> Ask Librarian</a>
      
        <div class="logo">
          <!--<img src="lanternz.gif">-->
          <h1 style="color: yellow; font-size: 25px;text-align: center;">NJM Online Library</h1>
      </div>
  </div>
=======
    <head>
    </head>
    <body>
        <form action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method = "post">
            Username
            <input id = "username" type = "text" name = "username" required /><br /><br />
>>>>>>> 11722e8a3b5be8a242c8b24c15b55dd61e698438

            Password
            <input type = "password" name = "password" required /><br /><br />

            <input type = "submit" value = "submit" name = "submit" />
        </form>
<<<<<<< HEAD
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
=======
>>>>>>> 11722e8a3b5be8a242c8b24c15b55dd61e698438

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