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
<html lang = "en-US" dir = "ltr">
    <head>
        <meta name = "viewport" content = "width=device-width, initial-scale=1">
        <link rel = "stylesheet" href = "http://comet.cs.brynmawr.edu/~nchoudhary/CS380-Library-System/style.css">
        <meta charset = "utf-8">
        <link rel = "stylesheet" href = "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script defer src = "http://comet.cs.brynmawr.edu/~nchoudhary/CS380-Library-System/login-page.js"></script>
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
        <div class = "hero-image">
            <div id = "navbar">
            <a href = "http://comet.cs.brynmawr.edu/~nchoudhary/CS380-Library-System/librarian_index.php"> Home</a>
            <a href = "http://comet.cs.brynmawr.edu/~nchoudhary/CS380-Library-System/lib_nav.php"> Books</a>
            <a href = "http://comet.cs.brynmawr.edu/~nchoudhary/CS380-Library-System/logout.php">Log Out</a>

            
                <div class = "logo">
                    <h1 style = "color: yellow; font-size: 25px;text-align: center;">NJM Online Library</h1>
                </div>
            </div>
        </div>
        <div class = "row">
            <div class = "leftcolumn">
                <div class = "card2">
                    <h2 style = 'text-align: center;'>Thank you for Choosing to Work with Us </h2>
                    <h3>About Us</h3>
                    <div class = "fakeimg">
                        <img src = "http://comet.cs.brynmawr.edu/~nchoudhary/CS380-Library-System/images/readonline.jpg"> 
                    </div>
                    <p>NJM is a student owned and run online library where students from different states can request and return books.</p>
                    <p>We bring to you the latest books from top NY Bestselling authors</p>
                    <p>Join us for our weekly find reads on instagram through our IG handle NJM_Lib_Reads</p>
                    <p>We hope you enjoy our amazing collection of antique books and resourceful academic resources for all your school work</p>
                    <p>We bring to you the latest books from top NY Bestselling authors</p>
                </div>
            </div>
            <div class = "rightcolumn">
                    <h4><a href = "http://comet.cs.brynmawr.edu/~nchoudhary/CS380-Library-System/main_login.php">Log Out</a></h4>
                    <h4><a href = "http://comet.cs.brynmawr.edu/~nchoudhary/CS380-Library-System/lib_add_patron.php">Add New Patron Account</a></h4>
                    <h4><a href = "http://comet.cs.brynmawr.edu/~nchoudhary/CS380-Library-System/addbook.php">Add New Book Record</a></h4>
                    <h4><a href = "http://comet.cs.brynmawr.edu/~nchoudhary/CS380-Library-System/librarianViewBorrowed.php">Return Borrowed Books</a></h4>
                    
                <h3>Monthly Book Club Reads</h3>
                <div class = "fakeimg">
                        <img src = "http://comet.cs.brynmawr.edu/~nchoudhary/CS380-Library-System/images/persuasion_ja.jpg"> <br>Persuasion by Jane Austen
                </div><br />
                <div class = "fakeimg"><img src = "http://comet.cs.brynmawr.edu/~nchoudhary/CS380-Library-System/images/anxious_people.jpeg"><br />Anxious People by Fredrick Backman</div><br />
            </div>
        </div>
        <div class = "footer">
            <p style = "color:white;  text-align: center; ">
                <br /><br />
                Contact us @
                Email: ouremail@brynmawr.edu <br />
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
        </script>
    </body>
</html>