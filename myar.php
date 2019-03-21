<?php
session_start();
include("connection.php");
?>
<?php
$rc = "SELECT * FROM post";
$perpage = 5;
$pages = ceil($rc->num_rows / $perpage);
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
if (!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in'])) {
    header('Location:signin.php');
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
                <li>
                    <h3>Welcome <?php echo $_SESSION['username'] ?><h3>
                </li>
                <li><a href="viewus.php">Home</a></li>

                <li><a href='new_article.php'>Create Article</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="signout.php">Sign Out</a></li>

            </ul>
            <header>
                <img src="icon.jpg" style="width:100%" style="height:100%" />
            </header>
            <form class="form-wrapper" action="search.php" method="get">
                <input type="text" name="value" id="search" placeholder="Search for  ..." required>
                <input type="submit" name="search" id="submit" value="Search">
            </form>
            <h1>My Articles</h1>

            <?php
            $sql = "SELECT *,users.username as au FROM posts,users where author_id=" . $_SESSION['user_id'] . " 
            ORDER BY posts.id DESC limit $start,$perpage";
            $s = $pdo->prepare($sql);
            $s->execute();
            $row = $s->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <?php
            foreach ($row as $r) {
                $id = $r['id'];
                $title = $r['title'];
                $author = $r['au'];
                $content = $r['content'];
                $ptd = $r['date'];
                $tag = $r['tags'];
                $cat = $r['cat'];
                ?>

            <div class="article">
                <h2><?php echo $title ?></h2>
                <p>Created By: <?php echo $author ?> &nbsp;&nbsp;&nbsp;&nbsp; Created At : <?php echo $ptd ?></p>

                <p><?php
                    echo substr($content, 0, 100) . "<a href='view.php?track=$id'>&nbsp;&nbsp; Read more..</a>" . "<p>"
                        . "<a href='edit.php?track=$id'>  Edit Article  </a>&nbsp;&nbsp;" . "&nbsp;&nbsp;<a href='delete.php?id=$id'> Delete Article  </a></p>"
                    ?></p>
                <p>Category : <?php echo $cat ?></p>
                <p>Tag : <?php echo $tag ?></p>

            </div>
            <?php 
        } ?>


            <?php
            if ($prev > 0)
                echo "<a href='viewus.php?p=$prev'>Previous</a>&nbsp;&nbsp;";
            if ($page < $pages)
                echo "&nbsp;&nbsp;<a href='viewus.php?p=$next'>Next</a>";
            ?>
            <footer></footer>
        </div>
    </div>
</body>

</html>
<?php 
} ?> 