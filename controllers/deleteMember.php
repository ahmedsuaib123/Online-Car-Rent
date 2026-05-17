<?php

session_start();

require_once("../config/db.php");
require_once("../models/carModel.php");

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
    exit();
}

if (
    !isset($_POST['csrf_token']) ||
    !isset($_SESSION['csrf_token']) ||
    $_POST['csrf_token'] !== $_SESSION['csrf_token']
) {
    echo json_encode(["status" => "error", "message" => "CSRF validation failed"]);
    exit();
}

if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    echo json_encode(["status" => "error", "message" => "Invalid member ID"]);
    exit();
}

$id = intval($_POST['id']);

$status = deleteMemberById($id);

if ($status) {
    echo json_encode(["status" => "success", "message" => "Member deleted successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => "Delete failed. Member may not exist."]);
}

exit();

?>




