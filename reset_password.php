<?php
    require("db.php");

    if(isset($_GET['username'])) {
        $username = $_GET['username'];
        $checkuser = "SELECT security_question, security_answer FROM njm_users WHERE username = '$username'";
        $results = $connection->query($checkuser);
        
        if($results->num_rows < 1) {
            $error = "No user exists";
        } else {
            $row = $results->fetch_assoc();
            $question = $row['security_question'];
            $hashedanswer = $row['security_answer'];
        }
    }

    if(isset($_POST['submit'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $password2 = $_POST['retyped']; // compare the retyped password
        $answer = $_POST['answer'];
        $question = $_POST['question'];
        $getanswer = "SELECT security_answer FROM njm_users WHERE username = '$username'";
        $results = $connection->query($getanswer);

        if($results->num_rows < 1) {
            $error = "No user exists";
        } else {
            $row = $results->fetch_assoc();
            $hashedanswer = $row['security_answer'];
        }

        if(password_verify($answer, $hashedanswer)) {
            if((strlen($password) < 6) or (strlen($password) > 20)) {
                $error = "Invalid Password";
            } else {
                $hashedpassword = password_hash($password, PASSWORD_DEFAULT);
                $addpatron = "UPDATE njm_users SET password = '$hashedpassword' WHERE username = '$username'";
            }
            header("location: http://comet.cs.brynmawr.edu/~nchoudhary/main_login.php");
            exit;
        } else {
            echo "Invalid user";
        }
        
    }
?>

<?php
    include 'db.php';
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
      <a href="index.php"> Home</a>
      <a href="main_login.php"> Log In</a>
      <a href="navBookTable.php"> Books</a>
      <a href="#"> Contact Us</a>
      
        <div class="logo">
          <!--<img src="lanternz.gif">-->
          <h1 style="color: yellow; font-size: 25px;text-align: center;">NJM Online Library</h1>
      </div>
  </div>


  </div>
<div class = "row">
  <div class = "leftcolumn">
        <div class = "card2">
        <h2 style = "text-align: center;">Welcome to the NJM Online Library</h2>

            <div class = "box">
                <form    style= "text-align: left;padding-left: 20px;" id = "resetpassword" onsubmit = "resetPassword(); return false;" action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method = "post">
                    <input type = "hidden" name = "username" value = "<?php echo $username; ?>" />
                    <input type = "hidden" name = "question" value = "<?php echo $question; ?>" />
                    <br><br><br>
                    
                    Security Question
                    <input type="text" placeholder="<?php echo $question; ?>">
                    <br>
                    <br>

                    Security Answer
                    <input type = "text" name = "answer" value = "<?php if(isset($_POST['answer'])) {echo $answer;} ?>" required /><br /><br />

                    Password
                    <input type = "password" name = "password" id = "pw1" value = "<?php if(isset($_POST['password'])) {echo $password;} ?>" required /><br /><br />

                    Retype Password
                    <input type = "password" name = "retyped" id = "pw2" value = "<?php if(isset($_POST['retyped'])) {echo $password2;} ?>" required /><br /><br />

                    <input type = "submit" value = "submit" name = "submit" />
                </form>
            </div>
        </div>
  </div>
    <div class="rightcolumn">
        <div class="card">
            <h4><a href="index.php"> Request Help</a></h4>
            <h4><a href="main_login.php"> Log In</a></h4>
        </div>
        <div class="card">
          <h3>Monthly Book Club Reads</h3>
          <div class="fakeimg">
            <img src="images/persuasion_ja.jpg"> 
          </div><br>
          <div class="fakeimg"><img src="images/anxious_people.jpeg"> </div><br>
        </div>
    
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
            function resetPassword() {
                // check for potential mismatched passwords
                let pw1 = document.getElementById("pw1").value;
                let pw2 = document.getElementById("pw2").value;

                if(pw1 != pw2) {
                    alert("Mismatched Passwords");
                    return false;
                }

                // getting and saving the user's form input for a new patron
                let form = new FormData(document.getElementById('resetpassword'));
                let userInput = "";

                for(object of form) {
                    console.log(object);
                    userInput += object[0] + "=" + object[1] + "&";
                }

                // make sure that submit is set so that it can be processed using PHP
                userInput += "submit=submit";

                // sending the processed user input to be loaded using PHP
                fetch("addpatron.php", {
                    method: "POST",
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: bodyText
                }).then (function(data) {
                    console.log("Reply: ");
                    console.log(data);
                    alert("Submitted");
                });
            }
        </script>

</body>
</html>