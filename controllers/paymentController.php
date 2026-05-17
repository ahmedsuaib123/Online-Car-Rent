<?php
session_start();
require_once("../config/db.php");
require_once("../models/paymentModel.php");
require_once("../models/orderModel.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'member') {
    header("Location: ../views/login.php");
    exit();
}

if (isset($_POST['pay_now'])) {

    $order_id = intval($_POST['order_id']);
    $payment_method = htmlspecialchars(trim($_POST['payment_method']));
    $transaction_id = htmlspecialchars(trim($_POST['transaction_id']));
    $errors = [];

    if ($order_id <= 0) $errors[] = "Invalid order.";
    if (empty($payment_method)) $errors[] = "Payment method required.";
    if (empty($transaction_id)) $errors[] = "Transaction ID required.";

    $order = getOrderById($order_id);

    if (!$order) $errors[] = "Order not found.";
    if ($order && $order['user_id'] != $_SESSION['user_id']) $errors[] = "Unauthorized.";
    if ($order && $order['status'] == 'confirmed') $errors[] = "Already paid.";
    if ($order && $order['status'] == 'cancelled') $errors[] = "Order is cancelled.";

    if (!empty($errors)) {
        $msg = implode(" ", $errors);
        header("Location: ../views/payment.php?order_id=$order_id&error=" . urlencode($msg));
        exit();
    }

    $paid = addPayment($order_id, $order['total_cost'], $payment_method, $transaction_id);

    if (!$paid) {
        header("Location: ../views/payment.php?order_id=$order_id&error=" . urlencode("Payment failed. Try again."));
        exit();
    }

    updateOrderStatus($order_id, 'confirmed');
    updatePaymentMethod($order_id, $payment_method);

    header("Location: ../views/successfullPayment.php?order_id=" . $order_id);
    exit();
}

header("Location: ../views/home.php");
exit();

?>