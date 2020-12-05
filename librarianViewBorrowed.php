<?php
    include 'db.php';
?>

<html>

<head>
    <link rel="stylesheet" type="text/css" href="bookTableStyle.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
    
    //filterFunction deals with filtering the columns of the table
    function filterFunction() {

        //get the input values to filter
        var bookInput, authorInput, idInput, dueInput, bookFilter,  authorFilter, idFilter, dueFilter, table, tr, td, i, txtValue;
        bookInput = document.getElementById("bookInput"); 
        authorInput = document.getElementById("authorInput"); 
        idInput = document.getElementById("idInput");
        dueInput = document.getElementById("dueInput");

        bookFilter = bookInput.value.toUpperCase(); 
        authorFilter = authorInput.value.toUpperCase(); 
        idFilter = idInput.value.toUpperCase();
        dueFilter = dueInput.value.toUpperCase();

        //get the table to search through 
        table = document.getElementById("result");
        tr = table.getElementsByTagName("tr");

        //go through values of the table and filter by inputs and skip the header and the inputs rows
        for (i = 2; i < tr.length; i++) {
            //checking the columns based on inputs
            idTd = tr[i].getElementsByTagName("td")[0];
            bookTd = tr[i].getElementsByTagName("td")[1];
            authorTd = tr[i].getElementsByTagName("td")[2];
            dueTd = tr[i].getElementsByTagName("td")[3];
            if (bookTd && authorTd && idTd && dueTd) {
                txtValueBook = bookTd.textContent || bookTd.innerText;
                txtValueAuthor = authorTd.textContent || authorTd.innerText;
                txtValueid = idTd.textContent || idTd.innerText;
                txtValueDue = dueTd.textContent || dueTd.innerText;
                if (txtValueBook.toUpperCase().indexOf(bookFilter) > -1 &&
                    txtValueAuthor.toUpperCase().indexOf(authorFilter) > -1 &&
                    txtValueid.toUpperCase().indexOf(idFilter) > -1 &&
                    txtValueDue.toUpperCase().indexOf(dueFilter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }

    }

    </script>

</head>

<body>
    <h1>Librarian View Borrow Book Page</h1>

    <br></br>

    <div class='listing' id='listing'>
        <?php
        ob_start();

            //creates the table for the books that can be borrowed and option to return
            $q = "select njm_transactions.user_id, njm_books.title, njm_books.author, njm_transactions.due_date, njm_transactions.book_id
                from njm_books inner join njm_transactions 
                on njm_books.book_id = njm_transactions.book_id 
                where user_id = 14 and status = 'Not Available'  
                group by njm_transactions.book_id;";

            $result = $conn->query($q);
            if ($result->num_rows > 0) {
                echo "
                
                <table id='result'>
                <tr>
                    <th>User ID</th>
                    <th>Book Title</th>
                    <th>Author</th>
                    <th>Due Date</th>
                    <th>Select</th>
                </tr> 
                <tr>
                <td><input type='text' id='idInput' onkeyup='filterFunction()' placeholder='Search for User ID..'></td>
                <td><input type='text' id='bookInput' onkeyup='filterFunction()' placeholder='Search for Title..'></td>
                <td><input type='text' id='authorInput' onkeyup='filterFunction()' placeholder='Search for Author..'></td>
                <td><input type='text' id='dueInput' onkeyup='filterFunction()' placeholder='Search for Due Date..'></td>
                
                </tr>
                ";
                echo "<tbody class='bookRows' id = 'bookRows'>";
                while ($row = $result->fetch_assoc()) {
                    $book_id = $row['book_id'];
                    $user_id = $row['user_id'];
                    $title = $row['title'];
                    $author = $row['author'];
                    $due_date = $row['due_date'];
                    echo "<form id='bookorder' name='form1' method='post'> <tr class='table'>
                        <td class='table' data-input='user_id'> <input type = 'hidden' name='user_id' value= '$user_id' > $user_id </td>
                        <td class='table' data-input='title'> <input type = 'hidden' name='title' value= '$title' > $title </td>
                        <td class='table' data-input='author'> <input type = 'hidden' name='author' value= '$author' > $author </td>
                        <td class='table' data-input='due_date'> <input type = 'hidden' name='due_date' value= '$due_date' > $due_date </td>
                        
                        <input type = 'hidden' name='book_id' value= '$book_id' >
                        <td> <button class='editbtn' type='submit' value='submit'> Return </button></td>
                        </tr> </form>";
                }
                
                echo "</tbody>";
                

                //checks if form has been submitted and adds to the transaction table and changes the status in the books table
                if (isset($_POST['book_id'])){
                    $returnDate = date('Y-m-d H:i:s'); //gets the current date it was returned

                    $q = "insert into njm_transactions (transaction_type, book_id, user_id, due_date) values 
                    ('returned', '".$_POST['book_id']."', 14, '$returnDate');";  //need to change 14 to the user_id

                    if (mysqli_query($conn, $q)) {
                        echo "New record created successfully";
                      } else {
                        echo "Error: " . $q . "<br>" . mysqli_error($conn);
                    }

                    $statusChange = "update njm_books set status = 'Available' where book_id = '".$_POST['book_id']."';";

                    if (mysqli_query($conn, $statusChange)) {
                        echo "New record created successfully";
                      } else {
                        echo "Error: " . $statusChange . "<br>" . mysqli_error($conn);
                    }

                    header("Location: ".$_SERVER['REQUEST_URI']); //reloads the page to update the changes in the table
                   
                }

            }
            else {
                echo "None Borrowed";
            }
            echo "</table>";
            
        ?>
    </div>




</body>

</html>
