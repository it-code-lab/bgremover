<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}
require_once 'db.php';

// Safe defaults
$credits = $_SESSION['credits'] ?? 0;
$freeUses = $_SESSION['free_uses_today'] ?? 0;
$firstName = $_SESSION['first_name'] ?? 'User';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="dashboard_styles.css">
</head>
<body>
<?php include("components/header.php"); ?>

<?php if (isset($_GET['credits']) && $_GET['credits'] === 'added'): ?>
  <div class="alert alert-success">
    🎉 Credits added successfully! You can now remove more backgrounds.
  </div>
<?php endif; ?>

<div class="dashboard-container">
  <h2>Welcome, <?= htmlspecialchars($firstName) ?></h2>

  <div class="tabs">
    <button class="tab-button active" data-tab="remover">Background Remover</button>
    <button class="tab-button" data-tab="history">Purchase History</button>
  </div>

  <!-- Tab: Background Remover -->
  <div id="remover" class="tab-content active">
    <div class="credit-summary">
      You have <strong><?= $credits ?></strong> credits remaining.
    </div>
    <div class="usage-stats">
      Today’s usage: <strong><?= $freeUses ?> / 3</strong> free background removals used
    </div>

    <a href="buy_credits.php" class="cta-button">Buy More Credits</a>

    <form id="uploadForm" class="dashboard-form" enctype="multipart/form-data">
      <input type="file" name="image" id="imageInput" required>
      <button type="submit">Remove Background</button>
    </form>

    <div id="result"></div>

    <?php if ($credits == 0): ?>
      <div class="ad-container">
        <p style="font-size: 0.9rem; color: #888;">🔔 Sponsored Message</p>
        <p>[Place Ad Here]</p>
      </div>
    <?php endif; ?>
  </div>

  <!-- Tab: Purchase History -->
  <div id="history" class="tab-content">
    <h3>Your Purchase History</h3>
    <table class="history-table">
      <thead>
        <tr>
          <th>Date</th>
          <th>Credits</th>
          <th>Amount</th>
          <th>Currency</th>
          <th>Session ID</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $stmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$_SESSION['user_id']]);
        $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (empty($transactions)) {
          echo "<tr><td colspan='5'>No purchases yet.</td></tr>";
        } else {
          foreach ($transactions as $txn): ?>
            <tr>
              <td><?= date("M d, Y H:i", strtotime($txn['created_at'])) ?></td>
              <td><?= $txn['credits_added'] ?></td>
              <td>$<?= number_format($txn['amount_paid'], 2) ?></td>
              <td><?= strtoupper($txn['currency']) ?></td>
              <td><?= substr($txn['session_id'], 0, 10) . '...' ?></td>
            </tr>
          <?php endforeach;
        } ?>
      </tbody>
    </table>
  </div>
</div>

<script src="script.js"></script>
<script>
  const buttons = document.querySelectorAll(".tab-button");
  const contents = document.querySelectorAll(".tab-content");

  buttons.forEach(btn => {
    btn.addEventListener("click", () => {
      buttons.forEach(b => b.classList.remove("active"));
      contents.forEach(c => c.classList.remove("active"));
      btn.classList.add("active");
      document.getElementById(btn.dataset.tab).classList.add("active");
    });
  });
</script>
</body>
</html>
