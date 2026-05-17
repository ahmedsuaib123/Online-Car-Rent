<?php
require_once("../config/db.php");
require_once("../models/carModel.php");

header("Content-Type: application/json");

$car_id = isset($_POST['car_id']) ? intval($_POST['car_id']) : 0;
$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : "";
$end_date = isset($_POST['end_date']) ? $_POST['end_date'] : "";

if ($car_id <= 0 || empty($start_date) || empty($end_date)) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid input",
        "total_cost" => 0
    ]);
    exit;
}

$car = getCarById($car_id);

if (!$car) {
    echo json_encode([
        "status" => "error",
        "message" => "Car not found",
        "total_cost" => 0
    ]);
    exit;
}

$start = strtotime($start_date);
$end = strtotime($end_date);

$days = ($end - $start) / 86400;

if ($days <= 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid date range",
        "total_cost" => 0
    ]);
    exit;
}

$total_cost = $days * $car['price_per_day'];

echo json_encode([
    "status" => "success",
    "days" => $days,
    "price_per_day" => $car['price_per_day'],
    "total_cost" => $total_cost
]);
?>