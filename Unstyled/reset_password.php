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
<!DOCTYPE html>
<html lang = "en-US" dir = "ltr">
    <head></head>
    <body>
        <form id = "resetpassword" onsubmit = "resetPassword(); return false;" action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method = "post">
            <input type = "hidden" name = "username" value = "<?php echo $username; ?>" />
            <input type = "hidden" name = "question" value = "<?php echo $question; ?>" />

            Security Question
            <p><?php echo $question; ?></p>

            Security Answer
            <input type = "text" name = "answer" value = "<?php if(isset($_POST['answer'])) {echo $answer;} ?>" required /><br /><br />

            Password
            <input type = "password" name = "password" id = "pw1" value = "<?php if(isset($_POST['password'])) {echo $password;} ?>" required /><br /><br />

            Retype Password
            <input type = "password" name = "retyped" id = "pw2" value = "<?php if(isset($_POST['retyped'])) {echo $password2;} ?>" required /><br /><br />

            <input type = "submit" value = "submit" name = "submit" />
        </form>
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