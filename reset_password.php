<?php
    require("db.php");

    if(isset($_POST['submit'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        if((strlen($password) < 6) or (strlen($password) > 20)) {
            $error = "Invalid Password";
        } else {
            $hashedpassword = password_hash($password, PASSWORD_DEFAULT);
        }
    }
?>
<!DOCTYPE html>
<html lang = "en-US" dir = "ltr">
    <head></head>
    <body>
        <form id = "resetpassword" onsubmit = "resetPassword(); return false;" action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method = "post">
            Username
            <input type = "text" name = "username" value = "<?php if(isset($_POST['username'])) {echo $username;} ?>" required /><br /><br />

            Security Question
            <input type = "text" name = "question" value = "<?php if(isset($_POST['question'])) {echo $question;} ?>" required /><br /><br />

            Security Answer
            <input type = "text" name = "answer" value = "<?php if(isset($_POST['answer'])) {echo $answer;} ?>" required /><br /><br />

            Password
            <input type = "password" name = "password" id = "pw1" value = "<?php if(isset($_POST['password'])) {echo $password;} ?>" required /><br /><br />

            Retype Password
            <input type = "password" name = "retyped" id = "pw2" value = "<?php if(isset($_POST['retyped'])) {echo $retyped;} ?>" required /><br /><br />

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