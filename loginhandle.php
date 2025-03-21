<?php
include 'connect.php';
include 'user_session.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare the SQL statement to fetch user data by email
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    // Fetch result
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        session_start();
        $row = $result->fetch_assoc();

        // Verify the hashed password
        if (password_verify($password, $row['password_hash'])) {
            // Password is correct, set session variables
            $role = $row["role"];
            $username = $row['username'];
            $user_id = $row['user_id'];
            loginUser($user_id, $username, $role, $email);
        } else {
            // Password is incorrect
            header('Location: registration_login.php?error=Invalid Email ID or Password');
            exit();
        }
    } else {
        // No user found with that email
        header('Location: registration_login.php?error=Invalid Email ID or Password');
        exit();
    }
}