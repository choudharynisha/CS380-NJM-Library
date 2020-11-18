<?php
    require("db.php");

    if(isset($_POST['submit'])) {
        // gets the new patron's information upon pressing submit
        $username = $_POST['username'];
        $password = $_POST['password'];
        $email = $_POST['email'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $street = $_POST['streetaddress'];
        $town = $_POST['town'];
        $state = $_POST['state'];
        $zip = $_POST['zip'];

        // form validation
        if((strlen($username) < 5) or (strlen($username) > 20)) {
            die("Invalid Username");
        }

        if((strlen($password) < 6) or (strlen($password) > 20)) {
            die("Invalid Password");
        }
        
        if((strlen($state) < 4) or (strlen($state) > 50)) {
            die("Invalid State Name");
        }

        if(strlen($zip) != 5) {
            die("Invalid Zip Code");
        }

        // add new patron
        $addpatron = "INSERT INTO njm_users (username, password, email, role, first_name, last_name, street_address, town, state, zip) VALUES ($username, $password, $email, 'borrower', $firstname, $lastname, $street, $town, $state, $zip)";
        
        if($connnection->query($addpatron) === TRUE) {
            // successful addition of the new book to njm_books
            echo "New patron added successfuly";
        } else {
            echo "Error – " . $addpatron . "<br>" . $connnection->error;
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <link href = "addpatron.css" rel = "stylesheet" />
    </head>
    <body>
        <form action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method = "post">
            Username
            <input type = "text" name = "username" required /><br /><br />

            Password
            <input type = "text" name = "password" required /><br /><br />

            Email
            <input type = "email" name = "email" required /><br /><br />

            First Name
            <input type = "text" name = "firstname" required /><br /><br />

            Last Name
            <input type = "text" name = "lastname" required /><br /><br />

            Street Address
            <input type = "text" name = "address" required /><br /><br />

            Town
            <input type = "text" name = "town" required /><br /><br />

            State
            <input type = "text" name = "state" required /><br /><br />

            Zip Code
            <input type = "text" name = "zip" required /><br /><br />

            <input type = "submit" value = "submit" name = "submit" />
        </form>
    </body>
</html>