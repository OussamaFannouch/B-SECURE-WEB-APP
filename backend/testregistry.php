<?php
include 'connection.php';

function is_email_allowed($email) {
    $blocklist_path = "disposable_email_blocklist.conf";

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit;
    }

    // Extract the domain
    $domain = substr(strrchr($email, "@"), 1);

    // Check if blocklist file exists
    if (!file_exists($blocklist_path)) {
        echo "Warning: Blocklist file not found. Allowing email by default.";
        return true;
    }

    // Read blocklist file
    $blocked_domains = file($blocklist_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    // Check if the domain is in the blocklist
    if (in_array($domain, $blocked_domains)) {
        echo "Email is not allowed (Temporary emails are blacklisted).";
        exit;
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

    if (!empty($error_messages)) {
        echo implode("<br>", $error_messages);
        exit;
    }

    return true;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $FirstName = $_POST['FirstName'];
    $LastName = $_POST['LastName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if all fields are filled
    if (empty($FirstName) || empty($LastName) || empty($email) || empty($password) || empty($confirm_password)) {
        echo "All fields are required.";
        exit;
    }

    // Validate email
    is_email_allowed($email);

    // Validate password
    validate_password($password);

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "Passwords do not match.";
        exit;
    }

    // Check if the email already exists in the database
    $check_email_query = "SELECT email FROM users WHERE email = ?";
    $stmt = $conn->prepare($check_email_query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Email already exists
        echo "Error: The email address is already registered.";
        $stmt->close();
        $conn->close();
        exit;
    }

    // Email doesn't exist, proceed to insert
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $insert_query = "INSERT INTO users (FirstName, LastName, email, password) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("ssss", $FirstName, $LastName, $email, $hashed_password);

    if ($stmt->execute()) {
        echo "User created successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>