<?php
    include 'db.php';
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="style2.css">
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
        <a class="active" href="javascript:void(0)">Home</a>
        <a href="javascript:void(0)">News</a>
        <a href="javascript:void(0)">Contact</a>
      
        <div class="logo">
          <!--<img src="lanternz.gif">-->
          <h1 style="color: yellow; font-size: 25px;text-align: center;">NJM Online Library</h1>
      </div>
  </div>


  </div>
<div class="row">
  <div class="leftcolumn">
      <div class="card2">
          <h2 style="text-align: center;">Borrowed Books</h2>
          <h3>About Us</h3>
            <div class="fakeimg">
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
            </div>
        </div>
    </div>
    <div class="rightcolumn">
      <div class="card">
          <h4><a href="#">Log in</a></h4>
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



