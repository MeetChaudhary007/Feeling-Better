<?php
    // include 'connect.php';
    // $pass = $_POST['password'];
    // $email = $_POST['email'];

        
    //  $sql = "update users set password = '$pass' where email = '$email';";
    //         if (mysqli_query($conn, $sql)) {
    //           header("Location: registration_login.php?success=Password Reset Successfully");
    //           } 
        
    //     else
    //     {
    //         echo "<script>alert('error updating password');
    //         window.location=document.referrer;
    //         </script>";
    //     }
?>






<?php
include 'connect.php';

$email = $_POST['email'];
$pass = $_POST['password'];

// Hash the new password
$hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

// Use prepared statements to avoid SQL injection
$stmt = $conn->prepare("UPDATE users SET password_hash = ? WHERE email = ?");
$stmt->bind_param("ss", $hashed_pass, $email);

if ($stmt->execute()) {
    header("Location: registration_login.php?success=Password Reset Successfully");
} else {
    echo "<script>alert('Error updating password: " . $stmt->error . "'); window.location=document.referrer;</script>";
}

$stmt->close();
$conn->close();
?>
