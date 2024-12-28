<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once './backend/connection.php';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']); // Added this line
    $date = $conn->real_escape_string($_POST['date']);
    $time = $conn->real_escape_string($_POST['time']);
    $query = "INSERT INTO meetings (title, description, date, time) VALUES (?, ?, ?, ?)"; // Modified this line
    $stmt = $conn->prepare($query);
    
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    
    $stmt->bind_param("ssss", $title, $description, $date, $time); // Modified this line
    $stmt->execute();
    if ($stmt->affected_rows === 1) {
        echo "<script>alert('Meeting created successfully!');</script>";
    } else {
        echo "<script>alert('Failed to create meeting.');</script>";
    }
    $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create a New Meeting</title>
    <style>
        /* Previous styles remain exactly the same */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        body {
            min-height: 100vh;
            background: #0f0f1a;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        /* Animated background waves */
        .wave {
            position: fixed;
            width: 100vw;
            height: 100vh;
            opacity: 0.3;
            left: 0;
            top: 0;
            z-index: 0;
            background: repeating-linear-gradient(
                35deg,
                transparent,
                transparent 50px,
                rgba(0, 255, 255, 0.1) 50px,
                rgba(0, 255, 255, 0.1) 100px
            );
            animation: wave 10s linear infinite;
        }

        .wave:nth-child(2) {
            opacity: 0.2;
            animation-delay: -5s;
            background: repeating-linear-gradient(
                -35deg,
                transparent,
                transparent 50px,
                rgba(179, 0, 255, 0.1) 50px,
                rgba(179, 0, 255, 0.1) 100px
            );
        }

        @keyframes wave {
            0% { transform: translateX(-50%) rotate(0deg); }
            100% { transform: translateX(50%) rotate(360deg); }
        }

        .container {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            border-radius: 30px;
            padding: 50px;
            width: 100%;
            max-width: 600px;
            box-shadow: 
                0 25px 45px rgba(0, 0, 0, 0.2),
                inset 0 0 0 1px rgba(255, 255, 255, 0.1);
            position: relative;
            z-index: 1;
            animation: container-appear 0.6s ease-out;
        }

        @keyframes container-appear {
            0% { 
                opacity: 0;
                transform: translateY(20px);
            }
            100% { 
                opacity: 1;
                transform: translateY(0);
            }
        }

        h1 {
            color: #fff;
            margin-bottom: 40px;
            text-align: center;
            font-size: 2.5em;
            font-weight: 700;
            background: linear-gradient(45deg, #00ffff, #b300ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            position: relative;
        }

        h1::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, #00ffff, #b300ff);
            border-radius: 3px;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        label {
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 10px;
            display: block;
            font-size: 0.95em;
            font-weight: 500;
            transform: translateY(0);
            transition: all 0.3s ease;
        }

        input, textarea {
            width: 100%;
            padding: 15px;
            border: 2px solid rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            color: #fff;
            font-size: 1em;
            transition: all 0.3s ease;
        }

        textarea {
            min-height: 100px;
            resize: vertical;
        }

        input:focus, textarea:focus {
            outline: none;
            border-color: #00ffff;
            box-shadow: 
                0 0 0 4px rgba(0, 255, 255, 0.1),
                0 0 20px rgba(0, 255, 255, 0.2);
            background: rgba(255, 255, 255, 0.07);
        }

        input::placeholder, textarea::placeholder {
            color: rgba(255, 255, 255, 0.3);
        }

        button {
            width: 100%;
            padding: 16px;
            border: none;
            border-radius: 12px;
            background: linear-gradient(45deg, #00ffff, #b300ff);
            color: #fff;
            font-size: 1.1em;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                90deg,
                transparent,
                rgba(255, 255, 255, 0.2),
                transparent
            );
            transition: 0.5s;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 
                0 10px 20px rgba(0, 0, 0, 0.2),
                0 0 20px rgba(0, 255, 255, 0.4);
        }

        button:hover::before {
            left: 100%;
        }

        /* Custom styling for date and time inputs */
        input[type="date"]::-webkit-calendar-picker-indicator,
        input[type="time"]::-webkit-calendar-picker-indicator {
            filter: invert(1);
            opacity: 0.7;
            cursor: pointer;
            transition: 0.3s;
        }

        input[type="date"]::-webkit-calendar-picker-indicator:hover,
        input[type="time"]::-webkit-calendar-picker-indicator:hover {
            opacity: 1;
            scale: 1.1;
        }

        /* Responsive design */
        @media (max-width: 640px) {
            .container {
                padding: 30px;
                border-radius: 20px;
            }

            h1 {
                font-size: 2em;
            }

            input, textarea, button {
                padding: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="wave"></div>
    <div class="wave"></div>
    <div class="container">
        <h1>Create a New Meeting</h1>
        <form action="createmeeting.php" method="POST">
            <div class="form-group">
                <label for="title">Meeting Title</label>
                <input type="text" id="title" name="title" required placeholder="Enter meeting title">
            </div>
            
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" placeholder="Enter meeting description"></textarea>
            </div>
            
            <div class="form-group">
                <label for="date">Date</label>
                <input type="date" id="date" name="date" required>
            </div>
            
            <div class="form-group">
                <label for="time">Time</label>
                <input type="time" id="time" name="time" required>
            </div>
            
            <button type="submit">Schedule Meeting</button>
        </form>
    </div>
</body>
</html>