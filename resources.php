<?php
    include 'db.php';
    session_start();
    $expiry = 5400; //90 min is 5400 sec
        if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time'] > $expiry)) {
            session_unset();
            session_destroy();
            echo "<script>
                alert('Please login again. Your session expired'); 
                window.location.href = 'main_login.php';
                </script>";
        }
?>
<!DOCTYPE html>
<html>
<head>
	<title>
		NMJ Online Library Management System
	</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<style type="text/css">
	nav
	{
		float: right;
		word-spacing: 30px;
	    padding: 20px;
	}
	nav li 
	{
		display: inline-block;
		line-height: 80px;
	}
</style>
</head>


<body>
	<div id="container">
		<header>
		<div class="logo">
            <img src="images/lanternz.gif" style="border-radius: 45%;">
            <h1 style="color: black; font-size: 25px;text-align: center;">NMJ Online Library</h1>
            
			
		</div>
			<nav>
				<ul>
					<li><a href="index.html">HOME</a></li>
					<li><a href="resources.html">BOOKS</a></li>
					<li><a href="login.html">LOGIN</a></li>
					<a href="logout.php">Log Out</a>
                    <li><a href="feedback.html">FAQ</a></li>
                    <form class="search" action="/resources.php" style="margin:auto;">
                        <input type="text" placeholder="Search..." name="search2">
                        <button type="submit"><i class="fa fa-search"></i></button>
                      </form>
				</ul>
			</nav>
		</header>

		<section>
			<div class="sec_back">
				<br><br><br>
				
			</div>
			</section>
			<footer>
				<p style="color:white;  text-align: center; ">
					<br><br>
					Contact us @
					Email: ouremail.brynmawr.edu <br>
					Mobile: +1 610 526 5000
				</p>
			</footer>
		
		
	</div>
</body>

</html>
