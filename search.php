<?php
session_start();
include_once("connection.php");
?>
<?php
$sql = "SELECT count(id) as num FROM posts";
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
<html>

<head>
    <title>Home Page</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <link href="Homepage.css" rel="stylesheet" type="text/css">
</head>

<body>
    <div class="container">

        <div class="box">
            <?php
            $sea = $_GET['value'];
            $run = "SELECT posts.id,posts.date,posts.title,posts.content,posts.cat,posts.tags,
                users.username as au FROM users,posts WHERE (posts.title like '%$sea%' or posts.cat 
                like '%$sea%' or posts.tags like '%$sea%' or users.username like '%$sea%') and  
                users.id=posts.author_id ORDER BY posts.id DESC";


            if (isset($_GET['search'])) {
                $s = $pdo->query($run);
                // echo '<pre>';
                // print_r($s);
                // exit();
                $s->execute();
                $row = $s->fetchAll();

                if (!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in'])) {
                    ?>
            <ul>

                <li><a href="home.php">Home</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="signin.php">Sign In</a></li>
                <li><a href="signup.php">Sign Up</a></li>

            </ul>
            <?php

        } else {
            ?>
            <ul>
                <li>
                    <h3>Welcome <?php echo $_SESSION['username'] ?><h3>
                </li>
                <li><a href="viewus.php">Home</a></li>
                <li><a href="signout.php">Sign Out</a></li>
                <li><a href="about.php">About</a></li>
                <li> <a href="myar.php">My Articles</a></li>
                <li><a href='new_article.php'>Create Article</a></li>
            </ul>
            <?php 
        } ?>

            <header><img src="icon.jpg" style="width:100%" style="height:100%" /></header>
            <form class="form-wrapper" action="" method="get">
                <input type="text" name="value" id="search" placeholder="Search for  ..." required>
                <input type="submit" name="search" id="submit" value="Search">
            </form>

            <h1>Search Result</h1>>
            <?php
            foreach ($row as $r) {
                $id = $r['id'];
                $title = $r['title'];
                $author = $r['au'];
                $cat = $r['cat'];
                $content = $r['content'];
                $tags = $r['tags'];
                $ptd = $r['date'];
                ?>
            <div class="article">

                <h2><?php echo $title ?></h2>
                <p>Created At : <?php echo $ptd ?></p>
                <p><?php echo substr($content, 0, 100) . "<a href='view.php?track=$id'> Read more..</a>" ?></p>
                <p>Tag : <?php echo $tags ?></p>
            </div><?php

                }
            }
            if ($prev > 0)
                echo "<a href='search.php?p=$prev'>Previous</a>&nbsp;&nbsp;";
            if ($page < $pages)
                echo "&nbsp;&nbsp;<a href='search.php?p=$next'>Next</a>";
            ?>
            <footer></footer>
        </div>
    </div>
</body>

</html> 