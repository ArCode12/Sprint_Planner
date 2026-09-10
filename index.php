<?php
  session_start();
  if (isset($_SESSION["user_id"])) {
    header("Location: board.php");
    exit;
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sprint Planner</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>

<header class="site-header">
  <div class="site-header-inner">
    <span class="site-logo">Sprint Planner</span>
    <nav class="site-nav">
      <a href="guide.php" class="site-link">How it works</a>
      <a href="login.php" class="btn ghost">Log in</a>
      <a href="signup.php" class="btn primary">Sign up</a>
    </nav>
  </div>
</header>

<main>
  <section class="hero">
    <span class="hero-badge">✨ Free to use — no credit card needed</span>
    <h1>Plan your sprints without the clutter</h1>
    <p class="hero-sub">
      A simple, focused board for tracking work through a sprint —
      backlog to done, with due dates and priorities built in.
    </p>
    <div class="hero-actions">
      <a href="demo.php" class="btn primary btn-large">Try it now — no signup needed</a>
      <a href="login.php" class="btn ghost btn-large">Log in</a>
    </div>
    <p class="hero-hint">Try a live demo board instantly, no account required.</p>
  </section>

  <section class="features">
    <div class="feature">
      <h3>Drag and drop</h3>
      <p>Move cards between Backlog, To Do, Coding, Testing, and Done as work progresses.</p>
    </div>
    <div class="feature">
      <h3>Due dates and priority</h3>
      <p>Keep track of what's urgent and when it's due, right on the card.</p>
    </div>
    <div class="feature">
      <h3>Your own board</h3>
      <p>Sign up and your cards are saved to your account — private, and there whenever you come back.</p>
    </div>
  </section>
</main>

</body>
</html>

