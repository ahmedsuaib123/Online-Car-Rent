<?php

session_start();

require_once("../config/db.php");
require_once("../models/carModel.php");
require_once("../models/orderModel.php");


if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("location: ../views/login.php");
    exit();
}


$conn = getConnection();


$totalCars = getTotalCars();
$totalOrders = getTotalOrders();


$memberQuery = "SELECT COUNT(*) AS total 
                FROM users 
                WHERE role='member'";

$memberResult = mysqli_query($conn, $memberQuery);

$memberData = mysqli_fetch_assoc($memberResult);

$totalMembers = $memberData['total'];


$blogQuery = "SELECT COUNT(*) AS total 
              FROM blogs";

$blogResult = mysqli_query($conn, $blogQuery);

$blogData = mysqli_fetch_assoc($blogResult);

$totalBlogs = $blogData['total'];


$memberSql = "SELECT * 
              FROM users 
              WHERE role='member'
              ORDER BY created_at DESC";

$memberResult = mysqli_query($conn, $memberSql);

$members = [];

while ($row = mysqli_fetch_assoc($memberResult)) {
    $members[] = $row;
}



if (isset($_GET['deleteMemberId'])) {

    header('Content-Type: application/json');

    $id = $_GET['deleteMemberId'];

    $response = [];

    $status = deleteMemberById($id);

    if ($status) {
        $response = [
            "status" => "success",
            "message" => "Deleted successfully"
        ];
    } else {
        $response = [
            "status" => "error",
            "message" => "Delete failed"
        ];
    }

    echo json_encode($response);
    exit();
}



$orders = getAllOrders();




?>