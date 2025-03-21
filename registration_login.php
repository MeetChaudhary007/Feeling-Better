<?php
include 'user_session.php';
if (isLoggedIn()) {
    header('Location: ' . getServerUrl() . '/medion/dashboard/dashboard.php');
    exit();
}

include 'connect.php';
include 'glogin.php';
if (isset($_GET['error'])) {
    echo "<script>alert('{$_GET['error']}');</script>";
}
if (isset($_GET['success'])) {
    echo "<script>alert('{$_GET['success']}');</script>";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" />
    <title>User Registration and Login</title>
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap");


        .login-with-google-btn {
            transition: background-color .3s, box-shadow .3s;

            padding: 12px 16px 12px 42px;
            border: none;
            border-radius: 3px;
            box-shadow: 0 -1px 0 rgba(0, 0, 0, .04), 0 1px 1px rgba(0, 0, 0, .25);
            margin-left: 80px;

            color: #757575;
            font-size: 14px;
            font-weight: 500;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, "Fira Sans", "Droid Sans", "Helvetica Neue", sans-serif;

            background-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTgiIGhlaWdodD0iMTgiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGcgZmlsbD0ibm9uZSIgZmlsbC1ydWxlPSJldmVub2RkIj48cGF0aCBkPSJNMTcuNiA5LjJsLS4xLTEuOEg5djMuNGg0LjhDMTMuNiAxMiAxMyAxMyAxMiAxMy42djIuMmgzYTguOCA4LjggMCAwIDAgMi42LTYuNnoiIGZpbGw9IiM0Mjg1RjQiIGZpbGwtcnVsZT0ibm9uemVybyIvPjxwYXRoIGQ9Ik05IDE4YzIuNCAwIDQuNS0uOCA2LTIuMmwtMy0yLjJhNS40IDUuNCAwIDAgMS04LTIuOUgxVjEzYTkgOSAwIDAgMCA4IDV6IiBmaWxsPSIjMzRBODUzIiBmaWxsLXJ1bGU9Im5vbnplcm8iLz48cGF0aCBkPSJNNCAxMC43YTUuNCA1LjQgMCAwIDEgMC0zLjRWNUgxYTkgOSAwIDAgMCAwIDhsMy0yLjN6IiBmaWxsPSIjRkJCQzA1IiBmaWxsLXJ1bGU9Im5vbnplcm8iLz48cGF0aCBkPSJNOSAzLjZjMS4zIDAgMi41LjQgMy40IDEuM0wxNSAyLjNBOSA5IDAgMCAwIDEgNWwzIDIuNGE1LjQgNS40IDAgMCAxIDUtMy43eiIgZmlsbD0iI0VBNDMzNSIgZmlsbC1ydWxlPSJub256ZXJvIi8+PHBhdGggZD0iTTAgMGgxOHYxOEgweiIvPjwvZz48L3N2Zz4=);
            background-color: white;
            background-repeat: no-repeat;
            background-position: 12px 11px;
            cursor: pointer;

            &:hover {
                box-shadow: 0 -1px 0 rgba(0, 0, 0, .04), 0 2px 4px rgba(0, 0, 0, .25);
            }

            &:active {
                background-color: #eeeeee;
            }

            &:focus {
                outline: none;
                box-shadow:
                    0 -1px 0 rgba(0, 0, 0, .04),
                    0 2px 4px rgba(0, 0, 0, .25),
                    0 0 0 3px #c8dafc;
            }

            &:disabled {
                filter: grayscale(100%);
                background-color: #ebebeb;
                box-shadow: 0 -1px 0 rgba(0, 0, 0, .04), 0 1px 1px rgba(0, 0, 0, .25);
                cursor: not-allowed;
            }
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #40dcf4;
            /* Light blue background color */
            overflow: auto;
        }

        .container {
            position: relative;
            max-width: 400px;
            width: 100%;
            padding: 25px;
            border-radius: 8px;
            background-color: #fff;
            animation: fadeIn 0.5s ease-in-out;
            /* Add shadow effect */
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
            /* Horizontal offset, vertical offset, blur radius, color */
        }


        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .container header {
            font-size: 22px;
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
            text-align: center;
            animation: slideIn 0.5s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .container form {
            margin-top: 20px;
        }

        form .field {
            margin-bottom: 20px;
        }

        form .input-field {
            position: relative;
            height: 55px;
            width: 100%;
        }

        .input-field input,
        .input-field select {
            height: 100%;
            width: 100%;
            outline: none;
            border: none;
            border-radius: 8px;
            padding: 0 15px;
            border: 1px solid #d1d1d1;
            transition: border-color 0.3s ease;
        }

        .input-field input:focus,
        .input-field select:focus {
            border-color: #40dcf4;
            /* Light blue */
        }

        .invalid input,
        .invalid select {
            border-color: #d93025;
        }

        .input-field .show-hide {
            position: absolute;
            right: 13px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 18px;
            color: #919191;
            cursor: pointer;
            padding: 3px;
        }

        .input-field .bx-show {
            display: none;
        }

        .input-field input[type="text"]~.bx-show {
            display: block;
        }

        .field .error {
            display: flex;
            align-items: center;
            margin-top: 6px;
            color: #d93025;
            font-size: 13px;
            display: none;
        }

        .invalid .error {
            display: flex;
        }

        .error .error-icon {
            margin-right: 6px;
            font-size: 15px;
        }

        .gender-radio {
            margin-bottom: 10px;
        }

        .gender-radio label {
            margin-right: 15px;
            display: inline-flex;
            align-items: center;
            transition: color 0.3s ease;
        }

        .gender-radio input {
            margin-right: 5px;
        }

        .gender-radio input:checked+span {
            color: #40dcf4;
            /* Light blue */
        }

        .button {
            margin: 25px 0 6px;
        }

        .button input {
            color: #fff;
            font-size: 16px;
            font-weight: 400;
            background-color: #40dcf4;
            /* Light blue */
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            padding: 10px;
            border-radius: 8px;
        }

        .button input:hover {
            background-color: #1fb7e6;
            /* Slightly darker shade for hover */
        }

        .toggle-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #40dcf4;
            /* Light blue */
            cursor: pointer;
            text-decoration: underline;
        }

        .hidden {
            display: none;
        }

        /* Change the cursor for the calendar icon in the date input field */
        .input-field input[type="date"]::-webkit-calendar-picker-indicator {
            cursor: pointer;
            /* Change cursor to pointer when hovering over the calendar icon */
        }

        .forgot-password {
            text-align: center;
            /* Center align the text */
            margin-top: 20px;
            /* Add space above the link */
        }

        .forgot-password a {
            color: #40dcf4;
            /* Match the light blue color */
            text-decoration: underline;
            /* Underline the link */
            font-size: 16px;
            /* Slightly smaller font size for better readability */
        }
    </style>

