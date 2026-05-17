<?php

require_once("../config/db.php");


function getAllOrders()
{
    $conn = getConnection();

    $sql = "SELECT 
                orders.id,
                users.name AS user_name,
                cars.name AS car_name,
                cars.model,
                orders.start_date,
                orders.end_date,
                orders.total_cost,
                orders.status,
                orders.payment_method,
                orders.order_date
            FROM orders
            INNER JOIN users ON orders.user_id = users.id
            INNER JOIN cars ON orders.car_id = cars.id
            ORDER BY orders.order_date ASC";

    $result = mysqli_query($conn, $sql);

    $orders = [];

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $orders[] = $row;
        }
    }

    return $orders;
}


function getOrderById($id)
{
    $conn = getConnection();

    $sql = "SELECT * FROM orders WHERE id = ?";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        return null;
    }

    mysqli_stmt_bind_param($stmt, "i", $id);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    return mysqli_fetch_assoc($result);
}


function createOrder($user_id, $car_id, $start_date, $end_date, $total_cost, $payment_method)
{
    $conn = getConnection();

    $sql = "INSERT INTO orders
    (user_id, car_id, start_date, end_date, total_cost, payment_method, status)
    VALUES (?, ?, ?, ?, ?, ?, 'pending')";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        return false;
    }

    mysqli_stmt_bind_param(
        $stmt,
        "iissds",
        $user_id,
        $car_id,
        $start_date,
        $end_date,
        $total_cost,
        $payment_method
    );


    if(mysqli_stmt_execute($stmt)){
    return mysqli_insert_id($conn);
}
return false;
}


function updateOrderStatus($order_id, $status)
{
    $conn = getConnection();

    $sql = "UPDATE orders SET status=? WHERE id=?";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        return false;
    }

    mysqli_stmt_bind_param($stmt, "si", $status, $order_id);

    return mysqli_stmt_execute($stmt);
}


function deleteOrder($order_id)
{
    $conn = getConnection();

    $sql = "DELETE FROM orders WHERE id=?";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        return false;
    }

    mysqli_stmt_bind_param($stmt, "i", $order_id);

    return mysqli_stmt_execute($stmt);
}


function filterOrders($status = null, $from_date = null, $to_date = null)
{
    $conn = getConnection();

    $sql = "SELECT 
                orders.id,
                users.name AS user_name,
                cars.name AS car_name,
                orders.start_date,
                orders.end_date,
                orders.total_cost,
                orders.status,
                orders.payment_method,
                orders.order_date
            FROM orders
            INNER JOIN users ON orders.user_id = users.id
            INNER JOIN cars ON orders.car_id = cars.id
            WHERE 1=1";

    if ($status != null && $status != "") {
        $sql .= " AND orders.status = '$status'";
    }

    if ($from_date != null && $from_date != "") {
        $sql .= " AND orders.order_date >= '$from_date'";
    }

    if ($to_date != null && $to_date != "") {
        $sql .= " AND orders.order_date <= '$to_date'";
    }

    $sql .= " ORDER BY orders.order_date ASC";

    $result = mysqli_query($conn, $sql);

    $orders = [];

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $orders[] = $row;
        }
    }

    return $orders;
}


function getTotalOrders()
{
    $conn = getConnection();

    $sql = "SELECT COUNT(*) AS total FROM orders";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row['total'];
    }

    return 0;
}

function updatePaymentMethod($order_id, $payment_method)
{
    $conn = getConnection();
    $sql  = "UPDATE orders SET payment_method=? WHERE id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "si", $payment_method, $order_id);
    return mysqli_stmt_execute($stmt);
}




?>