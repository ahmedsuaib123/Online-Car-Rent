<?php
session_start();
require_once("../models/carModel.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'member') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['car_id'])) {
    header("Location: home.php");
    exit();
}

$car_id = intval($_GET['car_id']);
$car    = getCarById($car_id);

if (!$car) {
    echo "<p>Car not found.</p>";
    exit();
}

$error = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : '';
$today = date('Y-m-d');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Rent a Car — <?php echo htmlspecialchars($car['name']); ?></title>
<style>
*{
    box-sizing:border-box;
    margin:0;
    padding:0
}

nav{
    background:black;
    color:white;
    padding:14px 30px;
    display:flex;
    justify-content:space-between;
    align-items:center
}
nav a{
    color:white;
    text-decoration:none;
    font-size:14px
}
.container{
    max-width:600px;
    margin:40px auto;
    padding:0 16px;
}
.card{
    border: 2px solid black;
    background:white;
    border-radius:12px;
    padding:28px;
    
   
}
.car-img{
    width:100%;
    height:200px;
    object-fit:cover;
    margin-bottom:16px
    
}
.car-title{
    font-size:20px;
    font-weight:600;
    margin-bottom:4px
}
.car-meta{
    color:black;
    font-size:14px;
    margin-bottom:20px
}
label{
    display:block;
    font-size:14px;
    font-weight:500;
    margin-bottom:4px;
    color:black;
}
input,select{
    width:100%;
    padding:10px 14px;
    border:1px solid black;
    border-radius:8px;
    font-size:14px;
    margin-bottom:4px
}

.field{
    margin-bottom:16px
}
.error-msg{
    color:#e63946;
    font-size:13px;
    margin-bottom:12px;
    background:#fff0f0;
    padding:10px;
    border-radius:6px;
    border-left:3px solid #e63946
}
.field-error{
    color:#e63946;
    font-size:12px;
    margin-top:2px;
    display:none
}
.total-box{
    background:lightblue;
    border-radius:8px;
    padding:14px;
    margin-bottom:20px;
    display:flex;
    justify-content:space-between;
    align-items:center
}
.total-box span{
    font-size:22px;
    font-weight:700;
    color:black;
}
.btn{
    width:100%;
    padding:13px;
    background:blue;
    color:white;
    border:none;
    border-radius:8px;
    font-size:16px;
    cursor:pointer;
    font-weight:600
}

.badge{
    display:inline-block;
    padding:4px 10px;
    border-radius:20px;
    font-size:12px;
    font-weight:500
}
.badge-available{
    background:lightgreen;
    color:black;
}
.badge-unavailable{
    background:#fce4ec;
    color:black;
    }
</style>
</head>
<body>

<nav>
  <span>Online Car Rent</span>
  <a href="home.php">Back to Home</a>
</nav>

<div class="container">
  <div class="card">

    <?php if($car['image_path']): ?>
    <img class="car-img"
         src="public/uploads/<?php echo htmlspecialchars($car['image_path']); ?>"
         alt="<?php echo htmlspecialchars($car['name']); ?>"
         onerror="this.src='public/uploads/carImage.jpeg'">
    <?php endif; ?>
<div class="car-title">
    <?php echo htmlspecialchars($car['name']); ?>
</div>

<div class="car-meta">

    <?php echo htmlspecialchars($car['model']); ?> -
    
    <?php echo htmlspecialchars($car['type']); ?> -
    
    <strong>
        <?php echo number_format($car['price_per_day'], 2); ?> tk
    </strong>/day -

    <span class="badge <?php echo $car['availability_status'] === 'available' ? 'badge-available' : 'badge-unavailable'; ?>">

        <?php echo htmlspecialchars($car['availability_status']); ?>

    </span>

</div>

    <?php if($error): ?>
    <div class="error-msg"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if($car['availability_status'] !== 'available'): ?>
      <p style="color:#e63946;font-weight:500;">This car is currently unavailable for booking.</p>
    <?php else: ?>

    <form method="POST" action="../controllers/orderController.php" onsubmit="return validateOrderForm()">

      <input type="hidden" name="car_id" value="<?php echo $car['id']; ?>">
      <input type="hidden" name="total_cost" id="total_hidden" value="0">

      <div class="field">
        <label>Start Date</label>
        <input type="date" id="start_date" name="start_date" min="<?php echo $today; ?>" onchange="calcTotal()">
        <div class="field-error" id="err_start">Please select a valid start date.</div>
      </div>

      <div class="field">
        <label>End Date</label>
        <input type="date" id="end_date" name="end_date" min="<?php echo $today; ?>" onchange="calcTotal()">
        <div class="field-error" id="err_end">End date must be after start date.</div>
      </div>

      <div class="total-box">
        <div>
          <div style="font-size:13px;color:Black;"><b>Total Cost</b> </div>
          <div style="font-size:12px;color:black;" id="days_label"></div>
        </div>
        <span><span id="total_cost">0.00</span> tk </span>
      </div>


      <button class="btn" type="submit" name="create_order">Place Order</button>

    </form>

    <?php endif; ?>
  </div>
</div>

<script>
const pricePerDay = <?php echo floatval($car['price_per_day']); ?>;
const today       = '<?php echo $today; ?>';

function calcTotal(){
  const start = document.getElementById('start_date').value;
  const end   = document.getElementById('end_date').value;

  document.getElementById('err_start').style.display = 'none';
  document.getElementById('err_end').style.display   = 'none';

  if(!start || !end) return;

  // ✅ AJAX call for server-side calculation
  const xhttp = new XMLHttpRequest();
  xhttp.open('POST','../ajax/calculate-total.php',true);
  xhttp.setRequestHeader('Content-type','application/x-www-form-urlencoded');

  xhttp.onreadystatechange = function(){
    if(xhttp.readyState==4 && xhttp.status==200){
      const res = JSON.parse(xhttp.responseText);
      if(res.status === 'success'){
        document.getElementById('total_cost').innerText  = parseFloat(res.total_cost).toFixed(2);
        document.getElementById('total_hidden').value    = res.total_cost;
        document.getElementById('days_label').innerText  = res.days + ' days *  ' + parseFloat(res.price_per_day).toFixed(2);
      } else {
        document.getElementById('total_cost').innerText = '0.00';
        document.getElementById('total_hidden').value   = '0';
        document.getElementById('days_label').innerText = '';
      }
    }
  };

  const car_id = <?php echo $car['id']; ?>;
  xhttp.send('car_id='+car_id+'&start_date='+start+'&end_date='+end);
}

// ✅ JS Validation
function validateOrderForm(){
  const start = document.getElementById('start_date').value;
  const end   = document.getElementById('end_date').value;
  const total = parseFloat(document.getElementById('total_hidden').value);
  let valid   = true;

  if(!start || start < today){
    document.getElementById('err_start').style.display = 'block';
    valid = false;
  }
  if(!end || end <= start){
    document.getElementById('err_end').style.display = 'block';
    valid = false;
  }
  if(total <= 0){
    alert('Please select valid dates first.');
    valid = false;
  }
  return valid;
}
</script>
</body>
</html>