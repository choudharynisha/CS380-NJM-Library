<?php
    include 'db.php';
?>


<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="style.css">
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
    <div class="hero-image">

        <div id="navbar">
            <a href="index.html"> Home</a>
            <a href="main_login.php"> Log In</a>
            <a href="logout.php">Log Out</a>
            <a href="bookTable.php"> Books</a>
            <a href="index.html"> Ask Librarian</a>
        
            <div class="logo">
            <!--<img src="lanternz.gif">-->
            <h1 style="color: yellow; font-size: 25px;text-align: center;">NJM Online Library</h1>
        </div>
    </div>


    </div>
    <div class="row">
        <div class="leftcolumn">
            <div class="card2">
            <h1>Librarian View Borrow Book Page</h1>

<br></br>

<div class='listing' id='listing'>
    <?php
    ob_start();

        //creates the table for the books that can be borrowed and option to return
        /*$q = "select njm_transactions.user_id, njm_books.title, njm_books.author, njm_transactions.due_date, njm_transactions.book_id
            from njm_books inner join njm_transactions 
            on njm_books.book_id = njm_transactions.book_id 
            where status = 'Not Available'  
            group by njm_transactions.book_id;";*/
    
        $q = "select njm_transactions.user_id, njm_users.username, njm_transactions.book_id as book_id, 
                njm_books.title, njm_books.author, njm_transactions.due_date as due_date from njm_transactions 
                inner join 
                (select njm_transactions.book_id as book_id, max(njm_transactions.due_date) as due_date 
                from njm_transactions where njm_transactions.transaction_type = 'borrowed' group by book_id) maxTable 
                on njm_transactions.book_id = maxTable.book_id and njm_transactions.due_date = maxTable.due_date 
                inner join njm_users on njm_users.user_id = njm_transactions.user_id 
                inner join njm_books on njm_books.book_id = njm_transactions.book_id 
                where njm_books.status = 'Not Available';";

        $result = $connection->query($q);
        if ($result->num_rows > 0) {
            echo "
            
            <table id='result'>
            <tr>
                <th>Borrower Username</th>
                <th>Book Title</th>
                <th>Author</th>
                <th>Due Date</th>
                <th>Return</th>
            </tr> 
            <tr>
            <td><input type='text' id='idInput' onkeyup='filterFunction()' placeholder='Search for User ID..'></td>
            <td><input type='text' id='bookInput' onkeyup='filterFunction()' placeholder='Search for Title..'></td>
            <td><input type='text' id='authorInput' onkeyup='filterFunction()' placeholder='Search for Author..'></td>
            <td><input type='text' id='dueInput' onkeyup='filterFunction()' placeholder='Search for Due Date..'></td>
            <td> Action</td>
            </tr>
            ";
            echo "<tbody class='bookRows' id = 'bookRows'>";
            while ($row = $result->fetch_assoc()) {
                $book_id = $row['book_id'];
                $user_id = $row['username'];
                $title = $row['title'];
                $author = $row['author'];
                $due_date = $row['due_date'];
                echo "<form id='bookorder' name='form1' method='post'> <tr class='table'>
                    <td class='table' data-input='user_id'> <input type = 'hidden' name='user_id' value= '$user_id' > $user_id </td>
                    <td class='table' data-input='title'> <input type = 'hidden' name='title' value= '$title' > $title </td>
                    <td class='table' data-input='author'> <input type = 'hidden' name='author' value= '$author' > $author </td>
                    <td class='table' data-input='due_date'> <input type = 'hidden' name='due_date' value= '$due_date' > $due_date </td>
                    
                    <input type = 'hidden' name='book_id' value= '$book_id' >
                    <td> <button class='editbtn' type='submit' value='submit'> Check In </button></td>
                    </tr> </form>";
            }
            
            echo "</tbody>";
            

            //checks if form has been submitted and adds to the transaction table and changes the status in the books table
            if (isset($_POST['book_id'])){
                $userId = "select user_id from njm_transactions where due_date = '".$_POST['due_date']."' and book_id = '".$_POST['book_id']."';";

                //Session variable to get the user id of the username
                $idResult = $connection->query($userId);
                while ($row = $idResult->fetch_assoc()) {
                    $id = $row['user_id'];
                    echo "$id";
                } 
                
                $returnDate = date('Y-m-d H:i:s'); //gets the current date it was returned

                $q = "insert into njm_transactions (transaction_type, book_id, user_id, due_date) values 
                ('returned', '".$_POST['book_id']."', $id, '$returnDate');";  //need to change 14 to the user_id

                if (mysqli_query($connection, $q)) {
                    //shows alert box to indicate that a book has been checked in
                    echo "<script>
                            alert('Returned ".$_POST['title']." By ".$_POST['author']."'); 
                            window.location.href = window.location.search;
                        </script>";
                  } else {
                    echo "Error: " . $q . "<br>" . mysqli_error($connection);
                }

                $statusChange = "update njm_books set status = 'Available' where book_id = '".$_POST['book_id']."';";

                if (mysqli_query($connection, $statusChange)) {
                    echo "New record created successfully";
                  } else {
                    echo "Error: " . $statusChange . "<br>" . mysqli_error($connection);
                }

                //header("Location: ".$_SERVER['REQUEST_URI']); //reloads the page to update the changes in the table
               
            }

        }
        else {
            echo "None Borrowed";
        }
        echo "</table>";
        
    ?>
</div>

            </div>
        </div>

        <div class="rightcolumn">
        <div class="card">
            <h4><a href="main_login.php">Log in</a></h4>
            <h4><a href="#">Request Librarian Help</a></h4>
            <h4><a href="#">Feedback</a></h4>
            </div>
            <div class="card">
                <h3>Monthly Book Club Reads</h3>
                <div class="fakeimg"><img src="images/persuasion_ja.jpg"> </div><br>
                <div class="fakeimg"><img src="images/anxious_people.jpeg"> </div><br>
            </div>
        </div>
    </div>

    <div class="footer">
        <p style="color:white;  text-align: center; ">
            <br><br>
            Contact us @
            Email: ouremail.brynmawr.edu <br>
            Mobile: +1 610 526 5000
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













<html>

<body>
</body>

</html>
