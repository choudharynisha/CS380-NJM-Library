<?php
    require('db.php');
    $start = 0;

    $getpublishers = "SELECT publisher_id, name, city, state FROM njm_publishers";
    $getgenres = "SELECT DISTINCT genre FROM njm_books";
    $allpublishers = $connnection->query($getpublishers);
    $allgenres = $connnection->query($getgenres);

    if($allpublishers->num_rows > 0) {
        $publishers = "";

        while($row = $allpublishers->fetch_assoc()) {
            $publishers .= "<option value = " . $row['publisher_id'] . ">" . $row['name'] . ", " . $row['city'] . ", " . $row['state'] . "</option>";
        }

        $publishers .= "<option value = 0>[NEW PUBLISHER]</option>";
    } else {
        $publishers = "<option value = 1>[New Publisher]</option>";
    }

    if($allgenres->num_rows > 0) {
        $genres = "";
        
        while($row = $allgenres->fetch_assoc()) {
            $genres .= "<option value = " . $row['genre'] . ">" . $row['genre'] . "</option>";
        }
    } else {
        $genres = "<option value = 'new genre'>[New Genre]</option>";
    }

    if(isset($_POST['submit'])) {
        require("db.php");
        $title = "";
        $author = "";
        $genre = "";
        $year = 0;
        $publisher = 1;
        
        if(isset($_POST['title'])) {
            $title = $_POST['title'];
        }
        
        if(isset($_POST['author'])) {
            $author = $_POST['author'];
        }
        
        if(isset($_POST['genre'])) {
            $genre = $_POST['genre'];
        }
        
        if(isset($_POST['year'])) {
            $year = $_POST['year'];
        }
        
        if(isset($_POST['publishers'])) {
            $publisher = $_POST['publishers'];

            if($publisher == 0) {

            }
        }

        if(($year > date('Y')) xor ($year < 1832)) {
            die("Invalid Year of Publication");
        }

        $sql = "SELECT * FROM njm_books WHERE title = $title, author = $author, genre = $genre, year = $year, publisher_id = $publisher";
        $book = $connnection->query($sql);

        if($book->num_rows > 0) {
            echo $book;
            die("Book is Already in the Collection");
        }

        $sql = "INSERT INTO njm_books (title, author, genre, year, publisher_id, status) VALUES ('$title', '$author', '$genre', '$year', '$publisher', 'Available')";

        if($connnection->query($sql) === TRUE) {
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
            <select name = "genre">
                <?php echo $genres; ?>
            </select>
            <br /><br />

            Year of Publication
            <input type = "number" name = "year" required /><br /><br />

            Publisher
            <select name = "publishers" onchange = "addNew(this)">
                <?php echo $publishers; ?>
            </select>
            <br /><br />

            <div id = "newPublisher">
                Publisher Name
                <input type = "text" name = "newPublisher" id = "publisher" /><br /><br />

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
            function addNew(e) {
                let id = e.selectedIndex;
                let e2 = document.getElementById("newPublisher");
                if(e.options[id].text == "[NEW PUBLISHER]") {
                    e2.style.display = "block";
                    document.getElementById("publisher").required = true;
                    document.getElementById("city").required = true;
                    document.getElementById("state").required = true;
                    document.getElementById("zip").required = true;
                } else {
                    e2.style.display = "none";
                    document.getElementById("publisher").required = false;
                    document.getElementById("city").required = false;
                    document.getElementById("state").required = false;
                    document.getElementById("zip").required = false;
                }
            }
        </script>
    </body>
</html>