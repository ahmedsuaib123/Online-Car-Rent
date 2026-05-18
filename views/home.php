<?php

    session_start();

    require_once('../models/userModel.php');
    require_once('../models/carModel.php');

    // AUTO LOGIN USING COOKIE
    if(!isset($_SESSION['status'])){

        if(isset($_COOKIE['remember_user'])){

            $users = getAllUsers();

            foreach($users as $u){

                if($u['id'] == $_COOKIE['remember_user']){

                    $_SESSION['status'] = true;
                    $_SESSION['user_id'] = $u['id'];
                    $_SESSION['name'] = $u['name'];
                    $_SESSION['role'] = $u['role'];
                    $_SESSION['email'] = $u['email'];
                }
            }
        }
    }

    // SESSION CHECK

    if(!isset($_SESSION['status'])){

        header('location: login.php');
    }


    if($_SESSION['role'] != 'member'){

        header('location: adminDashboard.php');
    }

    // FETCH FEATURED CARS

    $cars = getFeaturedCars();

    // FETCH CATEGORIES

    $categories = getCategories();

?>

<html>

<head>

    <title>Online Car Rent | Home</title>

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

                        <a href="category.php">
                            <font color="white">Category</font>
                        </a>

                        &nbsp;&nbsp;&nbsp;


                        <a href="blog.php">

                            <font color="white">Blog</font>

                        </a>

                        &nbsp;&nbsp;&nbsp;

                        <a href="rentalHistory.php">

                            <font color="white">Rental History</font>

                        </a>

                        &nbsp;&nbsp;&nbsp;

                        <a href="../controllers/logout.php">

                            <font color="white">Logout</font>

                        </a>

                    </td>

                </tr>

            </table>

        </td>

    </tr>

    <!-- WELCOME -->

    <tr>
        <td colspan="2" align="center">
            <h1>Welcome <?php echo $_SESSION['name']; ?></h1>
        </td>

    </tr>

    <!-- SEARCH -->
    <tr>

        <td colspan="2" align="center">

            <br>

            <b>SEARCH </b><input type="text" id="search" placeholder="Search by Car name,model or type" onkeyup="searchCars()" style="width:300px; padding:10px;">

            <br><br>

        </td>

    </tr>

    <!-- SEARCH RESULT -->

    <tr>

        <td colspan="2">

            <div id="searchResult"></div>

        </td>

    </tr>


    <!-- FEATURED CARS -->
    <tr>
        <td colspan="2" align="center">
            <h2>Featured Cars</h2>
        </td>
    </tr>

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

                            <img class="car-img"
                            src="public/uploads/<?php echo htmlspecialchars($car['image_path']); ?>"
                            alt="<?php echo htmlspecialchars($car['name']); ?>"
                            onerror="this.src='public/uploads/carImage.jpeg'" width="250" height="150">

                            <h3><?php echo $car['name']; ?></h3>

                            <p>Model : <?php echo $car['model']; ?></p>

                            <p>Type : <?php echo $car['type']; ?></p>

                            <p>Price : <?php echo $car['price_per_day']; ?> BDT/day</p>

                            <a href="orderForm.php?car_id=<?php echo $car['id']; ?>">
    <button>Rent Now</button>
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

</table>

<script>

function searchCars(){

    let keyword = document.getElementById("search").value;

    let xttp = new XMLHttpRequest();

    xttp.open('GET','../Ajax/searchCars.php?keyword='+keyword,true);

    xttp.onreadystatechange = function(){

        if(this.readyState == 4 && this.status == 200){

            let cars = JSON.parse(this.responseText);

            let output = "";

            for(let i=0; i<cars.length; i++){

                output += `

                <table border="1"
                       width="400"
                       align="center"
                       cellpadding="10"
                       style="margin-bottom:10px; background:white;">

                    <tr>

                        <td align="center">

                            <h3>${cars[i].name}</h3>

                            <p>Model : ${cars[i].model}</p>

                            <p>Type : ${cars[i].type}</p>

                            <p>Price : ${cars[i].price_per_day} BDT/day</p>

                        </td>

                    </tr>

                </table>

                `;
            }

            document.getElementById("searchResult").innerHTML = output;
        }
    }

    xttp.send();
}

</script>

</body>

</html>