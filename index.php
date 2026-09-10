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
    <a href="index.php" class="site-logo">
      <span class="logo-icon">S</span>
      Sprint Planner
    </a>
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

  <section class="preview-section">
    <p class="section-eyebrow">See it in action</p>
    <h2>This is what your board looks like</h2>
    <p>A clean, focused view of every stage of your sprint — no clutter, no setup required.</p>

    <div class="preview-frame">
      <div class="mini-board">
        <div class="mini-col">
          <h4>BACKLOG</h4>
          <div class="mini-card">Draft the sprint goal</div>
          <div class="mini-card blue">Review last sprint</div>
        </div>
        <div class="mini-col">
          <h4>TO DO</h4>
          <div class="mini-card green">Set up test cases</div>
        </div>
        <div class="mini-col">
          <h4>CODING IN PROGRESS</h4>
          <div class="mini-card purple">Build login flow</div>
        </div>
        <div class="mini-col">
          <h4>TESTING IN PROGRESS</h4>
        </div>
        <div class="mini-col">
          <h4>DONE</h4>
          <div class="mini-card">Project setup</div>
        </div>
      </div>
    </div>
  </section>

  <section class="faq-section">
    <div class="faq-inner">
      <div class="faq-header">
        <p class="section-eyebrow">Questions</p>
        <h2>Frequently asked questions</h2>
        <p>Everything you need to know before getting started.</p>
      </div>

      <div class="faq-item open">
        <div class="faq-question">Is Sprint Planner free to use?</div>
        <div class="faq-answer">Yes — creating an account and using your own board is completely free right now.</div>
      </div>
      <div class="faq-item">
        <div class="faq-question">Do I need to install anything?</div>
        <div class="faq-answer">No, it runs entirely in your browser. Just sign up and start adding cards.</div>
      </div>
      <div class="faq-item">
        <div class="faq-question">Can I try it before signing up?</div>
        <div class="faq-answer">Yes — click "Try it now" on the home page for a fully working demo board. It won't save your changes, but it's a great way to see how everything works.</div>
      </div>
      <div class="faq-item">
        <div class="faq-question">Is my data private?</div>
        <div class="faq-answer">Yes — once you have an account, only you can see your own cards.</div>
      </div>
    </div>
  </section>
</main>

<script>
document.querySelectorAll(".faq-question").forEach(q => {
  q.addEventListener("click", () => {
    q.parentElement.classList.toggle("open");
  });
});
</script>

</body>
</html>