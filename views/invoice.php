<?php
session_start();
require_once("../config/db.php");
require_once("../models/orderModel.php");
require_once("../models/carModel.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$order = getOrderById(intval($_GET['order_id']));
$car   = getCarById($order['car_id']);


$start    = new DateTime($order['start_date']);
$end      = new DateTime($order['end_date']);
$days     = $start->diff($end)->days;
?>

<!DOCTYPE html>
<html>
<head>
<title>Invoice</title>
<style>
.box{
    width:550px;
    margin:40px auto;
    background:pink;
    padding:30px;
    border-radius:10px;
    font-family:Arial,sans-serif
}
h2{
    text-align:center;
    margin-bottom:20px;
    color:#333
}
table{
    width:100%;
    border-collapse:collapse;
    margin-bottom:20px
}
table td{
    padding:10px 14px;
    border:1px solid;
    font-size:14px;color:#444
}

.total-row td{
    font-size:16px;
    font-weight:bold;    
}
button{
    width:100%;
    padding:12px;
    margin:6px 0;
    border:none;
    border-radius:6px;
    font-size:15px;
    cursor:pointer;
    font-weight:bold;
    
}
.cancel{
    background:red;
    color:white}
.pay{
    background:green;
    color:white
    }
</style>
</head>
<body>

<div class="box">

<h2>Invoice</h2>

<table>
  <tr>
    <td><b>Car Name</b></td>
    <td><?php echo htmlspecialchars($car['name']); ?></td>
  </tr>
  <tr>
    <td><b>Model</b></td>
    <td><?php echo htmlspecialchars($car['model']); ?></td>
  </tr>
  <tr>
    <td><b>Type</b></td>
    <td><?php echo htmlspecialchars($car['type']); ?></td>
  </tr>
  <tr>
    <td><b>Price Per Day</b></td>
    <td><?php echo number_format($car['price_per_day'], 2); ?>tk</td>
  </tr>
  <tr>
    <td><b>Start Date</b></td>
    <td><?php echo $order['start_date']; ?></td>
  </tr>
  <tr>
    <td><b>End Date</b></td>
    <td><?php echo $order['end_date']; ?></td>
  </tr>
  <tr>
    <td><b>Rental Period</b></td>
    <td><?php echo $days; ?> day/days</td>
  </tr>
  <tr class="total-row">
    <td><b>Total Cost</b></td>
    <td><?php echo number_format($order['total_cost'], 2); ?> tk </td>
  </tr>
</table>



<button class="cancel" onclick="cancelOrder(<?php echo $order['id']; ?>)">
  Cancel
</button>

<a href="payment.php?order_id=<?php echo $order['id']; ?>">
  <button class="pay">Finalize</button>
</a>

</div>

<script>
function cancelOrder(id){
  let xttp = new XMLHttpRequest();
  xttp.open("POST","../ajax/cancel-order.php",true);
  xttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");

  xttp.onreadystatechange = function(){
    if(xttp.readyState==4 && xttp.status==200){
      let res = JSON.parse(xttp.responseText);
      if(res.status=="success"){
        alert("Order Cancelled");
        window.location.href="home.php";
      }
    }
  };

  xttp.send("order_id="+id);
}
</script>

</body>
</html>