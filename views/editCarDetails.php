<?php

session_start();

require_once("../config/db.php");
require_once("../models/carModel.php");

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if (!isset($_GET['id'])) {
    header("location: carList.php");
    exit();
}

$id  = $_GET['id'];
$car = getCarById($id);

if (!$car) {
    header("location: carList.php");
    exit();
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Car</title>

    <style>

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: #f2f2f2;
        }

        fieldset {
            width: 500px;
            padding: 30px;
            background: white;
        }

        table {
            width: 100%;
        }

        input[type=text],
        input[type=number],
        select,
        textarea {
            width: 100%;
            padding: 8px;
        }

        textarea {
            resize: none;
        }

        img {
            border: 1px solid #ccc;
            padding: 5px;
        }

    </style>

</head>
<body>

<fieldset>

    <legend><h2>Edit Car Details</h2></legend>

    <form method="POST" action="../controllers/carController.php" enctype="multipart/form-data">

        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <input type="hidden" name="id" value="<?php echo $car['id']; ?>">
        <input type="hidden" name="old_image" value="<?php echo $car['image_path']; ?>">

        <table>

            <tr>
                <td>Car Name</td>
                <td>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($car['name']); ?>" required>
                </td>
            </tr>

            <tr>
                <td>Model</td>
                <td>
                    <input type="text" name="model" value="<?php echo htmlspecialchars($car['model']); ?>" required>
                </td>
            </tr>

            <tr>
                <td>Type</td>
                <td>
                    <select name="type" required>

                        <option value="Private Car" <?php echo ($car['type'] == "Private Car") ? "selected" : ""; ?>>Private Car</option>

                        <option value="Microbus" <?php echo ($car['type'] == "Microbus") ? "selected" : ""; ?>>Microbus</option>

                        <option value="Pick-up" <?php echo ($car['type'] == "Pick-up") ? "selected" : ""; ?>>Pick-up</option>

                        <option value="SUV" <?php echo ($car['type'] == "SUV") ? "selected" : ""; ?>>SUV</option>

                    </select>
                </td>
            </tr>

            <tr>
                <td>Price Per Day</td>
                <td>
                    <input type="number" name="price_per_day" min="1" value="<?php echo $car['price_per_day']; ?>" required>
                </td>
            </tr>

            <tr>
                <td>Availability</td>
                <td>
                    <select name="availability_status" required>

                        <option value="available" <?php echo ($car['availability_status'] == "available") ? "selected" : ""; ?>>Available</option>

                        <option value="unavailable" <?php echo ($car['availability_status'] == "unavailable") ? "selected" : ""; ?>>Unavailable</option>

                    </select>
                </td>
            </tr>

            <tr>
                <td>Description</td>
                <td>
                    <textarea name="description" rows="4"><?php echo htmlspecialchars($car['description']); ?></textarea>
                </td>
            </tr>

            <tr>
                <td>Current Image</td>
                <td>
                    <?php if (!empty($car['image_path'])) { ?>
                        <img src="./public/uploads/<?php echo basename($car['image_path']); ?>" width="120">
                    <?php } ?>
                </td>
            </tr>

            <tr>
                <td>Change Image</td>
                <td>
                    <input type="file" name="image" accept=".jpg,.jpeg,.png">
                </td>
            </tr>

            <tr>
                <td></td>
                <td>
                    <input type="submit" name="updateCar" value="Update Car">
                </td>
            </tr>

        </table>

    </form>

    <br>

    <a href="adminDashboard.php" style="display:inline-block; margin-top:10px; text-decoration:none; background:#007bff; color:white; padding:8px 16px; border-radius:4px; font-size:13px;">
        &larr; Back to Dashboard
    </a>

    <script>

        document.querySelector("form").addEventListener("submit", function(e) {

            let name  = document.querySelector("[name='name']").value.trim();
            let model = document.querySelector("[name='model']").value.trim();
            let price = document.querySelector("[name='price_per_day']").value;

            if (name === "" || model === "" || price === "") {
                alert("All required fields must be filled!");
                e.preventDefault();
                return;
            }

            if (price <= 0) {
                alert("Price must be greater than 0!");
                e.preventDefault();
                return;
            }

        });

    </script>

</fieldset>

</body>
</html>

