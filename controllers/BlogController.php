<?php
session_start();

include("../config/db.php");
include("../models/BlogModel.php");

$conn = getConnection();

$blogModel = new BlogModel($conn);


// CREATE BLOG

if(isset($_POST['submit'])){

    if(!isset($_SESSION['user_id'])){
        die("Login First");
    }

    $title = htmlspecialchars(trim($_POST['title']));
    $content = htmlspecialchars(trim($_POST['content']));

    // VALIDATION

    if($title == "" || $content == ""){
        die("All fields required");
    }

    $user_id = $_SESSION['user_id'];

    $blogModel->insertBlog($user_id, $title, $content);

    exit();
}



// DELETE BLOG

if(isset($_GET['delete'])){

    if(!isset($_SESSION['user_id'])){
        die("Login First");
    }

    $id = $_GET['delete'];

    $blog = $blogModel->getSingleBlog($id);

    // ADMIN CAN DELETE ALL
    // MEMBER CAN DELETE OWN

    if($_SESSION['role'] == "admin" ||
       $_SESSION['user_id'] == $blog['user_id']){

        $blogModel->deleteBlog($id);
    }

    exit();
}

?>