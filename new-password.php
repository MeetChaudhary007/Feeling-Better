<?php
include 'connect.php';

$otpmain = $_POST['otpmain'];
$otp = $_POST['otp'];
$email = $_POST['email'];

if($otp != $otpmain)
{
    echo "<script>alert('OTP does not match');
        window.location=document.referrer;
        </script>";
} 

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create a New Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="forgot.css">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-4 form">
                <form action="new-pass-handle.php" onsubmit="return validatepass()" method="post">
                    <h2 class="text-center">New Password</h2>
                    <div class="form-group">
                        <input class="form-control" id="pass" type="password" name="password" placeholder="Create new password" required>
                        <span id="passwordErr" class="error"></span>
                    </div>
                    <div class="form-group">
                        <input class="form-control" id="pass2" type="password" name="cpassword" placeholder="Confirm your password" required>
                        <span id="password2Err" class="error"></span>
                        </div>
                    <input type="hidden" name="email" value="<?php echo $email; ?>">
                    <div class="form-group">
                        <input class="form-control button" type="submit" name="changep" value="change">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        
        function validatepass(){
    
        var password = document.getElementById("pass").value;
        var password2 = document.getElementById("pass2").value;
        var isValid = true;
    
        var passwordErr = document.getElementById("passwordErr");
        var password2Err = document.getElementById("password2Err");
    
    // Password validation
    if (password.trim() === "") {
        passwordErr.innerHTML = "* Password is required";
        isValid = false;
    } else if (password.length < 8 || password.length > 15) {
        passwordErr.innerHTML = "Password must be between 8 and 15 characters long";
        isValid = false;
    } else if (!/[a-z]/.test(password)) {
        passwordErr.innerHTML = "Password must contain at least one lowercase letter";
        isValid = false;
    } else if (!/[A-Z]/.test(password)) {
        passwordErr.innerHTML = "Password must contain at least one uppercase letter";
        isValid = false;
    } else if (!/\d/.test(password)) {
        passwordErr.innerHTML = "Password must contain at least one number";
        isValid = false;
    } else if (!/[!@#$%^&*]/.test(password)) {
        passwordErr.innerHTML = "Password must contain at least one special character";
        isValid = false;
    } else {
        passwordErr.innerHTML = "";
    }
    
    //password2
    if (password2.trim() === "") {
        password2Err.innerHTML = "* Password is required";
        isValid = false;
    } else if (password2.length < 8 || password.length > 15) {
        password2Err.innerHTML = "Password must be between 8 and 15 characters long";
        isValid = false;
    } else if (!/[a-z]/.test(password2)) {
        password2Err.innerHTML = "Password must contain at least one lowercase letter";
        isValid = false;
    } else if (!/[A-Z]/.test(password2)) {
        password2Err.innerHTML = "Password must contain at least one uppercase letter";
        isValid = false;
    } else if (!/\d/.test(password2)) {
        password2Err.innerHTML = "Password must contain at least one number";
        isValid = false;
    } else if (!/[!@#$%^&*]/.test(password2)) {
        password2Err.innerHTML = "Password must contain at least one special character";
        isValid = false;
    } else {
        password2Err.innerHTML = "";
    }
    if(password != password2)
    {
        passwordErr.innerHTML = "both password must be same";
        isValid = false;
    }
        return isValid;
    
    }
        </script>
</body>
</html>