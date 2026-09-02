<?php
  session_start();
  include "db_connect.php";
  mysqli_select_db($conn, "sprint_planner");

  $error = "";

  if ($_SERVER["REQUEST_METHOD"] === "POST") {
       $identifier = mysqli_real_escape_string($conn, $_POST["identifier"]);
    $password = $_POST["password"];

        $sql = "SELECT * FROM users WHERE email = '$identifier' OR username = '$identifier'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user["password"])) {
      $_SESSION["user_id"] = $user["id"];
      $_SESSION["user_name"] = $user["username"];
      header("Location: index.php");
      exit;
    } else {
      $error = "Incorrect email or password";
    }
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sprint Planner — Log in</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="auth-page">
  <div class="auth-card">
    <h1>Log in</h1>

    <form method="POST" action="login.php">
      <div class="field">
        <span>Username or email</span>
        <input type="text" name="identifier" placeholder="you@example.com or your username" required>
      </div>

      <div class="field">
        <span>Password</span>
        <input type="password" name="password" placeholder="••••••••" required>
      </div>

      <?php if ($error): ?>
        <p class="auth-error"><?php echo htmlspecialchars($error); ?></p>
      <?php endif; ?>

      <button type="submit" class="btn primary">Log in</button>
    </form>

    <p class="auth-switch">
      <span>Don't have an account?</span>
      <a href="signup.php">Sign up</a>
    </p>
  </div>
</div>

</body>
</html>