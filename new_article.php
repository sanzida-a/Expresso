<?php
include_once("connection.php");
session_start();
?>
<?php
if (!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in'])) {
    header('Location:signin.php');
} else {
    $sid = $_SESSION['user_id'];
    ?>

<html>

<head>
    <title>Add Article</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <link href="article.css" rel="stylesheet" type="text/css">
</head>

<body>
    <?php
    $title = $tags = $content = $cat = $ptd = "";
    $titleErr = $tagErr = $conErr = $catErr = "";
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
        if (empty($_FILES["image"])) { } else {
            $image = base64_encode(file_get_contents(addslashes($_FILES['image']['tmp_name'])));
        }
        $ptd = date("Y-m-d   H:i:sa");



        $sql = "INSERT INTO posts (title,author_id, cat,tags, content, image, date)
                VALUES (:title, :sid, :cat, :tags, :content, :image, :ptd) ";
        $s = $pdo->prepare($sql);
        $s->bindValue(':title', $title);
        $s->bindValue(':sid', $sid);
        $s->bindValue(':cat', $cat);
        $s->bindValue(':tags', $tags);
        $s->bindValue(':image', $image);
        $s->bindValue(':ptd', $ptd);
        $s->bindValue(':content', $content);
        $s->execute();
        echo "<font color='white'>Article updated successfully.";
    }
    ?>
    <ul>
        <ul>
            <li><a href="viewus.php">Home</a></li>
            <li> <a href="myar.php">My Articles</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="signout.php">Sign Out</a></li>


        </ul>
    </ul>
    <div class="box">
        <h1>Create New Article</h1>
        <p><span class="error"></span></p>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
            <div class="group">
                <label for="title" class="label">Title: </label>
                <input type="text" name="title" value="<?php echo $title ?>"><br><br>
                <span class="error"> <?php echo $titleErr; ?></span><br><br>
            </div>
            <div class="group">
                <label for="cat" class="label">Category: </label>
                <input type="text" name="cat" value="<?php echo $cat ?>"><br><br>
                <span class="error"> <?php echo $catErr; ?></span><br><br>
            </div>

            <div class="group">
                <label for="content" class="label">Content: </label>
                <textarea placeholder="Content" name="content" cols="70" rows="30"></textarea>
                <span class="error"> <?php echo $conErr; ?></span><br><br>
            </div>
            <div class="group">
                <label for="tags" class="label">Tags: </label>
                <input type="text" name="tags" value="<?php echo $tags ?>">
                <span class="error"> <?php echo $tagErr; ?></span><br><br>
            </div>
            <div class="group">
                <label for="image" class="label">Image: </label>
                <input type="file" name="image" value="$image">

            </div>
            <div class="group">
                <button type="submit" name="submit" value="Submit">Post</button>
            </div>
        </form>
    </div>


</body>

</html>
<?php 
} ?> 