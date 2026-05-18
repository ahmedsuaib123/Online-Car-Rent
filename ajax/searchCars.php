<?php

    header('Content-Type: application/json');

    require_once('../models/carModel.php');

    if(isset($_GET['keyword'])){

        $keyword = trim($_GET['keyword']);

        $cars = searchCars($keyword);

        echo json_encode($cars);

    } else{

        echo json_encode([]);
    }

?>