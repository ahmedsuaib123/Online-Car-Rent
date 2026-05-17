<html>

<head>
    <title>Online Car Rent | Registration</title>
</head>

<body>

    <table border="2" width="100%" height="100%" bgcolor="#f2f2f2">

        <tr>
            <td colspan="2" align="center">
                <h2>Online Car Rent</h2>
            </td>
        </tr>

        <tr height="740px">

            <td colspan="2" align="center">

                <form action="../controllers/registrationCheck.php" method="post"  onsubmit="return validateForm()">

                    <fieldset style="width:450px" padding="20px">

                        <legend align="center">Registration</legend> 

                        <br>

                        <table align="center" cellpadding="10">

                            <tr>
                                <td>Name</td>
                                <td>
                                    <input type="text" name="name" id="name" placeholder="Enter your name"  required>
                                </td>
                            </tr>

                            <tr>
                                <td>Email</td>
                                <td>
                                    <input type="email" name="email" id="email" placeholder="Enter your email" required>
                                </td>
                            </tr>

                            <tr>
                                <td>Password</td>
                                <td>
                                    <input type="password" name="password" id="password" minlength="8" required>
                                </td>
                            </tr>

                            <tr>
                                <td>Confirm Password</td>
                                <td>
                                    <input type="password" name="confirmPassword" id="confirmPassword" required>
                                </td>
                            </tr>

                            <tr>
                                <td>Address</td>
                                <td>
                                    <input type="text" name="address" id="address" placeholder="Enter your address">
                                </td>
                            </tr>

                            <tr>
                                <td>Phone</td>
                                <td>
                                    <input type="tel" name="phone" id="phone" placeholder="Enter your phone">
                                </td>
                            </tr>

                            <tr>
                                <td>Role</td>
                                <td>
                                    <select name="role" id="role">
                                        <option value="">Select Role</option>
                                        <option value="admin">Admin</option>
                                        <option value="member">Member</option>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="2">
                                    <hr>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="2" align="center">

                                    <input type="submit" name="register" value="Register">
                                    <input type="reset" value="Reset">

                                </td>
                            </tr>

                            <tr>
                                <td colspan="2" align="center">
                                    <br>
                                    <input type="button" value="Login" onclick="window.location.href='login.php'">

                                </td>
                            </tr>

                        </table>

                    </fieldset>

                </form>

            </td>

        </tr>

    </table>

<script>

function validateForm(){

    let name = document.getElementById("name").value;
    let email = document.getElementById("email").value;
    let password = document.getElementById("password").value;
    let confirmPassword = document.getElementById("confirmPassword").value;
    let role = document.getElementById("role").value;

    if(name == "" || email == "" || password == "" || confirmPassword == "" || role == ""){

        alert("All required fields must be filled!");
        return false;
    }

    if(password.length < 8){

        alert("Password must be at least 8 characters!");
        return false;
    }

    if(password != confirmPassword){

        alert("Password & Confirm Password do not match!");
        return false;
    }

    return true;
}

</script>

</body>

</html>