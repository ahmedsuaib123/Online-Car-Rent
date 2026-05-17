<?php
session_start();

require_once("../config/db.php");
require_once("../models/orderModel.php");
require_once("../models/carModel.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'member') {
    header("Location: ../views/login.php");
    exit();
}

$con = getConnection();

if (isset($_POST['create_order'])) {

    $user_id = intval($_SESSION['user_id']);
    $car_id = intval($_POST['car_id']);
    $start_date = trim($_POST['start_date']);
    $end_date = trim($_POST['end_date']);
    $payment_method = htmlspecialchars(trim($_POST['payment_method']));
    $errors = [];

    if ($car_id <= 0) {
        $errors[] = "Invalid car.";
    }

    if (empty($start_date)) {
        $errors[] = "Start date required.";
    }

    if (empty($end_date)) {
        $errors[] = "End date required.";
    }

    $today = date('Y-m-d');

    if (!empty($start_date) && $start_date < $today) {
        $errors[] = "Start date cannot be in the past.";
    }

    if (!empty($start_date) && !empty($end_date) && $end_date <= $start_date) {
        $errors[] = "End date must be after start date.";
    }

    $car = getCarById($car_id);

    if (!$car) {
        $errors[] = "Car not found.";
    }

    if (!empty($errors)) {
        $msg = implode(" ", $errors);
        header("Location: ../views/orderForm.php?car_id=$car_id&error=" . urlencode($msg));
        exit();
    }

    $days = (strtotime($end_date) - strtotime($start_date)) / 86400;
    $total_cost = $days * $car['price_per_day'];

    $order_id = createOrder($user_id, $car_id, $start_date, $end_date, $total_cost, 'pending');

    if (!$order_id) {
        header("Location: ../views/orderForm.php?car_id=$car_id&error=" . urlencode("Order failed. Try again."));
        exit();
    }

    header("Location: ../views/invoice.php?order_id=" . $order_id);
    exit();
}

if (isset($_GET['cancel'])) {

    $order_id = intval($_GET['cancel']);
    $order = getOrderById($order_id);

    if ($order && $order['user_id'] == $_SESSION['user_id'] && $order['status'] != 'confirmed') {
        updateOrderStatus($order_id, 'cancelled');
    }

    header("Location: ../views/home.php");
    exit();
}

if (isset($_GET['finalize'])) {

    $order_id = intval($_GET['finalize']);
    $order = getOrderById($order_id);

    if (!$order || $order['user_id'] != $_SESSION['user_id']) {
        header("Location: ../views/home.php");
        exit();
    }

    header("Location: ../views/payment.php?order_id=" . $order_id);
    exit();
}

?>