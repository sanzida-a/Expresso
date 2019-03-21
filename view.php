<?php session_start();
include_once("connection.php");
$t = $_GET['track'];
if (!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in'])) {
    //header('Location:signin.php');
    $sql1 = "SELECT posts.id,posts.title,posts.author_id,posts.cat,
    posts.content,posts.tags,posts.date,posts.image,users.username as au
     FROM users,posts WHERE users.id=posts.author_id and posts.id=$t";

    ?>
<html>

<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <link href="view.css" rel="stylesheet" type="text/css">
</head>

<body>


    <div class="box">
        <ul>
            <li><a href="home.php">Home</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="signin.php">Sign In</a></li>
            <li><a href="signup.php">Sign Up</a></li>
        </ul>
        <?php

        $img = array();
        $s = $pdo->prepare($sql1);
        $s->execute();
        $r = $s->fetch(PDO::FETCH_ASSOC);
        $title = $r['title'];
        $author = $r['au'];
        $cat = $r['cat'];
        $content = $r['content'];
        $tag = $r['tags'];
        $img = $r['image'];
        $ptd = $r['date'];

        ?>
        <h1><?php echo $title ?></h1>

        <p>Written By : <?php echo $author ?> Created At : <?php echo $ptd ?></p>
        <p>Category : <?php echo $cat ?></p>
        <p><?php echo '<img class="img" src="data:image;base64,' . $img . '" width="800" height="400"/>'; ?></p>
        <p><?php echo $content ?></p>
        <p>Tag : <?php echo $tag ?></p>
    </div>

</body>

</html>

<?php 
} else {

    $sql1 = "SELECT posts.id,posts.title,posts.author_id,posts.cat,
    posts.content,posts.tags,posts.date,posts.image,users.username as au
     FROM users,posts WHERE users.id=posts.author_id and posts.id=$t";

    ?>
<html>

<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <link href="view.css" rel="stylesheet" type="text/css">
</head>

<body>


    <div class="box">
        <ul>
            <li><a href="viewus.php">Home</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="new_article.php">Create Article</a></li>
            <li><a href="myar.php">My Article</a></li>
            <li><a href="signout.php">Sign Out</a></li>
        </ul>
        <?php
        if (isset($_GET['success']) && $_GET['success'] == 2) {
                echo 'Article updated successfully.';
            }
        $img = array();
        $s = $pdo->prepare($sql1);
        $s->execute();
        $r = $s->fetch(PDO::FETCH_ASSOC);
        $title = $r['title'];
        $author = $r['au'];
        $cat = $r['cat'];
        $content = $r['content'];
        $tag = $r['tags'];
        $img = $r['image'];
        $ptd = $r['date'];


        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            if (empty($_POST["content"])) {
                $conErr = "Content is required";
            } else {
                $comment = $_POST['content'];
            }

            $pidm = $t;
            $user_id = $_SESSION['user_id'];
            $ctd = date("Y-m-d   H:i:sa");
            $sql2 = "INSERT INTO comments(post_id, content, author_id,time)
                                     VALUES('$pidm','$comment', '$user_id','$ctd')";
            $s = $pdo->prepare($sql2);
            $s->execute();
        }

        ?>
        <h1><?php echo $title ?></h1>

        <p>Written By : <?php echo $author ?> Created At : <?php echo $ptd ?></p>
        <p>Category : <?php echo $cat ?></p>
        <p><?php echo '<img class="img" src="data:image;base64,' . $img . '" width="800" height="400"/>'; ?></p>
        <p><?php echo $content ?></p>
        <p>Tag : <?php echo $tag ?></p>

        <p><span class="error"></span></p>
        <form method="post" action="">
            <input type="hidden" name="pidm" value="<?php $t ?>">
            <textarea placeholder="Leave a comment..." name="content" cols="40" rows="3"></textarea>
            <div class="group">
                <button type="submit" name="submit" value="Submit">Comment</button>
            </div>

    </div>
    <div class="box2">
        <?php
        $vcom = "SELECT comments.post_id,comments.content,comments.time,comments.author_id,
                             users.username as un from users,comments WHERE users.id=comments.author_id 
                              and comments.post_id=$t ORDER BY comments.id DESC";
        $st = $pdo->prepare($vcom);
        $st->execute();
        $row = $st->fetchAll(PDO::FETCH_ASSOC);
        foreach ($row as $r) {
            $name = $r['un'];
            $comment = $r['content'];
            $ctd = $r['time'];
            ?>
        <h1>Comments</h1>
        <p><?php echo $name ?> : <?php echo $comment ?></p>
        <p>Created At : <?php echo $ctd ?></p>

        </p>


        </ div>
        <?php 
    } ?>
    </div>
    <?php  ?>
</body>

</html>
<?php 
} ?> 