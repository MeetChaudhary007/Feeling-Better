<?php
include 'connect.php';
include 'user_session.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if username already exists
    $checkUsernameQuery = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($checkUsernameQuery);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $usernameResult = $stmt->get_result();

    if ($usernameResult->num_rows > 0) {
        // Username already exists
        header('Location: registration_login.php?error=Username already taken. Please choose another.');
        exit();
    }

    // Check if email already exists
    $checkEmailQuery = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($checkEmailQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $emailResult = $stmt->get_result();

    if ($emailResult->num_rows > 0) {
        // Email already exists
        header('Location: registration_login.php?error=Email already in use. Please use a different one.');
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Prepare the SQL statement for inserting the new user
    $insertQuery = "INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, ?)";
    $role = $_POST['role'];

    if ($insert_stmt = $conn->prepare($insertQuery)) {
        // Bind parameters
        $insert_stmt->bind_param("ssss", $username, $email, $hashed_password, $role);

        // Execute the statement
        if ($insert_stmt->execute()) {
            // Get the user_id of the newly inserted user
            $user_id = $conn->insert_id;

            // Registration successful, log the user in
            loginUser($user_id, $username, $role, $email);
        } else {
            // Registration failed, handle error
            header('Location: registration_login.php?error=Error during registration: ' . $conn->error);
            exit();
        }
    } else {
        // Prepare failed
        header('Location: registration_login.php?error=Failed to prepare statement.');
        exit();
    }
}