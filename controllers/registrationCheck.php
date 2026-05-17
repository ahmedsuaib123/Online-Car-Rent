<?php
    require_once('../models/userModel.php');

    if(isset($_POST['register'])){

        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirmPassword'];
        $address = $_POST['address'];
        $phone = $_POST['phone'];
        $role = $_POST['role'];

        if($name=="" || $email=="" || $password=="" || $confirmPassword=="" || $address=="" || $phone=="" || $role==""){
            echo "Null data found!";
        }

        else if(strlen($password) < 8){
            echo "Password must be at least 8 characters!";

        }

        else if($password != $confirmPassword){
            echo "Password & Confirm Password do not match!";
        }

        else if(checkEmail($email)){
            echo "Email already exists!";
        } else{
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $user = [
                'name' => $name,
                'email' => $email,
                'password' => $hashedPassword,
                'role' => $role,
                'address' => $address,
                'phone' => $phone
            ];

            $status = addUser($user);

            if($status){
                header('location: ../views/login.php');
            } else {
                echo "Database Error!";
            }
        }
    } else {
        header('location: ../views/registration.php');
    }
?>