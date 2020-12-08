<?php
    require("db.php");

    if(isset($_POST['submit'])) {
        // gets the new patron's information upon pressing submit
        $username = $_POST['username'];
        $password = $_POST['password'];
        $email = $_POST['email'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $street = $_POST['address'];
        $town = $_POST['town'];
        $state = $_POST['state'];
        $zip = $_POST['zip'];
        $question = $_POST['question'];
        $answer = $_POST['answer'];
        $error = "";

        // form validation
        if((strlen($username) < 5) or (strlen($username) > 20)) {
            $error = "Invalid Username";
        }

        if((strlen($password) < 6) or (strlen($password) > 20)) {
            $error = "Invalid Password";
        } else {
            $hashedpassword = password_hash($password, PASSWORD_DEFAULT);
        }
        
        if((strlen($state) < 4) or (strlen($state) > 50)) {
            $error = "Invalid State Name";
        }

        if(strlen($zip) != 5) {
            $error = "Invalid Zip Code";
        }

        // in order to make sure that no one can easily change the password
        $hashedsecurityanswer = password_hash($answer, PASSWORD_DEFAULT);

        $checkusers = "SELECT * FROM njm_users WHERE username = '$username' OR email = '$email'";
        $results = $connection->query($checkusers);
        
        if($results->num_rows > 0) {
            $error = "Username or email exists";
        }
        
        // add new patron
        if(strlen($error) == 0) {
            $addpatron = "INSERT INTO njm_users (username, password, email, role, first_name, last_name, street_address, town, state, zip, security_question, security_answer) VALUES ('$username', '$hashedpassword', '$email', 'borrower', '$firstname', '$lastname', '$street', '$town', '$state', '$zip', '$question', '$hashedsecurityanswer')";
            
            if($connection->query($addpatron) === TRUE) {
                // successful addition of the new book to njm_books
                // reset the variables so that the librarian can add a new patron, if wanted
                $username = "";
                $password = "";
                $email = "";
                $firstname = "";
                $lastname = "";
                $street = "";
                $town = "";
                $state = "";
                $zip = "";
                $question = "";
                $answer = "";
                exit("Successfully added");
            } else {
                exit("Error – " . $addpatron . "<br>" . $connection->error);
            }
        } else {
            exit($error);
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
    <meta charset = "utf-8">
    <link rel = "stylesheet" href = "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href = "addpatron.css" rel = "stylesheet" />

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


  </div>
<div class="row">
  <div class="leftcolumn">
    <div class="card2">
      <h2 style="text-align: center;">Add New Patron Account</h2>
      <div class="boxb">
      <form style = "padding-top: 70px;" id = "newpatronform" onsubmit = "addPatron(); return false;" action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method = "post">
            
            
            Username
            <input type = "text" name = "username" value = "<?php if(isset($_POST['username'])) {echo $username;} ?>" required /><br /><br />

            Password
            <input type = "password" name = "password" value = "<?php if(isset($_POST['password'])) {echo $password;} ?>" required /><br /><br />

            Email
            <input type = "email" name = "email" value = "<?php if(isset($_POST['email'])) {echo $email;} ?>" required /><br /><br />

            First Name
            <input type = "text" name = "firstname" value = "<?php if(isset($_POST['firstname'])) {echo $firstname;} ?>" required /><br /><br />

            Last Name
            <input type = "text" name = "lastname" value = "<?php if(isset($_POST['lastname'])) {echo $lastname;} ?>" required /><br /><br />

            Street Address
            <input type = "text" name = "address" value = "<?php if(isset($_POST['address'])) {echo $street;} ?>" required /><br /><br />

            Town
            <input type = "text" name = "town" value = "<?php if(isset($_POST['town'])) {echo $town;} ?>" required /><br /><br />

            State
            <input type = "text" name = "state" value = "<?php if(isset($_POST['state'])) {echo $state;} ?>" required /><br /><br />

            Zip Code
            <input type = "text" name = "zip" value = "<?php if(isset($_POST['zip'])) {echo $zip;} ?>" required /><br /><br />

            Security Question
            <input type = "text" name = "question" value = "<?php if(isset($_POST['question'])) {echo $question;} ?>" required /><br /><br />

            Security Answer
            <input type = "text" name = "answer" value = "<?php if(isset($_POST['answer'])) {echo $answer;} ?>" required /><br /><br />

            <input type = "submit" value = "submit" name = "submit" />
        </form>
        <a href = "">Forgot Password</a>
        
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
            function addPatron() {
                // getting and saving the user's form input for a new patron
                let form = new FormData(document.getElementById('newpatronform'));
                let userInput = "";

                for(obj of form) {
                    console.log(obj);
                    userInput += obj[0] + "=" + obj[1] + "&";
                }

                // make sure that submit is set so that it can be processed using PHP
                userInput += "submit=submit";

                // sending the processed user input to be loaded using PHP
                fetch("addpatron.php", {
                    method: "POST",
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: userInput
                }).then (function(data) {
                    console.log("Reply: ");
                    console.log(data);
                    alert("Submitted");
                });
            }
        </script>

</body>
</html>