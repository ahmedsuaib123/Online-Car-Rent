<?php

    session_start();

    require_once('../models/userModel.php');

    // SESSION CHECK

    if(!isset($_SESSION['status'])){

        header('location: login.php');
    }

    // FETCH USER

    $user = getUserById($_SESSION['user_id']);

?>

<html>

<head>

    <title>Profile</title>

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

                        <?php

                            if($_SESSION['role'] == 'admin'){

                        ?>

                            <a href="adminDashboard.php">

                                <font color="white">Admin Dashboard</font>

                            </a>

                        <?php

                            } else{

                        ?>

                            <a href="home.php">

                                <font color="white">Home</font>

                            </a>

                        <?php

                            }

                        ?>
                        &nbsp;&nbsp;&nbsp;

                        <a href="profile.php">

                            <font color="white">Profile</font>

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

    <!-- SUCCESS MESSAGE -->

    <tr>

        <td colspan="2" align="center">

            <?php

                if(isset($_GET['success'])){

                    echo "<h3 style='color:green'>Profile Updated Successfully</h3>";
                }

            ?>

        </td>

    </tr>

    <!-- PROFILE FORM -->

    <tr>

        <td align="center">

            <form action="../controllers/profileController.php" 
                  method="post"
                  enctype="multipart/form-data"
                  onsubmit="return validateForm()">

                <fieldset style="width:500px">

                    <legend align="center">Profile</legend>

                    <br>

                    <!-- PROFILE PICTURE -->

                    <?php

                        $imagePath = "public/uploads/default.png";

                        if($user['profile_picture'] != ""){

                            $imagePath = "../".$user['profile_picture'];
                        }

                    ?>

                    <img src="<?php echo $imagePath; ?>" 
                         width="120"
                         height="120"
                         style="border-radius:50%; border:2px solid black; object-fit:cover;">

                    <br><br>

                    Upload Picture :
                    <input type="file" name="profile_picture">

                    <br><br>

                    Name :
                    <input type="text"
                           name="name"
                           id="name"
                           value="<?php echo $user['name']; ?>">

                    <br><br>

                    Email :
                    <input type="email"
                           name="email"
                           id="email"
                           value="<?php echo $user['email']; ?>">

                    <br><br>

                    Address :
                    <input type="text"
                           name="address"
                           id="address"
                           value="<?php echo $user['address']; ?>">

                    <br><br>

                    Phone :
                    <input type="text"
                           name="phone"
                           id="phone"
                           value="<?php echo $user['phone']; ?>">

                    <br><br>

                    <hr>

                    <h3>Change Password</h3>

                    Current Password :
                    <input type="password"
                           name="currentPassword"
                           id="currentPassword">

                    <br><br>

                    New Password :
                    <input type="password"
                           name="newPassword"
                           id="newPassword">

                    <br><br>

                    Confirm Password :
                    <input type="password"
                           name="confirmPassword"
                           id="confirmPassword">

                    <br><br>

                    <input type="submit"
                           name="updateProfile"
                           value="Update Profile">

                </fieldset>

            </form>

        </td>

    </tr>

</table>

<script>

function validateForm(){
    let name = document.getElementById("name").value;
    let email = document.getElementById("email").value;

    if(name == "" || email == ""){

        alert("Name & Email required!");
        return false;
    }

    let newPassword = document.getElementById("newPassword").value;
    let confirmPassword = document.getElementById("confirmPassword").value;

    if(newPassword != "" || confirmPassword != ""){

        if(newPassword.length < 8){

            alert("New Password must be at least 8 characters!");
            return false;
        }

        if(newPassword != confirmPassword){

            alert("Password does not match!");
            return false;
        }
    }
    return true;
}

</script>

</body>

</html>