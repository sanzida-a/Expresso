<?php
session_start();
include_once("connection.php");
if (!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in'])) {
    header('Location:signin.php?success=0');
} else {
    $sql1 = "SELECT * FROM posts WHERE posts.id=" . $_GET['track'] . " AND author_id=" . $_SESSION['user_id'];
    $s = $pdo->prepare($sql1);
    $s->execute();
    $r = $s->fetch(PDO::FETCH_ASSOC);
    //$img=array();

    $t = $r['title'];
    $c = $r['cat'];
    $con = $r['content'];
    $tag = $r['tags'];
    $img = $r['image'];

    ?>

<html>

<head>
    <title>Update Article</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <link href="article.css" rel="stylesheet" type="text/css">
</head>

<body>
    <?php
    $title = $tags = $content = $cat = $ptd = "";
    $titleErr = $tagErr = $conErr = $catErr = $imgErr = "";

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if (empty($_POST["title"])) {
            $titleErr = "Title is required";
        } else {
            $title = $_POST['title'];
        }
        if (empty($_POST["cat"])) {
            $catErr = "Category is required";
        } else {
            $cat = $_POST['cat'];
        }
        if (empty($_POST["tags"])) {
            $tagErr = "Tags is required";
        } else {
            $tags = $_POST['tags'];
        }
        if (empty($_POST["content"])) {
            $conErr = "Content is required";
        } else {
            $content = $_POST['content'];
        }

        $im = $_FILES['im']['tmp_name'];
        if ($im != "") {
            $im = base64_encode(file_get_contents(addslashes($_FILES['im']['tmp_name'])));
        } else {
            $im = $img;
        }

        $ptd = date("Y-m-d   H:i:sa");
        $sql = "UPDATE posts SET title='$title', tags='$tags', content='$content',cat='$cat',image='$im',
                date='$ptd' WHERE  author_id=" . $_SESSION['user_id'] . " AND id= " . $_GET['track'];
        $s = $pdo->prepare($sql);
        $s->bindValue(':title', $title);
        $s->bindValue(':cat', $cat);
        $s->bindValue(':tags', $tags);
        $s->bindValue(':im', $im);
        $s->bindValue(':ptd', $ptd);
        $s->bindValue(':content', $content);
        $s->execute();
        $r = $s->fetch(PDO::FETCH_ASSOC);
        $id = $_GET['track'];
        echo "<font color='white'>Article updated successfully.";
        header("location:view.php?track=$id&success=2");
    } ?>

    <ul>
        <li><a href="viewus.php">Home</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="new_article.php">Create Article</a></li>
        <li><a href="signout.php">Sign Out</a></li>
    </ul>
    <div class="box">
        <h1>Update Article</h1>
        <p><span class="error"></span></p>
        <form method="post" action="" enctype="multipart/form-data">
            <div class="group">
                <label for="title" class="label">Title: </label>
                <input type="text" name="title" value="<?php echo $t; ?>"><br><br>
                <span class="error"> <?php echo $titleErr; ?></span><br><br>
            </div>
            <div class="group">
                <label for="cat" class="label">Category: </label>
                <input type="text" name="cat" value="<?php echo $c; ?>"><br><br>
                <span class="error"> <?php echo $catErr; ?></span><br><br>
            </div>

            <div class="group">
                <label for="content" class="label">Content: </label>
                <textarea placeholder="Content" name="content" cols="70" rows="30">
                        <?php echo $con; ?></textarea>
                <span class="error"> <?php echo $conErr; ?></span><br><br>
            </div>

            <div class="group">
                <label for="tags" class="label">Tags: </label>
                <input type="text" name="tags" value="<?php echo $tag; ?>">
                <span class="error"> <?php echo $tagErr; ?></span><br><br>
            </div>

            <div class="group">
                <label for="image" class="label">Image: </label>
                <input type="file" name="im" value="<?php $img ?>"><br><br>
            </div>

            <div class="group">
                <button type="submit" name="submit" value="Submit">Update</button>
            </div>
        </form>
    </div>

</body>

</html>
<?php 
} ?> 