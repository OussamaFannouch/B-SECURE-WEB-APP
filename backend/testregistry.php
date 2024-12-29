<?php
session_start();
$errors = [];
require_once '../backend/connection.php';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate password
    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    }
    if (!preg_match('/[A-Z]/', $password)) {
        $errors[] = "Password must contain at least one uppercase letter.";
    }
    if (!preg_match('/[\W]/', $password)) {
        $errors[] = "Password must contain at least one special character.";
    }

    // If no errors, proceed with registration (e.g., save to database)
    if (empty($errors)) {
        // Include your database connection file
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Prepare and execute the query
        $query = "INSERT INTO users (firstName, lastName, email, password) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssss", $firstName, $lastName, $email, $hashedPassword);

        if ($stmt->execute()) {
            // Registration successful
            $_SESSION['success'] = "Registration successful. Please log in.";
            header("Location: /Bseccopie/frontend/auth/login.php");
            exit();
        } else {
            $errors[] = "Failed to register. Please try again.";
            header("Location: /Bseccopie/frontend/auth/register.php");
        }

        $stmt->close();
        $conn->close();
    }
}
?>
