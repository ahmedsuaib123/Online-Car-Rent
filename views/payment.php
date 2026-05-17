<?php
session_start();
require_once("../models/orderModel.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$order_id = intval($_GET['order_id']);
$order = getOrderById($order_id);

if (!$order || $order['user_id'] != $_SESSION['user_id']) {
    header("Location: home.php");
    exit();
}

if ($order['status'] == 'confirmed') {
    header("Location: successfullPayment.php?order_id=" . $order['id']);
    exit();
}

if ($order['status'] == 'cancelled') {
    header("Location: home.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Payment</title>

<style>
*{
margin:0;
padding:0;
}


.box{
width:450px;
margin:60px auto;
background:lightblue;
padding:30px;
border:2px solid black;

}

h2{
margin-bottom:20px;
color:black;
}

.amount{
background:white;
padding:15px;
border-radius:8px;
margin-bottom:20px;
font-size:18px;
font-weight:bold;
color:blue;
border:1px solid black;
}

label{
display:block;
margin-bottom:5px;
color:black;
font-size:14px;
}

select,input{
width:100%;
padding:10px;
margin-bottom:15px;
border:1px solid black;
border-radius:6px;
font-size:14px;
}

button{
width:100%;
padding:12px;
background:green;
color:#fff;
border:none;
border-radius:6px;
font-size:16px;
cursor:pointer;
}

.error{
color:red;
margin-bottom:15px;
font-size:14px;
}

</style>

</head>

<body>

<div class="box">
<h2>Complete Payment</h2>

<?php if(isset($_GET['error'])){ ?>
<p class="error"><?php echo htmlspecialchars($_GET['error']); ?></p>
<?php } ?>

<div class="amount">
Total Amount: <?php echo number_format($order['total_cost'],2); ?> tk
</div>

<form method="POST" action="../controllers/paymentController.php">

<input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">

<label>Payment Method</label>
<select name="payment_method" required>
<option value="">Select Method</option>
<option value="bkash">bKash</option>
<option value="nagad">Nagad</option>
<option value="card">Credit Card</option>
<option value="bank">Bank Transfer</option>
<option value="cash">Cash on Delivery</option>
</select>

<label>Transaction ID</label>
<input type="text" name="transaction_id" placeholder="Enter Transaction ID" required>

<button type="submit" name="pay_now">Confirm Payment</button>

</form>
</div>

</body>
</html>