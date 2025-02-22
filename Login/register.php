<?php
// register.php

// **1. Start the Session**
session_start();

// **2. Include Database Connection Script**
include ('../Database/connection.php');

// **3. Initialize an Errors Array and Success Message**
$errors = [];
$success = '';

// **4. Handle Form Submission**
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // **5. Retrieve and Sanitize User Inputs**
    $role = isset($_POST['role']) ? trim($_POST['role']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

    // **6. Validate Inputs**

    // a. Validate Role
    $valid_roles = ['Student', 'Educator', 'Admin'];
    if (!in_array($role, $valid_roles)) {
        $errors[] = "Invalid role selected.";
    }

    // b. Validate Email
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // c. Validate Username
    if (empty($username)) {
        $errors[] = "Username is required.";
    } elseif (strlen($username) < 3 || strlen($username) > 50) {
        $errors[] = "Username must be between 3 and 50 characters.";
    }

    // d. Validate Password
    if (empty($password)) {
        $errors[] = "Password is required.";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }

    // e. Confirm Password
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    // **7. Check if Email or Username Already Exists in the Respective Table**
    if (empty($errors)) {
        // Determine the table based on role
        switch ($role) {
            case 'Student':
                $table = 'student';
                $id_field = 'student_id';
                $email_field = 'student_email';
                $username_field = 'student_username';
                break;
            case 'Educator':
                $table = 'educator';
                $id_field = 'educator_id';
                $email_field = 'educator_email';
                $username_field = 'educator_username';
                break;
            case 'Admin':
                $table = 'admin';
                $id_field = 'admin_id';
                $email_field = 'admin_email';
                $username_field = 'admin_username';
                break;
        }

        // Prepare a SELECT statement to check existing email or username
        $stmt = $conn->prepare("SELECT $id_field FROM $table WHERE $email_field = ? OR $username_field = ?");
        if ($stmt) {
            $stmt->bind_param("ss", $email, $username);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $errors[] = "An account with this email or username already exists.";
            }

            $stmt->close();
        } else {
            $errors[] = "Database error: Unable to prepare statement.";
        }
    }

    // **8. If No Errors, Proceed to Insert the New User**
    if (empty($errors)) {

        // Prepare an INSERT statement based on role
        switch ($role) {
            case 'Student':
                $insert_stmt = $conn->prepare("INSERT INTO student (student_username, student_password, student_email, student_contacts, student_DOJ) VALUES (?, ?, ?, ?, ?)");
                break;
            case 'Educator':
                $insert_stmt = $conn->prepare("INSERT INTO educator (educator_username, educator_password, educator_email, educator_institution, educator_contacts, educator_DOJ) VALUES (?, ?, ?, ?, ?, ?)");
                break;
            case 'Admin':
                $insert_stmt = $conn->prepare("INSERT INTO admin (admin_username, admin_password, admin_email, admin_DOJ) VALUES (?, ?, ?, ?)");
                break;
        }

        if ($insert_stmt) {
            // Bind parameters based on role
            switch ($role) {
                case 'Student':
                    // Assuming student_contacts and student_DOJ are optional
                    $contacts = isset($_POST['contacts']) ? trim($_POST['contacts']) : null;
                    $doj = isset($_POST['doj']) ? $_POST['doj'] : null;
                    $insert_stmt->bind_param("sssss", $username, $password, $email, $contacts, $doj);
                    break;
                case 'Educator':
                    // Assuming educator_institution, educator_contacts, and educator_DOJ are provided
                    $institution = isset($_POST['institution']) ? trim($_POST['institution']) : '';
                    $contacts = isset($_POST['contacts']) ? trim($_POST['contacts']) : null;
                    $doj = isset($_POST['doj']) ? $_POST['doj'] : null;
                    $insert_stmt->bind_param("sssss", $username, $password, $email, $institution, $contacts, $doj);
                    break;
                case 'Admin':
                    // Assuming admin_DOJ is provided
                    $doj = isset($_POST['doj']) ? $_POST['doj'] : null;
                    $insert_stmt->bind_param("ssss", $username, $password, $email, $doj);
                    break;
            }

            if ($insert_stmt->execute()) {
                // Registration successful

                // Optionally, log the user in immediately after registration
                // Here, we'll redirect them to the login page with a success message
                $_SESSION['success_message'] = "Registration successful! You can now log in.";
                header("Location: login.php");
                exit();
            } else {
                $errors[] = "Registration failed. Please try again.";
            }

            $insert_stmt->close();
        } else {
            $errors[] = "Database error: Unable to prepare statement.";
        }
    }

    // **9. Close the Database Connection**
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign-up Form</title>
    <link rel="stylesheet" href="register.css">
    <!-- Ionicons for Icons -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</head>

