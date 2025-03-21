<?php
session_start();
// $_SESSION['logged_in'] = false;
// Function to check if a user is logged in
function isLoggedIn()
{
    return ( isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true);
}

// Function to redirect to the login page if not logged in
function checkSession()
{
    if (!isLoggedIn()) {
        // Use relative path to index.php
        header('Location: ' . getServerUrl() . '/medion/index.php');
        exit();
    }
}

// Function to log in a user (usually after validating credentials)
function loginUser($user_id, $username, $role, $email)
{
    $_SESSION['user_id'] = $user_id;
    $_SESSION['logged_in'] = true;
    $_SESSION['username'] = $username;
    $_SESSION['role'] = $role;
    $_SESSION['email'] = $email;
    // Use relative path to dashboard.php
    header('Location: ' . getServerUrl() . '/medion/dashboard/dashboard.php');
    exit();
}

// Function to log out a user
function logoutUser()
{
    session_unset();
    session_destroy();
    // Use relative path to index.php
    header('Location: ' . getServerUrl() . '/medion/index.php');
    exit();
}

// Helper function to get the server URL dynamically
function getServerUrl()
{
    // Get the protocol (HTTP or HTTPS)
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

    // Get the hostname (e.g., localhost or domain name)
    $host = $_SERVER['HTTP_HOST'];

    // Combine protocol and host to create the base server URL
    return $protocol . $host;
}
