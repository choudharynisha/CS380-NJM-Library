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
        <  <a href="index.php"> Home</a>
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
      <div class = "box" >
            <h1 style = "text-align: center; font-size: 35px;">Login in as </h1>
            <br><br>
            <a href="patron_login.php" class="button-basic" style= "margin:auto; padding-bottom: 15px;">Patron</a> 
            <br><br>
            <a href="librarian_login.php" class="button-basic" style= "margin:auto; padding-bottom: 15px;">Librarian</a>
        </div>
    </div>
    </div>
  </div>
  <div class="rightcolumn" >
      <h3>Monthly Book Club Reads</h3>
      <div class="fakeimg"><img src="images/persuasion_ja.jpg"> <br>Persuasion by Jane Austen</div>
      <div class="fakeimg"><img src="images/anxious_people.jpeg"> <br>Persuasion by Jane Austen</div>
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