<body>

    <section>
        <form action="register.php" method="POST">
            <!-- Display Error Messages -->
            <?php
            if (!empty($errors)) {
                echo '<div class="error-message">';
                foreach ($errors as $error) {
                    echo htmlspecialchars($error) . "<br>";
                }
                echo '</div>';
            }
            ?>

            <h1>Register / Sign-up</h1>

            <!-- Role Selection -->
            <div class="radio-group">
                <input type="radio" id="student" name="role" value="Student" <?php if (isset($_POST['role']) && $_POST['role'] === 'Student') echo 'checked'; ?> required>
                <label for="student">Student</label>

                <input type="radio" id="educator" name="role" value="Educator" <?php if (isset($_POST['role']) && $_POST['role'] === 'Educator') echo 'checked'; ?> required>
                <label for="educator">Educator</label>

                <input type="radio" id="admin" name="role" value="Admin" <?php if (isset($_POST['role']) && $_POST['role'] === 'Admin') echo 'checked'; ?> required>
                <label for="admin">Admin</label>
            </div>

            <!-- Email Input -->
            <div class="inputbox">
                <ion-icon name="mail-outline"></ion-icon>
                <input type="email" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required placeholder=" ">
                <label for="email">Email</label>
            </div>

            <!-- Username Input -->
            <div class="inputbox">
                <ion-icon name="person-outline"></ion-icon>
                <input type="text" id="username" name="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required placeholder=" ">
                <label for="username">Username</label>
            </div>

            <!-- Password Input -->
            <div class="inputbox">
                <ion-icon name="eye-off-outline" id="togglePassword1"></ion-icon>
                <input type="password" id="password1" name="password" required placeholder=" ">
                <label for="password1">Password</label>
            </div>

            <!-- Confirm Password Input -->
            <div class="inputbox">
                <ion-icon name="eye-off-outline" id="togglePassword2"></ion-icon>
                <input type="password" id="password2" name="confirm_password" required placeholder=" ">
                <label for="password2">Confirm Password</label>
            </div>

            <!-- Additional Fields Based on Role -->
            <div id="additional-fields">
                <!-- Educator Fields -->
                <div class="educator-fields" style="display: none;">
                    <div class="inputbox">
                        <ion-icon name="school-outline"></ion-icon>
                        <input type="text" id="institution" name="institution" placeholder=" ">
                        <label for="institution">Institution</label>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn">Sign up</button>

            <!-- Login Link -->
            <div class="register">
                <p>Already have an account? <a href="login.php">Login</a></p>
            </div>

        </form>
    </section>

    <!-- Password Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const togglePassword1 = document.querySelector('#togglePassword1');
            const passwordInput1 = document.querySelector('#password1');

            togglePassword1.addEventListener('click', () => {
                // Toggle the type attribute
                const type = passwordInput1.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput1.setAttribute('type', type);

                // Toggle the eye icon
                togglePassword1.setAttribute('name', type === 'password' ? 'eye-off-outline' : 'eye-outline');
            });

            const togglePassword2 = document.querySelector('#togglePassword2');
            const passwordInput2 = document.querySelector('#password2');

            togglePassword2.addEventListener('click', () => {
                // Toggle the type attribute
                const type = passwordInput2.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput2.setAttribute('type', type);

                // Toggle the eye icon
                togglePassword2.setAttribute('name', type === 'password' ? 'eye-off-outline' : 'eye-outline');
            });

            // Show/Hide additional fields based on role selection
            const roleRadios = document.querySelectorAll('input[name="role"]');
            const educatorFields = document.querySelector('.educator-fields');

            roleRadios.forEach(radio => {
                radio.addEventListener('change', () => {
                    if (radio.value === 'Educator') {
                        educatorFields.style.display = 'block';
                    } else {
                        educatorFields.style.display = 'none';
                    }
                });
            });
        });
    </script>

</body>

</html>