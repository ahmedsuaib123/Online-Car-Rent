<?php
require_once("../config/db.php");
require_once("../models/orderModel.php");

header("Content-Type: application/json");

$order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;

if ($order_id <= 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid order id"
    ]);
    exit;
}
$order = getOrderById($order_id);

if (!$order) {
    echo json_encode([
        "status" => "error",
        "message" => "Order not found"
    ]);
    exit;
}

if ($order['status'] == "confirmed") {
    echo json_encode([
        "status" => "error",
        "message" => "Confirmed order cannot be cancelled"
    ]);
    exit;
}

$result = updateOrderStatus($order_id, "cancelled");

if ($result) {
    echo json_encode([
        "status" => "success",
        "message" => "Order cancelled successfully"
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Failed to cancel order"
    ]);
}
?>
