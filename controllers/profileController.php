<?php

    session_start();

    require_once('../models/userModel.php');

    // SESSION CHECK

    if(!isset($_SESSION['status'])){

        header('location: ../views/login.php');
    }

    // UPDATE PROFILE

    if(isset($_POST['updateProfile'])){

        $id = $_SESSION['user_id'];

        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $address = trim($_POST['address']);
        $phone = trim($_POST['phone']);

        // CURRENT USER

        $user = getUserById($id);

        // DEFAULT OLD IMAGE

        $profilePicture = $user['profile_picture'];

        // IMAGE UPLOAD

        if(isset($_FILES['profile_picture']) && $_FILES['profile_picture']['name'] != ""){

            // VALIDATION

            $mime = mime_content_type($_FILES['profile_picture']['tmp_name']);

            $allowed = [

                'image/jpeg',
                'image/jpg',
                'image/png'
            ];

            if(in_array($mime, $allowed)){

                if($_FILES['profile_picture']['size'] <= 2 * 1024 * 1024){

                    // YOUR FORMAT

                    
                    $src = $_FILES['profile_picture']['tmp_name'];

                    $ext = explode('.', $_FILES['profile_picture']['name']);

                    $index = count($ext);

                    $newName = time().".".$ext[$index-1];

                    $des = '../views/public/uploads/'.$newName;

                    // DATABASE PATH

                    $profilePicture = 'views/public/uploads/'.$newName;

                    // MOVE FILE

                    if(move_uploaded_file($src, $des)){

                        // SUCCESS

                    } else{

                        die("Image Upload Failed!");
                    }

                } else{

                    die("File size must be under 2MB!");
                }

            } else{

                die("Only JPG, JPEG, PNG allowed!");
            }
        }

        // UPDATE USER

        $updatedUser = [

            'id' => $id,
            'name' => $name,
            'email' => $email,
            'address' => $address,
            'phone' => $phone,
            'profile_picture' => $profilePicture
        ];

        updateUser($updatedUser);

        // PASSWORD CHANGE

        $currentPassword = $_POST['currentPassword'];

        $newPassword = $_POST['newPassword'];

        $confirmPassword = $_POST['confirmPassword'];

        if($currentPassword != "" || $newPassword != "" || $confirmPassword != ""){

            if(password_verify($currentPassword, $user['password_hash'])){

                if($newPassword == $confirmPassword){

                    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                    changePassword($id, $hashedPassword);

                } else{

                    die("New Password & Confirm Password mismatch!");
                }

            } else{

                die("Current Password Incorrect!");
            }
        }

        // UPDATE SESSION

        $_SESSION['name'] = $name;

        $_SESSION['email'] = $email;

        // REDIRECT

        header('location: ../views/profile.php?success=1');

    } else{

        header('location: ../views/profile.php');
    }

?>