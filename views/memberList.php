<?php
// view/memberList.php

session_start();

require_once("../config/db.php");

// Admin check
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("location: login.php");
    exit();
}

// CSRF token generate
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$conn = getConnection();

$sql    = "SELECT * FROM users WHERE role = 'member' ORDER BY id ASC";
$result = mysqli_query($conn, $sql);

$members = [];
while ($row = mysqli_fetch_assoc($result)) {
    $members[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member List | Online Car Rent</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            min-height: 100vh;
        }

        /*  Navbar  */
        .navbar {
            background: #333;
            color: white;
            padding: 12px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar h1 {
            font-size: 18px;
            font-weight: bold;
        }

        .navbar a {
            color: #ccc;
            text-decoration: none;
            margin-left: 12px;
            font-size: 13px;
            padding: 6px 12px;
            border-radius: 4px;
            transition: background 0.2s;
        }

        .navbar a:hover,
        .navbar a.active {
            background: #555;
            color: white;
        }

        /*  Container  */
        .container {
            width: 90%;
            max-width: 1100px;
            margin: 30px auto;
            background: white;
            border: 2px solid #333;
            border-radius: 8px;
            padding: 25px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid #eee;
        }

        .page-header h2 {
            font-size: 22px;
            color: #333;
        }

        .badge {
            background: #333;
            color: white;
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 13px;
        }

        /*  Alert banners  */
        .alert {
            padding: 10px 16px;
            border-radius: 4px;
            margin-bottom: 15px;
            font-size: 14px;
        }
        .alert-success { background: #d4edda; color: green; border: 1px solid white; }
        .alert-error   { background: white; color: red; border: 1px solid white; }

        /*  Table  */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #333;
            color: white;
            padding: 11px 14px;
            text-align: left;
            font-size: 14px;
        }

        td {
            padding: 10px 14px;
            border-bottom: 1px solid #ddd;
            font-size: 14px;
            color: #444;
            vertical-align: middle;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tbody tr:hover td {
            background: #fafafa;
        }

        /* ── Delete button ── */
        .deleteBtn {
            display: inline-block;
            background: orange;
            color: white;
            padding: 6px 14px;
            border-radius: 4px;
            font-size: 13px;
            text-decoration: none;
            cursor: pointer;
            transition: background 0.2s;
        }

        .deleteBtn:hover {
            background: orange;
        }

        /*  Empty row  */
        .empty-msg {
            text-align: center;
            color: silver;
            padding: 30px;
            font-style: italic;
        }

        /*  Back link  */
        .back-link {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            background: red;
            color: white;
            padding: 8px 16px;
            border-radius: 4px;
            font-size: 13px;
        }

        .back-link:hover {
            background: red;
        }

        /*  Toast  */
        #toast {
            position: fixed;
            bottom: 25px;
            right: 25px;
            padding: 12px 20px;
            border-radius: 6px;
            color: white;
            font-size: 14px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            z-index: 9999;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.4s ease;
        }

        #toast.show {
            opacity: 1;
        }
    </style>
</head>
<body>

<!-- CSRF Token hidden input  -->
<input type="hidden" id="csrf_token_value"
       value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

<!-- Navbar -->
<div class="navbar">
    <h1>Online Car Rent — Admin Panel</h1>
    <div>
        <a href="adminDashboard.php">Dashboard</a>

        <a href="carList.php">Cars</a>

        <a href="memberList.php" class="active">Members</a>

        <a href="orderHistory.php">Orders</a>

        <a href="../controllers/logout.php">Logout</a>
    </div>
</div>

<!-- Main content -->
<div class="container">

    <div class="page-header">

        <h2>Member List</h2>

        <span class="badge" id="memberCountBadge">

            Total Members: <?= count($members) ?>
        </span>
    </div>

    <!-- Flash messages -->
    <?php if (isset($_GET['success']) && $_GET['success'] === 'deleted'): ?>

        <div class="alert alert-success">Member deleted successfully.</div>

    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>

        <div class="alert alert-error"> Something went wrong. Please try again.</div>

    <?php endif; ?>

    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Joined</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody id="memberTableBody">

            <?php if (count($members) === 0): ?>
                <tr>
                    <td colspan="7" class="empty-msg">No members found.</td>
                </tr>

            <?php else: ?>

                <?php foreach ($members as $m): ?>
                <tr id="row<?= $m['id'] ?>">
                    <td><?= htmlspecialchars($m['id']) ?></td>

                    <td><?= htmlspecialchars($m['name']) ?></td>

                    <td><?= htmlspecialchars($m['email']) ?></td>

                    <td><?= htmlspecialchars($m['phone']   ?? '—') ?></td>

                    <td><?= htmlspecialchars($m['address'] ?? '—') ?></td>

                    <td><?= date('d M Y', strtotime($m['created_at'])) ?></td>
                    <td>
                        <a href="#"
                           class="deleteBtn"

                           data-id="<?= $m['id'] ?>"
                           
                           data-name="<?= htmlspecialchars($m['name'], ENT_QUOTES) ?>">
                            Delete
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>

            <?php endif; ?>

        </tbody>
    </table>

    

    

    <br>
<a href="adminDashboard.php" style="display:inline-block; margin-top:15px; text-decoration:none; background:#007bff; color:white; padding:8px 16px; border-radius:4px; font-size:13px;">← Back to Dashboard</a>

</div>

<!-- Toast div -->
<div id="toast"></div>

<script src="../ajax/memberAjax.js"></script>

</body>
</html>
