<?php
    require('db.php');
    session_start();
    $expiry = 5400; //90 min is 5400 sec
    if(isset($_SESSION['login_time']) && (time() - $_SESSION['login_time'] > $expiry)) {
        session_unset();
        session_destroy();
        echo "<script>
            alert('Please login again. Your session expired'); 
            window.location.href = 'main_login.php';
            </script>";
    }

    $getpublishers = "SELECT publisher_id, name, city, state FROM njm_publishers ORDER BY name, city";
    $getgenres = "SELECT DISTINCT genre FROM njm_books ORDER BY genre";
    $allpublishers = $connection->query($getpublishers);
    $allgenres = $connection->query($getgenres);

    if($allpublishers->num_rows > 0) {
        // all publishers in the publishers table
        $publishers = "";

        while($row = $allpublishers->fetch_assoc()) {
            $publishers .= "<option value = '" . $row['publisher_id'] . "'>" . $row['name'] . ", " . $row['city'] . ", " . $row['state'] . "</option>";
        }

        $publishers .= "<option value = 0>[NEW PUBLISHER]</option>";
    } else {
        // no publishers in the publisher table and publisher to be added upon addition of the first book
        $publishers = "<option value = 0>[NEW PUBLISHER]</option>";
    }

    if($allgenres->num_rows > 0) {
        // genres from books already in the table
        $genres = "";
        
        while($row = $allgenres->fetch_assoc()) {
            $genres .= "<option value = " . $row['genre'] . ">" . $row['genre'] . "</option>";
        }

        $genres .= "<option value = 0>[NEW GENRE]</option>";
    } else {
        $genres = "<option value = 0>[NEW GENRE]</option>";
    }

    if(isset($_POST['submit'])) {
        // gets the new book's information upon pressing submit
        $title = $connection->real_escape_string($_POST['title']);
        $author = $connection->real_escape_string($_POST['author']);
        $genre = strlen($_POST['newGenre']) > 0 ? $connection->real_escape_string($_POST['newGenre']) : $connection->real_escape_string($_POST['genre']);
        $year = $_POST['year'];
        $publisher = $connection->real_escape_string($_POST['publishers']);
        
        if($publisher == 0) {
            // a book with a new publisher
            // get publisher information to be added to the njm_publishers table so that this new book can be added
            $name = $connection->real_escape_string($_POST['publisher']);
            $city = $connection->real_escape_string($_POST['city']);
            $state = $_POST['state'];
            $zip = $_POST['zip'];

            $addpublisher = "INSERT INTO njm_publishers (name, city, state, zip) VALUES ('$name', '$city', '$state', '$zip')";
            
            if($connection->query($addpublisher) === TRUE) {
                // successful addition of the new publisher to njm_publishers
                $getpublisher = "SELECT publisher_id FROM njm_publishers WHERE name = '$name' AND city = '$city' AND state = '$state' AND zip = '$zip'";
                // echo $getpublisher;
                $result = $connection->query($getpublisher) or die($connection->error); // update the publisher id (0 to the new publisher's id in njm_publishers)
                $publisher = $result->fetch_assoc()['publisher_id'];
            } else {
                die("Unable to add new publisher");
            }
        }

        if(($year > date('Y')) xor ($year < 1454)) {
            // publication year entered is after the current year or before Johannes Gutenburg built the world's first ever printing press
            // (according to https://www.booktrust.org.uk)
            die("Invalid Year of Publication");
        }

        $addbook = "INSERT INTO njm_books (title, author, genre, year, publisher_id, status) VALUES ('$title', '$author', '$genre', '$year', $publisher, 'Available')";

        if($connection->query($addbook) === TRUE) {
            // successful addition of the new book to njm_books
            echo "New book added successfuly";

        } else {
            echo "Error – " . $addbook . "<br>" . $connection->error;
        }

        die("{publisher: $publisher}");
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta name = "viewport" content = "width=device-width, initial-scale=1">
        <link rel = "stylesheet" href = "style.css">
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
                    <a href = "librarian_index.php">Home</a>
                    <a href = "lib_nav.php">Books</a>
                    <a href = "logout.php">Log Out</a>

                    <div class = "logo">
                    <h1 style = "color: yellow; font-size: 25px;text-align: center;">NJM Online Library</h1>
                </div>
            </div>
        </div>
        <div class = "row">
            <div class = "leftcolumn">
                <div class = "card2">
                    <h2 style = "text-align: center;">Add A New Book Record</h2>
                    <div class = "boxc">
                        <form style = "padding-top: 30px; text-align: left; padding-left: 20px;" id = "newbookform" onsubmit = "addBook(); return false;" action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method = "post">
                            Title
                            <input type = "text" name = "title" required /><br /><br />

                            Author
                            <input type = "text" name = "author" required /><br /><br />

                            Genre
                            <select name = "genre" onchange = "addNewGenre(this)">
                                <?php echo $genres; ?>
                            </select>
                            <br /><br />

                            <div id = "newGenre">
                                <input type = "text" name = "newGenre" id = "genre" /><br /><br />
                            </div>

                            Year of Publication
                            <input type = "number" name = "year" required /><br /><br />

                            Publisher
                            <select name = "publishers" onchange = "addNewPublisher(this)">
                                <?php echo $publishers; ?>
                            </select>
                            <br /><br />

                            <div id = "newPublisher">
                                Publisher Name
                                <input type = "text" name = "publisher" id = "publisher" /><br /><br />

                                City
                                <input type = "text" name = "city" id = "city" /><br /><br />

                                State
                                <input type = "text" name = "state" id = "state" /><br /><br />

                                Zip Code
                                <input type = "text" name = "zip" id = "zip" /><br /><br />
                            </div>

                            <input type = "submit" value = "submit" name = "submit" />
                        </form>
                </div>
            </div>
        </div>
            <div class = "rightcolumn">
                <h4><a href = "main_login.php">Log Out</a></h4>
                <h4><a href = "lib_add_patron.php">Add New Patron Account</a></h4>
                <h4><a href = "addbook.php">Add New Book Record</a></h4>
                <h4><a href = "librarianViewBorrowed.php">Return Borrowed Books</a></h4>
                <h3>Monthly Book Club Reads</h3>
                <div class="fakeimg"><img src="images/persuasion_ja.jpg"><br>Persuasion by Jane Austen</div>
                <div class="fakeimg"><img src="images/anxious_people.jpeg"><br>Anxious People by Fredrick Backman</div><br>
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