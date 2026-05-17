<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("location: login.php");
    exit();
}



require_once("../config/db.php");
require_once("../models/carModel.php");

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

 $cars = getAllCars();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Car List</title>

    <style>
        body {
            font-family: Arial;
            background: #f2f2f2;
            margin: 0;
            padding: 20px;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
        }

        fieldset {
            width: 900px;
            background: white;
            padding: 20px;
            border: 2px solid black;
            border-radius: 10px;
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            text-align: center;
        }

        th {
            background: #ddd;
        }

        a {
            text-decoration: none;
            color: blue;
        }

        img {
            border-radius: 5px;
        }
    </style>

</head>
<body>

<div class="container">

<fieldset>

    <legend><b>Car Management</b></legend>

    <h3>Car List</h3>

    <button onclick="window.location.href='addCar.php'"
            style="background:green; 
            color:white; 
            padding:8px 16px;
             border:none; 
             border-radius:4px;
              cursor:pointer; 
              font-size:14px;">

        + Add New Car
    </button>

    <br><br>

    <?php if (isset($_GET['success']) && $_GET['success'] === 'added'): ?>
        <p style="color:green;"> Car added successfully.</p>
    <?php endif; ?>


    <?php if (isset($_GET['success']) && $_GET['success'] === 'updated'): ?>
        <p style="color:green;"> Car updated successfully.</p>
    <?php endif; ?>


    <?php if (isset($_GET['success']) && $_GET['success'] === 'deleted'): ?>
        <p style="color:green;"> Car deleted successfully.</p>
    <?php endif; ?>


    <?php if (isset($_GET['error']) && $_GET['error'] === 'hasorders'): ?>
        <p style="color:red;">❌ Cannot delete — this car has active orders.</p>
    <?php endif; ?>


    <table border="1">

        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Model</th>
            <th>Type</th>
            <th>Price Per/Day</th>
            <th>Status</th>
            <th>Description</th>
            <th>Image</th>
            <th>Action</th>
        </tr>

        <?php foreach ($cars as $car) { ?>

        <tr>
            <td><?= htmlspecialchars($car['id']) ?></td>

            <td><?= htmlspecialchars($car['name']) ?></td>

            <td><?= htmlspecialchars($car['model']) ?></td>

            <td><?= htmlspecialchars($car['type']) ?></td>

            <td><?= htmlspecialchars($car['price_per_day']) ?></td>

            <td><?= htmlspecialchars($car['availability_status']) ?></td>

            <td><?= htmlspecialchars($car['description']) ?></td>

            <td>
                <?php if (!empty($car['image_path'])) { ?>
                    <img src="<?= htmlspecialchars($car['image_path']) ?>" width="80">
                <?php } ?>
            </td>

            <td>
                <!-- <a href="carDetails.php?id=<?= $car['id'] ?>">View</a> | -->

                <a href="editCarDetails.php?id=<?= $car['id'] ?>">Edit</a> |

                <a href="../controllers/carController.php?deleteCarId=<?= $car['id'] ?>&csrf_token=<?= $_SESSION['csrf_token'] ?>"

                   onclick="return confirm('Are you sure?')">

                   Delete
                </a>




            </td>
        </tr>

        <?php } ?>

    </table>

    <br>
    <a href="adminDashboard.php"

       style="display:inline-block; text-decoration:none; background:#007bff; color:white; padding:8px 16px; border-radius:4px; font-size:13px;">
       
        ← Back to Dashboard
    </a>

</fieldset>

</div>

</body>
</html>