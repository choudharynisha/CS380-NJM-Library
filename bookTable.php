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
        var bookInput, authorInput, genreInput, yearInput, bookFilter,  authorFilter, genreFilter, yearFilter, table, tr, td, i, txtValue;
        bookInput = document.getElementById("bookInput"); 
        authorInput = document.getElementById("authorInput"); 
        genreInput = document.getElementById("genreInput");
        yearInput = document.getElementById("yearInput");

        bookFilter = bookInput.value.toUpperCase(); 
        authorFilter = authorInput.value.toUpperCase(); 
        genreFilter = genreInput.value.toUpperCase();
        yearFilter = yearInput.value.toUpperCase();

        //get the table to search through 
        table = document.getElementById("result");
        tr = table.getElementsByTagName("tr");

        //go through values of the table and filter by inputs and skip the header and the inputs rows
        for (i = 2; i < tr.length; i++) {
            //checking the columns based on inputs
            bookTd = tr[i].getElementsByTagName("td")[0];
            authorTd = tr[i].getElementsByTagName("td")[1];
            genreTd = tr[i].getElementsByTagName("td")[2];
            yearTd = tr[i].getElementsByTagName("td")[3];
            if (bookTd && authorTd && genreTd && yearTd) {
                txtValueBook = bookTd.textContent || bookTd.innerText;
                txtValueAuthor = authorTd.textContent || authorTd.innerText;
                txtValueGenre = genreTd.textContent || genreTd.innerText;
                txtValueYear = yearTd.textContent || yearTd.innerText;
                if (txtValueBook.toUpperCase().indexOf(bookFilter) > -1 &&
                txtValueAuthor.toUpperCase().indexOf(authorFilter) > -1 &&
                    txtValueGenre.toUpperCase().indexOf(genreFilter) > -1 &&
                    txtValueYear.toUpperCase().indexOf(yearFilter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }

    }

    $(document).ready(function() {

        //Toggles the Show or Hide Borrowed div
        $('.viewBooks').click(function(event) {
            event.stopPropagation();
            $(".showup").slideToggle("fast");
            $('.showup').load('loadBorrowBooksDiv.php', function() {  //loads the php file to update the div with new borrowed books
                console.log('Load was performed.');
            });

        });

        //Toggles the the text for Show or Hide Borrowed button
        $('.viewBtn').click(function(event) {
            var button = $(this);
            button.text(button.text() == "Hide Borrowed" ? "Show Borrowed" : "Hide Borrowed")
        });

    });
    </script>

</head>

<body>
    <h1>Student Borrow Book Page</h1>

    <div class="viewBooks">
        <button class="viewBtn">Show Borrowed</button>
    </div>

    <div class="showup" id="showup">

    </div>

    <br></br>

    <div class='listing' id='listing'>
        <?php
            ob_start();
            //query to create table of available books
            $q = "select * from njm_books where status = 'Available';";
            $result = $conn->query($q);
            if ($result->num_rows > 0) {
                echo "
                
                <table id='result'>
                <tr>
                    <th>Book Title</th>
                    <th>Author</th>
                    <th>Genre</th>
                    <th>Year</th>
                    <th>Select</th>
                </tr> 
                <tr>
                <td><input type='text' id='bookInput' onkeyup='filterFunction()' placeholder='Search for Title..'></td>
                <td><input type='text' id='authorInput' onkeyup='filterFunction()' placeholder='Search for Author..'></td>
                <td><input type='text' id='genreInput' onkeyup='filterFunction()' placeholder='Search for Genre..'></td>
                <td><input type='text' id='yearInput' onkeyup='filterFunction()' placeholder='Search for Year..'></td>
                
                </tr>
                ";
                echo "<tbody class='bookRows' id = 'bookRows'>";
                while ($row = $result->fetch_assoc()) {
                    $title = $row['title'];
                    $author = $row['author'];
                    $book_id = $row['book_id'];
                    $genre = $row['genre'];
                    $year = $row['year'];
                    echo "<form id='bookorder' name='form1' method='post'> <tr class='table'>
                        <td class='table' data-input='title'> <input type = 'hidden' name='title' value= '$title' > $title </td>
                        <td class='table' data-input='author'> <input type = 'hidden' name='author' value= '$author' > $author </td>
                        <td class='table' data-input='genre'> <input type = 'hidden' name='genre' value= '$genre' > $genre </td>
                        <td class='table' data-input='year'> <input type = 'hidden' name='year' value= '$year' > $year </td>
                        
                        <input type = 'hidden' name='book_id' value= '$book_id' >
                        <td> <button class='editbtn' type='submit' value='submit'> Borrow </button></td>
                        </tr> </form>";
                }
                
                echo "</tbody>";
                

                //checks if form has been submitted and adds to the transaction table and changes the status in the books table
                if (isset($_POST['title']) && isset($_POST['author'])){

                    $dueDate = date('Y-m-d',strtotime('+7 day')); //gets the current date and adds a week to it
                    $q = "insert into njm_transactions (transaction_type, book_id, user_id, due_date) values 
                    ('borrowed', '".$_POST['book_id']."', 14, '$dueDate');";  //need to change 14 to user_id

                    if (mysqli_query($conn, $q)) {
                        echo "New record created successfully";
                      } else {
                        echo "Error: " . $q . "<br>" . mysqli_error($conn);
                    }

                    $statusChange = "update njm_books set status = 'Not Available' where book_id = '".$_POST['book_id']."';";

                    if (mysqli_query($conn, $statusChange)) {
                        echo "New record created successfully";
                      } else {
                        echo "Error: " . $statusChange . "<br>" . mysqli_error($conn);
                    }

                    header("Location: ".$_SERVER['REQUEST_URI']); //reloads the page to update the changes in the table
                   
                }

            }
            else {
                echo "Did not Work!";
            }
            echo "</table>";
            
        ?>
    </div>




</body>

</html>
