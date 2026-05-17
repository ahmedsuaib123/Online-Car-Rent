<?php
session_start();

require_once("../config/db.php");

$conn = getConnection();

/* ADMIN CHECK */
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("location: login.php");
    exit();
}

/* ORDER + USER + CAR + PAYMENT DATA */

$filterStatus   = $_GET['status']    ?? '';
$filterFromDate = $_GET['from_date'] ?? '';
$filterToDate   = $_GET['to_date']   ?? '';

$sql = "SELECT orders.id, orders.status, orders.start_date, orders.end_date, orders.total_cost,

               orders.payment_method, orders.order_date,

               users.name AS member_name,

               cars.name AS car_name, cars.model AS car_model

        FROM orders

        LEFT JOIN users ON orders.user_id = users.id

        LEFT JOIN cars ON orders.car_id = cars.id

        WHERE 1=1";

$params = [];

$types = "";

if ($filterStatus != "") {
    $sql .= " AND orders.status = ?";
    $params[] = $filterStatus;
    $types .= "s";
}

if ($filterFromDate != "") {
    $sql .= " AND DATE(orders.order_date) >= ?";
    $params[] = $filterFromDate;
    $types .= "s";
}

if ($filterToDate != "") {
    $sql .= " AND DATE(orders.order_date) <= ?";
    $params[] = $filterToDate;
    $types .= "s";
}

$sql .= " ORDER BY orders.order_date ASC";

$stmt = mysqli_prepare($conn, $sql);

if (!empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$orders = [];
while ($row = mysqli_fetch_assoc($result)) {
    $orders[] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order History</title>

    <style>
        body{
            font-family: Arial;
            background:#f2f2f2;
        }

        .container{
            width:95%;
            margin:auto;
            margin-top:20px;
            background:white;
            padding:20px;
        }

        table{
            width:100%;
            border-collapse: collapse;
        }

        th, td{
            border:1px solid #ccc;
            padding:10px;
            text-align:center;
        }

        th{
            background:#333;
            color:white;
        }

        h2{
            text-align:center;
        }

.filter-form {
    margin-bottom: 20px;
    padding: 15px;
    background: red;
    border: 1px solid pink;
    border-radius: 6px;
}

.filter-form label {
    font-size: 13px;
    font-weight: bold;
}

.filter-form select,
.filter-form input[type="date"] {
    padding: 6px;
    border: 1px solid pink;
    border-radius: 4px;
    font-size: 13px;
}

.filter-form input[type="submit"] {
    padding: 7px 18px;
    background: #333;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 13px;
}

.filter-form input[type="submit"]:hover {
    background: #555;
}

.filter-form a {
    padding: 7px 14px;
    background: #aaa;
    color: white;
    border-radius: 4px;
    text-decoration: none;
    font-size: 13px;
}

    </style>

</head>
<body>

<div class="container">

<h2>Order History</h2>

<form method="GET" class="filter-form">

    <label>Status</label>
    <br>
    <select name="status">
        <option value="">All</option>
        <option value="pending"   <?= (isset($_GET['status']) && $_GET['status']==='pending')   ? 'selected' : '' ?>>Pending</option>

        <option value="confirmed" <?= (isset($_GET['status']) && $_GET['status']==='confirmed') ? 'selected' : '' ?>>Confirmed</option>

        <option value="cancelled" <?= (isset($_GET['status']) && $_GET['status']==='cancelled') ? 'selected' : '' ?>>Cancelled</option>
    </select>

    &nbsp;&nbsp;

    <label>From Date</label>
    <input type="date" name="from_date" value="<?= isset($_GET['from_date']) ? htmlspecialchars($_GET['from_date']) : '' ?>">

    &nbsp;&nbsp;

    <label>To Date</label>
    <input type="date" name="to_date" value="<?= isset($_GET['to_date']) ? htmlspecialchars($_GET['to_date']) : '' ?>">

    &nbsp;&nbsp;

    <input type="submit" value="Filter">

    &nbsp;

    <a href="orderHistory.php">Reset</a>

</form>



<table>
    <tr>
        <th>ID</th>
        <th>Member Name</th>
        <th>Car Name</th>
        <th>Model</th>
        <th>Payment Method</th>
        <th>Total Cost</th>
        <th>Status</th>
    </tr>


    
    <?php foreach ($orders as $row) { ?>

    <!-- <?= htmlspecialchars($row['id']) ?> -->

    <tr>
    
        <td><?= htmlspecialchars($row['id']) ?></td>

        <td><?= htmlspecialchars($row['member_name']) ?></td>

        <td><?= htmlspecialchars($row['car_name']) ?></td>

        <td><?= htmlspecialchars($row['car_model']) ?></td>

        <td><?= htmlspecialchars($row['payment_method']) ?? 'N/A' ?></td>
        
         <td><?= htmlspecialchars($row['total_cost']) ?></td>
         
        <td><?= htmlspecialchars($row['status']) ?></td>
    </tr>

    <?php } ?>

</table>

<br>
<a href="adminDashboard.php" style="display:inline-block; text-decoration:none; background:#007bff; color:white; padding:8px 16px; border-radius:4px; font-size:13px;">← Back to Dashboard</a>



</div>

</body>
</html>