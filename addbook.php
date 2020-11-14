<?php
    require('db.php');
    $sql = "SELECT publisher_id, name, city, state FROM njm_publishers";
    $publishers = $conn->query($sql);

    if($publishers->num_rows > 0) {
        $options = "";

        while($row = $publishers->fetch_assoc()) {
            $options .= "<option value = " . $row['publisher_id'] . ">" . $row['name'] . ", " . $row['city'] . ", " . $row['state'] . "</option>";
        }

        $options .= "<option value = " . ($publishers->num_rows + 1) . ">[NEW PUBLISHER]</option>";
    } else {
        $options = "<option value = 1>[New Publisher]</option>";
    }

    if(isset($_POST)) {
        $title = $_POST['title'];
        $author = $_POST['author'];
        $genre = $_POST['genre'];
        $year = $_POST['year'];
        $publisher = $_POST['publisher'];

        $sql = "INSERT INTO njm_books (title, author, genre, year, publisher_id, status)
        VALUES ('$title', '$author', '$genre', '$year', '$publisher', 'Available')";

        if($conn->query($sql) === TRUE) {
            echo "New book added successfuly";
        } else {
            echo "Error – " . $sql . "<br>" . $conn->error;
        }
    }
?>

<!DOCTYPE html>
<html>
    <form action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method = "post">
        Title
        <input type = "text" name = "title" required /><br /><br />

        Author
        <input type = "text" name = "author" required /><br /><br />

        Genre
        <input type = "text" name = "genre" required /><br /><br />

        Year of Publication
        <input type = "number" name = "year" required /><br /><br />

        Publisher
        <select name = "publishers">
            <?php echo $options; ?>
        </select>
        <br /><br />

        <input type = "submit" value = "submit" />
    </form>
</html>