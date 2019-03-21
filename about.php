<?php
include_once("connection.php");
session_start();
?>
<html>

<head>
    <title>Add Article</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <link href="about.css" rel="stylesheet" type="text/css">
</head>

<body>

    <?php if (!isset($_SESSION['logged_in'])) { ?>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="signin.php">Sign In</a></li>
        <li><a href="signup.php">Sign Up</a></li>
    </ul>
    <?php 
} else { ?>
    <ul>
        <li><a href="viewus.php">Home</a></li>
        <li><a href="about.php">About</a></li>
        <li> <a href="myar.php">My Articles</a></li>
        <li><a href='new_article.php'>Create Article</a></li>
        <li><a href="signout.php">Sign Out</a></li>
    </ul>
    <?php 
} ?>

    <h1>About Me and This Website</h1>
    <p>Hello! My name is Sanzida Akter.</p>
    <p>This website is created as a project. It is for those who like</br>
        to write creative content. It is a basic content management </br>
        system with with user accounts. You can create a new content</br>
        or join here to read others and share you opinion by comments.</p>
    </br>
    <h2>&nbsp; Contact me :</h2>
    <h3>&nbsp; brishti.sanzida@gmail.com</h3>


</body>

</html> 