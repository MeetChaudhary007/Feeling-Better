<?php
    if(isset($_GET['email'])) {
        $email = base64_decode($_GET['email']);
    }
    if(isset($_GET['otp'])) {
        $otp = base64_decode($_GET['otp']);
    }
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Code Verification</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="forgot.css">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-4 form">
                <form action="new-password.php" method="POST" autocomplete="off">
                    <h2 class="text-center">Code Verification</h2>
                    <div class="form-group">
                        <input class="form-control" type="number" name="otp" placeholder="Enter code" required>
                    </div>
                    <input type="hidden" name="otpmain" value="<?php echo $otp; ?>">
                    <input type="hidden" name="email" value="<?php echo $email; ?>">
                    <div class="form-group">
                        <input class="form-control button" type="submit" name="reset" value="submit">
                    </div>
                </form>
            </div>
        </div>
    </div>
    
</body>
</html>