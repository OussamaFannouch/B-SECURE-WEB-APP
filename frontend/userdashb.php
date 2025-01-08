<?php
    session_start();
    require_once '../backend/connection.php';

    if (!isset($_SESSION['user_id'])) {
        header("Location: /B-SECURE-WEB-APP/frontend/auth/login.php");
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $query = "SELECT firstName, lastName, email, role, last_login FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    $last_login = $user['last_login'] ? date('d/m/y H:i:s', strtotime($user['last_login'])) : 'Première connexion';

    $stmt->close();
    $conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>B-Secure Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Courier New', monospace;
        }

        body {
            background: #0a0f29;
            min-height: 100vh;
            padding: 20px;
            color: #4facfe;
            position: relative;
            padding-top: 80px;
        }

        .header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background: rgba(10, 15, 41, 0.95);
            padding: 15px 30px;
            border-bottom: 1px solid #4facfe;
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo img {
            height: 40px;
        }

        .nav-links {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .nav-link {
            color: #4facfe;
            text-decoration: none;
            padding: 8px 15px;
            border: 1px solid transparent;
            border-radius: 5px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-link:hover {
            border-color: #4facfe;
            background: rgba(79, 172, 254, 0.1);
        }

        .card {
            background: rgba(10, 15, 41, 0.95);
            border-radius: 15px;
            padding: 40px;
            width: 100%;
            max-width: 480px;
            position: relative;
            overflow: hidden;
            border: 1px solid #4facfe;
            box-shadow: 0 0 20px rgba(79, 172, 254, 0.2),
                        inset 0 0 20px rgba(79, 172, 254, 0.1);
            margin: 0 auto;
            margin-top: 20px;
        }

        .wave-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, #00f2fe20 0%, #4facfe20 50%, #6259ca20 100%);
            mask: repeating-linear-gradient(
                rgba(0, 0, 0, 0.6) 0px,
                transparent 1px,
                transparent 2px
            );
            animation: waveMove 20s linear infinite;
        }

        @keyframes waveMove {
            0% { transform: translateY(0); }
            100% { transform: translateY(-20px); }
        }

        .profile {
            position: relative;
            text-align: center;
            z-index: 1;
        }

        .profile-img {
            width: 150px;
            height: 150px;
            margin: 0 auto 25px;
            position: relative;
            border: 2px solid #4facfe;
            padding: 5px;
            background: #0a0f29;
            border-radius: 15px;
            overflow: hidden;
        }

        .profile-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 10px;
        }

        .name {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
            background: linear-gradient(135deg, #00f2fe 0%, #4facfe 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .title, .education {
            color: #4facfe;
            margin-bottom: 15px;
            font-size: 16px;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin: 30px 0;
            padding: 20px;
            background: rgba(79, 172, 254, 0.05);
            border: 1px solid rgba(79, 172, 254, 0.3);
            border-radius: 10px;
        }

        .stat-item {
            text-align: center;
            padding: 15px;
            border: 1px solid rgba(79, 172, 254, 0.3);
            border-radius: 5px;
            background: rgba(10, 15, 41, 0.8);
            transition: all 0.3s ease;
        }

        .stat-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(79, 172, 254, 0.2);
            border-color: #4facfe;
        }

        .stat-number {
            font-size: 24px;
            font-weight: bold;
            background: linear-gradient(135deg, #00f2fe 0%, #4facfe 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .activity-log {
            margin-top: 20px;
            font-size: 12px;
            color: #4facfe;
            text-align: left;
            padding: 10px;
            border: 1px solid rgba(79, 172, 254, 0.3);
            border-radius: 5px;
            background: rgba(10, 15, 41, 0.8);
        }

        .log-entry {
            margin-bottom: 5px;
        }

        .buttons {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .btn {
            flex: 1;
            padding: 15px;
            border: 1px solid #4facfe;
            background: transparent;
            color: #4facfe;
            font-size: 14px;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            border-radius: 5px;
        }

        .btn:hover {
            background: rgba(79, 172, 254, 0.1);
            box-shadow: 0 0 20px rgba(79, 172, 254, 0.2);
        }
        .activity-log {
        margin-top: 20px;
        font-size: 12px;
        color: #4facfe;
        text-align: left;
        padding: 10px;
        border: 1px solid rgba(79, 172, 254, 0.3);
        border-radius: 5px;
        background: rgba(10, 15, 41, 0.8);
        height: 120px;
        overflow-y: auto;
        position: relative;
    }

    .activity-log::-webkit-scrollbar {
        width: 5px;
    }

    .activity-log::-webkit-scrollbar-track {
        background: rgba(79, 172, 254, 0.1);
        border-radius: 5px;
    }

    .activity-log::-webkit-scrollbar-thumb {
        background: rgba(79, 172, 254, 0.5);
        border-radius: 5px;
    }

    .log-entry {
        margin-bottom: 8px;
        padding: 4px;
        border-bottom: 1px solid rgba(79, 172, 254, 0.1);
        animation: fadeIn 0.5s ease-in-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background: rgba(10, 15, 41, 0.95);
            padding: 15px 30px;
            border-bottom: 1px solid #4facfe;
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
</head>
<body>

    <header class="header">
        <a href="/B-SECURE-WEB-APP/frontend/index.html" class="logo">
            <img src="/B-SECURE-WEB-APP/frontend/media/logo.png" alt="B-Secure Logo">
        </a>
        <nav class="nav-links">
            <?php if ($user['role'] === 'admin'): ?>
            <a href="/B-SECURE-WEB-APP/frontend/createmeeting.php" class="nav-link">
                <i class="fas fa-plus"></i>
                Create Meeting
            </a>
            <?php endif; ?>
            <a href="/B-SECURE-WEB-APP/frontend/dashboard.php" class="nav-link">
                <i class="fas fa-calendar"></i>
                View Meetings
            </a>
            <a href="/B-SECURE-WEB-APP/frontend/auth/login.php" class="nav-link">
                <i class="fas fa-sign-out-alt"></i>
                Logout
            </a>
        </nav>
    </header>

    <div class="card">
        <div class="wave-bg"></div>
        <div class="profile">
            <div class="profile-img">
                <img src="https://www.gravatar.com/avatar/<?php echo md5(strtolower(trim($user['email']))); ?>?s=150&d=identicon" alt="Profile Image">
            </div>
            <h1 class="name"><?php echo htmlspecialchars($user['firstName'] . ' ' . $user['lastName']); ?></h1>
            <p class="title"><?php echo htmlspecialchars($user['role']); ?></p>
            <p class="education">Ensa khouribga - B-Secure Club</p>
            
            <div class="security-badges">
                <i class="badge fas fa-shield-alt"></i>
                <i class="badge fas fa-lock"></i>
                <i class="badge fas fa-code"></i>
            </div>

            <div class="stats">
                <div class="stat-item">
                    <div class="stat-number">65</div>
                    <div class="stat-label">CTF Points</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">43</div>
                    <div class="stat-label">Challenges</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo htmlspecialchars($user['role']); ?></div>
                    <div class="stat-label">Role</div>
                </div>
            </div>

            <div class="activity-log">
                <div class="log-entry">> Last login: <?php echo $last_login; ?> UTC</div>
                <div class="log-entry">> Email: <?php echo htmlspecialchars($user['email']); ?></div>
                <div class="log-entry">> Status: <?php echo $status; ?></div>
                <div class="log-entry">> Role: <?php echo htmlspecialchars($user['role']); ?></div>
            </div>
            

            <div class="buttons">
                <button class="btn"><i class="fas fa-terminal"></i> Terminal</button>
                <button class="btn"><i class="fas fa-code"></i> Projects</button>
            </div>
        </div>
    </div>
</body>
</html>
