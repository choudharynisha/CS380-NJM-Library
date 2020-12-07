<?php
    include 'db.php';
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="style.css">
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
    <?php
    //creates table that shows the borrowed books of the current user
    //this php file is called to update the div that shows what books have been borrowed

        $q = "select njm_books.title, njm_books.author, njm_transactions.due_date from njm_books 
        inner join njm_transactions on njm_books.book_id = njm_transactions.book_id 
        where user_id = 14 and njm_books.status = 'Not Available'
        group by njm_transactions.book_id;"; //change 14 to user_id
        $result = $connection->query($q);
        if ($result->num_rows > 0) {
            echo "
            <table id='result'>
            <tr>
                <th>Book Title</th>
                <th>Author</th>
                <th>Due Date</th>
            </tr>";
            while ($row = $result->fetch_assoc()) {
                $title = $row['title'];
                $author = $row['author'];
                $dueDate = $row['due_date'];
                echo "<tr class='table'>
                    <td class='table'> <input type = 'hidden' name='title' value= '$title' > $title </td>
                    <td class='table'> <input type = 'hidden' name='author' value= '$author' > $author </td>
                    <td class='table'> <input type = 'hidden' name='dueDate' value= '$dueDate' > $dueDate </td>
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
</body>
</html>



