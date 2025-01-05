<?php
if (!isset($_SESSION)) {
    session_start();
}
?>
<header class="header">
    <a href="/Bseccopie/frontend/index.html" class="logo">
        <img src="/Bseccopie/frontend/media/logo.png" alt="B-Secure Logo">
    </a>
    <nav class="nav-links">
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <a href="/Bseccopie/createmeeting.php" class="nav-link">
                <i class="fas fa-plus"></i>
                Create Meeting
            </a>
        <?php endif; ?>
        <a href="/Bseccopie/dashboard.php" class="nav-link">
            <i class="fas fa-calendar"></i>
            View Meetings
        </a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="/Bseccopie/frontend/userdashb.php" class="nav-link">
                <i class="fas fa-user"></i>
                Profile
            </a>
            <a href="/Bseccopie/frontend/auth/login.php" class="nav-link">
                <i class="fas fa-sign-out-alt"></i>
                Logout
            </a>
        <?php endif; ?>
    </nav>
</header>