<?php

session_start();

require_once("../config/db.php");
require_once("../models/carModel.php");

if (empty($_SESSION['csrf_token'])) {

    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$msg = "";

if (isset($_POST['addCar'])) {

    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {

        die("Invalid CSRF request!");
    }

    $name        = $_POST['name'];

    $model       = $_POST['model'];

    $type        = $_POST['type'];

    $price       = $_POST['price_per_day'];

    $description = $_POST['description'];

    $status      = $_POST['availability_status'];


    if ($name == "" || $model == "" || $price == "") {

        $msg = "Fill required fields!";
    }
    else if ($price <= 0) {

        $msg = "Price must be greater than 0!";
    }
    else {

        $image = "carImage.jpeg";

        $result = addCar($name, $model, $type, $price, $status, $image, $description);

        if ($result) {

            header("location: carList.php");

            exit();

        } else {
            $con = getConnection();

            $msg = "Database Error: " . mysqli_error($con);
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Car</title>

    <style>

        body {
            font-family: Arial;
            background: white;  
        }

        .container {
            height: 100vh;

            display: flex;
            justify-content: center;
            
            align-items: center;
        }

        fieldset {
            width: 400px;
            background: white;
            padding: 20px;
            border: 2px solid black;
            border-radius: 10px;
        }

        input,
        select,
        
        textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 10px;
        }

        button {
            width: 100%;
            padding: 10px;
            background: green;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background: darkgreen;
        }

        .msg {
            color: red;
            text-align: center;
        }

    </style>

</head>
<body>

<div class="container">

    <fieldset>

        <legend><b>Add New Car</b></legend>

        <p class="msg"><?php echo $msg; ?></p>

        <form method="POST" action="addCar.php">

            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

            Name
            <input type="text" name="name">

            Model
            <input type="text" name="model">

            Type
            <select name="type">
                <option value="Private Car">Private Car</option>
                <option value="Microbus">Microbus</option>
                <option value="Pick-up">Pick-up</option>
                <option value="SUV">SUV</option>
            </select>

            Price Per/Day
            <input type="number" name="price_per_day">

            Description
            <textarea name="description"></textarea>

            Status
            <select name="availability_status">
                <option value="available">Available</option>
                <option value="unavailable">Unavailable</option>
            </select>

            <button type="submit" name="addCar">Add Car</button>

        </form>

        <br>

        <a href="adminDashboard.php" style="display:inline-block;

         margin-top:10px; 

         text-decoration:none;

          background:#007bff; 

          color:white; 

          padding:8px 16px;

           border-radius:4px;
           
            font-size:13px;">
            &larr; Back to Dashboard
        </a>

        <script>

            document.querySelector("form").addEventListener("submit", function(e) {

                let name  = document.querySelector("[name='name']").value.trim();
                let model = document.querySelector("[name='model']").value.trim();
                let price = document.querySelector("[name='price_per_day']").value;

                if (name === "" || model === "" || price === "") {
                    alert("Please fill all required fields!");
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

</div>

</body>
</html>

