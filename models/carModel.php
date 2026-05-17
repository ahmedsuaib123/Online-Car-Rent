<<<<<<< HEAD

<?php

    require_once("../config/db.php");

    // GET FEATURED CARS 

    function getFeaturedCars(){

         $con = getConnection();

         $sql = "select * from cars order by rand() limit 6";

         $result = mysqli_query($con, $sql);

         $cars = [];

         while($row = mysqli_fetch_assoc($result)){

            array_push($cars, $row);
        }

        return $cars;
     }

    //  GET DISTINCT CATEGORIES 

     function getCategories(){

         $con = getConnection();

         $sql = "select distinct type from cars";

         $result = mysqli_query($con, $sql);

        $categories = [];

        while($row = mysqli_fetch_assoc($result)){

            array_push($categories, $row);
        }

        return $categories;
     }

     //  GET CARS BY CATEGORY

     function getCarsByCategory($type){

        $con = getConnection();

         $sql = "select * from cars where type='{$type}'";

        $result = mysqli_query($con, $sql);

        $cars = [];

        while($row = mysqli_fetch_assoc($result)){

            array_push($cars, $row);
         }

         return $cars;
     }

     function searchCars($keyword){

        $con = getConnection();

         $keyword = mysqli_real_escape_string($con, $keyword);

        $sql = "select * from cars where name like '%{$keyword}%' or model like '%{$keyword}%' or type like '%{$keyword}%'";

       $result = mysqli_query($con, $sql);

        $cars = [];

        while($row = mysqli_fetch_assoc($result)){
             array_push($cars, $row);
         }

         return $cars;
     }


    //   GET ALL CARS 

function getAllCars(){

    $con = getConnection();

    $sql = "SELECT * FROM cars ORDER BY id ASC";

    $result = mysqli_query($con, $sql);

    $cars = [];

    while($row = mysqli_fetch_assoc($result)){

        array_push($cars, $row);
    }

    return $cars;
}



    //  GET CAR BY ID 
function getCarById($id) {

    $con = getConnection();

    $sql = "SELECT * FROM cars WHERE id = ?";

    $stmt = mysqli_prepare($con, $sql);

    mysqli_stmt_bind_param($stmt, "i", $id);

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    return mysqli_fetch_assoc($result);
}

//  ADD CAR
function addCar($name, $model, $type, $price, $status, $image_path, $description) {

    $con = getConnection();

    $sql = "INSERT INTO cars (name, model, type, price_per_day, availability_status, image_path, description)

   VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($con, $sql);

    mysqli_stmt_bind_param($stmt, "sssdsss", $name, $model, $type, $price, $status, $image_path, $description);
    
    return mysqli_stmt_execute($stmt);
}

//  UPDATE CAR
function updateCar($id, $name, $model, $type, $price, $status, $image_path, $description) {

    $con = getConnection();

    $sql = "UPDATE cars SET name=?, model=?, type=?, price_per_day=?, availability_status=?, image_path=?, description=? WHERE id=?";

    $stmt = mysqli_prepare($con, $sql);

    mysqli_stmt_bind_param($stmt, "sssdsssi", $name, $model, $type, $price, $status, $image_path, $description, $id);
    
    return mysqli_stmt_execute($stmt);
}

//  DELETE CAR
function deleteCar($id) {

    $con = getConnection();

    $sql = "DELETE FROM cars WHERE id = ?";

    $stmt = mysqli_prepare($con, $sql);

    mysqli_stmt_bind_param($stmt, "i", $id);
    
    return mysqli_stmt_execute($stmt);
}

//  HAS ACTIVE ORDERS
function hasActiveOrders($car_id) {

    $con = getConnection();

    $sql = "SELECT COUNT(*) AS total FROM orders WHERE car_id = ? AND status = 'pending'";

    $stmt = mysqli_prepare($con, $sql);

    mysqli_stmt_bind_param($stmt, "i", $car_id);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $row = mysqli_fetch_assoc($result);
    
    return $row['total'] > 0;
}

//  GET TOTAL CARS
function getTotalCars() {

    $con = getConnection();

    $sql = "SELECT COUNT(*) AS total FROM cars";

    $result = mysqli_query($con, $sql);

    $row = mysqli_fetch_assoc($result);
    
    return $row['total'];
}

//  DELETE MEMBER BY ID
function deleteMemberById($id) {

    $con = getConnection();

    $sql = "DELETE FROM users WHERE id = ? AND role = 'member'";

    $stmt = mysqli_prepare($con, $sql);

    mysqli_stmt_bind_param($stmt, "i", $id);
    
    return mysqli_stmt_execute($stmt);
}





=======
<?php
    require_once("../config/db.php");

    // GET FEATURED CARS 
    function getFeaturedCars(){
        $con = getConnection();
        $sql = "select * from cars order by rand() limit 6";

        $result = mysqli_query($con, $sql);
        $cars = [];

        while($row = mysqli_fetch_assoc($result)){
            array_push($cars, $row);
        }
        return $cars;
    }

    // GET DISTINCT CATEGORIES 
    function getCategories(){
        $con = getConnection();
        $sql = "select distinct type from cars";
        $result = mysqli_query($con, $sql);
        $categories = [];

        while($row = mysqli_fetch_assoc($result)){
            array_push($categories, $row);
        }
        return $categories;
    }

    // GET CARS BY CATEGORY 
    function getCarsByCategory($type){
        $con = getConnection();
        $sql = "select * from cars where type='{$type}'";
        $result = mysqli_query($con, $sql);
        $cars = [];

        while($row = mysqli_fetch_assoc($result)){
            array_push($cars, $row);
        }
        return $cars;
    }

    // function searchCars($keyword){
    //     $con = getConnection();
    //     $keyword = mysqli_real_escape_string($con, $keyword);
    //     $sql = "select * from cars where name like '%{$keyword}%' or model like '%{$keyword}%' or type like '%{$keyword}%'";
    //     $result = mysqli_query($con, $sql);

    //     $cars = [];

    //     while($row = mysqli_fetch_assoc($result)){
    //          array_push($cars, $row);
    //     }
        
    //     return $cars;
    //  }
>>>>>>> 09625203a78d1a5acc6cd3e263b7ecb718411128
?>