</head>

<body>
    <div class="container">
        <header id="form-header">User Login</header>

        <!-- Login Form -->
        <form id="login-form" action="loginhandle.php" method="post">
            <div class="field username-field">
                <div class="input-field">
                    <input type="text" name="email" id="email" placeholder="Email" class="login-username" />
                </div>
                <span class="error email-error">
                    <i class="bx bx-error-circle error-icon"></i>
                    <p class="error-text">Please enter your email</p>
                </span>
            </div>
            <div class="field password-field">
                <div class="input-field">
                    <input type="password" id="login-password" name="password" placeholder="Password" class="login-password" />
                    <i class="bx bx-show show-hide" data-target="#login-password"></i>
                    <i class="bx bx-hide show-hide" data-target="#login-password"></i>
                </div>
                <span class="error password-error">
                    <i class="bx bx-error-circle error-icon"></i>
                    <p class="error-text">Please enter your password</p>
                </span>
            </div>
            <div class="input-field button">
                <input type="submit" value="Login" />
            </div>
            <center>or</center>
            <button type="button" onclick="redirect()" class="login-with-google-btn">
                Sign in with Google
            </button>
            <div class="forgot-password">
                <a href="forgot-password.php">Forgot Password?</a>
            </div>
            <div class="toggle-link" id="switch-to-register">Don't have an account? Register</div>
        </form>

        <!-- Registration Form -->
        <form id="registration-form" class="hidden" action="registerhandle.php" method="post">
            <div class="field username-field">
                <div class="input-field">
                    <input type="text" name="username" id="username" placeholder="Username" maxlength="14" class="username" required />
                </div>
                <span class="error username-error">
                    <i class="bx bx-error-circle error-icon"></i>
                    <p class="error-text">Please enter your username min 5 max 14</p>
                </span>
            </div>

            <div class="field email-field">
                <div class="input-field">
                    <input type="email" name="email" placeholder="Enter your email" class="email" />
                </div>
                <span class="error email-error">
                    <i class="bx bx-error-circle error-icon"></i>
                    <p class="error-text">Please enter a valid email</p>
                </span>
            </div>

            <div class="field create-password">
                <div class="input-field">
                    <input type="password" id="create-password" name="password" placeholder="Create password"
                        class="password" />
                    <i class="bx bx-show show-hide" data-target="#create-password"></i>
                    <i class="bx bx-hide show-hide" data-target="#create-password"></i>
                </div>
                <span class="error password-error">
                    <i class="bx bx-error-circle error-icon"></i>
                    <p class="error-text">Please enter a valid password</p>
                </span>
            </div>
            <div class="field confirm-password">
                <div class="input-field">
                    <input type="password" id="confirm-password" placeholder="Confirm password" class="cPassword" />
                    <i class="bx bx-show show-hide" data-target="#confirm-password"></i>
                    <i class="bx bx-hide show-hide" data-target="#confirm-password"></i>
                </div>
                <span class="error cPassword-error">
                    <i class="bx bx-error-circle error-icon"></i>
                    <p class="error-text">Passwords don't match</p>
                </span>
            </div>

            <div class="field role-field">
                <div class="input-field">
                    <select name="role" id="role" required>
                        <option value="">Select Role</option>
                        <option value="medicine_manager">Medicine Manager</option>
                        <option value="customer">Customer</option>
                    </select>
                </div>
                <span class="error role-error">
                    <i class="bx bx-error-circle error-icon"></i>
                    <p class="error-text">Please select a role</p>
                </span>
            </div>

            <div class="input-field button">
                <input type="submit" value="Submit Now" />
            </div>
            <center>or</center>
            <button type="button" onclick="redirect()" class="login-with-google-btn">
                Sign in with Google
            </button>
            <div class="toggle-link" id="switch-to-login">Already have an account? Login</div>
        </form>
    </div>

    <script src="https://unpkg.com/boxicons@2.1.2/js/boxicons.min.js"></script>
    <script>
        function redirect() {
            window.location.href = '<?= $login_url ?>';
        }
        document.addEventListener("DOMContentLoaded", () => {
            const form = document.querySelector("form"),
                registrationForm = document.getElementById("registration-form"),
                loginForm = document.getElementById("login-form"),
                switchToLogin = document.getElementById("switch-to-login"),
                switchToRegister = document.getElementById("switch-to-register");

            function toggleForms() {
                registrationForm.classList.toggle("hidden");
                loginForm.classList.toggle("hidden");
                document.getElementById("form-header").textContent = registrationForm.classList.contains("hidden") ? "User Login" : "User Registration";
            }

            switchToLogin.addEventListener("click", toggleForms);
            switchToRegister.addEventListener("click", toggleForms);

            document.querySelectorAll('.show-hide').forEach(icon => {
                icon.addEventListener('click', () => {
                    const targetInput = document.querySelector(icon.getAttribute('data-target'));
                    if (targetInput.type === 'password') {
                        targetInput.type = 'text';
                        icon.classList.replace('bx-hide', 'bx-show');
                    } else {
                        targetInput.type = 'password';
                        icon.classList.replace('bx-show', 'bx-hide');
                    }
                });
            });
            const usernameInput = registrationForm.querySelector(".username"); 
            const usernamePattern = /^[A-Za-z0-9]{5,14}$/;// Alphanumeric min 5 and max 14 characters
            const emailInput = registrationForm.querySelector(".email");
            const emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
            const passInput = registrationForm.querySelector(".password");
            const passPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
            const cPassInput = registrationForm.querySelector(".cPassword");

            // Registration Form Validation Functions
            function checkUsername() {
                const isValid = usernameInput.value.match(usernamePattern);
                usernameInput.parentElement.parentElement.classList.toggle("invalid", !isValid);
                return isValid;
            }

            function checkEmail() {
                const isValid = emailInput.value.match(emailPattern);
                emailInput.parentElement.parentElement.classList.toggle("invalid", !isValid);
                return isValid;
            }

            function createPass() {
                const isValid = passInput.value.match(passPattern);
                passInput.parentElement.parentElement.classList.toggle("invalid", !isValid);
                return isValid;
            }

            function confirmPass() {
                const isValid = passInput.value === cPassInput.value && cPassInput.value !== "";
                cPassInput.parentElement.parentElement.classList.toggle("invalid", !isValid);
                return isValid;
            }

            registrationForm.addEventListener("submit", (e) => {
                const isUsernameValid = checkUsername(); 
                const isEmailValid = checkEmail();
                const isPassValid = createPass();
                const isConfirmPassValid = confirmPass();

                if (!(isUsernameValid && isEmailValid && isPassValid && isConfirmPassValid)) {
                    e.preventDefault();
                    console.log('Registration form is invalid, please fix errors.');
                } else {
                    console.log('Registration form is valid, submitting...');
                    // registrationForm.submit(); // Uncomment to submit programmatically
                }
            });


            // Add event listener for input fields
            usernameInput.addEventListener("keyup", checkUsername);
            emailInput.addEventListener("keyup", checkEmail);
            passInput.addEventListener("keyup", createPass);
            cPassInput.addEventListener("keyup", confirmPass);

            function checkLoginUsername() {
                const loginUsernameInput = loginForm.querySelector(".login-username");
                const isValid = loginUsernameInput.value.trim() !== "";
                loginUsernameInput.parentElement.parentElement.classList.toggle("invalid", !isValid);
                return isValid;
            }

            function checkLoginPassword() {
                const loginPasswordInput = loginForm.querySelector(".login-password");
                const isValid = loginPasswordInput.value.trim() !== "";
                loginPasswordInput.parentElement.parentElement.classList.toggle("invalid", !isValid);
                return isValid;
            }

            loginForm.addEventListener("submit", (e) => {
                const isLoginUsernameValid = checkLoginUsername();
                const isLoginPasswordValid = checkLoginPassword();

                if (!(isLoginUsernameValid && isLoginPasswordValid)) {
                    e.preventDefault();
                    console.log('Login form is invalid, please fix errors.');
                } else {
                    // Simulate login form submission
                    console.log('Login form is valid, submitting...');
                }
            });

            loginForm.querySelector(".login-username").addEventListener("keyup", checkLoginUsername);
            loginForm.querySelector(".login-password").addEventListener("keyup", checkLoginPassword);
        });
    </script>
</body>

</html>