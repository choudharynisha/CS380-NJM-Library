
<?php
    include 'db.php';
?>
<?php
            $q = "select njm_books.title, njm_books.author from njm_books 
            inner join njm_transactions  on njm_books.book_id = njm_transactions.book_id 
            where user_id = 2;"; //should change to actual user logged in later
            $result = $conn->query($q);
            if ($result->num_rows > 0) {
                echo "
                 <table id='result'>
                <tr>
                    <th>Book Title</th>
                    <th>Author</th>
                </tr>";
                while ($row = $result->fetch_assoc()) {
                    $title = $row['title'];
                    $author = $row['author'];
                    echo "<tr class='table'>
                        <td class='table'> <input type = 'hidden' name='title' value= '$title' > $title </td>
                        <td class='table'> <input type = 'hidden' name='author' value= '$author' > $author </td>
                        </tr>";
                }
                echo "</table> ";
            }
            else {
                echo "<table>
                <tr><th>Books Borrowed</th></tr>
                <tr><td>No Books</td></tr>
                </table>";
            }
        ?>
