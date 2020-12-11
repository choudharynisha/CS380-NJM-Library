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
      <a href="bookTable.php"> Books</a>
      <a href="index.php"> Contact Us</a>
      
        <div class="logo">
          <!--<img src="lanternz.gif">-->
          <h1 style="color: yellow; font-size: 25px;text-align: center;">NJM Online Library</h1>
      </div>
  </div>


  </div>
<div class="row">
  <div class="leftcolumn">
    <div class="card2">
      <h2 style="text-align: center;">Welcome to the NJM Online Library</h2>
      <h3>About Us</h3>
      <div class="fakeimg">
        <img src="images/readonline.jpg"> 
    </div>
      <p>NJM is a student owned and run online library where students from different states can request and return books.</p>
      <p>We bring to you the latest books from top NY Bestselling authors</p>
      <p>Join us for our weekly find reads on instagram through our IG handle NJM_Lib_Reads</p>
      <p>We hope you enjoy our amazing collection of antique books and resourceful academic resources for all your school work</p>
      <p>We bring to you the latest books from top NY Bestselling authors</p>
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

</body>
</html>