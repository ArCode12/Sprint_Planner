<!DOCTYPE html>
<?php
  session_start();
  if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
  }
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sprint Planner</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><rect width=%22100%22 height=%22100%22 rx=%2220%22 fill=%22%230B5FA5%22/><text x=%2250%22 y=%2268%22 font-size=%2260%22 font-family=%22sans-serif%22 font-weight=%22bold%22 fill=%22white%22 text-anchor=%22middle%22>S</text></svg>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="page">
    <div class="page-header">
        <h1 class="page-title">Sprint Planner</h1>
        <div class="user-area">
            <span class="user-badge">Hi, <?php echo htmlspecialchars($_SESSION["user_name"]); ?></span>
            <a href="logout.php" class="btn ghost">Log out</a>
        </div>
    </div>
    <div class="board-table" id="boardTable"></div>
</div>

<div class="modal-backdrop" id="modalBackdrop">
    <div class="modal">
        <h2 id="modalTitle">Add card</h2>
        <label class="field">
            <span>Title</span>
            <input type="text" id="cardTitleInput" maxlength="120" placeholder="e.g. Wire up auth endpoint">
        </label>
        <label class="field">
            <span>Details <em>(optional)</em></span>
            <textarea id="cardDetailInput" maxlength="500" rows="3" placeholder="Notes, acceptance criteria, links..."></textarea>
        </label>
        <label class="field">
            <span>Card color</span>
            <div class="swatches" id="swatches"></div>
        </label>
        <div class="modal-actions">
            <button class="btn ghost" id="deleteCardBtn" hidden>Delete</button>
            <div class="modal-actions-right">
                <button class="btn ghost" id="cancelModalBtn">Cancel</button>
                <button class="btn primary" id="saveCardBtn">Save</button>
            </div>
        </div>
    </div>
</div>

<script src="script.js"></script>
</body>
</html>