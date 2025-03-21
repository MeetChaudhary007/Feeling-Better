<?php
require'./gconfig.php';
# the createAuthUrl() method generates the login URL.
$login_url = $client->createAuthUrl();
/* 
 * After obtaining permission from the user,
 * Google will redirect to the login.php with the "code" query parameter.
*/
if (isset($_GET['code'])):

  session_start();
  $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
  if(isset($token['error'])){
    header('Location: newlogin.php');
    exit;
  }
  $_SESSION['token'] = $token;

  /* -- Inserting the user data into the database -- */

  # Fetching the user data from the google account
  $client->setAccessToken($token);
  $google_oauth = new Google_Service_Oauth2($client);
  $user_info = $google_oauth->userinfo->get();

  $google_id = trim($user_info->getId());
  $f_name = trim($user_info->getGivenName());
  $email = trim($user_info->getEmail());
  $picture = trim($user_info->getPicture());

  # Database connection
  require('connect.php');

  # Checking whether the email already exists in our database.
  $check_email = $conn->prepare("SELECT `email` FROM `users` WHERE `email`=?");
  $check_email->bind_param("s", $email);
  $check_email->execute();
  $check_email->store_result();

  if($check_email->num_rows === 0){
    # Inserting the new user into the database
    $date = date('Y-m-d');
    $role = "Google";
    $query_template = "INSERT INTO `users` (`username`, `password_hash`, `email`, `role`, `created_at`, `profile_image`) VALUES (?,?,?,?,?,?)";
    $insert_stmt = $conn->prepare($query_template);
    $insert_stmt->bind_param("ssssss", $f_name, $google_id, $email, $role, $date, $picture);
    if(!$insert_stmt->execute()){
      echo "Failed to insert user.";
      exit;
    }
  }

  // Fetch user role
  $query = "SELECT * FROM users WHERE email=?";
  $select_stmt = $conn->prepare($query);
  $select_stmt->bind_param("s", $email);
  $select_stmt->execute();

  // Fetch result
  $result = $select_stmt->get_result();

  if ($result->num_rows == 1) {
    // Fetch and display the table data
    $row = $result->fetch_assoc();
    $id = $row["user_id"]; 
    $username = $row["username"];
    $role = 'Google';
    $_SESSION["logged_in"] = true;
    $_SESSION['user_id'] = $id;
    $_SESSION['username'] = $username;
    $_SESSION['role'] = $role;

    // Redirect to the dashboard page
    header("Location: index.php");
    exit;
  }

endif;  
?>
