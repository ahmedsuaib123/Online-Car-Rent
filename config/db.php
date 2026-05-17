<?php
    $host='127.0.0.1';
    $dbuser='root';
    $dbpass='';
    $dbname='car_rental_system';

    function getConnection(){
        global $host;
        global $dbuser;
        global $dbpass;
        global $dbname;

        $con=mysqli_connect($host,$dbuser,$dbpass,$dbname);

        return $con;
    }
 ?>