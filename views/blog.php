<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("location: login.php");
    exit();
}

include("../config/db.php");
include("../models/BlogModel.php");

$conn = getConnection();

$blogModel = new BlogModel($conn);

$blogs = $blogModel->getAllBlogs();

?>

<!DOCTYPE html>
<html>

<head>
    <title>Blog Page</title>
    <style>
        /* ===== RESET & BASE ===== */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f5f7fa;
            color: #333;
            line-height: 1.6;
            padding: 20px;
        }

        /* ===== CONTAINER ===== */
        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        /* ===== HEADER ===== */
        h1 {
            text-align: center;
            color: #2c3e50;
            margin: 30px 0;
            font-size: 28px;
            padding-bottom: 15px;
            border-bottom: 3px solid #3498db;
        }

        /* ===== FORM STYLING ===== */
        #blogForm {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            margin-bottom: 30px;
        }

        #blogForm input,
        #blogForm textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 15px;
            transition: border-color 0.2s;
            margin-bottom: 15px;
        }

        #blogForm input:focus,
        #blogForm textarea:focus {
            outline: none;
            border-color: #3498db;
        }

        #blogForm textarea {
            height: 120px;
            resize: vertical;
        }

        #blogForm button {
            background: #3498db;
            color: white;
            border: none;
            padding: 12px 30px;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.2s;
        }

        #blogForm button:hover {
            background: #2980b9;
        }

        /* ===== DIVIDER ===== */
        hr {
            border: none;
            height: 2px;
            background: #e0e0e0;
            margin: 30px 0;
        }

        /* ===== BLOG CARDS ===== */
        .blog-box {
            background: white;
            padding: 25px;
            margin-bottom: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            border-left: 4px solid #3498db;
        }

        .blog-box h2 {
            color: #2c3e50;
            font-size: 22px;
            margin-bottom: 10px;
        }

        .blog-box p {
            margin: 8px 0;
            color: #555;
        }

        .blog-box p b {
            color: #3498db;
        }

        .blog-box > p:last-of-type {
            font-size: 13px;
            color: #999;
            margin-top: 15px;
            font-style: italic;
        }

        /* ===== DELETE BUTTON ===== */
        .blog-box button {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            margin-top: 10px;
            transition: background 0.2s;
        }

        .blog-box button:hover {
            background: #c0392b;
        }

        /* ===== EMPTY STATE (optional) ===== */
        #blogArea:empty::after {
            content: "No blogs yet. Be the first to post! ✍️";
            display: block;
            text-align: center;
            color: #999;
            padding: 40px;
            background: white;
            border-radius: 12px;
        }

        /* ===== SIMPLE RESPONSIVE ===== */
        @media (max-width: 768px) {
            .container {
                width: 100%;
            }
            
            #blogForm,
            .blog-box {
                padding: 20px;
            }
            
            h1 {
                font-size: 24px;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <h1> Post Experience </h1>

    <!-- BLOG FORM -->
    <form id="blogForm">
        <input type="text"
               name="title"
               id="title"
               placeholder="Enter Blog Title">

        <textarea name="content"
                  id="content"
                  placeholder="Write Your Experience"></textarea>

        <button type="submit">Post Blog</button>
    </form>

    <hr>

    <!-- BLOG LIST -->
    <div id="blogArea">

    <?php while($blog = mysqli_fetch_assoc($blogs)){ ?>

    <div class="blog-box">
        <h2><?php echo htmlspecialchars($blog['title']); ?></h2>
        
        <p><b>Author:</b> <?php echo htmlspecialchars($blog['name']); ?></p>
        
        <p><?php echo nl2br(htmlspecialchars($blog['content'])); ?></p>
        
        <p><?php echo date('M d, Y', strtotime($blog['created_at'])); ?></p>

        <?php if($_SESSION['role'] == "admin" || $_SESSION['user_id'] == $blog['user_id']){ ?>
            <button onclick="deleteBlog(<?php echo $blog['id']; ?>)">Delete</button>
        <?php } ?>
    </div>

    <?php } ?>

    </div>

</div>

<script>
// AJAX BLOG POST
document.getElementById("blogForm").addEventListener("submit", function(e){
    e.preventDefault();

    let title = document.getElementById("title").value;
    let content = document.getElementById("content").value;

    if(title.trim() == ""){
        alert("Title Required");
        return;
    }
    if(content.trim() == ""){
        alert("Content Required");
        return;
    }

    let formData = new FormData();
    formData.append("submit", true);
    formData.append("title", title);
    formData.append("content", content);

    fetch("../controllers/BlogController.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        location.reload();
    });
});

// DELETE BLOG
function deleteBlog(id){
    if(confirm("Delete this blog?")){
        fetch("../controllers/BlogController.php?delete=" + id)
        .then(response => response.text())
        .then(data => {
            location.reload();
        });
    }
}
</script>

</body>
</html>