<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../backend/connection.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize and retrieve user input
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    // Prepare query to check user credentials
    $query = "SELECT id, firstName, lastName, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the email exists
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            // Successful login
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['firstName'] = $user['firstName'];
            $_SESSION['lastName'] = $user['lastName'];
            header("Location: /Bseccopie/dashboard.html");
            exit();
        } else {
            // Incorrect password
            $_SESSION['error'] = "Invalid email or password.";
        }
    } else {
        // Incorrect email
        $_SESSION['error'] = "Invalid email or password.";
    }

    // Redirect back to login page with error
    header("Location: /Bseccopie/frontend/auth/test.php");
    exit();
}

$stmt->close();
$conn->close();
?>