<?php
session_start();
$errors = [];

function is_email_allowed($email) {
    $blocklist_path = "../backend/disposable_email_blocklist.conf";

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email format.";
    }

    // Extract the domain
    $domain = substr(strrchr($email, "@"), 1);

    // Check if blocklist file exists
    if (!file_exists($blocklist_path)) {
        return "Warning: Blocklist file not found. Allowing email by default.";
    }

    // Read blocklist file
    $blocked_domains = file($blocklist_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    // Check if the domain is in the blocklist
    if (in_array($domain, $blocked_domains)) {
        return "Email is not allowed (Temporary emails are blacklisted).";
    }

    return true;
}

function validate_password($password) {
    $error_messages = [];

    if (strlen($password) < 8) {
        $error_messages[] = "Password must be at least 8 characters long.";
    }
    if (!preg_match('/[A-Z]/', $password)) {
        $error_messages[] = "Password must contain at least one uppercase letter.";
    }
    if (!preg_match('/[a-z]/', $password)) {
        $error_messages[] = "Password must contain at least one lowercase letter.";
    }
    if (!preg_match('/\d/', $password)) {
        $error_messages[] = "Password must contain at least one number.";
    }
    if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
        $error_messages[] = "Password must contain at least one special character.";
    }

    return $error_messages;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate email
    $email_validation_result = is_email_allowed($email);
    if ($email_validation_result !== true) {
        $errors[] = $email_validation_result;
    }

    // Validate password
    $password_errors = validate_password($password);
    if (!empty($password_errors)) {
        $errors = array_merge($errors, $password_errors);
    }

    // If no errors, proceed with registration (e.g., save to database)
    if (empty($errors)) {
        // Include your database connection file
        require_once '../backend/connection.php';

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Prepare and execute the query
        $query = "INSERT INTO users (firstName, lastName, email, password, role) VALUES (?, ?, ?, ?, 'member')";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssss", $firstName, $lastName, $email, $hashedPassword);

        if ($stmt->execute()) {
            // Registration successful
            $_SESSION['success'] = "Registration successful. Please log in.";
            header("Location: /B-SECURE-WEB-APP/frontend/auth/login.php");
            exit();
        } else {
            $errors[] = "Failed to register. Please try again.";
        }

        $stmt->close();
        $conn->close();
    }

    $_SESSION['errors'] = $errors;
    $_SESSION['form_data'] = $_POST;
    header("Location: /B-SECURE-WEB-APP/frontend/auth/register.php");
    exit();
}
?>