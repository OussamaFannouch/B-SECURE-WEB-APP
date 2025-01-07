<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Corriger le chemin d'inclusion
require_once '../backend/connection.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in and get role
if (!isset($_SESSION['user_id'])) {
    header('Location: /Bseccoppie/frontend/auth/login.php');
    exit();
}

$userRole = $_SESSION['role'] ?? 'member'; // Default to member if role not set

// Fetch meetings from the database
$query = "SELECT id, title, description, date, time FROM meetings ORDER BY date, time";
$result = $conn->query($query);

// Check for query errors
if (!$result) {
    die("Query failed: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cybersecurity Club Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #00b4d8;
            --secondary-color: #7b2cbf;
            --background-dark: #1b1b1b;
            --text-light: #ffffff;
            --card-bg: #242424;
            --hover-color: #2a2a2a;
            --header-bg: #0a0a1a;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            margin: 0;
            padding: 20px;
            min-height: 100vh;
            color: var(--text-light);
        }

        .form-container {
            max-width: 1000px;
            margin: 40px auto;
            background: rgba(36, 36, 36, 0.95);
            padding: 30px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            border-radius: 15px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        h2 {
            color: var(--text-light);
            text-align: center;
            font-size: 2.5em;
            margin-bottom: 30px;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 8px;
            margin-top: 20px;
        }

        table th {
            background: var(--primary-color);
            color: var(--text-light);
            padding: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
            text-align: left;
        }

        table td {
            background: var(--card-bg);
            padding: 15px;
            color: var(--text-light);
        }

        table tr:hover td {
            background: var(--hover-color);
            transform: scale(1.01);
            transition: all 0.3s ease;
        }

        table th:first-child,
        table td:first-child {
            border-top-left-radius: 8px;
            border-bottom-left-radius: 8px;
        }

        table th:last-child,
        table td:last-child {
            border-top-right-radius: 8px;
            border-bottom-right-radius: 8px;
        }

        .download-btn {
            background: var(--primary-color);
            color: var(--text-light);
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .download-btn:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        input[type="email"] {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 8px 15px;
            border-radius: 5px;
            color: var(--text-light);
            margin-right: 10px;
            font-size: 14px;
        }

        input[type="email"]::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        input[type="email"]:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(0, 180, 216, 0.2);
        }

        .no-meetings {
            text-align: center;
            padding: 30px;
            font-style: italic;
            color: rgba(255, 255, 255, 0.7);
        }

        @keyframes glow {
            0% { box-shadow: 0 0 5px var(--primary-color); }
            50% { box-shadow: 0 0 20px var(--primary-color); }
            100% { box-shadow: 0 0 5px var(--primary-color); }
        }

        /* Existing styles remain the same */
        /* ... (keep all your existing styles) ... */
        /* New header styles */
        .header {
            background-color: var(--header-bg);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo img {
            height: 40px;
            width: auto;
        }

        .nav-buttons {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .nav-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 5px;
            text-decoration: none;
            color: var(--text-light);
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .nav-button.create {
            background-color: var(--primary-color);
        }

        .nav-button.view {
            background-color: transparent;
            border: 1px solid var(--primary-color);
        }

        .nav-button.logout {
            background-color: transparent;
            border: 1px solid #ff4444;
            color: #ff4444;
        }

        .nav-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .nav-button.create:hover {
            background-color: #0095b3;
        }

        .nav-button.view:hover {
            background-color: rgba(0, 180, 216, 0.1);
        }

        .nav-button.logout:hover {
            background-color: rgba(255, 68, 68, 0.1);
        }
        .download-form {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .download-btn {
        background: var(--primary-color);
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 5px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .download-btn:hover {
        background: var(--secondary-color);
        transform: translateY(-2px);
    }
    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid transparent;
        border-radius: 4px;
    }

    .alert-danger {
        color: #721c24;
        background-color: #f8d7da;
        border-color: #f5c6cb;
    }
    </style>
</head>
<body>
    <header class="header">
        <div class="logo">
            <img src="/Bseccopie/frontend/media/logo.png" alt="Cybersecurity Club Logo">
        </div>
        <nav class="nav-buttons">
            <?php if ($userRole === 'admin'): ?>
                <a href="/Bseccopie/frontend/createmeeting.php" class="nav-button create">
                    <i class="fas fa-plus"></i> Create Meeting
                </a>
            <?php endif; ?>
            <a href="/Bseccopie/frontend/dashboard.php" class="nav-button view">
                <i class="fas fa-calendar"></i> View Meetings
            </a>
            <a href="/Bseccopie/frontend/auth/login.php" class="nav-button logout">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </nav>
    </header>
    
    <div class="form-container">
    <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?php 
                echo $_SESSION['error'];
                unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>
    <h2><i class="fas fa-shield-alt"></i> Upcoming Meetings</h2>
    // Dans la section table du dashboard
<table>
    <thead>
        <tr>
            <th><i class="fas fa-heading"></i> Title</th>
            <th><i class="fas fa-align-left"></i> Description</th>
            <th><i class="fas fa-calendar"></i> Date</th>
            <th><i class="fas fa-clock"></i> Time</th>
            <th><i class="fas fa-tasks"></i> Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                    <td><?php echo $row['date']; ?></td>
                    <td><?php echo $row['time']; ?></td>
                    <td>
                        <form action="/Bseccopie/frontend/generate_pdf.php" method="POST" class="download-form">
                            <input type="hidden" name="meeting_id" value="<?php echo $row['id']; ?>">
                            <input type="hidden" name="meeting_title" value="<?php echo htmlspecialchars($row['title']); ?>">
                            <input type="hidden" name="meeting_date" value="<?php echo $row['date']; ?>">
                            <input type="hidden" name="meeting_time" value="<?php echo $row['time']; ?>">
                            <input type="email" name="email" placeholder="Votre email" required>
                            <button type="submit" class="download-btn">
                                <i class="fas fa-download"></i> Register
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" style="text-align: center;">Aucune réunion disponible</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
    </div>
</body>
</html>
<?php $conn->close(); ?>