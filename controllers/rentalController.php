<?php
session_start();

require_once("../config/db.php");
require_once("../models/orderModel.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/login.php");
    exit();
}

$user_id = intval($_SESSION['user_id']);
$role = $_SESSION['role'];

if ($role == 'admin') {
    $rentals = getAllOrders();
}
else {
    $rentals = getOrdersByUserId($user_id);
}

include("../views/rentalHistory.php");

?>