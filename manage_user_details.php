<?php
include 'connect.php';
include 'user_session.php';
checkSession();

$response = [];

// Determine which action to perform
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['profile_image'])) {
        $user_id = $_SESSION['user_id'];
        $target_dir = "images/profile/";
        $target_file = $target_dir . basename($_FILES["profile_image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if the image file is a real image
        $check = getimagesize($_FILES["profile_image"]["tmp_name"]);
        if ($check === false) {
            $response['success'] = false;
            $response['error'] = "File is not an image.";
            $uploadOk = 0;
        }

        // Check file size (e.g., limit to 5MB)
        if ($_FILES["profile_image"]["size"] > 5000000) {
            $response['success'] = false;
            $response['error'] = "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
            $response['success'] = false;
            $response['error'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            $response['success'] = false;
            $response['error'] = "Sorry, your file was not uploaded.";
        } else {
            // Fetch the current profile image from the database to delete it
            $fetchQuery = "SELECT profile_image FROM users WHERE user_id = ?";
            $stmt = $conn->prepare($fetchQuery);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            $oldProfileImage = $user['profile_image'];

            // Check if an old profile image exists and delete it
            if ($oldProfileImage && file_exists($oldProfileImage)) {
                unlink($oldProfileImage); // Delete the old image from the server
            }

            // Try to upload the new file
            if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
                // Update the user's profile image path in the database
                $updateQuery = "UPDATE users SET profile_image = ? WHERE user_id = ?";
                $stmt = $conn->prepare($updateQuery);
                $stmt->bind_param("si", $target_file, $user_id);
                if ($stmt->execute()) {
                    $response['success'] = true;
                    $response['imagePath'] = $target_file; // Return the new image path
                } else {
                    $response['success'] = false;
                    $response['error'] = "Error updating profile image: " . $conn->error;
                }
            } else {
                $response['success'] = false;
                $response['error'] = "Sorry, there was an error uploading your file.";
            }
        }
    } elseif (isset($_POST['userName'])) {
        // Handle user details update
        $user_id = $_SESSION['user_id'];
        $role = $_SESSION['role']; // Get user role (e.g., customer or medicine_manager)
        $username = $_POST['userName'];
        $gender = $_POST['gender'] ?? null;
        $email = $_POST['email'];

        // Common fields for both tables
        $firstName = $_POST['firstName'] ?? null;
        $lastName = $_POST['lastName'] ?? null;

        // Additional field for customers
        $address = $_POST['address'] ?? null;

        // Common field for both
        $phone = $_POST['phone'] ?? null;

        // Start a transaction
        $conn->begin_transaction();

        try {
            // Update the users table
            if ($role == 'admin') {
                $updateUserQuery = "UPDATE users SET username = ?, email = ? WHERE user_id = ?";
                $stmt = $conn->prepare($updateUserQuery);
                $stmt->bind_param("ssi", $username, $email, $user_id);
                if (!$stmt->execute()) {
                    throw new Exception("Error updating users table: " . $conn->error);
                }
            }
            // Update the customers or medicine_manager table based on role
            elseif ($role == 'customer') {
                $updateCustomerQuery = "UPDATE customers SET first_name = ?, last_name = ?, address = ?, contact = ?, gender = ? WHERE customer_id = ?";
                $stmt = $conn->prepare($updateCustomerQuery);
                $stmt->bind_param("sssssi", $firstName, $lastName, $address, $phone, $gender, $user_id);
                if (!$stmt->execute()) {
                    throw new Exception("Error updating customers table: " . $conn->error);
                }
            } elseif ($role == 'medicine_manager') {
                $updateManagerQuery = "UPDATE medicine_manager SET first_name = ?, last_name = ?, contact = ?, gender = ? WHERE manager_id = ?";
                $stmt = $conn->prepare($updateManagerQuery);
                $stmt->bind_param("ssssi", $firstName, $lastName, $phone, $gender, $user_id);
                if (!$stmt->execute()) {
                    throw new Exception("Error updating medicine_manager table: " . $conn->error);
                }
            }

            // Commit the transaction
            $conn->commit();

            // Update session details if necessary
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;

            $response['success'] = true;
        } catch (Exception $e) {
            // Rollback the transaction on error
            $conn->rollback();
            $response['success'] = false;
            $response['error'] = $e->getMessage();
        }
    } else {
        $response['success'] = false;
        $response['error'] = "Invalid request method.";
    }
}

header('Content-Type: application/json');
echo json_encode($response);