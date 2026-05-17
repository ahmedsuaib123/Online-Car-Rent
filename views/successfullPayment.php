<?php
session_start();
require_once("../config/db.php");
require_once("../models/orderModel.php");
require_once("../models/paymentModel.php");

if (!isset($_SESSION['user_id'])) {
header("Location: login.php");
exit();
}

$order_id=intval($_GET['order_id']);
$order=getOrderById($order_id);
$payment=getPaymentByOrderId($order_id);

if (!$order || $order['user_id']!=$_SESSION['user_id']) {
header("Location: home.php");
exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Payment Successful</title>

<style>



.box{
width:480px;
margin:60px auto;
background:white;
padding:35px;
border-radius:10px;
text-align:center;
}


h2{
color:green;
margin-bottom:25px;
}

.details{
background:lightgray;
border-radius:8px;
padding:20px;
text-align:left;
margin-bottom:25px;
}

.details p{
padding:8px 0;
border-bottom:1px solid black;
font-size:15px;
color:black;
}

.details span{
font-weight:bold;
color:black;
float:right;
}

.btn{
display:inline-block;
padding:12px 30px;
background:black;
color:white;
border-radius:6px;
text-decoration:none;
font-size:15px;
margin:5px;
}

.btn-outline{
background:white;
color:black;
border:2px solid black;
}
</style>

</head>

<body>

<div class="box">
<h2>Payment Successful!</h2>

<div class="details">
<p>Order ID <span>#<?php echo $order['id']; ?></span></p>
<p>Car <span><?php echo htmlspecialchars($order['car_name'] ?? 'N/A'); ?></span></p>
<p>Start Date <span><?php echo $order['start_date']; ?></span></p>
<p>End Date <span><?php echo $order['end_date']; ?></span></p>
<p>Total Paid <span><?php echo number_format($order['total_cost'],2); ?> tk</span></p>

<?php if($payment){ ?>
<p>Payment Method <span><?php echo htmlspecialchars(ucfirst($payment['payment_method'])); ?></span></p>
<p>Transaction ID <span><?php echo htmlspecialchars($payment['transaction_id']); ?></span></p>
<p>Payment Date <span><?php echo $payment['payment_date']; ?></span></p>
<?php } ?>

<p>Status <span>Confirmed</span></p>
</div>

<a href="rentalHistory.php" class="btn">View Rental History</a>
<a href="home.php" class="btn btn-outline">Back to Home</a>

</div>

</body>
</html>