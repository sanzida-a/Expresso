<?php

session_start();
include("connection.php");
if(!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in'])){
    header('Location:home.php');

} else {
    $gid = $_GET['id'];
    $sql = "DELETE FROM posts WHERE id=$gid and author_id=" . $_SESSION['user_id'] . "";
    $s = $pdo->prepare($sql);
    $s->execute();
    header('location: viewus.php?success=1');
}
?>