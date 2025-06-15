<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}
require_once 'db.php';




// $freeUses = $_SESSION['free_uses_today'] ?? 0;
$firstName = $_SESSION['first_name'] ?? 'User';
$user_id = $_SESSION['user_id'] ?? null;

$today = date('Y-m-d');

//$credits = $_SESSION['credits'] ?? 0;

$stmt = $pdo->prepare("SELECT credits FROM users WHERE id = ?");
$stmt->execute([ $user_id]);
$credits = $stmt->fetchColumn();

// Count today's free usage
$stmt = $pdo->prepare("SELECT COUNT(*) FROM usage_log WHERE user_id = ? AND DATE(used_at) = ? and usage_type = 'free'");
$stmt->execute([$user_id, $today]);
$daily_usage = $stmt->fetchColumn();

// Count total free usage
$stmt = $pdo->prepare("SELECT COUNT(*) FROM usage_log WHERE user_id = ? and usage_type = 'free' ");
$stmt->execute([$user_id]);
$total_usage = $stmt->fetchColumn();

$remaining_free_uses = 3 - $daily_usage;

if ($remaining_free_uses > (10 - $total_usage)) {
  $remaining_free_uses = 10 - $total_usage;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <link rel="icon" type="image/png" href="/BGREMOVER/images/icon.png">
  <title>Dashboard</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="dashboard_styles.css">
  <meta name="description" content="Remove background from images instantly. Free and paid versions available.">
  <meta name="robots" content="index, follow">

</head>

<body>
  <?php include("components/header.php"); ?>

  <?php if (isset($_GET['error']) && $_GET['error'] === 'freeusageexceeded'): ?>
    <div class="alert alert-error">
      ⚠ Free usage limit exceeded.
    </div>
  <?php endif; ?>

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
        Today’s free usage available: <strong><?= $remaining_free_uses ?> </strong>
      </div>

      <a href="pricing.php" class="cta-button">Buy More Credits</a>

      <form id="uploadForm" class="dashboard-form" enctype="multipart/form-data">
        <input type="file" name="image" id="imageInput" required>
        <!-- Inside #remover tab before or after #result -->
        <div class="preview-container" style="margin-top: 20px; text-align: center;">
          <img id="previewImage" src=""
            style="max-width: 100%; max-height: 300px; display: none; border: 1px solid #ccc; border-radius: 8px;" />
        </div>

        <button type="submit" id="submitBtn">Remove Background</button>
      </form>

      <div id="loader" style="display: none; text-align: center; margin-top: 20px;">
        <div class="spinner"></div>
        <p>Processing image, please wait...</p>
      </div>


      <div id="result"></div>

      <?php if ($credits == 0): ?>
        <div class="ad-container">
          <!-- DND -- Uncomment to enable ad section -->
          <!-- <p style="font-size: 0.9rem; color: #888;">🔔 Sponsored Message</p>
          <p>[Place Ad Here]</p> -->
        </div>
      <?php endif; ?>
    </div>

    <!-- Tab: Purchase History -->
    <div id="history" class="tab-content">
      <h3>Your Purchase History</h3>

      <div class="table-responsive">
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
            require_once 'db.php';
            $stmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ? ORDER BY created_at DESC");
            $stmt->execute([$_SESSION['user_id']]);
            $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($transactions as $txn):
              ?>
              <tr>
                <td><?= date("M d, Y H:i", strtotime($txn['created_at'])) ?></td>
                <td><?= $txn['credits_added'] ?></td>
                <td><?= number_format($txn['amount_paid'], 2) ?></td>
                <td><?= strtoupper($txn['currency']) ?></td>
                <td style="word-break: break-all;"><?= substr($txn['session_id'], 0, 20) . '...' ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script src="script.js"></script>
  <script src="header.js"></script>
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
  <script>
    // Image preview logic
    document.getElementById('imageInput').addEventListener('change', function (event) {
      const file = event.target.files[0];
      const preview = document.getElementById('previewImage');

      if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function (e) {
          preview.src = e.target.result;
          preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
      } else {
        preview.src = '';
        preview.style.display = 'none';
      }
    });

  </script>
</body>

</html>