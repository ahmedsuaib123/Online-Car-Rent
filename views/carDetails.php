<?php
session_start();

require_once("../config/db.php");
require_once("../models/carModel.php");

$conn = getConnection();

//  ADMIN CHECK 
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("location: login.php");
    exit();
}

//  ID CHECK 
if (!isset($_GET['id'])) {
    header("location: carList.php");
    exit();
}

$id = $_GET['id'];

//  GET CAR DATA 
$car = getCarById($id);

if (!$car) {
    header("location: carList.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Car Details</title>

    <style>
        body{
            font-family: Arial;
            background:#f2f2f2;
        }

        .box{
            width:50%;
            margin:auto;
            margin-top:50px;
            background:white;
            padding:20px;
            border-radius:10px;
            box-shadow:0px 0px 5px white;
            text-align:center;
        }

        img{
            width:200px;
            height:auto;
            border:1px solid white;
            padding:5px;
        }

        h2{
            margin-bottom:10px;
        }

        .info{
            text-align:left;
            margin-top:20px;
        }

        .info p{
            padding:5px 0;
            border-bottom:1px solid white;
        }

        a{
            display:inline-block;
            
            margin-top:20px;
            text-decoration:none;
            background:#333;
            color:white;
            padding:8px 15px;
            border-radius:5px;
        }
    </style>

</head>
<body>

<div class="box">

    <h2><?= $car['name'] ?></h2>

    <?php if (!empty($car['image_path'])) { ?>
        <img src="<?= $car['image_path'] ?>" alt="Car Image">
    <?php } ?>


    <!-- <?= htmlspecialchars($car['name']) ?> -->

    <div class="info">
        <p><b>Model:</b> <?= htmlspecialchars($car['model']) ?></p>
        <p><b>Type:</b> <?= htmlspecialchars($car['type']) ?></p>
        <p><b>Price/Day:</b> <?= htmlspecialchars($car['price_per_day']) ?></p>
        <p><b>Status:</b> <?= htmlspecialchars($car['availability_status']) ?></p>
        <p><b>Description:</b> <?= htmlspecialchars($car['description']) ?></p>
    </div>

    <a href="carList.php">Back</a>

</div>

</body>
</html>