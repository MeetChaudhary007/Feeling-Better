<?php
include 'connect.php';
include 'mail.php';
$email = $_POST["email"];
$role;

$sqle = "SELECT * FROM users WHERE email = '$email';"; // check email is exist or not
mysqli_query($conn,$sqle);
$result = $conn->query($sqle);

// Check if any rows are returned
if ($result->num_rows >= 1) {
    $otp = random_int(100000, 999999);
    sendotp($email,$otp);
}
else
{
    echo "<script>alert('email not found');
    window.location=document.referrer;
    </script>";
    exit();
}