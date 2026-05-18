<?php

    session_start();

    require_once('../models/carModel.php');

    // SESSION CHECK

    if(!isset($_SESSION['status'])){

        header('location: login.php');
    }

    // MEMBER ONLY

    if($_SESSION['role'] != 'member'){

        header('location: adminDashboard.php');
    }

    // CATEGORY CHECK

    if(!isset($_GET['type'])){

        header('location: home.php');
    }

    $type = $_GET['type'];

    // FETCH CARS

    $cars = getCarsByCategory($type);

?>

<html>

<head>

    <title>Category Cars</title>

</head>

<body bgcolor="#f2f2f2">

<table border="1" width="100%">

    <!-- NAVBAR -->

    <tr>

        <td colspan="2" bgcolor="black">

            <table width="100%">

                <tr>

                    <td>

                        <font color="white">

                            <h2>Online Car Rent</h2>

                        </font>

                    </td>

                    <td align="right">

                        <a href="home.php">

                            <font color="white">Home</font>

                        </a>

                        &nbsp;&nbsp;&nbsp;

                        <a href="profile.php">

                            <font color="white">Profile</font>

                        </a>

                        &nbsp;&nbsp;&nbsp;

                        <a href="../Controller/logout.php">

                            <font color="white">Logout</font>

                        </a>

                    </td>

                </tr>

            </table>

        </td>

    </tr>

    <!-- CATEGORY TITLE -->

    <tr>

        <td colspan="2" align="center">

            <h1><?php echo $type; ?> Cars</h1>

        </td>

    </tr>

    <!-- CAR LIST -->

    <tr>

        <td colspan="2">

            <table width="100%" cellpadding="20">

                <tr>

                <?php

                    $count = 0;

                    foreach($cars as $car){

                ?>

                    <td align="center">

                        <fieldset>

                            <?php if($car['image_path']): ?>
    <img class="car-img"
         src="public/uploads/cars/<?php echo htmlspecialchars($car['image_path']); ?>"
         alt="<?php echo htmlspecialchars($car['name']); ?>"
         onerror="this.src='public/uploads/cars/carImage.jpeg'" width="250" height="150">
    <?php endif; ?>
                            <h3><?php echo $car['name']; ?></h3>

                            <p>Model : <?php echo $car['model']; ?></p>

                            <p>Type : <?php echo $car['type']; ?></p>

                            <p>Price : <?php echo $car['price_per_day']; ?> BDT/day</p>

                            <a href="carDetails.php?id=<?php echo $car['id']; ?>">

                                <!-- home.php এ car card এর button -->
<a href="orderForm.php?car_id=<?php echo $car['id']; ?>">
    <button>Rent Now</button>
</a>

                            </a>

                        </fieldset>

                    </td>

                <?php

                    $count++;

                    if($count % 3 == 0){

                        echo "</tr><tr>";
                    }

                    }

                ?>

                </tr>

            </table>

        </td>

    </tr>

    <!-- FOOTER -->

    <tr>

        <td colspan="2" align="center">

            Copyright &copy; 2026

        </td>

    </tr>

</table>

</body>

</html>