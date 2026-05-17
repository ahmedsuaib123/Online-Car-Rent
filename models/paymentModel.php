<?php

require_once("../config/db.php");

function addPayment($order_id,$amount,$payment_method,$transaction_id){
	$conn=getConnection();
	$sql="INSERT INTO payments (order_id, amount, payment_method, transaction_id) VALUES (?, ?, ?, ?)";
	$stmt=mysqli_prepare($conn,$sql);
	if(!$stmt) return false;
	mysqli_stmt_bind_param($stmt,"idss",$order_id,$amount,$payment_method,$transaction_id);
	return mysqli_stmt_execute($stmt);
}

function getPaymentByOrderId($order_id){
	$conn=getConnection();
	$sql="SELECT * FROM payments WHERE order_id=?";
	$stmt=mysqli_prepare($conn,$sql);
	mysqli_stmt_bind_param($stmt,"i",$order_id);
	mysqli_stmt_execute($stmt);
	$result=mysqli_stmt_get_result($stmt);
	return mysqli_fetch_assoc($result);
}

function getTotalPayments(){
	$conn=getConnection();
	$result=mysqli_query($conn,"SELECT COUNT(*) AS total FROM payments");
	$row=mysqli_fetch_assoc($result);
	return $row['total'];
}

?>