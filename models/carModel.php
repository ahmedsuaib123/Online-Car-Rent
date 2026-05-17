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
?>





