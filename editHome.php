
<?php
    include 'db.php';
?>

<html>
<head>
    <link rel="stylesheet" type = "text/css" href="styleStudent.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>

        $(document).ready(function(){
            
            //Toggles the div with borrowed books
            $('.viewBooks').click(function(event){
                event.stopPropagation();
                $(".showup").slideToggle("fast");
                $('.showup').load('formSubmit.php', function() {console.log('Load was performed.');});
                
            });
            $(".showup").on("viewBooks", function (event) {
                event.stopPropagation();
                
            });
            
            //click Event for the borrow button (not really noing much now)
            $('.editbtn').click(function(){
                $(this).html('Loaned');
                console.log("HERE")
            });
            
        });

        $(document).on("viewBooks", function () {
            $(".showup").hide();
        });         
       
    </script>
    
</head> 

<body onLoad='init()'>
    <h1>Student Home Page</h1>

    <div class="viewBooks">
        <button>View Borrowed Books</button>
    </div>

    <div class="showup" id = "showup">
        
    </div>

    <br></br>
    <div id = "listing">
        
        <?php
            //creates the table for the books that can be borrowed
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
                <td><input type='text' id='myInput' onkeyup='myFunction()' placeholder='Search for Title..'></td>
                <td><input type='text' id='myInput2' onkeyup='myFunction()' placeholder='Search for Author..'></td>
                <td><input type='text' id='genreInput' onkeyup='myFunction()' placeholder='Search for Genre..'></td>
                <td><input type='text' id='yearInput' onkeyup='myFunction()' placeholder='Search for Year..'></td>
                
                </tr>
                ";
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
                        <td> <button class='editbtn' type='submit' value='Submit'>Borrow</button> </td>
                        </tr> </form>";
                }
                echo "</table> ";

                //checks if form has been submitted and adds to the transaction table and changes the status in the books table
                if (isset($_POST['title']) && isset($_POST['author'])){
                    $q = "insert into njm_transactions (transaction_type, book_id, user_id, due_date) values 
                    ('borrowed', '".$_POST['book_id']."', 2, '2020-11-11');";
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
                   
                }

            }
            else {
                echo "Did not Work!";
            }
        ?>

    </div>

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
    </script>
    
</body>

</html>