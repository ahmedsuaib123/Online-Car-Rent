<?php
session_start();
require_once("../config/db.php");
require_once("../models/orderModel.php");

if (!isset($_SESSION['user_id'])) {
header("Location: login.php");
exit();
}

$user_id=intval($_SESSION['user_id']);
$conn=getConnection();

$sql="SELECT orders.*,cars.name AS car_name,cars.model FROM orders JOIN cars ON cars.id=orders.car_id WHERE orders.user_id=? AND orders.status!='pending' ORDER BY orders.id DESC";

$stmt=mysqli_prepare($conn,$sql);
mysqli_stmt_bind_param($stmt,"i",$user_id);
mysqli_stmt_execute($stmt);
$result=mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html>
<head>
<title>Rental History</title>

<style>



h2{
text-align:center;
padding:30px 0 20px;
}

.container{
width:90%;
max-width:900px;
margin:0 auto;
}

table{
width:100%;
border-collapse:collapse;
background:white;
border-radius:10px;
overflow:hidden;
}

th,td{
border-bottom:1px solid;
padding:12px 15px;
text-align:center;
}

th{
background:lightblue;
}

td{
font-size:14px;
}


.badge{
padding:5px 12px;
border-radius:20px;
font-size:12px;
font-weight:bold;
}

.badge-confirmed{
background:green;
color:white;
}

.badge-pending{
background:yellow;
color:black;
}

.badge-cancelled{
background:red;
color:white;
}

.no-data{
text-align:center;
padding:30px;
}

.back-btn{
display:inline-block;
margin:20px 0;
padding:10px 20px;
color:black;
border-radius:6px;
text-decoration:none;
font-size:18px;
border:2px solid black;
background:pink;
}
</style>

</head>

<body>

<div class="container">
<h2>My Rental History</h2>
<a href="home.php" class="back-btn">Back to Home</a>

<table>
<tr>
<th>#</th>
<th>Car</th>
<th>Model</th>
<th>Start Date</th>
<th>End Date</th>
<th>Total (tk)</th>
<th>Payment Method</th>
<th>Status</th>
</tr>

<?php
$count=0;
while($row=mysqli_fetch_assoc($result)):
$count++;

$badge='badge-pending';
if($row['status']=='confirmed') $badge='badge-confirmed';
if($row['status']=='cancelled') $badge='badge-cancelled';
?>

<tr>
<td><?php echo $count; ?></td>
<td><?php echo htmlspecialchars($row['car_name']); ?></td>
<td><?php echo htmlspecialchars($row['model']); ?></td>
<td><?php echo $row['start_date']; ?></td>
<td><?php echo $row['end_date']; ?></td>
<td><?php echo number_format($row['total_cost'],2); ?> tk </td>
<td><?php echo htmlspecialchars(ucfirst($row['payment_method'] ?? 'N/A')); ?></td>
<td><span class="badge <?php echo $badge; ?>"><?php echo ucfirst($row['status']); ?></span></td>
</tr>

<?php endwhile; ?>

<?php if($count==0): ?>
<tr>
<td colspan="8" class="no-data">No rental history found.</td>
</tr>
<?php endif; ?>

</table>
</div>

</body>
</html>