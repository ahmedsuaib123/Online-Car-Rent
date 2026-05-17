<?php
    require_once("../config/db.php");

    function login($user){
        $con = getConnection();
        $sql = "select * from users where name='{$user['username']}'";

        $result = mysqli_query($con, $sql);

        if(mysqli_num_rows($result) == 1){
            $row = mysqli_fetch_assoc($result);
            if(password_verify($user['password'], $row['password_hash'])){
                return $row;
            } else{
                return false;
            }
        } else{
            return false;
        }
    }
    
    function addUser($user){
        $con = getConnection();
        $hashPassword = $user['password'];

        $sql = "insert into users values (null,'{$user['name']}','{$user['email']}','{$hashPassword}','{$user['role']}','','{$user['address']}','{$user['phone']}',current_timestamp())";

        if(mysqli_query($con, $sql)){
            return true;
        } else{
            return false;
        }
    }

    function getAllUsers(){
        $con = getConnection();
        $sql = "select * from users";
        $result = mysqli_query($con, $sql);
        $users = [];

        while($row = mysqli_fetch_assoc($result)){
            array_push($users, $row);
        }

        return $users;
    }

    function checkEmail($email){
        $con = getConnection();
        $sql = "select * from users where email='{$email}'";

        $result = mysqli_query($con, $sql);

        if(mysqli_num_rows($result) > 0){
            return true;
        } else{
            return false;

        }
    }

    function updateUser($user){
        $con = getConnection();

        $sql = "update users set name='{$user['name']}', email='{$user['email']}',address='{$user['address']}',phone='{$user['phone']}',profile_picture='{$user['profile_picture']}' where id={$user['id']}";

        if(mysqli_query($con, $sql)){
            return true;
        } else{
            return false;
        }
    }

    function getUserById($id){
        $con = getConnection();
        $sql = "select * from users where id='{$id}'";

        $result = mysqli_query($con, $sql);

        return mysqli_fetch_assoc($result);
    }

    function changePassword($id, $newPassword){
        $con = getConnection();
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $sql = "update users set password_hash='{$hashedPassword}' where id='{$id}'";

        if(mysqli_query($con, $sql)){
            return true;
        } else{
            return false;
        }
    }

?>