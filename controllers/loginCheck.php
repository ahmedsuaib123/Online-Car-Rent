<?php

    session_start();

    require_once('../models/userModel.php');

    if(isset($_POST['login'])){

        $username = $_POST['username'];
        $password = $_POST['password'];

        if($username == "" || $password == ""){
            echo "Null username/password!";
        }

        else{
            $user = [
                'username' => $username,
                'password' => $password
            ];

            $status = login($user);

            if($status){

                $_SESSION['status'] = true;

                $_SESSION['user_id'] = $status['id'];
                $_SESSION['name'] = $status['name'];
                $_SESSION['role'] = $status['role'];
                $_SESSION['email'] = $status['email'];

                // REMEMBER ME
                if(isset($_POST['rememberMe'])){

                    $token = bin2hex(random_bytes(16));

                    setcookie('remember_token', $token, time()+3600, '/');
                    setcookie('remember_user', $status['id'], time()+3600, '/');
                }

                // ROLE 
                if($status['role'] == 'admin'){
                    header('location: ../views/adminDashboard.php');
                } else{
                    header('location: ../views/home.php');
                }
            } else{
                echo "Invalid Username or Password!";
            }
        }
    } else{
        header('location: ../views/login.php');
    }

?>