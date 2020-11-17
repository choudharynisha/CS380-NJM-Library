<?php
    require('db.php');

    $getpublishers = "SELECT publisher_id, name, city, state FROM njm_publishers ORDER BY name, city";
    $getgenres = "SELECT DISTINCT genre FROM njm_books ORDER BY genre";
    $allpublishers = $connnection->query($getpublishers);
    $allgenres = $connnection->query($getgenres);

    if($allpublishers->num_rows > 0) {
        // all publishers in the publishers table
        $publishers = "";

        while($row = $allpublishers->fetch_assoc()) {
            $publishers .= "<option value = " . $row['publisher_id'] . ">" . $row['name'] . ", " . $row['city'] . ", " . $row['state'] . "</option>";
        }

        $publishers .= "<option value = 0>[NEW PUBLISHER]</option>";
    } else {
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
        $title = $_POST['title'];
        $author = $_POST['author'];
        $genre = isset($_POST['newGenre']) ? $_POST['newGenre'] : $_POST['genre'];
        $year = $_POST['year'];
        $publisher = $_POST['publishers'];
        
        if($publisher == 0) {
            $name = $_POST['publisher'];
            $city = $_POST['city'];
            $state = $_POST['state'];
            $zip = $_POST['zip'];

            $addpublisher = "INSERT INTO njm_publishers (name, city, state, zip) VALUES ('$name', '$city', '$state', '$zip')";
            
            if($connection->query($addpublisher) === TRUE) {
                $getpublisher = "SELECT publisher_id FROM njm_publishers WHERE name = $name, city = $city, state = $state, zip = $zip";
                $publisher = $connection->query($getpublisher);
                echo $publisher;
            } else {
                die("Unable to add new publisher");
            }
        }

        if(($year > date('Y')) xor ($year < 1454)) {
            // publication year entered is after the current year or before Johannes Gutenburg built the world's first ever printing press (according to https://www.booktrust.org.uk)
            die("Invalid Year of Publication");
        }

        $addbook = "INSERT INTO njm_books (title, author, genre, year, publisher_id, status) VALUES ('$title', '$author', '$genre', '$year', '$publisher', 'Available')";

        if($connnection->query($addbook) === TRUE) {
            echo "New book added successfuly";
        } else {
            echo "Error – " . $sql . "<br>" . $connnection->error;
        }
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <link href = "addbook.css" rel = "stylesheet" />
    </head>
    <body>
        <form action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method = "post">
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

        <script>
            function addNewGenre(element) {
                let id = element.selectedIndex;
                let newGenre = document.getElementById("newGenre");

                if(element.options[id].text == "[NEW GENRE]") {
                    newGenre.style.display = "block";
                    document.getElementById("genre").required = true;
                } else {
                    newGenre.style.display = "none";
                    document.getElementById("genre").required = false;
                }
            }

            function addNewPublisher(element) {
                let id = element.selectedIndex;
                let newPublisher = document.getElementById("newPublisher");

                if(element.options[id].text == "[NEW PUBLISHER]") {
                    newPublisher.style.display = "block";
                    document.getElementById("publisher").required = true;
                    document.getElementById("city").required = true;
                    document.getElementById("state").required = true;
                    document.getElementById("zip").required = true;
                } else {
                    newPublisher.style.display = "none";
                    document.getElementById("publisher").required = false;
                    document.getElementById("city").required = false;
                    document.getElementById("state").required = false;
                    document.getElementById("zip").required = false;
                }
            }
        </script>
    </body>
</html>