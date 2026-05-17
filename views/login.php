<?php

    session_start();

    require_once('../models/userModel.php');

  
    if(!isset($_SESSION['status']) && isset($_COOKIE['remember_user'])){

        $users = getAllUsers();

        foreach($users as $u){

            if($u['id'] == $_COOKIE['remember_user']){

                $_SESSION['status'] = true;
                $_SESSION['user_id'] = $u['id'];
                $_SESSION['username'] = $u['name'];
                $_SESSION['role'] = $u['role'];
                $_SESSION['email'] = $u['email'];

                header('location: home.php');
            }

        }
    }

?>

<html>
<head>
    <title>Online Car Rent | Login</title>
</head>

<body>

    <table border="2" width="100%" height="100%" bgcolor="#f2f2f2">

        <tr>
            <td colspan="2" align="center">
                <h2 align="center">Online Car Rent</h2>
            </td>
        </tr>
       
        <tr height="750px" >
            
            <td colspan="2" align="center" >

                <form action="../controllers/loginCheck.php" method="post" width="500px" >

                    <fieldset style="width:300px ">
                        <legend align="center">Welcome Back</legend>
                        <br><br>

                        Username <input type="text" name="username" id="username" value="">
                        <br><br><br>

                        Password <input type="password" name="password" id="password"value="">
                        <br><br>

                        <input type="checkbox" name="rememberMe" id="rememberMe"> Remember Me
                        
                        <hr>

                        <br>

                        <input type="submit" name="login" value="Submit">

                        <input type="button" value="Register" onclick="window.location.href='registration.php'">
                        <br><br>


                    </fieldset>

                </form>

            </td>
        </tr>

        <!-- <tr>
            <td colspan="2" align="center">
                Copyright &copy; 2026
            </td>
        </tr> -->

    </table>

</body>
</html>