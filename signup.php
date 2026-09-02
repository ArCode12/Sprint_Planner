<?php
  include "db_connect.php";
  mysqli_select_db($conn, "sprint_planner");

  $error = "";

  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = mysqli_real_escape_string($conn, $_POST["username"]);
    $email = trim($_POST["email"]);
    $email = $email === "" ? null : mysqli_real_escape_string($conn, $email);
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirm_password"];

    if ($password !== $confirmPassword) {
      $error = "Passwords do not match";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashedPassword')";

    try {
        mysqli_query($conn, $sql);
        $newUserId = mysqli_insert_id($conn);

        session_start();
        $_SESSION["user_id"] = $newUserId;
        $_SESSION["user_name"] = $username;

        header("Location: index.php");
        exit;
      } catch (mysqli_sql_exception $e) {
        $error = "That username or email is already in use";
      }
    }
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sprint Planner — Sign up</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="auth-page">
  <div class="auth-card">
    <h1>Sign up</h1>

    <form method="POST" action="signup.php">
      <div class="field">
        <span>Username</span>
        <input type="text" name="username" placeholder="Your username" required>
      </div>

      <div class="field">
        <span>Email</span>
        <input type="email" name="email" placeholder="you@example.com" required>
      </div>

      <div class="field">
        <span>Password</span>
        <input type="password" name="password" placeholder="••••••••" required>
      </div>

      <div class="field">
        <span>Confirm password</span>
        <input type="password" name="confirm_password" placeholder="••••••••" required>
      </div>

      <?php if ($error): ?>
        <p class="auth-error"><?php echo htmlspecialchars($error); ?></p>
      <?php endif; ?>

      <button type="submit" class="btn primary">Sign up</button>
    </form>

    <p class="auth-switch">
      <span>Already have an account?</span>
      <a href="login.php">Log in</a>
    </p>
  </div>
</div>

</body>
</html>