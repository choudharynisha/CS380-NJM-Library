<?php
    include 'db.php';
?>

<html>
<head>
    <link rel="stylesheet" type = "text/css" href="bookTableStyle.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>

        //function deals with inputs to filter by 
        function myFunction() {
            var input, input2, filter,filter2, table, tr, td, i, txtValue;
            input = document.getElementById("myInput");  //book input
            input2 = document.getElementById("myInput2"); //author input
            genreInput = document.getElementById("genreInput");
            yearInput = document.getElementById("yearInput");
            filter = input.value.toUpperCase();  //gets book input value
            filter2 = input2.value.toUpperCase(); //gets author input value
            genreFilter = genreInput.value.toUpperCase();
            yearFilter = yearInput.value.toUpperCase();
            table = document.getElementById("result");
            tr = table.getElementsByTagName("tr");
            for (i = 2; i < tr.length; i++) {
                //checking the columns based on inputs
                td = tr[i].getElementsByTagName("td")[0];
                td2 = tr[i].getElementsByTagName("td")[1];
                genreTd = tr[i].getElementsByTagName("td")[2];
                yearTd = tr[i].getElementsByTagName("td")[3];
                if (td && td2 && genreTd && genreTd) {
                txtValue = td.textContent || td.innerText;
                txtValue2 = td2.textContent || td2.innerText;
                txtValueGenre = genreTd.textContent || genreTd.innerText;
                txtValueYear = yearTd.textContent || yearTd.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1 
                    && txtValue2.toUpperCase().indexOf(filter2) > -1 
                    && txtValueGenre.toUpperCase().indexOf(genreFilter) > -1 
                    && txtValueYear.toUpperCase().indexOf(yearFilter) > -1) 
                {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
                }       
            }

        }

        $(document).ready(function(){
            
            //Toggles the div with borrowed books
            $('.viewBooks').click(function(event){
                event.stopPropagation();
                $(".showup").slideToggle("fast");
                $('.showup').load('loadBorrowBooksDiv.php', function() {console.log('Load was performed.');});
                //$('.listing').load('loadBookTable.php', function() {console.log('Load was performed for Table.');});
                //$('.listing').load(location.href + ' .listing');
                console.log("First")

            });

            $('.viewBtn').click(function(event){
                var button = $(this);
                button.text(button.text() == "Hide Borrowed" ? "Show Borrowed" : "Hide Borrowed")
            });

            /*$(".showup").on("viewBooks", function (event) {
                event.stopPropagation();
                console.log("Second")
                
            });*/
            
            //click Event for the borrow button (not really noing much now)
            $('.editbtn').click(function(event){
                $(this).html('Loaned');
                console.log("HERE")
            });
            
        });

        /*$(document).on("viewBooks", function () {
            $(".showup").hide();
            console.log("Third")
        });*/         
       
    </script>
    
</head> 

<body>
    <h1>Librarian View Borrow Book Page</h1>

    <br></br>
    
    <div class='listing' id = 'listing'>
        <?php
        ob_start();
            //creates the table for the books that can be borrowed
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
                <td><input type='text' id='myInput' onkeyup='myFunction()' placeholder='Search for User ID..'></td>
                <td><input type='text' id='myInput2' onkeyup='myFunction()' placeholder='Search for Title..'></td>
                <td><input type='text' id='genreInput' onkeyup='myFunction()' placeholder='Search for Author..'></td>
                <td><input type='text' id='yearInput' onkeyup='myFunction()' placeholder='Search for Due Date..'></td>
                
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
                    <td class='table' data-input='year'> <input type = 'hidden' name='user_id' value= '$user_id' > $user_id </td>
                        <td class='table' data-input='title'> <input type = 'hidden' name='title' value= '$title' > $title </td>
                        <td class='table' data-input='author'> <input type = 'hidden' name='author' value= '$author' > $author </td>
                        <td class='table' data-input='genre'> <input type = 'hidden' name='due_date' value= '$due_date' > $due_date </td>
                        
                        <input type = 'hidden' name='book_id' value= '$book_id' >
                        <td> <button class='editbtn' type='submit' value='submit'> Return </button></td>
                        </tr> </form>";
                }
                
                echo "</tbody>";
                

                //checks if form has been submitted and adds to the transaction table and changes the status in the books table
                if (isset($_POST['book_id'])){
                    $returnDate = date('Y-m-d H:i:s');
                    $q = "insert into njm_transactions (transaction_type, book_id, user_id, due_date) values 
                    ('returned', '".$_POST['book_id']."', 14, '$returnDate');";  //need to change to user_id and the actual date its due
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
                    header("Location: ".$_SERVER['REQUEST_URI']);
                   
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