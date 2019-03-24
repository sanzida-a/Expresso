<?php
session_start();
include_once("connection.php");

$sql = "SELECT COUNT(content) AS num FROM posts";
$s = $pdo->prepare($sql);
$s->execute();
$row = $s->fetch(PDO::FETCH_ASSOC);
$perpage = 6;

$pages = ceil($row['num'] / $perpage);
if (isset($_GET['p']) && is_numeric($_GET['p'])) {
    $page = $_GET['p'];
} else {
    $page = 1;
}
if ($page <= 0)
    $start = 0;
else
    $start = $page * $perpage - $perpage;
$prev = $page - 1;
$next = $page + 1;
?>
<?php
if (isset($_SESSION['valid'])) {
    header('Location: viewus.php');
} else {
    ?>
<html>

<head>
    <title>Home Page</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <link href="Homepage.css" rel="stylesheet" type="text/css">
</head>

<body>
    <div class="container">

        <div class="box">
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="signin.php">Sign In</a></li>
                <li><a href="signup.php">Sign Up</a></li>

            </ul>
            <header>
                <img src="icon.jpg" style="width:100%" style="height:100%" />

            </header>

            <form class="form-wrapper" action="search.php" method="get">
                <input type="text" name="value" id="search" placeholder="Search for  ..." required>
                <input type="submit" name="search" id="submit" value="Search">
            </form>

            <h1>Latest Articles</h1>
            <?php
            $run = "SELECT posts.id,posts.title,posts.cat,posts.content,posts.tags,posts.date,users.username as au
            FROM posts,users where users.id=posts.author_id ORDER BY posts.id DESC limit $start,$perpage";
            $s = $pdo->prepare($run);
            $s->execute();
            $row = $s->fetchAll(PDO::FETCH_ASSOC);
            foreach ($row as $r) {
                $id = $r['id'];
                $title = $r['title'];
                $author=$r['au'];
                $cat = $r['cat'];
                $content = $r['content'];
                $ptd = $r['date'];
            ?>
            
            <div class="article">

                <h2><?php echo $title ?></h2>
                <p>Created By: <?php echo $author ?> &nbsp;&nbsp;&nbsp;&nbsp; Created At : <?php echo $ptd ?></p>
                <p><?php
                    echo substr($content, 0, 100) . "<a href='view.php?track=$id'>&nbsp;&nbsp;Read more..</a>" . "<p>" ?></p>
                <p>Category : <?php echo $cat ?></p>

            </div>
            <?php 
        } ?>

            <?php
            if ($prev > 0)
                echo "<a href='home.php?p=$prev'>Previous</a>";
            if ($page < $pages)
                echo "<a href='home.php?p=$next'>Next</a>";
            ?>
            <footer></footer>
        </div>
    </div>
</body>

</html>
<?php 
} ?> 