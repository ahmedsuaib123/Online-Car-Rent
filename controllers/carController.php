<?php

session_start();

require_once("../config/db.php");
require_once("../models/carModel.php");

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$conn = getConnection();

function validateCar($name, $model, $type, $price, $status) {

    if (empty($name) || empty($model) || empty($type) || empty($status)) {
        return "empty";
    }

    if (!is_numeric($price) || $price <= 0) {
        return "price";
    }

    return "ok";
}


//  EDIT LOAD 

if (isset($_GET['editId'])) {

    $editId = $_GET['editId'];
    $car    = getCarById($editId);
}


//  UPDATE CAR  

if (isset($_POST['updateCar'])) {

    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF validation failed!");
    }

    $id          = $_POST['id'];
    $name        = $_POST['name'];
    $model       = $_POST['model'];
    $type        = $_POST['type'];
    $price       = $_POST['price_per_day'];
    $status      = $_POST['availability_status'];
    $description = $_POST['description'];

    $check = validateCar($name, $model, $type, $price, $status);

    if ($check != "ok") {
        header("location: ../views/editCarDetails.php?id=$id&error=$check");
        exit();
    }

    $image_path = $_POST['old_image'];

    if (isset($_FILES['image']) && $_FILES['image']['name'] != "") {
        $image      = $_FILES['image']['name'];
        $image_path = "./public/uploads/" . $image;
    }

    updateCar($id, $name, $model, $type, $price, $status, $image_path, $description);

    header("location: ../views/carList.php?success=updated");
    exit();
}


//  DELETE CAR

if (isset($_GET['deleteCarId'])) {

    if (!isset($_GET['csrf_token']) || $_GET['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF validation failed!");
    }

    $id = $_GET['deleteCarId'];

    if (hasActiveOrders($id)) {
        header("location: ../views/carList.php?error=hasorders");
        exit();
    }

    deleteCar($id);

    header("location: ../views/carList.php?success=deleted");
    exit();
}


$cars = getAllCars();

?>




