<?php
session_start();
require_once '../backend/connection.php';

function is_email_allowed($email) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email format.";
    }
    return true;
}

function validate_password($password) {
    $errors = [];
    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters.";
    }
    if (!preg_match('/[A-Z]/', $password)) {
        $errors[] = "Password must contain uppercase letter.";
    }
    if (!preg_match('/[a-z]/', $password)) {
        $errors[] = "Password must contain lowercase letter.";
    }
    if (!preg_match('/[0-9]/', $password)) {
        $errors[] = "Password must contain number.";
    }
    if (!preg_match('/[!@#$%^&*.]/', $password)) {
        $errors[] = "Password must contain special character.";
    }
    return $errors;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $errors = [];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate email
    $email_result = is_email_allowed($email);
    if ($email_result !== true) {
        $errors[] = $email_result;
    }

    // Validate password
    $password_errors = validate_password($password);
    if (!empty($password_errors)) {
        $errors = array_merge($errors, $password_errors);
    }

    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
        
        $query = "INSERT INTO users (firstName, lastName, email, password, role) VALUES (?, ?, ?, ?, 'member')";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssss", $firstName, $lastName, $email, $hashedPassword);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Registration successful. Please log in.";
            header("Location: /B-SECURE-WEB-APP/frontend/auth/login.php");
            exit();
        } else {
            $errors[] = "Registration failed. Please try again.";
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