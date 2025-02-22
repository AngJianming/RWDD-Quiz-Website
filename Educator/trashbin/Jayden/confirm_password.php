<?php
    include("Combine-educator.php");
    include("Bg-Animation.php");
    ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="confirm_password.css">
</head>
<body>
    <!-- Pop-up Container -->
    <div class="popup-container">
        <!-- Header Section -->
        <div class="popup-header">
        <img src="images/ResetPassword.png" alt="Lock Icon"style="width: 35px; height: auto;"> 
            Reset Password
        </div>

        <div class="divider"></div> <!-- Divider Line -->

        <!-- Instruction Text -->
        <div class="instruction-text">Please confirm your new password :</div>

        <!-- Input Fields -->
        <div class="input-field-container">
            <input type="password" class="input-field" placeholder="New password">
            <img src="images/eye.png" style="width: 45px; height: auto;">
        </div>

        <div class="input-field-container">
            <input type="password" class="input-field" placeholder="Confirm password">
            <img src="images/eye.png" style="width: 45px; height: auto;">
        </div>

        <!-- Button Container -->
        <div class="button-container">
            <button class="button">Cancel</button>
            <button class="button">Save and Return</button>
        </div>
    </div>
</body>
</html>
