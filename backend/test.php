<!-- <?php
$password = 'Ayman123.';
$hash = password_hash($password, PASSWORD_DEFAULT);
echo "New hash: " . $hash . "\n";
echo "Verification result: " . (password_verify($password, '$2y$10$8K1p/a7VaWG6hO/V1VhqhOzqNq3jK7dMCjqvzJHCYEUKh8ZkW8.Zi') ? 'true' : 'false') . "\n";
?>



<?php
$password = 'Oussama123.';
$hash = '$2y$10$u/3ClSbiy8cScVG2BiAY/eojahV0GjA/KRGVgTlA01p26p9/DpbC.';

// Test password verification
echo "Testing new hash...\n";
echo "Password: " . $password . "\n";
echo "Hash: " . $hash . "\n";
echo "Verification result: " . (password_verify($password, $hash) ? 'true' : 'false') . "\n";
?> -->



<?php
require_once 'connection.php';

// Test parameters
$password = 'Ayman123.';
$email = 'test@example.com';
$firstName = 'Test';
$lastName = 'User';

// Clear any existing test user
$delete_query = "DELETE FROM users WHERE email = ?";
$stmt = $conn->prepare($delete_query);
$stmt->bind_param("s", $email);
$stmt->execute();

// Test 1: Registration Process
echo "=== Test 1: Registration Process ===\n";
$hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
echo "Generated Hash: $hashedPassword\n";

$query = "INSERT INTO users (firstName, lastName, email, password, role) VALUES (?, ?, ?, ?, 'member')";
$stmt = $conn->prepare($query);
$stmt->bind_param("ssss", $firstName, $lastName, $email, $hashedPassword);

if ($stmt->execute()) {
    echo "Test user registered successfully\n\n";
} else {
    die("Registration failed: " . $stmt->error);
}

// Test 2: Login Process
echo "=== Test 2: Login Process ===\n";
$login_query = "SELECT id, password FROM users WHERE email = ?";
$stmt = $conn->prepare($login_query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

echo "Stored Hash: " . $user['password'] . "\n";
echo "Password Verification: " . (password_verify($password, $user['password']) ? "SUCCESS" : "FAILED") . "\n\n";

// Test 3: Debug Info
echo "=== Test 3: Debug Info ===\n";
echo "Password: $password\n";
echo "Password Length: " . strlen($password) . "\n";
echo "Password Bytes: " . bin2hex($password) . "\n";

// Cleanup
$stmt->close();
$conn->close();
?>