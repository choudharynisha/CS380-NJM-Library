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
        <link href = "addpatron.css" rel = "stylesheet" />
    </head>
    <body>
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