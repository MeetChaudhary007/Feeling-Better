<?php
if ($_GET['message'] === 'unset') {
    session_start();
    $_SESSION['logged_in'] = false;
    unset($_SESSION['user_id']);
    unset($_SESSION['role']);
    unset($_SESSION['email']);
    unset($_SESSION['username']);
    // session_destroy();
    header('Location:registration_login.php');
    die();
}