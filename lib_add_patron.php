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
<html lang = "en-US" dir = "ltr">
    <head>
        <meta name = "viewport" content = "width=device-width, initial-scale=1">
        <link rel = "stylesheet" href = "http://comet.cs.brynmawr.edu/~nchoudhary/CS380-Library-System/style.css">
        <meta charset = "utf-8">
        <link rel = "stylesheet" href = "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        

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
                    <h2 style = "text-align: center;">Add A New Patron Account</h2>
                    <div class = "boxb">

                    <form id = "newpatronform" onsubmit = "addPatron(); return false;" action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method = "post">
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
                </div>
            </div>
        </div>
            <div class = "rightcolumn" style="padding-top:70px">
                <h4><a href = "http://comet.cs.brynmawr.edu/~nchoudhary/CS380-Library-System/main_login.php">Log Out</a></h4>
                <h4><a href = "http://comet.cs.brynmawr.edu/~nchoudhary/CS380-Library-System/lib_add_patron.php">Add New Patron Account</a></h4>
                <h4><a href = "http://comet.cs.brynmawr.edu/~nchoudhary/CS380-Library-System/addbook.php">Add New Book Record</a></h4>
                <h4><a href = "http://comet.cs.brynmawr.edu/~nchoudhary/CS380-Library-System/librarianViewBorrowed.php">Return Borrowed Books</a></h4>
                    <h3>Monthly Book Club Reads</h3>
                    <div class = "fakeimg">
                        <img src = "http://comet.cs.brynmawr.edu/~nchoudhary/CS380-Library-System/images/persuasion_ja.jpg"> <br>Persuasion by Jane Austen
                    </div>
                    <div class = "fakeimg">
                        <img src = "http://comet.cs.brynmawr.edu/~nchoudhary/CS380-Library-System/images/anxious_people.jpeg"><br>Anxious People by Fredrick Backman
                    </div>
            </div>
        </div>

        <div class = "footer">
            <p style = "color:white;  text-align: center; ">
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
        
            function addNewGenre(element) {
                let id = element.selectedIndex;
                let newGenre = document.getElementById("newGenre");

                if(element.options[id].text == "[NEW GENRE]") {
                    // prompts the librarian to type in a new genre if and when [NEW GENRE] is selected
                    newGenre.style.display = "block";
                    document.getElementById("genre").required = true;
                } else {
                    // hides the field to type in a new genre if and when the librarian switches from [NEW GENRE] to a genre listed in the dropdown menu
                    newGenre.style.display = "none";
                    document.getElementById("genre").required = false;
                }
            }

            function addNewPublisher(element) {
                let id = element.selectedIndex;
                let newPublisher = document.getElementById("newPublisher");

                if(element.options[id].text == "[NEW PUBLISHER]") {
                    // prompts the librarian to enter publisher information if and when [NEW PUBLISHER] is selected
                    newPublisher.style.display = "block";
                    document.getElementById("publisher").required = true;
                    document.getElementById("city").required = true;
                    document.getElementById("state").required = true;
                    document.getElementById("zip").required = true;
                } else {
                    // hides the fields to enter new publisher information if and when the librarian switches from [NEW PUBLISHER] to a publisher listed in the dropdown menu
                    newPublisher.style.display = "none";
                    document.getElementById("publisher").required = false;
                    document.getElementById("city").required = false;
                    document.getElementById("state").required = false;
                    document.getElementById("zip").required = false;
                }
            }

            function addBook() {
                // getting and saving the user's form input for a new patron
                let form = new FormData(document.getElementById('newbookform'));
                let userInput = "";

                for(obj of form) {
                    console.log(obj);
                    userInput += obj[0] + "=" + obj[1] + "&";
                }

                // make sure that submit is set so that it can be processed using PHP
                userInput += "submit=submit";

                // sending the processed user input to be loaded using PHP
                fetch("addbook.php", {
                    method: "POST",
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: userInput
                }).then (function(data) {
                    console.log("Reply: ");
                    console.log(data.text);
                    alert("New Book Successfully Added");
                    location.replace("addbook.php")
                });
            }
        </script>
    </body>
</html>