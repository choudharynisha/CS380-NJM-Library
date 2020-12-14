<?php
    include 'db.php';
?>
<!DOCTYPE html>
<html lang = "en-US">
    <head>
        <meta name = "viewport" content = "width=device-width, initial-scale=1">
        <link rel = "stylesheet" href = "http://comet.cs.brynmawr.edu/~nchoudhary/CS380-Library-System/style.css">
        <link rel = "stylesheet" type = "text/css" href = "http://comet.cs.brynmawr.edu/~nchoudhary/CS380-Library-System/bookTableStyle.css">
        <script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script>
            //filterFunction deals with filtering the columns of the table
            function filterFunction() {

            //get the input values to filter
            var bookInput, authorInput, genreInput, yearInput, bookFilter,  authorFilter, genreFilter, yearFilter, table, tr, td, i, txtValue;
            bookInput = document.getElementById("bookInput"); 
            authorInput = document.getElementById("authorInput"); 
            genreInput = document.getElementById("genreInput");
            yearInput = document.getElementById("yearInput");
            publisherInput = document.getElementById("publisherInput");

            bookFilter = bookInput.value.toUpperCase(); 
            authorFilter = authorInput.value.toUpperCase(); 
            genreFilter = genreInput.value.toUpperCase();
            yearFilter = yearInput.value.toUpperCase();
            publisherFilter = publisherInput.value.toUpperCase();

            //get the table to search through 
            table = document.getElementById("result");
            tr = table.getElementsByTagName("tr");

                // go through values of the table and filter by inputs and skip the header and the inputs rows
                for (i = 2; i < tr.length; i++) {
                    //checking the columns based on inputs
                    bookTd = tr[i].getElementsByTagName("td")[0];
                    authorTd = tr[i].getElementsByTagName("td")[1];
                    genreTd = tr[i].getElementsByTagName("td")[2];
                    yearTd = tr[i].getElementsByTagName("td")[3];
                    publisherTd = tr[i].getElementsByTagName("td")[4];
                    if (bookTd && authorTd && genreTd && yearTd && publisherTd) {
                        txtValueBook = bookTd.textContent || bookTd.innerText;
                        txtValueAuthor = authorTd.textContent || authorTd.innerText;
                        txtValueGenre = genreTd.textContent || genreTd.innerText;
                        txtValueYear = yearTd.textContent || yearTd.innerText;
                        txtValuePublisher = publisherTd.textContent || publisherTd.innerText;
                        if (txtValueBook.toUpperCase().indexOf(bookFilter) > -1 &&
                            txtValueAuthor.toUpperCase().indexOf(authorFilter) > -1 &&
                            txtValueGenre.toUpperCase().indexOf(genreFilter) > -1 &&
                            txtValueYear.toUpperCase().indexOf(yearFilter) > -1 &&
                            txtValuePublisher.toUpperCase().indexOf(publisherFilter) > -1) {
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
                <a href = "http://comet.cs.brynmawr.edu/~nchoudhary/CS380-Library-System/librarian_index.php"> Home</a>
                <a href = "http://comet.cs.brynmawr.edu/~nchoudhary/CS380-Library-System/lib_nav.php"> Books</a>
                <a href = "http://comet.cs.brynmawr.edu/~nchoudhary/CS380-Library-System/logout.php"> Log Out</a>
                <div class = "logo"><h1 style="color: yellow; font-size: 25px;text-align: center;">NJM Online Library</h1></div>
            </div>
        </div>

        <div class = "row">
            <div class = "leftcolumn">
                <div class = "card2">
                    <h1>All Available Books</h1>
                    <div class = 'listing' id = 'listing'>
                        <?php
                            ob_start();

                            //query to create table of available books
                            //$q = "select * from njm_books where status = 'Available';";
                            $q = "select * from njm_books 
                                    inner join njm_publishers 
                                    on njm_books.publisher_id = njm_publishers.publisher_id 
                                    where njm_books.status = 'Available'";
                        
                            $result = $connection->query($q);
                            if($result->num_rows > 0) {
                                echo "
                                
                                <table id = 'result'>
                                <tr>
                                    <th>Book Title</th>
                                    <th>Author</th>
                                    <th>Genre</th>
                                    <th>Year</th>
                                    <th>Publisher</th>
                                    
                                </tr> 
                                <tr>
                                <td><input type = 'text' id = 'bookInput' onkeyup = 'filterFunction()' placeholder = 'Search for Title'></td>
                                <td><input type = 'text' id = 'authorInput' onkeyup = 'filterFunction()' placeholder = 'Search for Author'></td>
                                <td><input type = 'text' id = 'genreInput' onkeyup = 'filterFunction()' placeholder = 'Search for Genre'></td>
                                <td><input type = 'text' id = 'yearInput' onkeyup = 'filterFunction()' placeholder = 'Search for Year'></td>
                                <td><input type = 'text' id = 'publisherInput' onkeyup = 'filterFunction()' placeholder = 'Search for Publisher'></td>
                                </tr>
                                ";
                                echo "<tbody class='bookRows' id = 'bookRows'>";
                                while ($row = $result->fetch_assoc()) {
                                    $title = $row['title'];
                                    $author = $row['author'];
                                    $book_id = $row['book_id'];
                                    $genre = $row['genre'];
                                    $year = $row['year'];
                                    $publisher = $row['name'];
                                    echo "<form id = 'bookorder' name = 'form1' method = 'post'> <tr class = 'table'>
                                        <td class = 'table' data-input = 'title'> <input type = 'hidden' name = 'title' value = '$title' > $title </td>
                                        <td class = 'table' data-input = 'author'> <input type = 'hidden' name = 'author' value = '$author' > $author </td>
                                        <td class = 'table' data-input = 'genre'> <input type = 'hidden' name = 'genre' value = '$genre' > $genre </td>
                                        <td class = 'table' data-input = 'year'> <input type = 'hidden' name = 'year' value = '$year' > $year </td>
                                        <td class = 'table' data-input = 'publisher'> <input type = 'hidden' name ='publisher' value = '$publisher' > $publisher </td>
                                        </tr> </form>";
                                }
                                echo "</tbody>";
                            } else {
                                echo "Did not Work!";
                            }
                            echo "</table>";
                            
                        ?>
                    </div>
                    <br>
                    <div class = "showup" id = "showup"></div>
                </div>
            </div>

            <div class = "rightcolumn" style = "padding-top: 70px">
                <h4><a href = "http://comet.cs.brynmawr.edu/~nchoudhary/CS380-Library-System/main_login.php">Log Out</a></h4>
                <h4><a href = "http://comet.cs.brynmawr.edu/~nchoudhary/CS380-Library-System/lib_add_patron.php">Add New Patron Account</a></h4>
                <h4><a href = "http://comet.cs.brynmawr.edu/~nchoudhary/CS380-Library-System/addbook.php">Add New Book Record</a></h4>
                <h4><a href = "http://comet.cs.brynmawr.edu/~nchoudhary/CS380-Library-System/librarianViewBorrowed.php">Return Borrowed Books</a></h4>
            
                <h3>Monthly Book Club Reads</h3>
                <div class = "fakeimg"><img src = "http://comet.cs.brynmawr.edu/~nchoudhary/CS380-Library-System/images/persuasion_ja.jpg"><br>Persuasion by Jane Austen</div><br>
                <div class = "fakeimg"><img src = "http://comet.cs.brynmawr.edu/~nchoudhary/CS380-Library-System/images/anxious_people.jpeg"><br>Anxious People by Fredrick Backman</div><br>
            </div>
        </div>
        <div class = "footer">
            <p style = "color:white; text-align: center; ">
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
        </script>
    </body>
</html>
