<?php
session_start();
$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
$form_data = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : [];
unset($_SESSION['errors']);
unset($_SESSION['form_data']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" href="../media/Webclip.png" type="image/x-icon" />
    <link rel="shortcut icon" href="../media/Webclip.png" type="image/x-icon" />
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="theme-color" content="#1b4e72" />
    <title>B-Secure | Register</title>
    <!-- GOOGLE FONTS -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap"
      rel="stylesheet"
    />
    <!-- GENERAL CSS FILE -->
    <link rel="stylesheet" href="../css/general.css" />
    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="../css/all.min.css" />
    <!-- MAIN CSS FILE -->
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="../css/register.css" />
</head>
<body>
    <header class="header">
      <div class="container">
        <nav class="nav flex">
          <ul class="nav_list flex desktop_list">
            <li class="nav_link middle"><a href="#about">About Us</a></li>
            <li class="nav_link middle"><a href="#leadership">Leadership</a></li>
            <li class="nav_link middle"><a href="#contact">Join Us</a></li>
          </ul>
          <a href="../index.html" class="nav_logo">
            <div class="logo">
              <img src="../media/logo.png" alt="Bugbeat logo" />
            </div>
          </a>
          <ul class="nav_list flex desktop_list">
            <li class="nav_link middle"><a href="#gallery">Club Gallery</a></li>
            <li class="nav_link middle"><a href="../auth/register.php">Register</a></li>
            <li class="nav_link middle"><a href="../auth/login.php">Login</a></li>
          </ul>
          <div class="nav_icon hidden">
            <span></span>
            <span></span>
            <span></span>
          </div>
        </nav>
      </div>
    </header>

    <main class="main-content">
      <div class="form-container">
        <form class="form" action="http://localhost/backend/testregistry.php" method="POST">
          <p class="title">Register</p>
          <!-- Error Message Display -->
          <?php
          if (!empty($errors)) {
              echo "<div class='error-messages'>";
              foreach ($errors as $error) {
                  echo "<p class='error-message' style='color: red; font-size: 14px;'>$error</p>";
              }
              echo "</div>";
          }
          ?>
          <label>
              <input class="input" type="text" name="firstName" placeholder="" value="<?php echo isset($form_data['firstName']) ? htmlspecialchars($form_data['firstName']) : ''; ?>" required>
              <span>First Name</span>
          </label>
          <label>
              <input class="input" type="text" name="lastName" placeholder="" value="<?php echo isset($form_data['lastName']) ? htmlspecialchars($form_data['lastName']) : ''; ?>" required>
              <span>Last Name</span>
          </label>
          <label>
              <input class="input" type="email" name="email" placeholder="" value="<?php echo isset($form_data['email']) ? htmlspecialchars($form_data['email']) : ''; ?>" required>
              <span>Email</span>
          </label>
          <label>
              <input class="input" type="password" name="password" placeholder="" required>
              <span>Password</span>
          </label>
          <button class="submit">Register</button>
          <p class="signin">Already have an account? <a href="login.php">Login</a></p>
      </form>
    </main>
  </body>
</html>
