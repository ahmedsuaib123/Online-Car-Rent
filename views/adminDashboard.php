<?php
session_start();

require_once("../config/db.php");

$conn = getConnection();

/* ADMIN CHECK */
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("location: login.php");
    exit();
}

// COUNTS 

$carCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM cars"))['total'];

$userCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM users WHERE role='member'"))['total'];

$orderCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders"))['total'];


$blogCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM blogs"))['total'];

?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>

    <style>
        body{
            font-family: Arial;

            background:#f2f2f2;

            margin:0;

            padding:0;
        }

        .container{

            width:90%;
            margin:auto;
            margin-top:30px;
        }

        h1{
            text-align:center;
        }

        .cards{
            display:flex;
            justify-content:space-between;
            flex-wrap:wrap;
        }

        .card{
            width:20%;
            background:white;
            padding:20px;
            margin-top:20px;
            text-align:center;
            border-radius:8px;
            box-shadow:0px 0px 5px #ccc;
        }

        .card h2{
            margin:0;
            font-size:30px;
        }

        .menu{
            margin-top:40px;
            text-align:center;
        }

        .menu a{
            text-decoration:none;
            background:#333;
            color:white;
            padding:10px 15px;
            margin:5px;
            display:inline-block;
            border-radius:5px;
        }

        .menu a:hover{
            background:#555;
        }
    </style>

</head>
<body>

<div class="container">

    <h1>Admin Dashboard</h1>

    <!-- SUMMARY CARDS -->
    <div class="cards">

    <!-- <?= htmlspecialchars($car['name']) ?> -->

    <!-- <h2><?= htmlspecialchars($carCount) ?></h2> -->


        <div class="card">
            <h2><?= htmlspecialchars($carCount) ?></h2>
            <p>Total Cars</p>
        </div>

        <div class="card">
            <h2><?= $userCount ?></h2>
            <p>Total Members</p>
        </div>

        <div class="card">
            <h2><?= htmlspecialchars($orderCount) ?></h2>
            <p>Total Orders</p>
        </div>

        <div class="card">
    <h2><?= htmlspecialchars($blogCount) ?></h2>
    <p>Total Blog Posts</p>
</div>

    </div>

    <!-- NAVIGATION MENU -->
    <div class="menu">

        <a href="carList.php">Car Management</a>

        <a href="addCar.php">Add Car</a>

        <a href="memberList.php">Member List</a>

        <a href="orderHistory.php">Order History</a>

        <a href="payment.php">Car Search</a>

        <a href="carDetails.php">Car Details</a>
        
        <a href="../controllers/logout.php" style="background:red;">Logout</a>

    </div>

</div>

</body>
</html>